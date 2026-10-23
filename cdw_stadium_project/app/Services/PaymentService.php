<?php

namespace App\Services;

use App\Contracts\Payable;
use App\Models\Payment;

/**
 * Dịch vụ đồng bộ luồng thanh toán:
 * Booking, Sale và Subscription đều xuất dữ liệu thanh toán sang bảng payments.
 */
class PaymentService
{
    public function syncFromPayable(
        Payable $payable,
        ?string $paymentMethod = null,
        ?string $status = null,
        ?string $transactionCode = null,
        mixed $paidAt = null,
        ?string $note = null,
    ): Payment {
        $existing = $payable->payment()->first();

        $resolvedStatus = $status ?? $payable->resolvePaymentStatus($existing?->status);
        $resolvedMethod = $paymentMethod
            ?: ($existing?->payment_method ?: $payable->defaultPaymentMethod());

        $resolvedPaidAt = $paidAt;
        if ($resolvedPaidAt === null) {
            if ($resolvedStatus === 'paid') {
                $resolvedPaidAt = $existing?->paid_at ?? now();
            } else {
                $resolvedPaidAt = $existing?->paid_at;
            }
        }

        $type = $payable->getMorphClass();
        $resolvedCode = $transactionCode
            ?: ($existing?->transaction_code ?: Payment::generateTransactionCode($type));

        $payload = [
            'user_id' => $payable->payableUserId(),
            'amount' => $payable->payableAmount(),
            'payment_method' => $resolvedMethod,
            'transaction_code' => $resolvedCode,
            'status' => $resolvedStatus,
            'paid_at' => $resolvedPaidAt,
            'note' => $note ?? $existing?->note,
        ];

        if ($existing) {
            $existing->update($payload);

            return $existing->refresh();
        }

        return $payable->payment()->create($payload);
    }

    /**
     * Resolve nguồn đơn từ payable_type và payable_id để điền tự động user_id / amount khi tạo Payment thủ công.
     */
    public function resolvePayable(string $payableType, int $payableId): ?Payable
    {
        $modelClass = Payment::SOURCES[$payableType] ?? null;

        if (! $modelClass) {
            return null;
        }

        /** @var Payable|null $payable */
        $payable = $modelClass::find($payableId);

        return $payable;
    }
}
