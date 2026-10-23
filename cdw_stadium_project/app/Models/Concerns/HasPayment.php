<?php

namespace App\Models\Concerns;

use App\Models\Payment;
use Illuminate\Database\Eloquent\Relations\MorphOne;

/**
 * Gắn quan hệ thanh toán đa hình cho các model Payable.
 */
trait HasPayment
{
    public static function bootHasPayment(): void
    {
        // Xóa đơn nguồn thì xóa luôn bản ghi thanh toán để tránh dữ liệu mồ côi.
        static::deleting(function ($model) {
            $model->payment()->delete();
        });
    }

    public function payment(): MorphOne
    {
        return $this->morphOne(Payment::class, 'payable');
    }

    protected function formatPaymentReference(string $prefix): string
    {
        return '#'.$prefix.'-'.str_pad((string) $this->getKey(), 5, '0', STR_PAD_LEFT);
    }
}
