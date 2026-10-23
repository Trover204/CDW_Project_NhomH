<?php

namespace App\Contracts;

use Illuminate\Database\Eloquent\Relations\MorphOne;

/**
 * Đơn hàng phát sinh tiền (đặt sân, bán hàng, gói thành viên).
 * Mọi Payable đều xuất dữ liệu thanh toán qua bảng payments.
 */
interface Payable
{
    public function payment(): MorphOne;

    /** Số tiền cần thanh toán của đơn. */
    public function payableAmount(): float;

    /** Người dùng sở hữu đơn (null với khách lẻ). */
    public function payableUserId(): ?int;

    /** Tên khách hàng hiển thị trên chứng từ thanh toán. */
    public function paymentCustomerName(): ?string;

    /** Mã tham chiếu của đơn, ví dụ #BK-00001. */
    public function paymentReference(): string;

    /** Mô tả ngắn nội dung đơn. */
    public function paymentDescription(): string;

    /** Phương thức thanh toán mặc định khi chưa chỉ định. */
    public function defaultPaymentMethod(): string;

    /** Suy ra trạng thái thanh toán dựa trên trạng thái đơn và trạng thái thanh toán hiện tại. */
    public function resolvePaymentStatus(?string $currentStatus): string;
}
