<?php

namespace App\Models;

use App\Contracts\Payable;
use App\Models\Concerns\HasPayment;
use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Sale extends Model implements Payable
{
    use HasFactory, HasPayment, LogsActivity;

    protected $fillable = [
        'product_id',
        'user_id',
        'customer_name',
        'customer_phone',
        'quantity',
        'total_price',
        'status',
        'sale_date',
    ];

    protected $casts = [
        'total_price' => 'float',
        'sale_date' => 'date',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
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
        return $this->customer_name ?: ($this->user?->name ?? 'Khách lẻ');
    }

    public function paymentReference(): string
    {
        return $this->formatPaymentReference('SL');
    }

    public function paymentDescription(): string
    {
        $productName = $this->product?->name ?? 'Sản phẩm #'.$this->product_id;

        return "{$this->quantity}x {$productName}";
    }

    public function defaultPaymentMethod(): string
    {
        return 'cash';
    }

    public function resolvePaymentStatus(?string $currentStatus): string
    {
        return match ($this->status) {
            'cancelled' => $currentStatus === 'paid' ? 'refunded' : 'failed',
            'completed' => 'paid',
            default => $currentStatus ?: 'pending',
        };
    }
}
