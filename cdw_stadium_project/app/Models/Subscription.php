<?php

namespace App\Models;

use App\Contracts\Payable;
use App\Models\Concerns\HasPayment;
use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Subscription extends Model implements Payable
{
    use HasFactory, HasPayment, LogsActivity;

    protected $fillable = [
        'user_id',
        'plan_id',
        'starts_at',
        'expires_at',
        'status',
        'price',
    ];

    protected $casts = [
        'status' => 'boolean',
        'starts_at' => 'datetime',
        'expires_at' => 'datetime',
        'price' => 'float',
    ];

    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    // Các accessor tương thích ngược: đọc từ bảng payments (nguồn sự thật duy nhất về thanh toán).
    public function getAmountPaidAttribute(): float
    {
        return (float) ($this->payment?->amount ?? $this->price ?? 0);
    }

    public function getPaymentMethodAttribute(): ?string
    {
        return $this->payment?->payment_method;
    }

    public function getTransactionCodeAttribute(): ?string
    {
        return $this->payment?->transaction_code;
    }

    public function payableAmount(): float
    {
        return (float) $this->price;
    }

    public function payableUserId(): ?int
    {
        return $this->user_id ? (int) $this->user_id : null;
    }

    public function paymentCustomerName(): ?string
    {
        return $this->user?->name;
    }

    public function paymentReference(): string
    {
        return $this->formatPaymentReference('SUB');
    }

    public function paymentDescription(): string
    {
        $planName = $this->plan?->name ?? 'Gói #'.$this->plan_id;

        return "Gói thành viên: {$planName}";
    }

    public function defaultPaymentMethod(): string
    {
        return 'vnpay';
    }

    public function resolvePaymentStatus(?string $currentStatus): string
    {
        if (! $this->status) {
            return $currentStatus ?: 'failed';
        }

        return $currentStatus ?: 'paid';
    }
}
