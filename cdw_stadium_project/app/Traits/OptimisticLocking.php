<?php

namespace App\Traits;

use App\Exceptions\StaleModelLockException;

/**
 * Trait hỗ trợ khóa lạc quan (Optimistic Locking) thông qua cột `version`.
 *
 * Nguyên lý hoạt động:
 * 1. Khi tạo mới bản ghi (`creating`), `version` luôn được khởi tạo mặc định là `0`.
 * 2. Khi cập nhật qua `updateWithOptimisticLock($attributes)`:
 *    - Đối chiếu `WHERE id = :id AND version = :expected_version`.
 *    - Tăng nguyên tử `version = :expected_version + 1` và cập nhật `updated_at`.
 *    - Nếu `affectedRows === 0`, nghĩa là bản ghi đã bị sửa hoặc xóa bởi tiến trình khác.
 *    - Nếu `affectedRows > 0`, tự động `refresh()` model, ghi `ActivityLog` (`Updated`)
 *      và đồng bộ chỉ mục tìm kiếm `Laravel Scout` (nếu model có sử dụng `Searchable`).
 */
trait OptimisticLocking
{
    public static function getOptimisticLockErrorMessage(): string
    {
        return 'Dữ liệu này vừa được một quản trị viên khác cập nhật. Vui lòng tải lại trang để xem dữ liệu mới nhất.';
    }

    protected static function bootOptimisticLocking(): void
    {
        static::creating(function ($model) {
            $column = $model->getVersionColumn();
            if ($model->getAttribute($column) === null) {
                $model->setAttribute($column, 0);
            }
        });
    }

    protected function initializeOptimisticLocking(): void
    {
        $column = $this->getVersionColumn();

        if (! in_array($column, $this->fillable, true) && $this->fillable !== []) {
            $this->fillable[] = $column;
        }

        if (! isset($this->casts[$column])) {
            $this->casts[$column] = 'integer';
        }
    }

    public function getVersionColumn(): string
    {
        return 'version';
    }

    public function getCurrentVersion(): int
    {
        return (int) ($this->getAttribute($this->getVersionColumn()) ?? 0);
    }

    /**
     * Cập nhật bản ghi với kiểm tra khóa lạc quan (Optimistic Lock).
     *
     * @param  array<string, mixed>  $attributes
     * @return int Số dòng được cập nhật (1 nếu thành công, 0 nếu xung đột version).
     */
    public function updateWithOptimisticLock(array $attributes, ?int $expectedVersion = null): int
    {
        $versionCol = $this->getVersionColumn();

        $currentVersion = $expectedVersion
            ?? (array_key_exists($versionCol, $attributes) && $attributes[$versionCol] !== null && $attributes[$versionCol] !== ''
                ? (int) $attributes[$versionCol]
                : $this->getCurrentVersion());

        unset($attributes[$versionCol]);

        $payload = array_merge($attributes, [
            $versionCol => $currentVersion + 1,
        ]);

        if ($this->usesTimestamps()) {
            $updatedAtCol = $this->getUpdatedAtColumn();
            if ($updatedAtCol && ! array_key_exists($updatedAtCol, $payload)) {
                $payload[$updatedAtCol] = $this->freshTimestampString();
            }
        }

        $affectedRows = static::query()
            ->where($this->getKeyName(), $this->getKey())
            ->where($versionCol, $currentVersion)
            ->update($payload);

        if ($affectedRows > 0) {
            $this->refresh();

            if (method_exists($this, 'logAction')) {
                $this->logAction('Updated');
            }

            if (method_exists($this, 'shouldBeSearchable') && method_exists($this, 'searchable') && method_exists($this, 'unsearchable')) {
                $this->shouldBeSearchable() ? $this->searchable() : $this->unsearchable();
            }
        }

        return $affectedRows;
    }

    /**
     * Cập nhật bản ghi với Optimistic Lock hoặc ném ngoại lệ `StaleModelLockException` khi xung đột(dùng tốt trong DB::transaction).
     *
     * @param  array<string, mixed>  $attributes
     *
     * @throws StaleModelLockException
     */
    public function updateWithOptimisticLockOrFail(array $attributes, ?int $expectedVersion = null): static
    {
        $versionCol = $this->getVersionColumn();
        $resolvedVersion = $expectedVersion
            ?? (array_key_exists($versionCol, $attributes) && $attributes[$versionCol] !== null && $attributes[$versionCol] !== ''
                ? (int) $attributes[$versionCol]
                : $this->getCurrentVersion());

        $affectedRows = $this->updateWithOptimisticLock($attributes, $resolvedVersion);

        if ($affectedRows === 0) {
            throw new StaleModelLockException($this, $resolvedVersion, static::getOptimisticLockErrorMessage());
        }

        return $this;
    }
}


