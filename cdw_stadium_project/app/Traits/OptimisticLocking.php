<?php

namespace App\Traits;

trait OptimisticLocking
{
    /**
     * Update the model with optimistic locking.
     *
     * @param  array<string, mixed>  $attributes
     * @return int The number of affected rows (1 if successful, 0 if version mismatch).
     */
    public function updateWithOptimisticLock(array $attributes): int
    {
        $currentVersion = isset($attributes['version']) ? (int) $attributes['version'] : (int) ($this->version ?? 0);

        // Loại bỏ version khỏi mảng data để tự xử lý tăng ++
        unset($attributes['version']);

        // Query update với điều kiện version phải khớp
        $affectedRows = static::where($this->getKeyName(), $this->getKey())
            ->where('version', $currentVersion)
            ->update(array_merge($attributes, [
                'version' => $currentVersion + 1,
            ]));

        if ($affectedRows > 0) {
            $this->refresh();
            if (method_exists($this, 'logAction')) {
                $this->logAction('Updated');
            }
        }

        return $affectedRows;
    }
}

