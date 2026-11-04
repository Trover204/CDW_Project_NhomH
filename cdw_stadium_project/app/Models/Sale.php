<?php

namespace App\Models;

use App\Contracts\Payable;
use App\Models\Concerns\HasPayment;
use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Laravel\Scout\Searchable;

class Sale extends Model implements Payable
{
    use HasFactory, HasPayment, LogsActivity, Searchable;

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

    protected function makeAllSearchableUsing(Builder $query): Builder
    {
        return $query->with(['product', 'user']);
    }

    public function toSearchableArray(): array
    {
        $array = [
            'id' => (int) $this->id,
            'customer_name' => $this->customer_name ?: $this->user?->name,
            'customer_phone' => $this->customer_phone,
            'status' => $this->status,
        ];

        if (in_array(config('scout.driver'), ['elasticsearch', 'elastic'], true)) {
            $array['product_id'] = $this->product_id ? (int) $this->product_id : null;
            $array['user_id'] = $this->user_id ? (int) $this->user_id : null;
            $array['product_name'] = $this->product?->name;
            $array['quantity'] = (int) $this->quantity;
            $array['total_price'] = (float) $this->total_price;
            $array['sale_date'] = $this->sale_date?->format('Y-m-d');
            $array['created_at'] = $this->created_at?->timestamp;
        }

        return $array;
    }
}
