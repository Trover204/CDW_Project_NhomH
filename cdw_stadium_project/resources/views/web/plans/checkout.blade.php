@extends('web.layouts.app')

@section('title', __('Thanh toán gói thành viên') . ' - ' . $plan->name)

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
        background: #178A4C;
        border-color: #178A4C;
        color: #FFFFFF;
        box-shadow: 0 4px 14px rgba(23, 138, 76, 0.4);
        transform: scale(1.08);
    }
    .step-item.completed .step-number {
        background: #DCFCE7;
        border-color: #178A4C;
        color: #15803D;
    }
    .step-label {
        font-size: 13.5px;
        font-weight: 600;
        color: var(--ink-soft, #6B7280);
        white-space: nowrap;
        transition: color 0.3s ease;
    }
    .step-item.active .step-label {
        color: #178A4C;
        font-weight: 700;
    }

    /* Checkout Card */
    .checkout-card {
        background: var(--bg-white, #FFFFFF);
        border: 1px solid var(--line, #E3ECE6);
        border-radius: 24px;
        padding: 36px 32px;
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.05);
    }

    /* Plan Summary Header Box */
    .checkout-plan-box {
        display: flex;
        align-items: center;
        gap: 18px;
        padding: 20px 22px;
        background: var(--bg-page, #F8FAFC);
        border-radius: 16px;
        border: 1px solid var(--line, #E5E7EB);
        margin-bottom: 28px;
    }
    .checkout-plan-icon {
        width: 72px;
        height: 72px;
        background: linear-gradient(135deg, #E6F6F4 0%, #D2F85A33 100%);
        border-radius: 16px;
        border: 1px solid rgba(16, 103, 100, 0.15);
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        font-size: 32px;
        color: #178A4C;
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
        border-color: #178A4C;
        background: #F0FDF4;
    }
    .payment-option-card.selected {
        border-color: #178A4C;
        background: #F0FDF4;
        box-shadow: 0 4px 14px rgba(23, 138, 76, 0.12);
    }
    .payment-option-card input[type="radio"] {
        margin-right: 14px;
        width: 20px;
        height: 20px;
        accent-color: #178A4C;
        cursor: pointer;
    }

    /* QR Code Display Frame */
    .qr-payment-frame {
        background: linear-gradient(135deg, #F8FAFC 0%, #EFF6FF 100%);
        border: 2px solid #BFDBFE;
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
        background: #178A4C;
        color: #FFFFFF;
        border-color: #178A4C;
        box-shadow: 0 4px 12px rgba(23, 138, 76, 0.25);
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
        max-width: 250px;
        height: auto;
        display: block;
        border-radius: 12px;
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
        background: #E2E8F0;
        border: none;
        color: #334155;
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
        background: #178A4C;
        color: #FFFFFF;
    }

    /* Buttons */
    .btn-checkout-primary {
        height: 52px;
        padding: 0 32px;
        border-radius: 12px;
        background: #178A4C;
        color: #FFFFFF;
        border: none;
        font-size: 15.5px;
        font-weight: 700;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        box-shadow: 0 4px 14px rgba(23, 138, 76, 0.35);
        transition: all 0.2s ease;
        text-decoration: none;
    }
    .btn-checkout-primary:hover {
        background: #13733F;
        transform: translateY(-2px);
        box-shadow: 0 6px 18px rgba(23, 138, 76, 0.45);
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
        text-decoration: none;
    }
    .btn-checkout-back:hover {
        background: var(--line, #E5E7EB);
        color: var(--ink, #111827);
    }

    /* Form Fields */
    .form-group-custom {
        margin-bottom: 20px;
    }
    .form-label-custom {
        display: block;
        font-size: 14px;
        font-weight: 700;
        color: var(--ink, #11201B);
        margin-bottom: 8px;
    }
    .form-input-custom {
        width: 100%;
        height: 48px;
        padding: 0 16px;
        border: 1.5px solid var(--line, #D1D5DB);
        border-radius: 12px;
        font-size: 14.5px;
        background: var(--bg-white, #FFFFFF);
        color: var(--ink, #11201B);
        outline: none;
        transition: all 0.2s;
    }
    .form-input-custom:focus {
        border-color: #178A4C;
        box-shadow: 0 0 0 3px rgba(23, 138, 76, 0.12);
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
    <div style="margin-bottom: 22px; font-size: 14px; color: var(--ink-soft, #5E726A); display: flex; align-items: center; gap: 8px;">
        <a href="{{ route('plans.index') }}" style="color: #178A4C; text-decoration: none; font-weight: 600; display: inline-flex; align-items: center; gap: 4px;">
            <i class="ti ti-arrow-left"></i> {{ __('Gói thành viên') }}
        </a>
        <span>/</span>
        <span style="color: var(--ink, #11201B); font-weight: 500;">{{ __('Quy trình kích hoạt & Thanh toán') }}</span>
    </div>

    @if(session('error'))
        <div style="background: #E53E3E; color: white; padding: 14px 20px; border-radius: 12px; margin-bottom: 24px; text-align: center; font-weight: 600; box-shadow: 0 4px 12px rgba(229,62,62,0.15);">
            <i class="ti ti-alert-triangle" style="margin-right: 6px;"></i> {{ session('error') }}
        </div>
    @endif

    {{-- 3-Step Wizard Stepper --}}
    <div class="checkout-stepper">
        <div class="step-item active" id="step-nav-1">
            <div class="step-number" id="step-num-1">1</div>
            <div class="step-label">1. {{ __('Chọn phương thức') }}</div>
        </div>
        <div class="step-item" id="step-nav-2">
            <div class="step-number" id="step-num-2">2</div>
            <div class="step-label">2. {{ __('Xác nhận gói') }}</div>
        </div>
        <div class="step-item" id="step-nav-3">
            <div class="step-number" id="step-num-3">3</div>
            <div class="step-label">3. {{ __('Quét mã QR') }}</div>
        </div>
    </div>

    <div class="checkout-card">

        {{-- Plan Summary Header --}}
        <div class="checkout-plan-box">
            <div class="checkout-plan-icon">
                <i class="ti ti-crown"></i>
            </div>

            <div style="flex-grow: 1;">
                <span style="display: inline-block; background: #DCFCE7; color: #15803D; font-size: 11.5px; font-weight: 700; padding: 3px 10px; border-radius: 999px; margin-bottom: 6px;">
                    <i class="ti ti-sparkles" style="font-size: 11px;"></i> {{ __('Thời hạn :days ngày', ['days' => $plan->duration_days]) }}
                </span>
                <div style="font-weight: 800; color: var(--ink, #111827); font-size: 18px; margin-bottom: 6px; font-family: 'Oswald', sans-serif;">
                    {{ $plan->name }}
                </div>
                <div style="display: flex; align-items: baseline; justify-content: space-between; flex-wrap: wrap; gap: 8px;">
                    <span style="font-size: 13.5px; color: #15803D; font-weight: 600;">
                        <i class="ti ti-discount-2" style="font-size: 14px;"></i> {{ __('Đặc quyền giảm :percent% cho mọi lượt đặt sân', ['percent' => $plan->discount_percent]) }}
                    </span>
                    <span style="font-size: 24px; font-weight: 800; color: #EA580C; font-family: 'Oswald', sans-serif;" id="plan-price-display">
                        {{ number_format($plan->price, 0, ',', '.') }}₫
                    </span>
                </div>
            </div>
        </div>

        {{-- Form Master --}}
        <form action="{{ route('plans.subscribe', $plan->id) }}" method="POST" id="plan-checkout-form">
            @csrf

            {{-- ========================================================
                 BƯỚC 1: CHỌN PHƯƠNG THỨC THANH TOÁN
            ======================================================== --}}
            <div id="step-section-1">
                <h3 style="font-size: 18px; font-weight: 700; color: var(--ink, #11201B); margin: 0 0 18px; display: flex; align-items: center; gap: 8px;">
                    <i class="ti ti-credit-card" style="color: #178A4C;"></i> {{ __('Bước 1: Chọn phương thức thanh toán') }}
                </h3>

                {{-- Payment Options --}}
                <div style="margin-bottom: 24px;">
                    {{-- VietQR Bank Transfer --}}
                    <label class="payment-option-card selected" id="opt-bank_transfer">
                        <input type="radio" name="payment_method" value="bank_transfer" checked>
                        <div style="width: 40px; height: 40px; border-radius: 10px; background: #EFF6FF; display: flex; align-items: center; justify-content: center; font-size: 20px; color: #2563EB; margin-right: 14px; flex-shrink: 0;">
                            <i class="ti ti-building-bank"></i>
                        </div>
                        <div style="flex-grow: 1;">
                            <div style="font-weight: 700; font-size: 14.5px; color: var(--ink, #111827);">{{ __('Quét mã QR Ngân Hàng (VietQR / Chuyển khoản 24/7)') }}</div>
                            <div style="font-size: 12.5px; color: var(--ink-soft, #6B7280);">{{ __('Quét mã chuyển tiền tức thì từ TPBank, MB Bank, Vietcombank, Techcombank, BIDV...') }}</div>
                        </div>
                        <span style="font-size: 11.5px; font-weight: 700; background: #DCFCE7; color: #15803D; padding: 2px 8px; border-radius: 4px;">{{ __('Khuyên dùng') }}</span>
                    </label>

                    {{-- MoMo --}}
                    <label class="payment-option-card" id="opt-momo">
                        <input type="radio" name="payment_method" value="momo">
                        <div style="width: 40px; height: 40px; border-radius: 10px; background: #FDF2F8; display: flex; align-items: center; justify-content: center; font-size: 20px; color: #DB2777; margin-right: 14px; flex-shrink: 0;">
                            <i class="ti ti-wallet"></i>
                        </div>
                        <div style="flex-grow: 1;">
                            <div style="font-weight: 700; font-size: 14.5px; color: var(--ink, #111827);">{{ __('Ví điện tử MoMo') }}</div>
                            <div style="font-size: 12.5px; color: var(--ink-soft, #6B7280);">{{ __('Thanh toán qua ví MoMo & Napas 247 nhanh chóng') }}</div>
                        </div>
                    </label>

                    {{-- Visa/Mastercard --}}
                    <label class="payment-option-card" id="opt-visa">
                        <input type="radio" name="payment_method" value="visa">
                        <div style="width: 40px; height: 40px; border-radius: 10px; background: #EEF2FF; display: flex; align-items: center; justify-content: center; font-size: 20px; color: #4F46E5; margin-right: 14px; flex-shrink: 0;">
                            <i class="ti ti-credit-card"></i>
                        </div>
                        <div style="flex-grow: 1;">
                            <div style="font-weight: 700; font-size: 14.5px; color: var(--ink, #111827);">{{ __('Thẻ Quốc Tế (Visa / Mastercard / JCB)') }}</div>
                            <div style="font-size: 12.5px; color: var(--ink-soft, #6B7280);">{{ __('Cổng thanh toán thẻ quốc tế bảo mật cao') }}</div>
                        </div>
                    </label>

                    {{-- PayPal --}}
                    <label class="payment-option-card" id="opt-paypal">
                        <input type="radio" name="payment_method" value="paypal">
                        <div style="width: 40px; height: 40px; border-radius: 10px; background: #F0F9FF; display: flex; align-items: center; justify-content: center; font-size: 20px; color: #0284C7; margin-right: 14px; flex-shrink: 0;">
                            <i class="ti ti-brand-paypal"></i>
                        </div>
                        <div style="flex-grow: 1;">
                            <div style="font-weight: 700; font-size: 14.5px; color: var(--ink, #111827);">{{ __('Cổng thanh toán PayPal') }}</div>
                            <div style="font-size: 12.5px; color: var(--ink-soft, #6B7280);">{{ __('Thanh toán an toàn qua tài khoản PayPal') }}</div>
                        </div>
                    </label>
                </div>

                {{-- Price summary in step 1 --}}
                <div style="background: var(--bg-page, #F9FAFB); border-radius: 14px; padding: 18px 20px; border: 1px solid var(--line, #E5E7EB); margin-bottom: 24px;">
                    <div style="display: flex; justify-content: space-between; margin-bottom: 8px; font-size: 14px; color: var(--ink-soft, #6B7280);">
                        <span>{{ __('Gói thành viên:') }}</span>
                        <strong style="color: var(--ink, #111827);">{{ $plan->name }} ({{ $plan->duration_days }} {{ __('ngày') }})</strong>
                    </div>
                    <div style="display: flex; justify-content: space-between; margin-bottom: 8px; font-size: 14px; color: var(--ink-soft, #6B7280);">
                        <span>{{ __('Ưu đãi chiết khấu:') }}</span>
                        <span style="color: #16A34A; font-weight: 700;">{{ __('Giảm :percent% mỗi lượt đặt', ['percent' => $plan->discount_percent]) }}</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; align-items: baseline; border-top: 1px dashed var(--line, #E5E7EB); padding-top: 12px; margin-top: 8px;">
                        <span style="font-weight: 700; font-size: 15px; color: var(--ink, #111827);">{{ __('Tổng thanh toán:') }}</span>
                        <span style="font-size: 24px; font-weight: 800; color: #EA580C; font-family: 'Oswald', sans-serif;">
                            {{ number_format($plan->price, 0, ',', '.') }}₫
                        </span>
                    </div>
                </div>

                <div style="display: flex; justify-content: flex-end; gap: 12px;">
                    <a href="{{ route('plans.index') }}" class="btn-checkout-back">
                        <i class="ti ti-arrow-left"></i> {{ __('Quay lại') }}
                    </a>
                    <button type="button" class="btn-checkout-primary" id="btn-to-step-2">
                        <span>{{ __('Tiếp tục xác nhận') }}</span> <i class="ti ti-arrow-right"></i>
                    </button>
                </div>
            </div>

            {{-- ========================================================
                 BƯỚC 2: XÁC NHẬN THÔNG TIN THÀNH VIÊN
            ======================================================== --}}
            <div id="step-section-2" style="display: none;">
                <h3 style="font-size: 18px; font-weight: 700; color: var(--ink, #11201B); margin: 0 0 18px; display: flex; align-items: center; gap: 8px;">
                    <i class="ti ti-user-check" style="color: #178A4C;"></i> {{ __('Bước 2: Xác nhận thông tin tài khoản kích hoạt') }}
                </h3>

                <div style="background: var(--bg-page, #F8FAFC); padding: 20px; border-radius: 14px; border: 1px solid var(--line, #E5E7EB); margin-bottom: 24px;">
                    <div class="form-group-custom">
                        <label class="form-label-custom">{{ __('Họ và tên thành viên') }}</label>
                        <input type="text" class="form-input-custom" value="{{ Auth::user()->name }}" readonly style="background: #F3F4F6; cursor: not-allowed;">
                    </div>

                    <div class="form-group-custom">
                        <label class="form-label-custom">{{ __('Địa chỉ Email nhận quyền lợi') }}</label>
                        <input type="email" class="form-input-custom" value="{{ Auth::user()->email }}" readonly style="background: #F3F4F6; cursor: not-allowed;">
                    </div>

                    <div style="background: #ECFDF5; border: 1px solid #A7F3D0; border-radius: 12px; padding: 14px 16px; display: flex; gap: 12px; align-items: flex-start;">
                        <i class="ti ti-shield-check" style="color: #059669; font-size: 22px; flex-shrink: 0; margin-top: 2px;"></i>
                        <div style="font-size: 13px; color: #065F46; line-height: 1.5;">
                            <strong>{{ __('Kích hoạt tự động ngay sau khi thanh toán:') }}</strong>
                            {{ __('Gói cước sẽ được gắn trực tiếp vào tài khoản này, giảm giá tự động được kích hoạt cho mọi đơn đặt sân trong vòng :days ngày.', ['days' => $plan->duration_days]) }}
                        </div>
                    </div>
                </div>

                <div style="display: flex; justify-content: space-between; gap: 12px;">
                    <button type="button" class="btn-checkout-back" id="btn-back-to-1">
                        <i class="ti ti-arrow-left"></i> {{ __('Quay lại Bước 1') }}
                    </button>
                    <button type="button" class="btn-checkout-primary" id="btn-to-step-3">
                        <span>{{ __('Tiếp tục: Quét mã QR thanh toán') }}</span> <i class="ti ti-arrow-right"></i>
                    </button>
                </div>
            </div>

            {{-- ========================================================
                 BƯỚC 3: MÃ QR THANH TOÁN (NGÂN HÀNG & MOMO) & XÁC NHẬN
            ======================================================== --}}
            <div id="step-section-3" style="display: none;">
                <h3 style="font-size: 18px; font-weight: 700; color: var(--ink, #11201B); margin: 0 0 18px; display: flex; align-items: center; gap: 8px;">
                    <i class="ti ti-qrcode" style="color: #178A4C;"></i> {{ __('Bước 3: Quét mã QR & Xác nhận kích hoạt gói') }}
                </h3>

                @php
                    $planAmount = $plan->price;
                    $planMemo = 'PLAN' . $plan->id . 'U' . Auth::id();
                @endphp

                {{-- QR Payment Frame with Switch Pills --}}
                <div class="qr-payment-frame" id="qr-display-box">
                    {{-- Switch Pills between Bank and MoMo --}}
                    <div class="qr-switch-pills">
                        <button type="button" id="pill-bank" class="qr-switch-pill active-bank">
                            <i class="ti ti-building-bank"></i> {{ __('QR Ngân Hàng (TPBank)') }}
                        </button>
                        <button type="button" id="pill-momo" class="qr-switch-pill">
                            <span class="momo-mini-badge">MoMo</span> {{ __('QR Ví MoMo') }}
                        </button>
                    </div>

                    {{-- Countdown Timer --}}
                    <div style="font-size: 13px; color: #DC2626; font-weight: 700; margin-bottom: 16px; display: flex; align-items: center; justify-content: center; gap: 6px;">
                        <i class="ti ti-clock"></i> {{ __('Mã QR có hiệu lực trong:') }} <span id="countdown-timer">14:59</span>
                    </div>

                    {{-- =========================================
                         VIEW 1: MÃ QR TPBANK (VIETQR)
                    ========================================= --}}
                    <div id="bank-qr-view">
                        <div style="display: flex; align-items: center; justify-content: center; gap: 8px; margin-bottom: 12px;">
                            <span style="background: #178A4C; color: #fff; font-size: 11px; font-weight: 700; padding: 4px 10px; border-radius: 20px;">
                                VIETQR CHUẨN QUỐC GIA
                            </span>
                            <span style="font-size: 13px; color: #475569; font-weight: 600;">
                                {{ __('Hỗ trợ tất cả App Ngân Hàng & MoMo') }}
                            </span>
                        </div>

                        {{-- QR Image from public/img qr --}}
                        <div class="qr-img-wrapper">
                            <img id="vietqr-image" src="{{ asset('img qr/qr_card_clean.jpg') }}" alt="Mã QR TPBank - NGUYEN CHI HAI">
                            <div style="margin-top: 12px;">
                                <a href="{{ asset('img qr/qr_card_clean.jpg') }}" download="QR_ThanhToan_TPBank.jpg" class="btn-copy" style="text-decoration: none; display: inline-flex; align-items: center; gap: 6px; padding: 7px 14px; font-size: 13px; font-weight: 600;">
                                    <i class="ti ti-download"></i> {{ __('Tải mã QR về máy') }}
                                </a>
                            </div>
                        </div>

                        {{-- Transfer Info Table with One-Click Copy --}}
                        <table class="bank-info-table">
                            <tr>
                                <td>{{ __('Ngân hàng:') }}</td>
                                <td>
                                    <strong>TPBank (Ngân hàng Tiên Phong)</strong>
                                    <span style="font-size: 11px; color: #178A4C; margin-left: 8px;">24/7 Tức thì</span>
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
                                    <span id="bank-amount" style="color: #EA580C; font-weight: 800; font-size: 16px;">{{ number_format($planAmount, 0, ',', '.') }}₫</span>
                                    <button type="button" class="btn-copy" id="btn-copy-amount">{{ __('Sao chép') }}</button>
                                </td>
                            </tr>
                            <tr>
                                <td>{{ __('Nội dung chuyển khoản:') }}</td>
                                <td>
                                    <span id="bank-memo" style="color: #178A4C; font-weight: 700; font-family: monospace; font-size: 15px;">{{ $planMemo }}</span>
                                    <button type="button" class="btn-copy" id="btn-copy-memo">{{ __('Sao chép') }}</button>
                                </td>
                            </tr>
                        </table>

                        <div style="background: rgba(23,138,76,0.08); border-radius: 10px; padding: 12px; font-size: 13px; color: #166534; line-height: 1.5; max-width: 480px; margin: 0 auto; text-align: left;">
                            <i class="ti ti-info-circle" style="margin-right: 4px;"></i>
                            <strong>{{ __('Hướng dẫn:') }}</strong> {{ __('Quý khách mở App Ngân hàng (TPBank, MB, VCB, BIDV, Techcombank...) hoặc MoMo, chọn Quét mã QR để quét mã trên. Vui lòng giữ nguyên nội dung chuyển khoản để hệ thống tự động kích hoạt gói ngay lập tức.') }}
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
                                {{ __('Hỗ trợ tất cả App MoMo & Napas 247') }}
                            </span>
                        </div>

                        {{-- MoMo QR Image from public/img qr --}}
                        <div class="qr-img-wrapper" style="border: 2px solid #FBCFE8; box-shadow: 0 8px 24px rgba(165,0,100,0.12);">
                            <img id="momo-qr-image" src="{{ asset('img qr/momo_card_clean.jpg') }}" alt="Mã QR Ví MoMo - NGUYEN CHI HAI">
                            <div style="margin-top: 12px;">
                                <a href="{{ asset('img qr/momo_card_clean.jpg') }}" download="QR_ThanhToan_MoMo.jpg" class="btn-copy" style="text-decoration: none; display: inline-flex; align-items: center; gap: 6px; padding: 7px 14px; font-size: 13px; font-weight: 600; background: #A50064; color: #fff; border-color: #A50064;">
                                    <i class="ti ti-download"></i> {{ __('Tải mã QR MoMo về máy') }}
                                </a>
                            </div>
                        </div>

                        {{-- Transfer Info Table with One-Click Copy for MoMo --}}
                        <table class="bank-info-table">
                            <tr>
                                <td>{{ __('Cổng thanh toán:') }}</td>
                                <td>
                                    <strong style="color: #A50064;">Ví điện tử MoMo</strong>
                                    <span style="font-size: 11px; color: #A50064; margin-left: 8px; background: #FDF2F8; padding: 2px 6px; border-radius: 4px; font-weight: 700;">Napas 247</span>
                                </td>
                            </tr>
                            <tr>
                                <td>{{ __('Số tài khoản MoMo:') }}</td>
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
                                    <span id="momo-amount" style="color: #A50064; font-weight: 800; font-size: 16px;">{{ number_format($planAmount, 0, ',', '.') }}₫</span>
                                    <button type="button" class="btn-copy" id="btn-copy-momo-amount">{{ __('Sao chép') }}</button>
                                </td>
                            </tr>
                            <tr>
                                <td>{{ __('Nội dung chuyển tiền:') }}</td>
                                <td>
                                    <span id="momo-memo" style="color: #A50064; font-weight: 700; font-family: monospace; font-size: 15px;">{{ $planMemo }}</span>
                                    <button type="button" class="btn-copy" id="btn-copy-momo-memo">{{ __('Sao chép') }}</button>
                                </td>
                            </tr>
                        </table>

                        <div style="background: rgba(165,0,100,0.06); border: 1px solid #FBCFE8; border-radius: 10px; padding: 12px; font-size: 13px; color: #831843; line-height: 1.5; max-width: 480px; margin: 0 auto; text-align: left;">
                            <i class="ti ti-info-circle" style="margin-right: 4px; color: #A50064;"></i>
                            <strong>{{ __('Hướng dẫn MoMo:') }}</strong> {{ __('Mở ứng dụng MoMo &rarr; Chọn Quét mã QR &rarr; Quét mã trên để chuyển tiền tự động. Vui lòng giữ nguyên nội dung chuyển tiền để hệ thống tự động kích hoạt gói.') }}
                        </div>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div style="display: flex; justify-content: space-between; gap: 12px;">
                    <button type="button" class="btn-checkout-back" id="btn-back-to-2">
                        <i class="ti ti-arrow-left"></i> {{ __('Quay lại') }}
                    </button>
                    <button type="submit" class="btn-checkout-primary" id="btn-final-confirm">
                        <i class="ti ti-circle-check"></i> <span id="btn-final-text">{{ __('Tôi đã thanh toán — Kích hoạt ngay') }}</span>
                    </button>
                </div>
            </div>

        </form>

    </div>

</div>

@push('scripts')
<script nonce="{{ $cspNonce ?? '' }}">
document.addEventListener('DOMContentLoaded', function () {
    const stepNav1 = document.getElementById('step-nav-1');
    const stepNav2 = document.getElementById('step-nav-2');
    const stepNav3 = document.getElementById('step-nav-3');

    const stepSec1 = document.getElementById('step-section-1');
    const stepSec2 = document.getElementById('step-section-2');
    const stepSec3 = document.getElementById('step-section-3');

    const bankView = document.getElementById('bank-qr-view');
    const momoView = document.getElementById('momo-qr-view');
    const pillBank = document.getElementById('pill-bank');
    const pillMoMo = document.getElementById('pill-momo');
    const qrBox = document.getElementById('qr-display-box');
    const btnFinalText = document.getElementById('btn-final-text');

    function goToStep(step) {
        stepSec1.style.display = 'none';
        stepSec2.style.display = 'none';
        stepSec3.style.display = 'none';

        stepNav1.className = 'step-item';
        stepNav2.className = 'step-item';
        stepNav3.className = 'step-item';

        if (step === 1) {
            stepSec1.style.display = 'block';
            stepNav1.className = 'step-item active';
        } else if (step === 2) {
            stepSec2.style.display = 'block';
            stepNav1.className = 'step-item completed';
            stepNav2.className = 'step-item active';
        } else if (step === 3) {
            stepSec3.style.display = 'block';
            stepNav1.className = 'step-item completed';
            stepNav2.className = 'step-item completed';
            stepNav3.className = 'step-item active';
        }

        window.scrollTo({ top: 120, behavior: 'smooth' });
    }

    // Switch between Bank QR and MoMo QR
    function switchToBankQR() {
        if (bankView) bankView.style.display = 'block';
        if (momoView) momoView.style.display = 'none';
        if (pillBank) pillBank.className = 'qr-switch-pill active-bank';
        if (pillMoMo) pillMoMo.className = 'qr-switch-pill';
        if (qrBox) {
            qrBox.style.borderColor = '#BFDBFE';
            qrBox.style.background = 'linear-gradient(135deg, #F8FAFC 0%, #EFF6FF 100%)';
        }
        if (btnFinalText) btnFinalText.textContent = 'Tôi đã chuyển khoản Ngân hàng — Kích hoạt ngay';
    }

    function switchToMoMoQR() {
        if (bankView) bankView.style.display = 'none';
        if (momoView) momoView.style.display = 'block';
        if (pillMoMo) pillMoMo.className = 'qr-switch-pill active-momo';
        if (pillBank) pillBank.className = 'qr-switch-pill';
        if (qrBox) {
            qrBox.style.borderColor = '#FBCFE8';
            qrBox.style.background = 'linear-gradient(135deg, #FDF2F8 0%, #FFF1F2 100%)';
        }
        if (btnFinalText) btnFinalText.textContent = 'Tôi đã thanh toán MoMo — Kích hoạt ngay';
    }

    if (pillBank) pillBank.addEventListener('click', switchToBankQR);
    if (pillMoMo) pillMoMo.addEventListener('click', switchToMoMoQR);

    // Step 1 to 2
    document.getElementById('btn-to-step-2').addEventListener('click', function () {
        goToStep(2);
    });

    // Step 2 back to 1
    document.getElementById('btn-back-to-1').addEventListener('click', function () {
        goToStep(1);
    });

    // Step 2 to 3
    document.getElementById('btn-to-step-3').addEventListener('click', function () {
        goToStep(3);
    });

    // Step 3 back to 2
    document.getElementById('btn-back-to-2').addEventListener('click', function () {
        goToStep(2);
    });

    // Payment method radio change
    const paymentRadios = document.querySelectorAll('input[name="payment_method"]');
    paymentRadios.forEach(radio => {
        radio.addEventListener('change', function () {
            document.querySelectorAll('.payment-option-card').forEach(c => c.classList.remove('selected'));
            this.closest('.payment-option-card').classList.add('selected');

            if (this.value === 'momo') {
                switchToMoMoQR();
            } else {
                switchToBankQR();
            }
        });
    });

    // Copy to clipboard helper
    function copyText(val, btn) {
        if (navigator.clipboard) {
            navigator.clipboard.writeText(val).then(() => {
                const orig = btn.innerText;
                btn.innerText = 'Đã sao chép!';
                btn.style.background = '#15803D';
                btn.style.color = '#fff';
                setTimeout(() => {
                    btn.innerText = orig;
                    btn.style.background = '';
                    btn.style.color = '';
                }, 2000);
            });
        }
    }

    // Bank copy listeners
    const btnCopyAcc = document.getElementById('btn-copy-acc');
    if (btnCopyAcc) {
        btnCopyAcc.addEventListener('click', function () {
            copyText('10006118170', this);
        });
    }

    const btnCopyAmount = document.getElementById('btn-copy-amount');
    if (btnCopyAmount) {
        btnCopyAmount.addEventListener('click', function () {
            copyText('{{ $planAmount }}', this);
        });
    }

    const btnCopyMemo = document.getElementById('btn-copy-memo');
    if (btnCopyMemo) {
        btnCopyMemo.addEventListener('click', function () {
            copyText('{{ $planMemo }}', this);
        });
    }

    // MoMo copy listeners
    const btnCopyMoMoAcc = document.getElementById('btn-copy-momo-acc');
    if (btnCopyMoMoAcc) {
        btnCopyMoMoAcc.addEventListener('click', function () {
            copyText('PSP2615713900000057', this);
        });
    }

    const btnCopyMoMoAmount = document.getElementById('btn-copy-momo-amount');
    if (btnCopyMoMoAmount) {
        btnCopyMoMoAmount.addEventListener('click', function () {
            copyText('{{ $planAmount }}', this);
        });
    }

    const btnCopyMoMoMemo = document.getElementById('btn-copy-momo-memo');
    if (btnCopyMoMoMemo) {
        btnCopyMoMoMemo.addEventListener('click', function () {
            copyText('{{ $planMemo }}', this);
        });
    }

    // 15-minute Countdown Timer (matching merch checkout)
    let timeLeft = 14 * 60 + 59;
    const countdownEl = document.getElementById('countdown-timer');
    if (countdownEl) {
        const timer = setInterval(() => {
            const minutes = Math.floor(timeLeft / 60);
            const seconds = timeLeft % 60;
            countdownEl.textContent = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
            if (timeLeft <= 0) {
                clearInterval(timer);
                countdownEl.textContent = 'Hết hạn (Vui lòng tải lại trang)';
            }
            timeLeft--;
        }, 1000);
    }
});
</script>
@endpush

@endsection
