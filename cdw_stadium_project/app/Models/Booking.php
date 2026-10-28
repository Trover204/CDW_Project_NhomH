<?php

namespace App\Models;

use App\Contracts\Payable;
use App\Models\Concerns\HasPayment;
use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Booking extends Model implements Payable
{
    use HasFactory, HasPayment, LogsActivity;

    protected $fillable = [
        'user_id',
        'court_id',
        'subscription_id',
        'customer_name',
        'customer_phone',
        'customer_email',
        'booking_date',
        'start_time',
        'end_time',
        'subtotal',
        'discount_amount',
        'total_price',
        'note',
        'status',
    ];

    protected $casts = [
        'subtotal' => 'float',
        'discount_amount' => 'float',
        'total_price' => 'float',
        'booking_date' => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function court(): BelongsTo
    {
        return $this->belongsTo(Court::class);
    }

    public function subscription(): BelongsTo
    {
        return $this->belongsTo(Subscription::class);
    }

    public function review(): HasOne
    {
        return $this->hasOne(Review::class);
    }

    public function logs(): HasMany
    {
        return $this->hasMany(BookingLog::class);
    }

    public function payableAmount(): float
    {
        return (float) $this->total_price;
    }

    public function payableUserId(): ?int
    {
        return $this->user_id ? (int) $this->user_id : null;
    }

    public function paymentCustomerName(): ?string
    {
        return $this->customer_name ?: $this->user?->name;
    }

    public function paymentReference(): string
    {
        return $this->formatPaymentReference('BK');
    }

    public function paymentDescription(): string
    {
        $courtName = $this->court?->name ?? 'Sân #'.$this->court_id;
        $start = substr((string) $this->start_time, 0, 5);
        $end = substr((string) $this->end_time, 0, 5);

        return "{$courtName} ({$this->booking_date} {$start}-{$end})";
    }

    public function defaultPaymentMethod(): string
    {
        return 'cod';
    }

    public function resolvePaymentStatus(?string $currentStatus): string
    {
        if (in_array($this->status, ['cancelled', 'rejected'], true)) {
            return $currentStatus === 'paid' ? 'refunded' : 'failed';
        }

        if (in_array($this->status, ['completed', 'checked_in'], true)) {
            return 'paid';
        }

        return $currentStatus ?: 'pending';
    }
}
