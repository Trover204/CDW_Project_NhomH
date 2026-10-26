<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Str;

class Payment extends Model
{
    use HasFactory;

    public const TYPE_BOOKING = 'booking';

    public const TYPE_SALE = 'sale';

    public const TYPE_SUBSCRIPTION = 'subscription';

    /** Morph map: alias lưu trong payable_type => model nguồn. */
    public const SOURCES = [
        self::TYPE_BOOKING => Booking::class,
        self::TYPE_SALE => Sale::class,
        self::TYPE_SUBSCRIPTION => Subscription::class,
    ];

    public const SOURCE_TABLES = [
        self::TYPE_BOOKING => 'bookings',
        self::TYPE_SALE => 'sales',
        self::TYPE_SUBSCRIPTION => 'subscriptions',
    ];

    public const TYPES = [
        self::TYPE_BOOKING => 'Đặt sân',
        self::TYPE_SALE => 'Bán hàng',
        self::TYPE_SUBSCRIPTION => 'Gói thành viên',
    ];

    public const TYPE_COLORS = [
        self::TYPE_BOOKING => '#178A4C',
        self::TYPE_SALE => '#F59E0B',
        self::TYPE_SUBSCRIPTION => '#3B82F6',
    ];

    public const TYPE_PREFIXES = [
        self::TYPE_BOOKING => 'BK',
        self::TYPE_SALE => 'SL',
        self::TYPE_SUBSCRIPTION => 'SUB',
    ];

    public const STATUSES = [
        'pending' => 'Chờ thanh toán',
        'paid' => 'Đã thanh toán',
        'failed' => 'Thất bại',
        'refunded' => 'Đã hoàn tiền',
    ];

    public const STATUS_BADGES = [
        'pending' => 'warning',
        'paid' => 'success',
        'failed' => 'danger',
        'refunded' => 'info',
    ];

    public const METHODS = [
        'cash' => 'Tiền mặt',
        'cod' => 'Thanh toán tại sân',
        'bank_transfer' => 'Chuyển khoản',
        'vnpay' => 'VNPay',
        'momo' => 'Ví MoMo',
        'visa' => 'Thẻ Visa/Mastercard',
        'paypal' => 'PayPal',
    ];

    protected $fillable = [
        'payable_type',
        'payable_id',
        'user_id',
        'amount',
        'payment_method',
        'transaction_code',
        'status',
        'paid_at',
        'note',
    ];

    protected $casts = [
        'amount' => 'float',
        'paid_at' => 'datetime',
    ];

    public function payable(): MorphTo
    {
        return $this->morphTo();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopePaid(Builder $query): Builder
    {
        return $query->where('status', 'paid');
    }

    public function scopeOfType(Builder $query, ?string $type): Builder
    {
        return $type ? $query->where('payable_type', $type) : $query;
    }

    public function getTypeLabelAttribute(): string
    {
        return self::TYPES[$this->payable_type] ?? $this->payable_type;
    }

    public function getTypeColorAttribute(): string
    {
        return self::TYPE_COLORS[$this->payable_type] ?? '#6B7280';
    }

    public function getStatusLabelAttribute(): string
    {
        return self::STATUSES[$this->status] ?? $this->status;
    }

    public function getStatusBadgeAttribute(): string
    {
        return self::STATUS_BADGES[$this->status] ?? 'info';
    }

    public function getMethodLabelAttribute(): string
    {
        return self::METHODS[$this->payment_method] ?? strtoupper((string) $this->payment_method);
    }

    public function getReferenceAttribute(): string
    {
        return $this->payable?->paymentReference()
            ?? '#'.(self::TYPE_PREFIXES[$this->payable_type] ?? 'PAY').'-'.str_pad((string) $this->payable_id, 5, '0', STR_PAD_LEFT);
    }

    public function getDescriptionAttribute(): string
    {
        return $this->payable?->paymentDescription() ?? 'Đơn nguồn đã bị xóa';
    }

    public function getCustomerNameAttribute(): string
    {
        return $this->payable?->paymentCustomerName() ?: ($this->user?->name ?? 'Khách lẻ');
    }

    public static function generateTransactionCode(string $type): string
    {
        $prefix = self::TYPE_PREFIXES[$type] ?? 'PAY';

        return $prefix.'-'.now()->format('ymdHis').'-'.strtoupper(Str::random(5));
    }
}
