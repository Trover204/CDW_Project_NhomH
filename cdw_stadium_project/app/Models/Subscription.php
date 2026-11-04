<?php

namespace App\Models;

use App\Contracts\Payable;
use App\Models\Concerns\HasPayment;
use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Scout\Searchable;

class Subscription extends Model implements Payable
{
    use HasFactory, HasPayment, LogsActivity, Searchable;

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

    protected function makeAllSearchableUsing(Builder $query): Builder
    {
        return $query->with(['user', 'plan']);
    }

    public function toSearchableArray(): array
    {
        $array = [
            'id' => (int) $this->id,
            'user_id' => $this->user_id ? (int) $this->user_id : null,
            'plan_id' => $this->plan_id ? (int) $this->plan_id : null,
            'price' => (float) $this->price,
        ];

        if (config('scout.driver') === 'elasticsearch') {
            $array['user_name'] = $this->user?->name;
            $array['plan_name'] = $this->plan?->name;
            $array['status'] = (bool) $this->status;
            $array['starts_at'] = $this->starts_at?->toDateTimeString();
            $array['expires_at'] = $this->expires_at?->toDateTimeString();
            $array['created_at'] = $this->created_at?->timestamp;
        }

        return $array;
    }
}
