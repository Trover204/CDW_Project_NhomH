@extends('web.layouts.app')

@section('title', __('Thanh toán đơn hàng') . ' - ' . $product->name . ' - ' . config('app.name', 'Sân Nhóm H'))

@push('styles')
<style>
    .checkout-container {
        max-width: 820px;
        margin: 36px auto 70px;
        padding: 0 20px;
    }

    /* Stepper Bar */
    .checkout-stepper {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 32px;
        position: relative;
    }
    .checkout-stepper::before {
        content: "";
        position: absolute;
        top: 21px;
        left: 40px;
        right: 40px;
        height: 3px;
        background: var(--line, #E3ECE6);
        z-index: 1;
    }
    .step-item {
        position: relative;
        z-index: 2;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 8px;
        cursor: pointer;
        user-select: none;
    }
    .step-number {
        width: 44px;
        height: 44px;
        border-radius: 50%;
        background: var(--bg-white, #FFFFFF);
        border: 2.5px solid var(--line, #D1D5DB);
        color: var(--ink-soft, #6B7280);
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 16px;
        transition: all 0.3s ease;
        box-shadow: 0 2px 8px rgba(0,0,0,0.04);
    }
    .step-item.active .step-number {
        background: linear-gradient(135deg, #FF2E93 0%, #DB2777 100%);
        border-color: #EC4899;
        color: #FFFFFF;
        box-shadow: 0 4px 14px rgba(236, 72, 153, 0.4);
        transform: scale(1.08);
    }
    .step-item.completed .step-number {
        background: #FCE7F3;
        border-color: #EC4899;
        color: #BE185D;
    }
    .step-label {
        font-size: 13.5px;
        font-weight: 600;
        color: var(--ink-soft, #6B7280);
        white-space: nowrap;
        transition: color 0.3s ease;
    }
    .step-item.active .step-label {
        color: #EC4899;
        font-weight: 700;
    }

    /* Checkout Card */
    .checkout-card {
        background: var(--bg-white, #FFFFFF);
        border: 1px solid var(--line, #F1E4EC);
        border-radius: 24px;
        padding: 36px 32px;
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.05);
    }

    /* Product Summary Header */
    .checkout-prod-box {
        display: flex;
        align-items: center;
        gap: 18px;
        padding: 18px 20px;
        background: var(--bg-page, #FAF7F9);
        border-radius: 16px;
        border: 1px solid var(--line, #F1E4EC);
        margin-bottom: 28px;
    }
    .checkout-prod-img {
        width: 84px;
        height: 84px;
        background: #FFFFFF;
        border-radius: 12px;
        border: 1px solid var(--line, #F1E4EC);
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        padding: 6px;
    }
    .checkout-prod-img img {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
    }

    /* Quantity Control Buttons */
    .qty-btn {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        background: #FFFFFF;
        border: 2px solid var(--line, #D1D5DB);
        font-size: 22px;
        font-weight: 700;
        color: var(--ink, #181824);
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
        user-select: none;
    }
    .qty-btn:hover {
        border-color: #EC4899;
        color: #EC4899;
        background: #FDF2F8;
        transform: translateY(-1px);
    }
    .qty-btn:active {
        transform: scale(0.95);
    }

    /* Payment Radio Cards */
    .payment-option-card {
        display: flex;
        align-items: center;
        padding: 16px 18px;
        border: 2px solid var(--line, #E5E7EB);
        border-radius: 14px;
        cursor: pointer;
        transition: all 0.2s ease;
        background: var(--bg-white, #FFFFFF);
        margin-bottom: 12px;
        position: relative;
        user-select: none;
    }
    .payment-option-card:hover {
        border-color: #EC4899;
        background: #FDF2F8;
    }
    .payment-option-card.selected {
        border-color: #EC4899;
        background: #FDF2F8;
        box-shadow: 0 4px 14px rgba(236, 72, 153, 0.14);
    }
    .payment-option-card input[type="radio"] {
        margin-right: 14px;
        width: 20px;
        height: 20px;
        accent-color: #EC4899;
        cursor: pointer;
    }

    /* Form Fields */
    .form-group-custom {
        margin-bottom: 20px;
    }
    .form-label-custom {
        display: block;
        font-size: 14px;
        font-weight: 700;
        color: var(--ink, #181824);
        margin-bottom: 8px;
    }
    .form-label-custom span.req {
        color: #DC2626;
        margin-left: 2px;
    }
    .form-input-custom {
        width: 100%;
        height: 48px;
        padding: 0 16px;
        border: 1.5px solid var(--line, #D1D5DB);
        border-radius: 12px;
        font-size: 14.5px;
        background: var(--bg-white, #FFFFFF);
        color: var(--ink, #181824);
        outline: none;
        transition: all 0.2s;
    }
    .form-input-custom:focus {
        border-color: #EC4899;
        box-shadow: 0 0 0 3px rgba(236, 72, 153, 0.14);
    }

    /* Size Selector Pills */
    .size-pills {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }
    .size-pill-label {
        flex: 1 1 50px;
        text-align: center;
        padding: 10px;
        border: 1.5px solid var(--line, #D1D5DB);
        border-radius: 10px;
        font-size: 14px;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.2s;
        background: var(--bg-white, #FFFFFF);
        color: var(--ink, #181824);
        user-select: none;
    }
    .size-pill-label:hover {
        border-color: #EC4899;
        color: #EC4899;
    }
    .size-pill-label.selected {
        background: linear-gradient(135deg, #FF2E93 0%, #DB2777 100%);
        color: #FFFFFF;
        border-color: #EC4899;
    }

    /* QR Code Display Frame */
    .qr-payment-frame {
        background: linear-gradient(135deg, #FDF2F8 0%, #FAF7F9 100%);
        border: 2px solid #FBCFE8;
        border-radius: 20px;
        padding: 26px;
        margin-bottom: 28px;
        text-align: center;
        transition: all 0.3s ease;
    }
    [data-theme="dark"] .qr-payment-frame {
        background: #1f2937;
        border-color: #374151;
    }
    .qr-switch-pills {
        display: flex;
        justify-content: center;
        gap: 12px;
        margin-bottom: 18px;
        flex-wrap: wrap;
    }
    .qr-switch-pill {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 9px 18px;
        border-radius: 30px;
        font-size: 13.5px;
        font-weight: 700;
        border: 1.5px solid var(--line, #CBD5E1);
        background: var(--bg-white, #FFFFFF);
        color: var(--ink, #1F2937);
        cursor: pointer;
        transition: all 0.2s ease;
        box-shadow: 0 1px 3px rgba(0,0,0,0.04);
    }
    .qr-switch-pill:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 10px rgba(0,0,0,0.08);
    }
    .qr-switch-pill.active-bank {
        background: linear-gradient(135deg, #FF2E93 0%, #DB2777 100%);
        color: #FFFFFF;
        border-color: #EC4899;
        box-shadow: 0 4px 12px rgba(236, 72, 153, 0.28);
    }
    .qr-switch-pill.active-momo {
        background: #A50064;
        color: #FFFFFF;
        border-color: #A50064;
        box-shadow: 0 4px 12px rgba(165, 0, 100, 0.25);
    }
    .momo-mini-badge {
        background: #A50064;
        color: #FFFFFF;
        font-size: 11px;
        font-weight: 800;
        padding: 2px 7px;
        border-radius: 5px;
        line-height: 1;
    }
    .active-momo .momo-mini-badge {
        background: #FFFFFF;
        color: #A50064;
    }
    .qr-img-wrapper {
        background: #FFFFFF;
        padding: 14px;
        border-radius: 16px;
        display: inline-block;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08);
        border: 1px solid #E2E8F0;
        margin-bottom: 18px;
    }
    .qr-img-wrapper img {
        max-width: 240px;
        height: auto;
        display: block;
    }

    .bank-info-table {
        width: 100%;
        max-width: 460px;
        margin: 0 auto 20px;
        border-collapse: collapse;
        text-align: left;
    }
    .bank-info-table td {
        padding: 10px 12px;
        font-size: 14px;
        border-bottom: 1px dashed rgba(0,0,0,0.08);
    }
    .bank-info-table td:first-child {
        color: var(--ink-soft, #64748B);
        font-weight: 500;
        width: 38%;
    }
    .bank-info-table td:last-child {
        color: var(--ink, #0F172A);
        font-weight: 700;
    }

    .btn-copy {
        background: #FCE7F3;
        border: none;
        color: #BE185D;
        font-size: 11.5px;
        font-weight: 600;
        padding: 4px 9px;
        border-radius: 6px;
        cursor: pointer;
        transition: all 0.2s;
        margin-left: 8px;
        float: right;
    }
    .btn-copy:hover {
        background: #EC4899;
        color: #FFFFFF;
    }

    /* Buttons */
    .btn-checkout-primary {
        width: 100%;
        height: 52px;
        border-radius: 12px;
        background: linear-gradient(135deg, #FF2E93 0%, #DB2777 100%);
        color: #FFFFFF;
        border: none;
        font-size: 16px;
        font-weight: 700;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        transition: all 0.2s;
        box-shadow: 0 4px 14px rgba(236, 72, 153, 0.3);
    }
    .btn-checkout-primary:hover {
        background: linear-gradient(135deg, #DB2777 0%, #BE185D 100%);
        box-shadow: 0 6px 18px rgba(236, 72, 153, 0.42);
        transform: translateY(-1px);
    }
    .btn-checkout-back {
        height: 52px;
        padding: 0 24px;
        border-radius: 12px;
        background: var(--bg-page, #F3F4F6);
        color: var(--ink-soft, #4B5563);
        border: 1.5px solid var(--line, #D1D5DB);
        font-size: 14.5px;
        font-weight: 600;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.2s;
    }
    .btn-checkout-back:hover {
        background: var(--line, #E5E7EB);
        color: var(--ink, #111827);
    }

    @media (max-width: 640px) {
        .checkout-card {
            padding: 24px 18px;
        }
        .checkout-stepper {
            margin-bottom: 24px;
        }
        .step-label {
            font-size: 11.5px;
        }
        .step-number {
            width: 38px;
            height: 38px;
            font-size: 14px;
        }
    }
</style>
@endpush

@section('content')

<div class="checkout-container">

    {{-- Breadcrumb --}}
    <div style="margin-bottom: 22px; font-size: 14px; color: var(--ink-soft, #525266); display: flex; align-items: center; gap: 8px;">
        <a href="{{ route('products.index') }}" style="color: #EC4899; text-decoration: none; font-weight: 600; display: inline-flex; align-items: center; gap: 4px;">
            <i class="ti ti-arrow-left"></i> Cửa hàng Merch
        </a>
        <span>/</span>
        <span style="color: var(--ink, #181824); font-weight: 500;">{{ __('Quy trình đặt mua & Thanh toán') }}</span>
    </div>

    @if(session('error'))
        <div style="background: #E53E3E; color: white; padding: 14px 20px; border-radius: 12px; margin-bottom: 24px; text-align: center; font-weight: 600; box-shadow: 0 4px 12px rgba(229,62,62,0.15);">
            <i class="ti ti-alert-triangle" style="margin-right: 6px;"></i> {{ session('error') }}
        </div>
    @endif

    {{-- 3-Step Wizard Stepper (No inline onclick!) --}}
    <div class="checkout-stepper">
        <div class="step-item active" id="step-nav-1">
            <div class="step-number" id="step-num-1">1</div>
            <div class="step-label">1. {{ __('Chọn thanh toán') }}</div>
        </div>
        <div class="step-item" id="step-nav-2">
            <div class="step-number" id="step-num-2">2</div>
            <div class="step-label">2. {{ __('Thông tin khách') }}</div>
        </div>
        <div class="step-item" id="step-nav-3">
            <div class="step-number" id="step-num-3">3</div>
            <div class="step-label">3. {{ __('Quét mã QR') }}</div>
        </div>
    </div>

    <div class="checkout-card">

        {{-- Product Summary Header --}}
        <div class="checkout-prod-box">
            <div class="checkout-prod-img">
                @if($product->image_url)
                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}">
                @else
                    <i class="ti ti-shirt" style="font-size: 36px; color: #9CA3AF;"></i>
                @endif
            </div>

            <div style="flex-grow: 1;">
                @if($product->team)
                    <span style="display: inline-block; background: #FDF2F8; color: #DB2777; font-size: 11.5px; font-weight: 600; padding: 2px 8px; border-radius: 4px; margin-bottom: 4px;">
                        <i class="ti ti-shield" style="font-size: 11px;"></i> {{ $product->team }} {{ $product->season ? "• {$product->season}" : '' }}
                    </span>
                @endif
                <div style="font-weight: 700; color: var(--ink, #181824); font-size: 16.5px; margin-bottom: 6px;">{{ $product->name }}</div>
                <div style="display: flex; align-items: baseline; justify-content: space-between;">
                    <span style="font-size: 13px; color: #DB2777; font-weight: 600;">
                        <i class="ti ti-check" style="font-size: 12px;"></i> Còn {{ $product->stock_quantity }} sản phẩm
                    </span>
                    <span style="font-size: 19px; font-weight: 800; color: #EC4899; font-family: 'Oswald', sans-serif;">
                        {{ number_format($product->cost_price, 0, ',', '.') }}₫
                    </span>
                </div>
            </div>
        </div>

        {{-- The Master Form --}}
        <form action="{{ route('products.purchase', $product->id) }}" method="POST" id="purchase-form">
            @csrf

            {{-- ========================================================
                 BƯỚC 1: CHỌN SỐ LƯỢNG & PHƯƠNG THỨC THANH TOÁN
            ======================================================== --}}
            <div id="step-section-1">
                <h3 style="font-size: 18px; font-weight: 700; color: var(--ink, #181824); margin: 0 0 18px; display: flex; align-items: center; gap: 8px;">
                    <i class="ti ti-credit-card" style="color: #EC4899;"></i> {{ __('Bước 1: Chọn số lượng & Phương thức thanh toán') }}
                </h3>

                {{-- Quantity Selector --}}
                <div class="form-group-custom" style="background: var(--bg-page, #FAF7F9); padding: 16px 20px; border-radius: 14px; border: 1px solid var(--line, #F1E4EC); margin-bottom: 24px;">
                    <label class="form-label-custom">
                        {{ __('Số lượng áo đặt mua') }} <span class="req">*</span>
                    </label>
                    <div style="display: flex; align-items: center; gap: 14px;">
                        <button type="button" id="btn-qty-minus" class="qty-btn" aria-label="Giảm số lượng">-</button>
                        <input type="number" name="quantity" id="quantity" value="1" min="1" max="{{ $product->stock_quantity }}" required
                               class="form-input-custom" style="width: 80px; text-align: center; font-weight: 700; font-size: 18px;">
                        <button type="button" id="btn-qty-plus" class="qty-btn" aria-label="Tăng số lượng">+</button>
                        <span style="font-size: 13.5px; color: var(--ink-soft, #6B7280);">
                            (Tối đa: {{ $product->stock_quantity }} cái)
                        </span>
                    </div>
                </div>

                {{-- Payment Methods --}}
                <div class="form-group-custom">
                    <label class="form-label-custom">
                        Chọn phương thức thanh toán <span class="req">*</span>
                    </label>

                    {{-- VietQR Bank Transfer (Recommended) --}}
                    <label class="payment-option-card selected" id="opt-qr">
                        <input type="radio" name="payment_method" value="qr" checked>
                        <div style="flex-grow: 1;">
                            <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 4px;">
                                <span style="background: #EC4899; color: #fff; padding: 3px 8px; border-radius: 6px; font-size: 11px; font-weight: 700;">{{ __('KHUYÊN DÙNG') }}</span>
                                <span style="font-weight: 700; font-size: 15px; color: var(--ink, #181824);">{{ __('Quét mã QR Ngân Hàng (VietQR / Chuyển khoản 24/7)') }}</span>
                            </div>
                            <div style="font-size: 13px; color: var(--ink-soft, #6B7280);">{{ __('Quét mã chuyển tiền tức thì từ MB Bank, Vietcombank, Techcombank, BIDV... hoặc MoMo') }}</div>
                        </div>
                        <i class="ti ti-qrcode" style="font-size: 28px; color: #EC4899;"></i>
                    </label>

                    {{-- MoMo --}}
                    <label class="payment-option-card" id="opt-momo">
                        <input type="radio" name="payment_method" value="momo">
                        <div style="flex-grow: 1;">
                            <div style="font-weight: 700; font-size: 15px; color: var(--ink, #111827); margin-bottom: 4px;">{{ __('Ví điện tử MoMo') }}</div>
                            <div style="font-size: 13px; color: var(--ink-soft, #6B7280);">{{ __('Thanh toán qua ví điện tử MoMo hoặc quét mã QR MoMo') }}</div>
                        </div>
                        <span style="display: inline-flex; align-items: center; justify-content: center; width: 34px; height: 34px; border-radius: 8px; background: #A50064; color: #fff; font-weight: 700; font-size: 11px;">MoMo</span>
                    </label>

                    {{-- Visa / Mastercard --}}
                    <label class="payment-option-card" id="opt-visa">
                        <input type="radio" name="payment_method" value="visa">
                        <div style="flex-grow: 1;">
                            <div style="font-weight: 700; font-size: 15px; color: var(--ink, #111827); margin-bottom: 4px;">{{ __('Thẻ Quốc tế (Visa / MasterCard / JCB)') }}</div>
                            <div style="font-size: 13px; color: var(--ink-soft, #6B7280);">{{ __('Thanh toán bằng thẻ ghi nợ hoặc thẻ tín dụng quốc tế an toàn 3D Secure') }}</div>
                        </div>
                        <i class="ti ti-credit-card" style="font-size: 26px; color: #1E3A8A;"></i>
                    </label>

                    {{-- PayPal --}}
                    <label class="payment-option-card" id="opt-paypal">
                        <input type="radio" name="payment_method" value="paypal">
                        <div style="flex-grow: 1;">
                            <div style="font-weight: 700; font-size: 15px; color: var(--ink, #111827); margin-bottom: 4px;">{{ __('Cổng thanh toán PayPal') }}</div>
                            <div style="font-size: 13px; color: var(--ink-soft, #6B7280);">{{ __('Thanh toán quốc tế qua tài khoản PayPal bảo mật') }}</div>
                        </div>
                        <i class="ti ti-brand-paypal" style="font-size: 26px; color: #003087;"></i>
                    </label>

                    {{-- COD --}}
                    <label class="payment-option-card" id="opt-cod">
                        <input type="radio" name="payment_method" value="cod">
                        <div style="flex-grow: 1;">
                            <div style="font-weight: 700; font-size: 15px; color: var(--ink, #111827); margin-bottom: 4px;">{{ __('Thanh toán khi nhận hàng (COD)') }}</div>
                            <div style="font-size: 13px; color: var(--ink-soft, #6B7280);">{{ __('Kiểm tra hàng trước khi thanh toán tiền mặt cho nhân viên giao hàng') }}</div>
                        </div>
                        <i class="ti ti-truck" style="font-size: 26px; color: #D97706;"></i>
                    </label>
                </div>

                {{-- Price Total Box --}}
                <div style="display: flex; justify-content: space-between; align-items: center; padding: 18px 20px; background: #FDF2F8; border-radius: 14px; border: 1.5px solid #FBCFE8; margin-bottom: 24px;">
                    <div>
                        <span style="display: block; font-size: 12.5px; text-transform: uppercase; letter-spacing: 0.5px; font-weight: 700; color: #BE185D;">Tổng tiền tạm tính</span>
                        <span style="font-size: 12px; color: #DB2777;">{{ __('Miễn phí') }} giao hàng toàn quốc</span>
                    </div>
                    <span style="font-weight: 800; font-size: 26px; color: #EC4899; font-family: 'Oswald', sans-serif;" class="total-display-tag">
                        {{ number_format($product->cost_price, 0, ',', '.') }}₫
                    </span>
                </div>

                <button type="button" id="btn-to-step2" class="btn-checkout-primary">
                    Tiếp tục: Nhập thông tin khách hàng <i class="ti ti-arrow-right"></i>
                </button>
            </div>

            {{-- ========================================================
                 BƯỚC 2: NHẬP THÔNG TIN KHÁCH HÀNG
            ======================================================== --}}
            <div id="step-section-2" style="display: none;">
                <h3 style="font-size: 18px; font-weight: 700; color: var(--ink, #181824); margin: 0 0 18px; display: flex; align-items: center; gap: 8px;">
                    <i class="ti ti-user-check" style="color: #EC4899;"></i> Bước 2: Nhập thông tin nhận hàng
                </h3>

                {{-- Customer Name --}}
                <div class="form-group-custom">
                    <label class="form-label-custom">
                        {{ __('Họ và tên người nhận') }} <span class="req">*</span>
                    </label>
                    <input type="text" name="customer_name" id="customer_name"
                           value="{{ old('customer_name', auth()->user()->name ?? '') }}"
                           class="form-input-custom" placeholder="Ví dụ: Nguyễn Văn A" required>
                    <div id="err-name" style="color: #DC2626; font-size: 13px; margin-top: 5px; display: none;">{{ __('Vui lòng nhập họ và tên người nhận.') }}</div>
                </div>

                {{-- Customer Phone --}}
                <div class="form-group-custom">
                    <label class="form-label-custom">
                        Số điện thoại liên hệ <span class="req">*</span>
                    </label>
                    <input type="tel" name="customer_phone" id="customer_phone"
                           value="{{ old('customer_phone', auth()->user()->phone ?? '') }}"
                           class="form-input-custom" placeholder="Ví dụ: 0987654321" required>
                    <div id="err-phone" style="color: #DC2626; font-size: 13px; margin-top: 5px; display: none;">{{ __('Vui lòng nhập số điện thoại hợp lệ (tối thiểu 9 số).') }}</div>
                </div>

                {{-- Shipping Address --}}
                <div class="form-group-custom">
                    <label class="form-label-custom">
                        {{ __('Địa chỉ nhận hàng chi tiết') }} <span class="req">*</span>
                    </label>
                    <input type="text" name="shipping_address" id="shipping_address"
                           class="form-input-custom" placeholder="Số nhà, tên đường, phường/xã, quận/huyện, tỉnh/thành phố..." required>
                    <div id="err-address" style="color: #DC2626; font-size: 13px; margin-top: 5px; display: none;">{{ __('Vui lòng nhập địa chỉ nhận hàng chi tiết.') }}</div>
                </div>

                {{-- Size Selection --}}
                <div class="form-group-custom">
                    <label class="form-label-custom">
                        Chọn kích thước áo (Size)
                    </label>
                    <div class="size-pills">
                        <div class="size-pill-label" data-size="S">S</div>
                        <div class="size-pill-label" data-size="M">M</div>
                        <div class="size-pill-label selected" data-size="L">L</div>
                        <div class="size-pill-label" data-size="XL">XL</div>
                        <div class="size-pill-label" data-size="XXL">XXL</div>
                    </div>
                </div>

                {{-- Note --}}
                <div class="form-group-custom">
                    <label class="form-label-custom">
                        Ghi chú đơn hàng (Tùy chọn)
                    </label>
                    <textarea name="note" id="order_note" rows="2"
                              style="width: 100%; padding: 12px 16px; border: 1.5px solid var(--line, #D1D5DB); border-radius: 12px; font-size: 14px; outline: none; background: var(--bg-white, #fff); color: var(--ink, #11201B);"
                              placeholder="Ví dụ: In số 7 sau lưng áo, giao giờ hành chính...">Size L</textarea>
                </div>

                {{-- Navigation Buttons --}}
                <div style="display: flex; gap: 12px; margin-top: 28px;">
                    <button type="button" id="btn-back-step-1" class="btn-checkout-back">
                        <i class="ti ti-arrow-left"></i> Quay lại
                    </button>
                    <button type="button" id="btn-to-step-3" class="btn-checkout-primary">
                        Tiếp tục: Quét mã QR thanh toán <i class="ti ti-arrow-right"></i>
                    </button>
                </div>
            </div>

            {{-- ========================================================
                 BƯỚC 3: MÃ QR THANH TOÁN & XÁC NHẬN ĐƠN HÀNG
            ======================================================== --}}
            <div id="step-section-3" style="display: none;">
                <h3 style="font-size: 18px; font-weight: 700; color: var(--ink, #181824); margin: 0 0 18px; display: flex; align-items: center; gap: 8px;">
                    <i class="ti ti-qrcode" style="color: #EC4899;"></i> Bước 3: Quét mã QR & Xác nhận thanh toán
                </h3>

                {{-- QR Payment Container (Visible for QR / Bank / MoMo) --}}
                <div id="qr-display-box" class="qr-payment-frame">
                    {{-- Switch Pills between Bank and MoMo --}}
                    <div class="qr-switch-pills">
                        <button type="button" id="pill-bank" class="qr-switch-pill active-bank">
                            <i class="ti ti-building-bank"></i> {{ __('QR Ngân Hàng (TPBank)') }}
                        </button>
                        <button type="button" id="pill-momo" class="qr-switch-pill">
                            <span class="momo-mini-badge">MoMo</span> {{ __('QR Ví MoMo') }}
                        </button>
                    </div>

                    {{-- Countdown Timer (Shared for both) --}}
                    <div style="font-size: 13px; color: #DC2626; font-weight: 700; margin-bottom: 16px; display: flex; align-items: center; justify-content: center; gap: 6px;">
                        <i class="ti ti-clock"></i> {{ __('Mã QR có hiệu lực trong:') }} <span id="countdown-timer">14:59</span>
                    </div>

                    {{-- =========================================
                         VIEW 1: MÃ QR TPBANK (VIETQR)
                    ========================================= --}}
                    <div id="bank-qr-view">
                        <div style="display: flex; align-items: center; justify-content: center; gap: 8px; margin-bottom: 12px;">
                            <span style="background: #EC4899; color: #fff; font-size: 11px; font-weight: 700; padding: 4px 10px; border-radius: 20px;">
                                VIETQR CHUẨN QUỐC GIA
                            </span>
                            <span style="font-size: 13px; color: #475569; font-weight: 600;">
                                {{ __('Hỗ trợ tất cả App Ngân Hàng & MoMo') }}
                            </span>
                        </div>

                        {{-- QR Image --}}
                        <div class="qr-img-wrapper" style="display: inline-flex; flex-direction: column; align-items: center; padding: 16px; background: #ffffff; border-radius: 18px; box-shadow: 0 8px 24px rgba(0,0,0,0.08); border: 1.5px solid #E2E8F0; margin-bottom: 16px;">
                            <img id="vietqr-image" src="{{ asset('img qr/qr_card_clean.jpg') }}" alt="Mã QR TPBank - NGUYEN CHI HAI" style="max-width: 270px; width: 100%; height: auto; border-radius: 12px; display: block;">
                            <div style="margin-top: 12px;">
                                <a href="{{ asset('img qr/qr_card_clean.jpg') }}" download="QR_ThanhToan_TPBank.jpg" class="btn-copy" style="text-decoration: none; display: inline-flex; align-items: center; gap: 6px; padding: 7px 14px; font-size: 13px; font-weight: 600;">
                                    <i class="ti ti-download"></i> {{ __('Tải mã QR') }} về máy
                                </a>
                            </div>
                        </div>

                        {{-- Transfer Info Table with One-Click Copy --}}
                        <table class="bank-info-table">
                            <tr>
                                <td>{{ __('Ngân hàng:') }}</td>
                                <td>
                                    <strong>TPBank (Ngân hàng Tiên Phong)</strong>
                                    <span style="font-size: 11px; color: #DB2777; margin-left: 8px;">24/7 Tức thì</span>
                                </td>
                            </tr>
                            <tr>
                                <td>{{ __('Số tài khoản:') }}</td>
                                <td>
                                    <span id="bank-acc-num" style="font-weight: 700; font-size: 16px; letter-spacing: 0.5px;">1000 6118 170</span>
                                    <button type="button" class="btn-copy" id="btn-copy-acc">{{ __('Sao chép') }}</button>
                                </td>
                            </tr>
                            <tr>
                                <td>{{ __('Chủ tài khoản:') }}</td>
                                <td>
                                    <strong style="text-transform: uppercase;">NGUYEN CHI HAI</strong>
                                </td>
                            </tr>
                            <tr>
                                <td>{{ __('Số tiền cần chuyển:') }}</td>
                                <td>
                                    <span id="bank-amount" style="color: #EC4899; font-weight: 800; font-size: 16px;"></span>
                                    <button type="button" class="btn-copy" id="btn-copy-amount">{{ __('Sao chép') }}</button>
                                </td>
                            </tr>
                            <tr>
                                <td>{{ __('Nội dung chuyển khoản:') }}</td>
                                <td>
                                    <span id="bank-memo" style="color: #DB2777; font-weight: 700; font-family: monospace; font-size: 15px;"></span>
                                    <button type="button" class="btn-copy" id="btn-copy-memo">{{ __('Sao chép') }}</button>
                                </td>
                            </tr>
                        </table>

                        <div style="background: rgba(236,72,153,0.08); border-radius: 10px; padding: 12px; font-size: 13px; color: #9D174D; line-height: 1.5; max-width: 480px; margin: 0 auto; text-align: left;">
                            <i class="ti ti-info-circle" style="margin-right: 4px;"></i>
                            <strong>{{ __('Hướng dẫn:') }}</strong> Quý khách mở App Ngân hàng (TPBank, MB, VCB, BIDV, Techcombank...) hoặc MoMo, chọn <strong>Quét mã QR</strong> để quét mã trên. Vui lòng giữ nguyên nội dung chuyển khoản để đơn hàng được xác nhận nhanh chóng.
                        </div>
                    </div>

                    {{-- =========================================
                         VIEW 2: MÃ QR VÍ MOMO (NAPAS 247)
                    ========================================= --}}
                    <div id="momo-qr-view" style="display: none;">
                        <div style="display: flex; align-items: center; justify-content: center; gap: 8px; margin-bottom: 12px;">
                            <span style="background: #A50064; color: #fff; font-size: 11px; font-weight: 700; padding: 4px 10px; border-radius: 20px;">
                                VÍ ĐIỆN TỬ MOMO
                            </span>
                            <span style="font-size: 13px; color: #475569; font-weight: 600;">
                                Hỗ trợ tất cả App MoMo & Napas 247
                            </span>
                        </div>

                        {{-- MoMo QR Image --}}
                        <div class="qr-img-wrapper" style="display: inline-flex; flex-direction: column; align-items: center; padding: 16px; background: #ffffff; border-radius: 18px; box-shadow: 0 8px 24px rgba(165,0,100,0.12); border: 2px solid #FBCFE8; margin-bottom: 16px;">
                            <img id="momo-qr-image" src="{{ asset('img qr/momo_card_clean.jpg') }}" alt="Mã {{ __('QR Ví MoMo') }} - NGUYEN CHI HAI" style="max-width: 270px; width: 100%; height: auto; border-radius: 12px; display: block;">
                            <div style="margin-top: 12px;">
                                <a href="{{ asset('img qr/momo_card_clean.jpg') }}" download="QR_ThanhToan_MoMo.jpg" class="btn-copy" style="text-decoration: none; display: inline-flex; align-items: center; gap: 6px; padding: 7px 14px; font-size: 13px; font-weight: 600; background: #A50064; color: #fff; border-color: #A50064;">
                                    <i class="ti ti-download"></i> {{ __('Tải mã QR') }} MoMo về máy
                                </a>
                            </div>
                        </div>

                        {{-- Transfer Info Table with One-Click Copy for MoMo --}}
                        <table class="bank-info-table">
                            <tr>
                                <td>Cổng thanh toán:</td>
                                <td>
                                    <strong style="color: #A50064;">{{ __('Ví điện tử MoMo') }}</strong>
                                    <span style="font-size: 11px; color: #A50064; margin-left: 8px; background: #FDF2F8; padding: 2px 6px; border-radius: 4px; font-weight: 700;">Napas 247</span>
                                </td>
                            </tr>
                            <tr>
                                <td>Số tài khoản MoMo:</td>
                                <td>
                                    <span id="momo-acc-num" style="font-weight: 700; font-size: 15px; letter-spacing: 0.5px; color: #111827;">PSP2615713900000057</span>
                                    <button type="button" class="btn-copy" id="btn-copy-momo-acc">{{ __('Sao chép') }}</button>
                                </td>
                            </tr>
                            <tr>
                                <td>{{ __('Chủ tài khoản:') }}</td>
                                <td>
                                    <strong style="text-transform: uppercase;">NGUYEN CHI HAI</strong>
                                </td>
                            </tr>
                            <tr>
                                <td>{{ __('Số tiền cần chuyển:') }}</td>
                                <td>
                                    <span id="momo-amount" style="color: #A50064; font-weight: 800; font-size: 16px;"></span>
                                    <button type="button" class="btn-copy" id="btn-copy-momo-amount">{{ __('Sao chép') }}</button>
                                </td>
                            </tr>
                            <tr>
                                <td>Nội dung chuyển tiền:</td>
                                <td>
                                    <span id="momo-memo" style="color: #A50064; font-weight: 700; font-family: monospace; font-size: 15px;"></span>
                                    <button type="button" class="btn-copy" id="btn-copy-momo-memo">{{ __('Sao chép') }}</button>
                                </td>
                            </tr>
                        </table>

                        <div style="background: rgba(165,0,100,0.06); border: 1px solid #FBCFE8; border-radius: 10px; padding: 12px; font-size: 13px; color: #831843; line-height: 1.5; max-width: 480px; margin: 0 auto; text-align: left;">
                            <i class="ti ti-info-circle" style="margin-right: 4px; color: #A50064;"></i>
                            <strong>Hướng dẫn MoMo:</strong> Mở ứng dụng MoMo &rarr; Chọn <strong>Quét mã QR</strong> &rarr; Quét mã trên để chuyển tiền tự động. Vui lòng giữ nguyên nội dung chuyển tiền để đơn hàng được xác nhận nhanh chóng.
                        </div>
                    </div>
                </div>

                {{-- COD Alternative Notice (if COD chosen) --}}
                <div id="cod-display-box" style="display: none; background: #FEF3C7; border: 1.5px solid #FCD34D; border-radius: 16px; padding: 22px; margin-bottom: 24px;">
                    <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 8px;">
                        <i class="ti ti-truck-delivery" style="font-size: 26px; color: #B45309;"></i>
                        <h4 style="margin: 0; font-size: 16px; font-weight: 700; color: #92400E;">Thanh toán tiền mặt khi nhận hàng (COD)</h4>
                    </div>
                    <p style="margin: 0 0 10px; font-size: 14px; color: #78350F; line-height: 1.5;">
                        Quý khách sẽ thanh toán số tiền <strong class="total-display-tag" style="color: #B45309;"></strong> trực tiếp cho nhân viên giao hàng sau khi kiểm tra đầy đủ áo thi đấu và hóa đơn.
                    </p>
                </div>

                {{-- Real-time Payment Status Notice Box --}}
                <div style="background: #FDF2F8; border: 1px solid #FBCFE8; border-radius: 14px; padding: 14px 18px; margin-bottom: 22px; display: flex; align-items: center; gap: 12px;">
                    <div style="width: 38px; height: 38px; border-radius: 50%; background: #FCE7F3; color: #DB2777; display: flex; align-items: center; justify-content: center; font-size: 20px; flex-shrink: 0;">
                        <i class="ti ti-bell-ringing"></i>
                    </div>
                    <div style="font-size: 13px; color: #9D174D; line-height: 1.45;">
                        <strong>Thông báo xác nhận thanh toán tự động:</strong>
                        Sau khi quét mã chuyển khoản thành công, quý khách nhấn nút xác nhận bên dưới để hệ thống phát hành biên lai & lên đơn giao hàng ngay lập tức.
                    </div>
                </div>

                {{-- Final Confirmation Button --}}
                <div style="display: flex; gap: 12px;">
                    <button type="button" id="btn-back-step-2" class="btn-checkout-back">
                        <i class="ti ti-arrow-left"></i> Sửa thông tin
                    </button>
                    <button type="submit" class="btn-checkout-primary" id="btn-final-confirm">
                        <i class="ti ti-circle-check"></i> Xác nhận đã thanh toán & Hoàn tất đặt hàng
                    </button>
                </div>
            </div>

        </form>
    </div>
</div>

{{-- Copy Notification Toast --}}
<div id="copy-toast-notification" style="position: fixed; bottom: 28px; right: 28px; background: #181824; color: #FFFFFF; padding: 12px 20px; border-radius: 12px; font-size: 13.5px; font-weight: 600; display: none; align-items: center; gap: 8px; box-shadow: 0 10px 25px rgba(0,0,0,0.2); z-index: 99999; border: 1px solid #EC4899;">
    <i class="ti ti-circle-check" style="color: #FF2E93; font-size: 18px;"></i>
    <span id="copy-toast-text">Đã sao chép vào bộ nhớ tạm!</span>
</div>

{{-- Payment Verification Overlay Modal when submitting --}}
<div id="payment-verifying-overlay" style="position: fixed; inset: 0; background: rgba(15, 23, 42, 0.7); backdrop-filter: blur(5px); display: none; align-items: center; justify-content: center; z-index: 999999; padding: 20px;">
    <div style="background: var(--bg-white, #FFFFFF); border-radius: 24px; max-width: 440px; width: 100%; padding: 32px 26px; text-align: center; box-shadow: 0 25px 60px rgba(0,0,0,0.3); border: 1px solid var(--line, #F1E4EC);">
        <div style="width: 72px; height: 72px; border-radius: 50%; background: #FCE7F3; color: #EC4899; display: inline-flex; align-items: center; justify-content: center; font-size: 38px; margin-bottom: 16px; box-shadow: 0 8px 20px rgba(236,72,153,0.25);">
            <i class="ti ti-shield-check"></i>
        </div>
        <h3 style="margin: 0 0 8px; font-size: 21px; font-weight: 800; color: var(--ink, #181824); font-family: 'Oswald', sans-serif; letter-spacing: 0.4px;">
            ĐANG XÁC THỰC THANH TOÁN & KHỞI TẠO ĐƠN...
        </h3>
        <p style="margin: 0 0 18px; font-size: 14px; color: var(--ink-soft, #525266); line-height: 1.5;">
            Hệ thống đang ghi nhận giao dịch và khởi tạo phiếu xuất kho giao hàng cho đơn của bạn. Vui lòng đợi trong giây lát!
        </p>
        <div style="background: #FDF2F8; border: 1px solid #FBCFE8; border-radius: 12px; padding: 10px 14px; font-size: 13px; color: #DB2777; font-weight: 700;">
            <i class="ti ti-qrcode"></i> <span id="verifying-memo-display"></span>
        </div>
    </div>
</div>

@push('scripts')
<script nonce="{{ $cspNonce ?? '' }}">
    document.addEventListener('DOMContentLoaded', function() {
        const unitPrice = {{ (float) $product->cost_price }};
        const maxStock = {{ (int) $product->stock_quantity }};
        const orderCode = 'SL' + Math.floor(100000 + Math.random() * 900000);
        let selectedPayment = 'qr';

        const qtyInput = document.getElementById('quantity');
        const btnMinus = document.getElementById('btn-qty-minus');
        const btnPlus = document.getElementById('btn-qty-plus');

        const btnToStep2 = document.getElementById('btn-to-step2');
        const btnToStep3 = document.getElementById('btn-to-step-3');
        const btnBackStep1 = document.getElementById('btn-back-step-1');
        const btnBackStep2 = document.getElementById('btn-back-step-2');

        const stepNav1 = document.getElementById('step-nav-1');
        const stepNav2 = document.getElementById('step-nav-2');
        const stepNav3 = document.getElementById('step-nav-3');

        function updateCalculations() {
            let qty = parseInt(qtyInput.value) || 1;
            if (qty < 1) qty = 1;
            if (qty > maxStock) qty = maxStock;
            qtyInput.value = qty;

            const total = qty * unitPrice;
            const formattedTotal = new Intl.NumberFormat('vi-VN', { style: 'decimal' }).format(total) + '₫';

            document.querySelectorAll('.total-display-tag').forEach(el => {
                el.textContent = formattedTotal;
            });

            const amountEl = document.getElementById('bank-amount');
            if (amountEl) amountEl.textContent = formattedTotal;

            const momoAmountEl = document.getElementById('momo-amount');
            if (momoAmountEl) momoAmountEl.textContent = formattedTotal;

            const memoEl = document.getElementById('bank-memo');
            if (memoEl) memoEl.textContent = orderCode;

            const momoMemoEl = document.getElementById('momo-memo');
            if (momoMemoEl) momoMemoEl.textContent = orderCode;
        }

        // Quantity Buttons
        if (btnMinus) {
            btnMinus.addEventListener('click', function(e) {
                e.preventDefault();
                let val = parseInt(qtyInput.value) || 1;
                if (val > 1) {
                    qtyInput.value = val - 1;
                    updateCalculations();
                }
            });
        }

        if (btnPlus) {
            btnPlus.addEventListener('click', function(e) {
                e.preventDefault();
                let val = parseInt(qtyInput.value) || 1;
                if (val < maxStock) {
                    qtyInput.value = val + 1;
                    updateCalculations();
                }
            });
        }

        if (qtyInput) {
            qtyInput.addEventListener('input', updateCalculations);
            qtyInput.addEventListener('change', updateCalculations);
        }

        // Step navigation
        function goToStep(step) {
            document.getElementById('step-section-1').style.display = (step === 1) ? 'block' : 'none';
            document.getElementById('step-section-2').style.display = (step === 2) ? 'block' : 'none';
            document.getElementById('step-section-3').style.display = (step === 3) ? 'block' : 'none';

            for (let i = 1; i <= 3; i++) {
                const item = document.getElementById('step-nav-' + i);
                const num = document.getElementById('step-num-' + i);
                if (!item || !num) continue;
                item.classList.remove('active', 'completed');

                if (i < step) {
                    item.classList.add('completed');
                    num.innerHTML = '<i class="ti ti-check"></i>';
                } else if (i === step) {
                    item.classList.add('active');
                    num.textContent = i;
                } else {
                    num.textContent = i;
                }
            }

            window.scrollTo({ top: 120, behavior: 'smooth' });
        }

        function validateAndGoToStep3() {
            const nameEl = document.getElementById('customer_name');
            const phoneEl = document.getElementById('customer_phone');
            const addressEl = document.getElementById('shipping_address');

            const name = nameEl ? nameEl.value.trim() : '';
            const phone = phoneEl ? phoneEl.value.trim() : '';
            const address = addressEl ? addressEl.value.trim() : '';

            let valid = true;
            const errName = document.getElementById('err-name');
            const errPhone = document.getElementById('err-phone');
            const errAddress = document.getElementById('err-address');

            if (!name) {
                if (errName) errName.style.display = 'block';
                valid = false;
            } else if (errName) {
                errName.style.display = 'none';
            }

            if (!phone || phone.length < 9) {
                if (errPhone) errPhone.style.display = 'block';
                valid = false;
            } else if (errPhone) {
                errPhone.style.display = 'none';
            }

            if (!address) {
                if (errAddress) errAddress.style.display = 'block';
                valid = false;
            } else if (errAddress) {
                errAddress.style.display = 'none';
            }

            if (!valid) return;

            updateCalculations();
            goToStep(3);
            startCountdown();
        }

        // Stepper click handlers
        if (btnToStep2) btnToStep2.addEventListener('click', function() { goToStep(2); });
        if (btnToStep3) btnToStep3.addEventListener('click', validateAndGoToStep3);
        if (btnBackStep1) btnBackStep1.addEventListener('click', function() { goToStep(1); });
        if (btnBackStep2) btnBackStep2.addEventListener('click', function() { goToStep(2); });

        if (stepNav1) stepNav1.addEventListener('click', function() { goToStep(1); });
        if (stepNav2) stepNav2.addEventListener('click', function() { goToStep(2); });
        if (stepNav3) stepNav3.addEventListener('click', validateAndGoToStep3);

        // Switch between Bank QR and MoMo QR
        function switchToMoMoQR() {
            const bankView = document.getElementById('bank-qr-view');
            const momoView = document.getElementById('momo-qr-view');
            const pillBank = document.getElementById('pill-bank');
            const pillMoMo = document.getElementById('pill-momo');
            const qrBox = document.getElementById('qr-display-box');

            if (bankView) bankView.style.display = 'none';
            if (momoView) momoView.style.display = 'block';

            if (pillBank) pillBank.classList.remove('active-bank');
            if (pillMoMo) pillMoMo.classList.add('active-momo');
            if (qrBox) {
                qrBox.style.borderColor = '#FBCFE8';
                qrBox.style.background = 'linear-gradient(135deg, #FFF1F2 0%, #FDF2F8 100%)';
            }
        }

        function switchToBankQR() {
            const bankView = document.getElementById('bank-qr-view');
            const momoView = document.getElementById('momo-qr-view');
            const pillBank = document.getElementById('pill-bank');
            const pillMoMo = document.getElementById('pill-momo');
            const qrBox = document.getElementById('qr-display-box');

            if (bankView) bankView.style.display = 'block';
            if (momoView) momoView.style.display = 'none';

            if (pillBank) pillBank.classList.add('active-bank');
            if (pillMoMo) pillMoMo.classList.remove('active-momo');
            if (qrBox) {
                qrBox.style.borderColor = '#BFDBFE';
                qrBox.style.background = 'linear-gradient(135deg, #F8FAFC 0%, #EFF6FF 100%)';
            }
        }

        const pillBank = document.getElementById('pill-bank');
        if (pillBank) pillBank.addEventListener('click', switchToBankQR);

        const pillMoMo = document.getElementById('pill-momo');
        if (pillMoMo) pillMoMo.addEventListener('click', switchToMoMoQR);

        // Payment Method Radio Handlers
        const radios = document.querySelectorAll('input[name="payment_method"]');
        radios.forEach(radio => {
            radio.addEventListener('change', function() {
                selectedPayment = this.value;
                document.querySelectorAll('.payment-option-card').forEach(card => card.classList.remove('selected'));
                const parent = this.closest('.payment-option-card');
                if (parent) parent.classList.add('selected');

                const qrBox = document.getElementById('qr-display-box');
                const codBox = document.getElementById('cod-display-box');
                const btnFinal = document.getElementById('btn-final-confirm');

                if (selectedPayment === 'cod') {
                    if (btnToStep3) btnToStep3.innerHTML = 'Tiếp tục: Xác nhận đơn COD <i class="ti ti-arrow-right"></i>';
                    if (qrBox) qrBox.style.display = 'none';
                    if (codBox) codBox.style.display = 'block';
                    if (btnFinal) btnFinal.innerHTML = '<i class="ti ti-check"></i> Xác nhận đặt đơn hàng (COD)';
                } else if (selectedPayment === 'momo') {
                    if (btnToStep3) btnToStep3.innerHTML = 'Tiếp tục: Quét mã QR MoMo <i class="ti ti-arrow-right"></i>';
                    if (qrBox) qrBox.style.display = 'block';
                    if (codBox) codBox.style.display = 'none';
                    if (btnFinal) btnFinal.innerHTML = '<i class="ti ti-circle-check"></i> Tôi đã thanh toán MoMo - Xác nhận đặt hàng';
                    switchToMoMoQR();
                } else {
                    if (btnToStep3) btnToStep3.innerHTML = 'Tiếp tục: Quét mã QR thanh toán <i class="ti ti-arrow-right"></i>';
                    if (qrBox) qrBox.style.display = 'block';
                    if (codBox) codBox.style.display = 'none';
                    if (btnFinal) btnFinal.innerHTML = '<i class="ti ti-circle-check"></i> Tôi đã thanh toán - Xác nhận đặt hàng';
                    switchToBankQR();
                }
            });
        });

        // Size Pill Handlers
        const sizePills = document.querySelectorAll('.size-pill-label');
        sizePills.forEach(pill => {
            pill.addEventListener('click', function() {
                sizePills.forEach(p => p.classList.remove('selected'));
                this.classList.add('selected');
                const size = this.dataset.size;
                const noteInput = document.getElementById('order_note');
                if (noteInput) {
                    let text = noteInput.value.replace(/Size [A-Z]+/g, '').trim();
                    if (text.endsWith(',')) text = text.slice(0, -1).trim();
                    noteInput.value = (text ? text + ', ' : '') + 'Size ' + size;
                }
            });
        });

        // Copy buttons with Toast Notification
        let toastTimeout = null;
        function showCopyToast(label) {
            const toast = document.getElementById('copy-toast-notification');
            const toastText = document.getElementById('copy-toast-text');
            if (!toast || !toastText) return;
            toastText.textContent = 'Đã sao chép: ' + label;
            toast.style.display = 'inline-flex';
            if (toastTimeout) clearTimeout(toastTimeout);
            toastTimeout = setTimeout(() => {
                toast.style.display = 'none';
            }, 2500);
        }

        function copyText(text, btn, label) {
            if (!navigator.clipboard) return;
            navigator.clipboard.writeText(text).then(() => {
                const old = btn.textContent;
                btn.textContent = 'Đã chép!';
                btn.style.background = '#EC4899';
                btn.style.color = '#fff';
                showCopyToast(label || text);
                setTimeout(() => {
                    btn.textContent = old;
                    btn.style.background = '';
                    btn.style.color = '';
                }, 2000);
            });
        }

        const btnCopyAcc = document.getElementById('btn-copy-acc');
        if (btnCopyAcc) {
            btnCopyAcc.addEventListener('click', function() {
                copyText('10006118170', this, 'STK TPBank 1000 6118 170');
            });
        }

        const btnCopyAmount = document.getElementById('btn-copy-amount');
        if (btnCopyAmount) {
            btnCopyAmount.addEventListener('click', function() {
                let qty = parseInt(qtyInput.value) || 1;
                const totalVal = qty * unitPrice;
                copyText(totalVal.toString(), this, new Intl.NumberFormat('vi-VN').format(totalVal) + '₫');
            });
        }

        const btnCopyMemo = document.getElementById('btn-copy-memo');
        if (btnCopyMemo) {
            btnCopyMemo.addEventListener('click', function() {
                copyText(orderCode, this, 'Nội dung CK ' + orderCode);
            });
        }

        // MoMo Copy Buttons
        const btnCopyMoMoAcc = document.getElementById('btn-copy-momo-acc');
        if (btnCopyMoMoAcc) {
            btnCopyMoMoAcc.addEventListener('click', function() {
                copyText('PSP2615713900000057', this, 'Mã TK MoMo PSP2615713900000057');
            });
        }

        const btnCopyMoMoAmount = document.getElementById('btn-copy-momo-amount');
        if (btnCopyMoMoAmount) {
            btnCopyMoMoAmount.addEventListener('click', function() {
                let qty = parseInt(qtyInput.value) || 1;
                const totalVal = qty * unitPrice;
                copyText(totalVal.toString(), this, new Intl.NumberFormat('vi-VN').format(totalVal) + '₫');
            });
        }

        const btnCopyMoMoMemo = document.getElementById('btn-copy-momo-memo');
        if (btnCopyMoMoMemo) {
            btnCopyMoMoMemo.addEventListener('click', function() {
                copyText(orderCode, this, 'Nội dung MoMo ' + orderCode);
            });
        }

        // Show loading/verifying overlay on submit
        const purchaseForm = document.getElementById('purchase-form');
        if (purchaseForm) {
            purchaseForm.addEventListener('submit', function() {
                const overlay = document.getElementById('payment-verifying-overlay');
                const memoDisplay = document.getElementById('verifying-memo-display');
                let qty = parseInt(qtyInput ? qtyInput.value : 1) || 1;
                const totalVal = qty * unitPrice;
                if (memoDisplay) {
                    memoDisplay.textContent = 'Mã đối soát: ' + orderCode + ' • Số tiền: ' + new Intl.NumberFormat('vi-VN').format(totalVal) + '₫';
                }
                if (overlay) {
                    overlay.style.display = 'flex';
                }
            });
        }

        // Countdown Timer
        let timerInterval = null;
        function startCountdown() {
            if (timerInterval) clearInterval(timerInterval);
            let duration = 15 * 60;
            const timerEl = document.getElementById('countdown-timer');

            timerInterval = setInterval(() => {
                const mins = Math.floor(duration / 60);
                const secs = duration % 60;
                if (timerEl) {
                    timerEl.textContent = `${mins.toString().padStart(2, '0')}:${secs.toString().padStart(2, '0')}`;
                }
                if (--duration < 0) {
                    clearInterval(timerInterval);
                    if (timerEl) timerEl.textContent = 'Mã đã hết hạn, vui lòng tải lại';
                }
            }, 1000);
        }

        // Initial update
        updateCalculations();
    });
</script>
@endpush

@endsection
