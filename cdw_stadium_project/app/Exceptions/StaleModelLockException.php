<?php

namespace App\Exceptions;

use Illuminate\Database\Eloquent\Model;
use RuntimeException;

/**
 * Ngoại lệ ném ra khi phát hiện xung đột phiên bản dữ liệu (Optimistic Locking Stale Object).
 */
class StaleModelLockException extends RuntimeException
{
    public function __construct(
        public readonly Model $model,
        public readonly int $expectedVersion,
        string $message = 'Dữ liệu này vừa được cập nhật bởi một phiên làm việc khác. Vui lòng tải lại trang để lấy dữ liệu mới nhất trước khi lưu.',
    ) {
        parent::__construct($message);
    }
}
