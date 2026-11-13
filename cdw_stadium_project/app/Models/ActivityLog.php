<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActivityLog extends Model
{
    use HasFactory;

    public const MODEL_LABELS = [
        'Facility' => 'Cơ sở',
        'SportType' => 'Loại môn thể thao',
        'Court' => 'Sân thể thao',
        'Product' => 'Sản phẩm',
        'Sale' => 'Đơn bán hàng',
        'Booking' => 'Đơn đặt sân',
        'Subscription' => 'Đăng ký gói TV',
        'Plan' => 'Gói thành viên (Plan)',
        'Payment' => 'Thanh toán',
        'Comment' => 'Bình luận tin tức',
        'Review' => 'Đánh giá sân',
        'User' => 'Người dùng',
        'News' => 'Tin tức',
        'Ad' => 'Quảng cáo',
    ];

    public const ACTION_LABELS = [
        'Created' => 'Thêm mới',
        'Updated' => 'Cập nhật',
        'Deleted' => 'Xóa',
    ];

    protected $fillable = [
        'user_id',
        'action',
        'description',
        'ip_address',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

