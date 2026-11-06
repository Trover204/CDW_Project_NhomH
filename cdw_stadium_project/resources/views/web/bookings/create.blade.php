@extends('web.layouts.app')

@section('title', __('Xác nhận & Thanh toán đặt sân') . ' - ' . $court->name)

@section('content')
<div class="booking-flow-page">
    <div class="wrap booking-container">

        @if ($errors->any())
            <div class="form-alert-error">
                <i class="ti ti-alert-circle error-icon"></i>
                <div class="error-list">
                    <strong>{{ __('Vui lòng kiểm tra lại thông tin:') }}</strong>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="form-alert-error">
                <i class="ti ti-alert-triangle error-icon"></i>
                <div class="error-list">
                    <strong>{{ session('error') }}</strong>
                </div>
            </div>
        @endif

        {{-- Client-side validation alert --}}
        <div id="client-validation-alert" class="form-alert-error" style="display: none;">
            <i class="ti ti-alert-circle error-icon"></i>
            <div class="error-list">
                <strong>{{ __('Vui lòng hoàn thiện đầy đủ thông tin trước khi thanh toán:') }}</strong>
                <p id="client-validation-msg" style="margin: 4px 0 0; font-size: 13.5px;"></p>
            </div>
        </div>

        @php
            $reqDate = request('date');
            $reqTime = request('time');
            $endDate = null;
            if ($reqTime) {
                try {
                    $endDate = \Carbon\Carbon::parse($reqTime)->addHour()->format('H:i');
                } catch (\Exception $e) {
                    $endDate = '19:00';
                }
            }
            $primaryImg = $court->images ? ($court->images->firstWhere('is_primary', true) ?? $court->images->first()) : null;
        @endphp

        <form action="{{ route('bookings.store', $court->id) }}" method="POST" class="booking-main-form" id="booking-master-form">
            @csrf
            <input type="hidden" name="payment_memo" id="payment_memo_input" value="">

            {{-- =====================================================================
                 GIAI ĐOẠN A: NHẬP THÔNG TIN NGƯỜI ĐẶT & LỊCH THI ĐẤU (MÀN HÌNH ĐẦU)
            ===================================================================== --}}
            <div id="booking-initial-stage">
                {{-- Step Progress Header --}}
                <div class="booking-steps-banner">
                    <div class="step-node active">
                        <div class="step-num"><i class="ti ti-check"></i></div>
                        <div class="step-text">
                            <span class="step-title">{{ __('Bước 1') }}</span>
                            <span class="step-sub">{{ __('Thông tin & Xác nhận') }}</span>
                        </div>
                    </div>
                    <div class="step-connector active"></div>
                    <div class="step-node active">
                        <div class="step-num">2</div>
                        <div class="step-text">
                            <span class="step-title">{{ __('Bước 2') }}</span>
                            <span class="step-sub">{{ __('Thanh toán & Giữ chỗ') }}</span>
                        </div>
                    </div>
                    <div class="step-connector"></div>
                    <div class="step-node">
                        <div class="step-num">3</div>
                        <div class="step-text">
                            <span class="step-title">{{ __('Bước 3') }}</span>
                            <span class="step-sub">{{ __('Hoàn tất đặt sân') }}</span>
                        </div>
                    </div>
                </div>

                <div class="booking-layout-grid">

                    {{-- Left Side: Customer & Schedule Form --}}
                    <div class="booking-form-col">
                        <div class="form-card-section">
                            <div class="card-section-head">
                                <i class="ti ti-user-circle"></i>
                                <div>
                                    <h3>{{ __('Thông tin người đặt sân') }}</h3>
                                    <p>{{ __('Vui lòng cung cấp chính xác để ban quản lý sân gửi mã vé và liên hệ khi cần.') }}</p>
                                </div>
                            </div>

                            <div class="form-grid-2">
                                <div class="input-field-group">
                                    <label class="form-label">{{ __('Họ và tên người đặt') }} <span class="req">*</span></label>
                                    <div class="input-with-icon">
                                        <i class="ti ti-user"></i>
                                        <input type="text" name="customer_name" id="customer_name_input" value="{{ old('customer_name', Auth::user()->name) }}" placeholder="{{ __('Ví dụ: Nguyễn Văn An') }}" required>
                                    </div>
                                </div>
                                <div class="input-field-group">
                                    <label class="form-label">{{ __('Số điện thoại liên hệ') }} <span class="req">*</span></label>
                                    <div class="input-with-icon">
                                        <i class="ti ti-phone"></i>
                                        <input type="tel" name="customer_phone" id="customer_phone_input" value="{{ old('customer_phone', Auth::user()->phone ?? '') }}" placeholder="{{ __('Ví dụ: 0912345678') }}" required>
                                    </div>
                                </div>
                            </div>

                            <div class="input-field-group">
                                <label class="form-label">{{ __('Địa chỉ Email nhận thông tin xác nhận') }} <span class="req">*</span></label>
                                <div class="input-with-icon">
                                    <i class="ti ti-mail"></i>
                                    <input type="email" name="customer_email" id="customer_email_input" value="{{ old('customer_email', Auth::user()->email) }}" placeholder="{{ __('name@example.com') }}" required>
                                </div>
                            </div>
                        </div>

                        <div class="form-card-section">
                            <div class="card-section-head">
                                <i class="ti ti-calendar-time"></i>
                                <div>
                                    <h3>{{ __('Thời gian & Lịch thi đấu') }}</h3>
                                    <p>{{ __('Kiểm tra kỹ ngày chơi và khung giờ trước khi tiếp tục.') }}</p>
                                </div>
                            </div>

                            <div class="form-grid-3">
                                <div class="input-field-group">
                                    <label class="form-label">{{ __('Ngày đặt sân') }} <span class="req">*</span></label>
                                    <div class="input-with-icon">
                                        <i class="ti ti-calendar"></i>
                                        <input type="date" name="booking_date" id="booking_date_input" value="{{ old('booking_date', $reqDate ?? ($selectedDate ?? \Carbon\Carbon::now()->format('Y-m-d'))) }}" min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" required>
                                    </div>
                                </div>
                                <div class="input-field-group">
                                    <label class="form-label">{{ __('Giờ bắt đầu') }} <span class="req">*</span></label>
                                    <div class="input-with-icon">
                                        <i class="ti ti-clock"></i>
                                        <input type="time" name="start_time" id="start_time_input" step="1800" value="{{ old('start_time', $reqTime ?? '18:00') }}" required>
                                    </div>
                                </div>
                                <div class="input-field-group">
                                    <label class="form-label">{{ __('Giờ kết thúc') }} <span class="req">*</span></label>
                                    <div class="input-with-icon">
                                        <i class="ti ti-clock-play"></i>
                                        <input type="time" name="end_time" id="end_time_input" step="1800" value="{{ old('end_time', $endDate ?? '19:00') }}" required>
                                    </div>
                                </div>
                            </div>

                            {{-- Already Booked Slots Alert --}}
                            @if(!empty($bookedSlots))
                                <div class="booked-slots-warning-box" style="margin-top: 14px; margin-bottom: 16px; background: #FEF2F2; border: 1.5px dashed #FCA5A5; border-radius: 12px; padding: 12px 16px; display: flex; align-items: flex-start; gap: 10px;">
                                    <i class="ti ti-alert-triangle" style="color: #DC2626; font-size: 20px; flex-shrink: 0; margin-top: 2px;"></i>
                                    <div style="font-size: 13px; color: #991B1B; line-height: 1.5;">
                                        <strong>{{ __('Khung giờ đã có người đặt trong ngày này:') }}</strong>
                                        <div style="display: flex; gap: 6px; flex-wrap: wrap; margin-top: 6px;">
                                            @foreach($bookedSlots as $bs)
                                                <span style="background: #FEE2E2; color: #B91C1C; padding: 2px 8px; border-radius: 6px; font-weight: 700; font-size: 12px; border: 1px solid #FCA5A5;">
                                                    <i class="ti ti-lock" style="font-size: 11px;"></i> {{ $bs }}
                                                </span>
                                            @endforeach
                                        </div>
                                        <span style="font-size: 11.5px; color: #7F1D1D; margin-top: 4px; display: inline-block;">{{ __('Vui lòng chọn khung giờ khác để tránh bị trùng lặp giữ chỗ.') }}</span>
                                    </div>
                                </div>
                            @endif

                            <div class="input-field-group">
                                <label class="form-label">{{ __('Ghi chú thêm cho ban quản lý sân (tùy chọn)') }}</label>
                                <textarea name="note" id="booking_note_input" rows="3" placeholder="{{ __('Ví dụ: Chuẩn bị thêm 2 bộ áo lưới, bóng thi đấu số 5...') }}">{{ old('note') }}</textarea>
                            </div>

                            <div class="agreement-checkbox-row">
                                <label class="checkbox-container">
                                    <input type="checkbox" id="agreement_checkbox" required checked>
                                    <span class="checkmark"></span>
                                    <span class="agreement-text">
                                        {{ __('Tôi cam kết thông tin trên là chính xác và đồng ý với') }}
                                        <a href="{{ route('pages.privacy') }}" target="_blank">{{ __('Điều khoản sử dụng') }}</a>
                                        {{ __('cũng như chính sách hủy sân của hệ thống.') }}
                                    </span>
                                </label>
                            </div>
                        </div>
                    </div>

                    {{-- Right Side: Order Summary Card --}}
                    <div class="booking-summary-col">
                        <div class="summary-card">
                            <div class="summary-head">
                                <span class="summary-badge"><i class="ti ti-ticket"></i> {{ __('Tóm tắt lịch đặt') }}</span>
                                <h4>{{ __('Chi tiết đơn giữ chỗ') }}</h4>
                            </div>

                            {{-- Court Preview --}}
                            <div class="court-summary-item">
                                <div class="court-summary-thumb">
                                    @if($primaryImg)
                                        <img src="{{ asset($primaryImg->image_path) }}" alt="{{ $court->name }}">
                                    @else
                                        <div class="thumb-icon-ph"><i class="ti ti-ball-football"></i></div>
                                    @endif
                                </div>
                                <div class="court-summary-info">
                                    <span class="sport-chip">{{ $court->sportType->name ?? __('Thể thao') }}</span>
                                    <h5 class="court-summary-title">{{ $court->name }}</h5>
                                    <div class="court-summary-loc">
                                        <i class="ti ti-map-pin"></i>
                                        <span>{{ $court->facility->address ?? __('Không rõ địa chỉ') }}</span>
                                    </div>
                                </div>
                            </div>

                            {{-- Calculation Breakdown --}}
                            <div class="summary-fee-list">
                                <div class="fee-row">
                                    <span class="fee-label">{{ __('Đơn giá sân') }}</span>
                                    <span class="fee-value" id="summary-unit-price">{{ number_format($court->price ?? 120000, 0, ',', '.') }}đ / {{ __('giờ') }}</span>
                                </div>
                                <div class="fee-row">
                                    <span class="fee-label">{{ __('Thời lượng thi đấu') }}</span>
                                    <span class="fee-value" id="summary-duration">1 {{ __('giờ') }}</span>
                                </div>
                                <div class="fee-row">
                                    <span class="fee-label">{{ __('Phí dịch vụ & tiện ích') }}</span>
                                    <span class="fee-value text-green">{{ __('Miễn phí') }}</span>
                                </div>
                                @if(isset($subscription) && $subscription && $subscription->plan && $subscription->plan->discount_percent > 0)
                                    <div class="fee-row" id="summary-vip-discount-row">
                                        <span class="fee-label text-green">
                                            <i class="ti ti-crown"></i> {{ __('Ưu đãi VIP (:name)', ['name' => $subscription->plan->name]) }}
                                        </span>
                                        <span class="fee-value text-green" id="summary-discount-val">
                                            -{{ $subscription->plan->discount_percent }}%
                                        </span>
                                    </div>
                                @endif
                                <div class="fee-divider"></div>
                                <div class="fee-row fee-total-row">
                                    <span class="total-label">{{ __('Tổng tiền tạm tính') }}</span>
                                    <span class="total-amount" id="summary-total-price">{{ number_format($court->price ?? 120000, 0, ',', '.') }}đ</span>
                                </div>
                            </div>

                            <div class="summary-action-box">
                                <button type="button" id="btn-proceed-to-checkout" class="btn-submit-order">
                                    <span>{{ __('Tiến hành xác nhận & thanh toán') }}</span>
                                    <i class="ti ti-arrow-right"></i>
                                </button>
                                <a href="{{ route('courts.show', $court->id) }}" class="btn-back-link">
                                    <i class="ti ti-arrow-left"></i>
                                    <span>{{ __('Quay lại trang chi tiết sân') }}</span>
                                </a>
                            </div>

                            <div class="summary-security-note">
                                <i class="ti ti-lock"></i>
                                <span>{{ __('Giao dịch được bảo mật an toàn 100% bởi hệ thống Sân Nhóm H') }}</span>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            {{-- =====================================================================
                 GIAI ĐOẠN B: QUY TRÌNH XÁC NHẬN & THANH TOÁN 3 BƯỚC (GIỐNG CHECKOUT)
            ===================================================================== --}}
            <div id="booking-checkout-wizard" class="checkout-container" style="display: none;">

                {{-- Breadcrumb --}}
                <div style="margin-bottom: 22px; font-size: 14px; color: var(--ink-soft, #525266); display: flex; align-items: center; gap: 8px; flex-wrap: wrap;">
                    <button type="button" id="btn-breadcrumb-back-stage1" style="background: none; border: none; padding: 0; cursor: pointer; color: #EC4899; font-size: 14px; font-weight: 600; display: inline-flex; align-items: center; gap: 4px; font-family: inherit;">
                        <i class="ti ti-arrow-left"></i> {{ __('Thông tin đặt sân') }}
                    </button>
                    <span>/</span>
                    <span style="color: var(--ink, #181824); font-weight: 500;">{{ __('Quy trình xác nhận & Thanh toán đặt sân') }}</span>
                </div>

                {{-- 3-Step Wizard Stepper --}}
                <div class="checkout-stepper">
                    <div class="step-item active" id="step-nav-1">
                        <div class="step-number" id="step-num-1">1</div>
                        <div class="step-label">1. {{ __('Chọn phương thức') }}</div>
                    </div>
                    <div class="step-item" id="step-nav-2">
                        <div class="step-number" id="step-num-2">2</div>
                        <div class="step-label">2. {{ __('Xác nhận lịch đặt') }}</div>
                    </div>
                    <div class="step-item" id="step-nav-3">
                        <div class="step-number" id="step-num-3">3</div>
                        <div class="step-label">3. {{ __('Quét mã QR') }}</div>
                    </div>
                </div>

                <div class="checkout-card">

                    {{-- Court Booking Summary Header (Giống Checkout Merch / Plan) --}}
                    <div class="checkout-prod-box">
                        <div class="checkout-prod-img">
                            @if($primaryImg)
                                <img src="{{ asset($primaryImg->image_path) }}" alt="{{ $court->name }}" style="width: 100%; height: 100%; object-fit: cover; border-radius: 8px;">
                            @else
                                <i class="ti ti-ball-football" style="font-size: 36px; color: #EC4899;"></i>
                            @endif
                        </div>

                        <div style="flex-grow: 1; min-width: 0;">
                            <div style="display: flex; align-items: center; gap: 8px; flex-wrap: wrap; margin-bottom: 6px;">
                                <span style="display: inline-flex; align-items: center; gap: 4px; background: #FCE7F3; color: #BE185D; font-size: 11.5px; font-weight: 700; padding: 3px 10px; border-radius: 999px;">
                                    <i class="ti ti-sparkles" style="font-size: 11px;"></i> {{ $court->sportType->name ?? __('Thể thao') }}
                                </span>
                                <span style="display: inline-flex; align-items: center; gap: 4px; background: #EFF6FF; color: #1D4ED8; font-size: 11.5px; font-weight: 700; padding: 3px 10px; border-radius: 999px;" id="chk-badge-schedule">
                                    <i class="ti ti-clock" style="font-size: 11px;"></i> <span id="chk-header-slot">18:00 - 19:00</span>
                                </span>
                            </div>

                            <div style="font-weight: 800; color: var(--ink, #181824); font-size: 18px; margin-bottom: 4px; font-family: 'Oswald', sans-serif;">
                                {{ $court->name }}
                            </div>

                            <div style="display: flex; align-items: baseline; justify-content: space-between; flex-wrap: wrap; gap: 8px;">
                                <span style="font-size: 13px; color: #DB2777; font-weight: 600; display: inline-flex; align-items: center; gap: 4px;">
                                    <i class="ti ti-map-pin" style="font-size: 14px;"></i> {{ $court->facility->address ?? __('Cơ sở Sân Nhóm H') }}
                                </span>
                                <span style="font-size: 24px; font-weight: 800; color: #EC4899; font-family: 'Oswald', sans-serif;" class="total-display-tag">
                                    {{ number_format($court->price ?? 120000, 0, ',', '.') }}₫
                                </span>
                            </div>
                        </div>
                    </div>

                    {{-- ========================================================
                         BƯỚC 1: CHỌN PHƯƠNG THỨC THANH TOÁN
                    ======================================================== --}}
                    <div id="step-section-1">
                        <h3 style="font-size: 18px; font-weight: 700; color: var(--ink, #181824); margin: 0 0 18px; display: flex; align-items: center; gap: 8px;">
                            <i class="ti ti-credit-card" style="color: #EC4899;"></i> {{ __('Bước 1: Chọn phương thức thanh toán') }}
                        </h3>

                        <div style="margin-bottom: 24px;">
                            {{-- VietQR Bank Transfer (Recommended) --}}
                            <label class="payment-option-card selected" id="opt-qr">
                                <input type="radio" name="payment_method" value="qr" checked>
                                <div style="width: 40px; height: 40px; border-radius: 10px; background: #FDF2F8; display: flex; align-items: center; justify-content: center; font-size: 20px; color: #EC4899; margin-right: 14px; flex-shrink: 0;">
                                    <i class="ti ti-building-bank"></i>
                                </div>
                                <div style="flex-grow: 1;">
                                    <div style="font-weight: 700; font-size: 14.5px; color: var(--ink, #181824);">{{ __('Quét mã QR Ngân Hàng (VietQR / Chuyển khoản 24/7)') }}</div>
                                    <div style="font-size: 12.5px; color: var(--ink-soft, #6B7280);">{{ __('Quét mã chuyển tiền tức thì từ TPBank, MB Bank, Vietcombank, Techcombank, BIDV...') }}</div>
                                </div>
                                <span style="font-size: 11.5px; font-weight: 700; background: #FCE7F3; color: #BE185D; padding: 3px 9px; border-radius: 6px; flex-shrink: 0;">{{ __('Khuyên dùng') }}</span>
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

                            {{-- Visa / Mastercard --}}
                            <label class="payment-option-card" id="opt-visa">
                                <input type="radio" name="payment_method" value="visa">
                                <div style="width: 40px; height: 40px; border-radius: 10px; background: #EEF2FF; display: flex; align-items: center; justify-content: center; font-size: 20px; color: #4F46E5; margin-right: 14px; flex-shrink: 0;">
                                    <i class="ti ti-credit-card"></i>
                                </div>
                                <div style="flex-grow: 1;">
                                    <div style="font-weight: 700; font-size: 14.5px; color: var(--ink, #111827);">{{ __('Thẻ Visa/Mastercard') }}</div>
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

                            {{-- COD / Pay at Stadium --}}
                            <label class="payment-option-card" id="opt-cod">
                                <input type="radio" name="payment_method" value="cod">
                                <div style="width: 40px; height: 40px; border-radius: 10px; background: #FFFBEB; display: flex; align-items: center; justify-content: center; font-size: 20px; color: #D97706; margin-right: 14px; flex-shrink: 0;">
                                    <i class="ti ti-cash"></i>
                                </div>
                                <div style="flex-grow: 1;">
                                    <div style="font-weight: 700; font-size: 14.5px; color: var(--ink, #111827);">{{ __('Thanh toán trực tiếp tại sân (COD)') }}</div>
                                    <div style="font-size: 12.5px; color: var(--ink-soft, #6B7280);">{{ __('Thanh toán tiền mặt cho ban quản lý sân trước giờ vào sân 15 phút') }}</div>
                                </div>
                            </label>
                        </div>

                        {{-- Price summary in Step 1 (Giống y hệt giao diện Plans & Merch Checkout) --}}
                        <div style="background: var(--bg-page, #FAF7F9); border-radius: 14px; padding: 18px 20px; border: 1px solid var(--line, #F1E4EC); margin-bottom: 24px;">
                            <div style="display: flex; justify-content: space-between; margin-bottom: 8px; font-size: 14px; color: var(--ink-soft, #6B7280);">
                                <span>{{ __('Sân thi đấu:') }}</span>
                                <strong style="color: var(--ink, #181824);">{{ $court->name }} ({{ $court->sportType->name ?? __('Thể thao') }})</strong>
                            </div>
                            <div style="display: flex; justify-content: space-between; margin-bottom: 8px; font-size: 14px; color: var(--ink-soft, #6B7280);">
                                <span>{{ __('Khung giờ & Ngày đặt:') }}</span>
                                <strong style="color: #DB2777;" id="chk-step1-schedule">18:00 - 19:00</strong>
                            </div>
                            <div style="display: flex; justify-content: space-between; margin-bottom: 8px; font-size: 14px; color: var(--ink-soft, #6B7280);">
                                <span>{{ __('Thời lượng thi đấu:') }}</span>
                                <strong style="color: var(--ink, #181824);" id="chk-step1-duration">1 giờ</strong>
                            </div>
                            @if(isset($subscription) && $subscription && $subscription->plan && $subscription->plan->discount_percent > 0)
                                <div style="display: flex; justify-content: space-between; margin-bottom: 8px; font-size: 14px; color: var(--ink-soft, #6B7280);">
                                    <span>{{ __('Ưu đãi hội viên VIP:') }}</span>
                                    <span style="color: #EC4899; font-weight: 700;" id="chk-step1-discount">Giảm {{ $subscription->plan->discount_percent }}% ({{ $subscription->plan->name }})</span>
                                </div>
                            @endif
                            <div style="display: flex; justify-content: space-between; align-items: baseline; border-top: 1px dashed var(--line, #F1E4EC); padding-top: 12px; margin-top: 8px;">
                                <span style="font-weight: 700; font-size: 15px; color: var(--ink, #181824);">{{ __('Tổng thanh toán:') }}</span>
                                <span style="font-size: 24px; font-weight: 800; color: #EC4899; font-family: 'Oswald', sans-serif;" class="total-display-tag">
                                    {{ number_format($court->price ?? 120000, 0, ',', '.') }}₫
                                </span>
                            </div>
                        </div>

                        <div style="display: flex; justify-content: flex-end; gap: 12px;">
                            <button type="button" class="btn-checkout-back" id="btn-back-stage1">
                                <i class="ti ti-arrow-left"></i> {{ __('Quay lại') }}
                            </button>
                            <button type="button" class="btn-checkout-primary" id="btn-to-step-2" style="width: auto; padding: 0 28px;">
                                <span>{{ __('Tiếp tục xác nhận') }}</span> <i class="ti ti-arrow-right"></i>
                            </button>
                        </div>
                    </div>

                    {{-- ========================================================
                         BƯỚC 2: XÁC NHẬN THÔNG TIN ĐẶT SÂN
                    ======================================================== --}}
                    <div id="step-section-2" style="display: none;">
                        <h3 style="font-size: 18px; font-weight: 700; color: var(--ink, #181824); margin: 0 0 18px; display: flex; align-items: center; gap: 8px;">
                            <i class="ti ti-user-check" style="color: #EC4899;"></i> {{ __('Bước 2: Kiểm tra & Xác nhận thông tin giữ chỗ') }}
                        </h3>

                        <div style="background: var(--bg-page, #FAF7F9); padding: 22px; border-radius: 16px; border: 1px solid var(--line, #F1E4EC); margin-bottom: 24px;">
                            <div class="form-grid-2" style="margin-bottom: 16px;">
                                <div class="form-group-custom" style="margin-bottom: 0;">
                                    <label class="form-label-custom">{{ __('Họ và tên người đặt sân') }}</label>
                                    <input type="text" id="confirm-customer-name" class="form-input-custom" readonly style="background: #F3F4F6; font-weight: 600;">
                                </div>
                                <div class="form-group-custom" style="margin-bottom: 0;">
                                    <label class="form-label-custom">{{ __('Số điện thoại nhận mã vé') }}</label>
                                    <input type="text" id="confirm-customer-phone" class="form-input-custom" readonly style="background: #F3F4F6; font-weight: 600;">
                                </div>
                            </div>

                            <div class="form-grid-2" style="margin-bottom: 16px;">
                                <div class="form-group-custom" style="margin-bottom: 0;">
                                    <label class="form-label-custom">{{ __('Địa chỉ Email nhận xác nhận') }}</label>
                                    <input type="text" id="confirm-customer-email" class="form-input-custom" readonly style="background: #F3F4F6;">
                                </div>
                                <div class="form-group-custom" style="margin-bottom: 0;">
                                    <label class="form-label-custom">{{ __('Lịch thi đấu & Khung giờ') }}</label>
                                    <input type="text" id="confirm-schedule-text" class="form-input-custom" readonly style="background: #FDF2F8; color: #DB2777; font-weight: 700; border-color: #FBCFE8;">
                                </div>
                            </div>

                            <div class="form-group-custom" style="margin-bottom: 16px;">
                                <label class="form-label-custom">{{ __('Phương thức thanh toán đã chọn') }}</label>
                                <input type="text" id="confirm-payment-method-text" class="form-input-custom" readonly style="background: #F3F4F6; font-weight: 700; color: #EC4899;">
                            </div>

                            <div style="background: #FDF2F8; border: 1px solid #FBCFE8; border-radius: 12px; padding: 14px 16px; display: flex; gap: 12px; align-items: flex-start;">
                                <i class="ti ti-shield-check" style="color: #EC4899; font-size: 22px; flex-shrink: 0; margin-top: 2px;"></i>
                                <div style="font-size: 13px; color: #9D174D; line-height: 1.5;">
                                    <strong>{{ __('Xác nhận giữ chỗ tự động ngay sau khi thanh toán:') }}</strong>
                                    {{ __('Mã vé điện tử sẽ được cấp trực tiếp cho tài khoản của bạn và gửi thông báo xác nhận ngay khi hoàn tất bước tiếp theo.') }}
                                </div>
                            </div>
                        </div>

                        <div style="display: flex; justify-content: space-between; gap: 12px; flex-wrap: wrap;">
                            <button type="button" class="btn-checkout-back" id="btn-back-step-1">
                                <i class="ti ti-arrow-left"></i> {{ __('Quay lại Bước 1') }}
                            </button>
                            <button type="button" class="btn-checkout-primary" id="btn-to-step-3" style="width: auto; padding: 0 28px;">
                                <span id="btn-to-step-3-text">{{ __('Tiếp tục: Quét mã QR thanh toán') }}</span> <i class="ti ti-arrow-right"></i>
                            </button>
                        </div>
                    </div>

                    {{-- ========================================================
                         BƯỚC 3: MÃ QR THANH TOÁN & XÁC NHẬN HOÀN TẤT ĐẶT SÂN
                    ======================================================== --}}
                    <div id="step-section-3" style="display: none;">
                        <h3 style="font-size: 18px; font-weight: 700; color: var(--ink, #181824); margin: 0 0 18px; display: flex; align-items: center; gap: 8px;">
                            <i class="ti ti-qrcode" style="color: #EC4899;"></i> {{ __('Bước 3: Quét mã QR & Xác nhận thanh toán đặt sân') }}
                        </h3>

                        {{-- QR Payment Container (Visible for QR / Bank / MoMo / Visa / PayPal) --}}
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

                            {{-- Countdown Timer --}}
                            <div style="font-size: 13px; color: #DC2626; font-weight: 700; margin-bottom: 16px; display: flex; align-items: center; justify-content: center; gap: 6px;">
                                <i class="ti ti-clock"></i> {{ __('Mã QR giữ chỗ có hiệu lực trong:') }} <span id="countdown-timer">14:59</span>
                            </div>

                            {{-- =========================================
                                 VIEW 1: MÃ QR TPBANK (VIETQR)
                            ========================================= --}}
                            <div id="bank-qr-view">
                                <div style="display: flex; align-items: center; justify-content: center; gap: 8px; margin-bottom: 12px; flex-wrap: wrap;">
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
                                        <a href="{{ asset('img qr/qr_card_clean.jpg') }}" download="QR_DatSan_TPBank.jpg" class="btn-copy" style="text-decoration: none; display: inline-flex; align-items: center; gap: 6px; padding: 7px 14px; font-size: 13px; font-weight: 600; float: none; margin-left: 0;">
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
                                    <strong>{{ __('Hướng dẫn:') }}</strong> {{ __('Quý khách mở App Ngân hàng (TPBank, MB, VCB, BIDV, Techcombank...) hoặc MoMo, chọn Quét mã QR để quét mã trên. Vui lòng giữ nguyên nội dung chuyển khoản để lịch đặt sân được xác nhận tức thì.') }}
                                </div>
                            </div>

                            {{-- =========================================
                                 VIEW 2: MÃ QR VÍ MOMO (NAPAS 247)
                            ========================================= --}}
                            <div id="momo-qr-view" style="display: none;">
                                <div style="display: flex; align-items: center; justify-content: center; gap: 8px; margin-bottom: 12px; flex-wrap: wrap;">
                                    <span style="background: #A50064; color: #fff; font-size: 11px; font-weight: 700; padding: 4px 10px; border-radius: 20px;">
                                        VÍ ĐIỆN TỬ MOMO
                                    </span>
                                    <span style="font-size: 13px; color: #475569; font-weight: 600;">
                                        {{ __('Hỗ trợ tất cả App MoMo & Napas 247') }}
                                    </span>
                                </div>

                                {{-- MoMo QR Image --}}
                                <div class="qr-img-wrapper" style="display: inline-flex; flex-direction: column; align-items: center; padding: 16px; background: #ffffff; border-radius: 18px; box-shadow: 0 8px 24px rgba(165,0,100,0.12); border: 2px solid #FBCFE8; margin-bottom: 16px;">
                                    <img id="momo-qr-image" src="{{ asset('img qr/momo_card_clean.jpg') }}" alt="Mã QR Ví MoMo - NGUYEN CHI HAI" style="max-width: 270px; width: 100%; height: auto; border-radius: 12px; display: block;">
                                    <div style="margin-top: 12px;">
                                        <a href="{{ asset('img qr/momo_card_clean.jpg') }}" download="QR_DatSan_MoMo.jpg" class="btn-copy" style="text-decoration: none; display: inline-flex; align-items: center; gap: 6px; padding: 7px 14px; font-size: 13px; font-weight: 600; background: #A50064; color: #fff; border-color: #A50064; float: none; margin-left: 0;">
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
                                            <span id="momo-amount" style="color: #A50064; font-weight: 800; font-size: 16px;"></span>
                                            <button type="button" class="btn-copy" id="btn-copy-momo-amount">{{ __('Sao chép') }}</button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>{{ __('Nội dung chuyển tiền:') }}</td>
                                        <td>
                                            <span id="momo-memo" style="color: #A50064; font-weight: 700; font-family: monospace; font-size: 15px;"></span>
                                            <button type="button" class="btn-copy" id="btn-copy-momo-memo">{{ __('Sao chép') }}</button>
                                        </td>
                                    </tr>
                                </table>

                                <div style="background: rgba(165,0,100,0.06); border: 1px solid #FBCFE8; border-radius: 10px; padding: 12px; font-size: 13px; color: #831843; line-height: 1.5; max-width: 480px; margin: 0 auto; text-align: left;">
                                    <i class="ti ti-info-circle" style="margin-right: 4px; color: #A50064;"></i>
                                    <strong>{{ __('Hướng dẫn MoMo:') }}</strong> {{ __('Mở ứng dụng MoMo → Chọn Quét mã QR → Quét mã trên để chuyển tiền tự động. Vui lòng giữ nguyên nội dung chuyển tiền để lịch đặt sân được xác nhận nhanh chóng.') }}
                                </div>
                            </div>
                        </div>

                        {{-- COD Alternative Notice (if COD chosen) --}}
                        <div id="cod-display-box" style="display: none; background: #FEF3C7; border: 1.5px solid #FCD34D; border-radius: 16px; padding: 22px; margin-bottom: 24px;">
                            <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 8px;">
                                <i class="ti ti-cash" style="font-size: 26px; color: #B45309;"></i>
                                <h4 style="margin: 0; font-size: 16px; font-weight: 700; color: #92400E;">{{ __('Thanh toán tiền mặt trực tiếp tại sân (COD)') }}</h4>
                            </div>
                            <p style="margin: 0 0 10px; font-size: 14px; color: #78350F; line-height: 1.5;">
                                {{ __('Quý khách sẽ thanh toán số tiền') }} <strong class="total-display-tag" style="color: #B45309;"></strong> {{ __('trực tiếp tại quầy lễ tân ban quản lý sân trước giờ thi đấu 15 phút.') }}
                            </p>
                            <div style="font-size: 12.5px; color: #92400E; display: flex; align-items: center; gap: 6px;">
                                <i class="ti ti-circle-check"></i> {{ __('Vui lòng xuất trình Mã giữ chỗ điện tử khi đến nhận sân.') }}
                            </div>
                        </div>

                        {{-- Real-time Payment Status Notice Box --}}
                        <div style="background: #FDF2F8; border: 1px solid #FBCFE8; border-radius: 14px; padding: 14px 18px; margin-bottom: 22px; display: flex; align-items: center; gap: 12px;">
                            <div style="width: 38px; height: 38px; border-radius: 50%; background: #FCE7F3; color: #DB2777; display: flex; align-items: center; justify-content: center; font-size: 20px; flex-shrink: 0;">
                                <i class="ti ti-bell-ringing"></i>
                            </div>
                            <div style="font-size: 13px; color: #9D174D; line-height: 1.45;">
                                <strong>{{ __('Thông báo xác nhận thanh toán tự động:') }}</strong>
                                {{ __('Sau khi quét mã chuyển khoản thành công, quý khách nhấn nút "Tôi đã thanh toán" bên dưới để hệ thống phát hành biên lai & mã vé giữ chỗ ngay lập tức.') }}
                            </div>
                        </div>

                        {{-- Final Confirmation Button --}}
                        <div style="display: flex; justify-content: space-between; gap: 12px;">
                            <button type="button" id="btn-back-step-2" class="btn-checkout-back">
                                <i class="ti ti-arrow-left"></i> {{ __('Quay lại') }}
                            </button>
                            <button type="submit" class="btn-checkout-primary" id="btn-final-confirm" style="flex: 1;">
                                <i class="ti ti-circle-check"></i>
                                <span id="btn-final-text">{{ __('Tôi đã thanh toán — Xác nhận hoàn tất đặt sân') }}</span>
                            </button>
                        </div>
                    </div>

                </div>
            </div>

        </form>

    </div>
</div>

{{-- Copy Notification Toast --}}
<div id="copy-toast-notification" style="position: fixed; bottom: 28px; right: 28px; background: #181824; color: #FFFFFF; padding: 12px 20px; border-radius: 12px; font-size: 13.5px; font-weight: 600; display: none; align-items: center; gap: 8px; box-shadow: 0 10px 25px rgba(0,0,0,0.2); z-index: 99999; border: 1px solid #EC4899;">
    <i class="ti ti-circle-check" style="color: #FF2E93; font-size: 18px;"></i>
    <span id="copy-toast-text">{{ __('Đã sao chép vào bộ nhớ tạm!') }}</span>
</div>

{{-- Payment Verification Overlay Modal when submitting --}}
<div id="payment-verifying-overlay" style="position: fixed; inset: 0; background: rgba(15, 23, 42, 0.7); backdrop-filter: blur(5px); display: none; align-items: center; justify-content: center; z-index: 999999; padding: 20px;">
    <div style="background: var(--bg-white, #FFFFFF); border-radius: 24px; max-width: 440px; width: 100%; padding: 32px 26px; text-align: center; box-shadow: 0 25px 60px rgba(0,0,0,0.3); border: 1px solid var(--line, #F1E4EC);">
        <div style="width: 72px; height: 72px; border-radius: 50%; background: #FCE7F3; color: #EC4899; display: inline-flex; align-items: center; justify-content: center; font-size: 38px; margin-bottom: 16px; box-shadow: 0 8px 20px rgba(236,72,153,0.25);">
            <i class="ti ti-shield-check"></i>
        </div>
        <h3 style="margin: 0 0 8px; font-size: 21px; font-weight: 800; color: var(--ink, #181824); font-family: 'Oswald', sans-serif; letter-spacing: 0.4px;">
            {{ __('ĐANG XÁC THỰC THANH TOÁN & GIỮ CHỖ...') }}
        </h3>
        <p style="margin: 0 0 18px; font-size: 14px; color: var(--ink-soft, #525266); line-height: 1.5;">
            {{ __('Hệ thống đang ghi nhận giao dịch và khởi tạo mã vé điện tử cho khung giờ của bạn. Vui lòng đợi trong giây lát!') }}
        </p>
        <div style="background: #FDF2F8; border: 1px solid #FBCFE8; border-radius: 12px; padding: 10px 14px; font-size: 13px; color: #DB2777; font-weight: 700;">
            <i class="ti ti-qrcode"></i> <span id="verifying-memo-display"></span>
        </div>
    </div>
</div>

@push('styles')
<style>
.booking-flow-page {
    background: var(--bg-page);
    min-height: 85vh;
    padding: 40px 0 70px;
}
.booking-container {
    max-width: 1040px;
}
.booking-steps-banner {
    background: var(--surface, #fff);
    border: 1px solid var(--line, #F1E4EC);
    border-radius: 20px;
    padding: 20px 32px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 30px;
    box-shadow: 0 4px 18px rgba(24, 24, 36, 0.03);
}
.step-node {
    display: flex;
    align-items: center;
    gap: 12px;
}
.step-num {
    width: 38px;
    height: 38px;
    border-radius: 50%;
    background: var(--bg-page, #FAF7F9);
    color: var(--ink-muted, #8E8EA0);
    font-size: 15px;
    font-weight: 700;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 2px solid var(--line, #F1E4EC);
}
.step-node.active .step-num {
    background: linear-gradient(135deg, #FF2E93 0%, #DB2777 100%);
    color: #fff;
    border-color: #EC4899;
}
.step-text {
    display: flex;
    flex-direction: column;
}
.step-title {
    font-size: 11.5px;
    font-weight: 700;
    text-transform: uppercase;
    color: var(--ink-muted, #8E8EA0);
    letter-spacing: 0.5px;
}
.step-node.active .step-title {
    color: #EC4899;
}
.step-sub {
    font-size: 14px;
    font-weight: 600;
    color: var(--ink, #181824);
}
.step-connector {
    flex: 1;
    height: 2px;
    background: var(--line, #F1E4EC);
    margin: 0 20px;
}
.step-connector.active {
    background: #EC4899;
}
.form-alert-error {
    background: #FEF2F2;
    border: 1px solid #FCA5A5;
    border-radius: 14px;
    padding: 16px 20px;
    margin-bottom: 24px;
    display: flex;
    gap: 14px;
    color: #991B1B;
}
.error-icon {
    font-size: 24px;
    color: #DC2626;
    flex-shrink: 0;
}
.error-list ul {
    margin: 6px 0 0;
    padding-left: 20px;
    font-size: 13.5px;
}
.booking-layout-grid {
    display: grid;
    grid-template-columns: 1.5fr 1fr;
    gap: 30px;
    align-items: flex-start;
}
.booking-form-col {
    display: flex;
    flex-direction: column;
    gap: 24px;
}
.form-card-section {
    background: var(--surface, #fff);
    border: 1px solid var(--line, #F1E4EC);
    border-radius: 20px;
    padding: 26px 28px;
    box-shadow: 0 4px 18px rgba(24, 24, 36, 0.03);
}
.card-section-head {
    display: flex;
    align-items: flex-start;
    gap: 14px;
    margin-bottom: 22px;
    padding-bottom: 16px;
    border-bottom: 1px solid var(--line, #F1E4EC);
}
.card-section-head i {
    font-size: 28px;
    color: #EC4899;
}
.card-section-head h3 {
    margin: 0 0 4px;
    font-size: 18px;
    font-weight: 700;
    color: var(--ink, #181824);
}
.card-section-head p {
    margin: 0;
    font-size: 13px;
    color: var(--ink-soft, #525266);
}
.form-grid-2 {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 18px;
    margin-bottom: 18px;
}
.form-grid-3 {
    display: grid;
    grid-template-columns: 1fr 1fr 1fr;
    gap: 14px;
    margin-bottom: 18px;
}
.input-field-group {
    display: flex;
    flex-direction: column;
    gap: 6px;
    margin-bottom: 16px;
}
.input-field-group:last-child {
    margin-bottom: 0;
}
.form-label {
    font-size: 13px;
    font-weight: 600;
    color: var(--ink, #181824);
}
.form-label .req {
    color: #ef4444;
}
.input-with-icon {
    display: flex;
    align-items: center;
    background: var(--bg-page, #FAF7F9);
    border: 1px solid #E5D5DF;
    border-radius: 12px;
    padding: 0 12px;
    transition: all 0.2s ease;
}
.input-with-icon i {
    font-size: 18px;
    color: var(--ink-muted, #8E8EA0);
    margin-right: 10px;
    flex-shrink: 0;
}
.input-with-icon input {
    width: 100%;
    height: 42px;
    border: none;
    outline: none;
    background: transparent;
    font-size: 14px;
    color: var(--ink, #181824);
    font-family: inherit;
}
.input-with-icon:focus-within {
    border-color: #EC4899;
    background: #ffffff;
    box-shadow: 0 0 0 3px rgba(236, 72, 153, 0.14);
}
.input-field-group textarea {
    width: 100%;
    border: 1px solid #E5D5DF;
    background: var(--bg-page, #FAF7F9);
    border-radius: 12px;
    padding: 12px;
    font-size: 13.5px;
    color: var(--ink, #181824);
    outline: none;
    font-family: inherit;
    resize: vertical;
    transition: all 0.2s;
}
.input-field-group textarea:focus {
    border-color: #EC4899;
    background: #ffffff;
    box-shadow: 0 0 0 3px rgba(236, 72, 153, 0.14);
}
.agreement-checkbox-row {
    margin-top: 14px;
    padding-top: 14px;
    border-top: 1px dashed var(--line, #F1E4EC);
}
.checkbox-container {
    display: flex;
    align-items: flex-start;
    gap: 10px;
    cursor: pointer;
    user-select: none;
    font-size: 13px;
    color: var(--ink-soft, #525266);
    line-height: 1.5;
}
.checkbox-container input {
    margin-top: 3px;
    width: 16px;
    height: 16px;
    accent-color: #EC4899;
}
.agreement-text a {
    color: #EC4899;
    font-weight: 600;
    text-decoration: underline;
}
.summary-card {
    background: var(--surface, #fff);
    border: 1px solid var(--line, #F1E4EC);
    border-radius: 20px;
    padding: 24px;
    box-shadow: 0 6px 24px rgba(24, 24, 36, 0.05);
    position: sticky;
    top: 90px;
}
.summary-head {
    margin-bottom: 20px;
}
.summary-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 11.5px;
    font-weight: 700;
    color: #DB2777;
    background: #FDF2F8;
    padding: 4px 10px;
    border-radius: 20px;
    margin-bottom: 8px;
}
.summary-head h4 {
    margin: 0;
    font-size: 18px;
    font-weight: 700;
    color: var(--ink, #181824);
}
.court-summary-item {
    display: flex;
    gap: 14px;
    align-items: center;
    background: var(--bg-page, #FAF7F9);
    border: 1px solid var(--line, #F1E4EC);
    border-radius: 14px;
    padding: 12px;
    margin-bottom: 20px;
}
.court-summary-thumb {
    width: 64px;
    height: 64px;
    border-radius: 10px;
    overflow: hidden;
    background: #FDF2F8;
    flex-shrink: 0;
}
.court-summary-thumb img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}
.thumb-icon-ph {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 28px;
    color: #EC4899;
}
.court-summary-info {
    flex: 1;
    min-width: 0;
}
.sport-chip {
    font-size: 11px;
    font-weight: 700;
    color: #EC4899;
}
.court-summary-title {
    margin: 2px 0 4px;
    font-size: 14.5px;
    font-weight: 700;
    color: var(--ink, #181824);
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.court-summary-loc {
    display: flex;
    align-items: center;
    gap: 4px;
    font-size: 12px;
    color: var(--ink-soft, #525266);
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.summary-fee-list {
    display: flex;
    flex-direction: column;
    gap: 12px;
    margin-bottom: 20px;
}
.fee-row {
    display: flex;
    justify-content: space-between;
    font-size: 13.5px;
    color: var(--ink-soft, #525266);
}
.fee-value {
    font-weight: 600;
    color: var(--ink, #181824);
}
.text-green {
    color: #DB2777 !important;
}
.fee-divider {
    height: 1px;
    background: var(--line, #F1E4EC);
    margin: 4px 0;
}
.fee-total-row {
    align-items: baseline;
    font-size: 15px;
    font-weight: 700;
    color: var(--ink, #181824);
}
.total-amount {
    font-size: 22px;
    font-weight: 800;
    color: #EC4899;
    font-family: 'Oswald', sans-serif;
}
.summary-action-box {
    display: flex;
    flex-direction: column;
    gap: 10px;
}
.btn-submit-order {
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    background: linear-gradient(135deg, #FF2E93 0%, #DB2777 100%);
    color: #fff;
    border: none;
    font-size: 15px;
    font-weight: 700;
    padding: 14px;
    border-radius: 12px;
    cursor: pointer;
    box-shadow: 0 4px 14px rgba(236, 72, 153, 0.3);
    transition: all 0.2s ease;
}
.btn-submit-order:hover {
    background: linear-gradient(135deg, #DB2777 0%, #BE185D 100%);
    transform: translateY(-2px);
    box-shadow: 0 6px 18px rgba(236, 72, 153, 0.42);
}
.btn-back-link {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    font-size: 13px;
    color: var(--ink-soft, #525266);
    padding: 8px;
}
.btn-back-link:hover {
    color: #EC4899;
}
.summary-security-note {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-top: 18px;
    padding-top: 14px;
    border-top: 1px solid var(--line, #F1E4EC);
    font-size: 11.5px;
    color: var(--ink-muted, #8E8EA0);
    line-height: 1.4;
}
.summary-security-note i {
    font-size: 16px;
    color: #EC4899;
    flex-shrink: 0;
}

/* =========================================================
   STYLES CHO QUY TRÌNH CHECKOUT & MÃ QR (GIỐNG PRODUCTS/PLANS)
========================================================= */
.checkout-container {
    max-width: 820px;
    margin: 0 auto 30px;
    padding: 0;
}
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
    background: var(--line, #F1E4EC);
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
.checkout-card {
    background: var(--bg-white, #FFFFFF);
    border: 1px solid var(--line, #F1E4EC);
    border-radius: 24px;
    padding: 36px 32px;
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.05);
}
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
    width: 80px;
    height: 80px;
    background: #FFFFFF;
    border-radius: 14px;
    border: 1px solid var(--line, #F1E4EC);
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    padding: 4px;
    overflow: hidden;
}
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
.form-group-custom {
    margin-bottom: 20px;
}
.form-label-custom {
    display: block;
    font-size: 13.5px;
    font-weight: 700;
    color: var(--ink, #181824);
    margin-bottom: 6px;
}
.form-input-custom {
    width: 100%;
    height: 46px;
    padding: 0 14px;
    border: 1.5px solid var(--line, #D1D5DB);
    border-radius: 12px;
    font-size: 14px;
    background: var(--bg-white, #FFFFFF);
    color: var(--ink, #181824);
    outline: none;
}
.qr-payment-frame {
    background: linear-gradient(135deg, #FDF2F8 0%, #FAF7F9 100%);
    border: 2px solid #FBCFE8;
    border-radius: 20px;
    padding: 26px;
    margin-bottom: 24px;
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
.btn-checkout-primary {
    height: 52px;
    padding: 0 28px;
    border-radius: 12px;
    background: linear-gradient(135deg, #FF2E93 0%, #DB2777 100%);
    color: #FFFFFF;
    border: none;
    font-size: 15.5px;
    font-weight: 700;
    cursor: pointer;
    display: inline-flex;
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

@media (max-width: 900px) {
    .booking-layout-grid {
        grid-template-columns: 1fr;
    }
    .summary-card {
        position: static;
    }
    .form-grid-3, .form-grid-2 {
        grid-template-columns: 1fr;
    }
    .booking-steps-banner {
        flex-direction: column;
        gap: 14px;
        align-items: flex-start;
    }
    .step-connector {
        display: none;
    }
    .checkout-card {
        padding: 24px 18px;
    }
}
</style>
@endpush

@push('scripts')
<script nonce="{{ $cspNonce ?? '' }}">
document.addEventListener('DOMContentLoaded', function() {
    const dateInput = document.getElementById('booking_date_input');
    const startInput = document.getElementById('start_time_input');
    const endInput = document.getElementById('end_time_input');
    const nameInput = document.getElementById('customer_name_input');
    const phoneInput = document.getElementById('customer_phone_input');
    const emailInput = document.getElementById('customer_email_input');
    const agreeInput = document.getElementById('agreement_checkbox');
    const memoHiddenInput = document.getElementById('payment_memo_input');

    const durationSpan = document.getElementById('summary-duration');
    const totalSpan = document.getElementById('summary-total-price');
    const unitPriceSpan = document.getElementById('summary-unit-price');

    const stageInitial = document.getElementById('booking-initial-stage');
    const stageWizard = document.getElementById('booking-checkout-wizard');
    const alertBox = document.getElementById('client-validation-alert');
    const alertMsg = document.getElementById('client-validation-msg');

    // Mảng quy tắc tính giá từ server
    const priceRules = @json($priceRules ?? []);
    const fallbackPrice = {{ (int) ($court->price ?? 120000) }};
    const discountPercent = {{ (int) (($subscription && $subscription->plan) ? $subscription->plan->discount_percent : 0) }};
    const courtId = {{ (int) $court->id }};
    const bookingMemoCode = 'BK' + courtId + 'T' + Math.floor(10000 + Math.random() * 90000);

    if (memoHiddenInput) {
        memoHiddenInput.value = bookingMemoCode;
    }

    let currentTotalAmount = fallbackPrice;
    let currentDurationHours = 1;
    let selectedPayment = 'qr';

    const paymentMethodLabels = {
        'qr': 'Quét mã QR Ngân Hàng (VietQR / Chuyển khoản 24/7)',
        'momo': 'Ví điện tử MoMo (Napas 247)',
        'visa': 'Thẻ Quốc tế (Visa / MasterCard)',
        'paypal': 'Cổng thanh toán PayPal',
        'cod': 'Thanh toán trực tiếp tại sân (COD)'
    };

    function isWeekend(dateStr) {
        if (!dateStr) return false;
        const d = new Date(dateStr);
        const day = d.getDay();
        return day === 0 || day === 6;
    }

    function formatDateVN(dateStr) {
        if (!dateStr) return '';
        const parts = dateStr.split('-');
        if (parts.length !== 3) return dateStr;
        return parts[2] + '/' + parts[1] + '/' + parts[0];
    }

    function calculateDynamicPrice() {
        const dVal = dateInput ? dateInput.value : '';
        const sVal = startInput ? startInput.value : '';
        const eVal = endInput ? endInput.value : '';

        if (!dVal || !sVal || !eVal) return;

        const sParts = sVal.split(':').map(Number);
        const eParts = eVal.split(':').map(Number);
        if (sParts.length < 2 || eParts.length < 2) return;

        const sMinutes = sParts[0] * 60 + sParts[1];
        const eMinutes = eParts[0] * 60 + eParts[1];

        if (eMinutes <= sMinutes) {
            if (durationSpan) durationSpan.textContent = '0 giờ';
            currentDurationHours = 0;
            return;
        }

        const diffHours = (eMinutes - sMinutes) / 60;
        currentDurationHours = diffHours;
        if (durationSpan) {
            durationSpan.textContent = diffHours + ' giờ';
        }

        const dayType = isWeekend(dVal) ? 'weekend' : 'weekday';
        let subtotal = 0;

        for (let m = sMinutes; m < eMinutes; m += 60) {
            const h = Math.floor(m / 60);
            const hourStr = (h < 10 ? '0' + h : h) + ':00:00';

            let matchedRule = null;
            if (priceRules && priceRules.length > 0) {
                for (let r of priceRules) {
                    if (r.day_type === dayType && r.start_time <= hourStr && r.end_time > hourStr) {
                        matchedRule = r;
                        break;
                    }
                }
            }

            const stepFraction = Math.min(60, eMinutes - m) / 60;
            if (matchedRule) {
                subtotal += Math.round(Number(matchedRule.price) * stepFraction);
            } else {
                subtotal += Math.round(fallbackPrice * stepFraction);
            }
        }

        let discount = 0;
        if (discountPercent > 0) {
            discount = Math.round(subtotal * (discountPercent / 100));
        }

        const finalTotal = Math.max(0, subtotal - discount);
        currentTotalAmount = finalTotal;

        const formattedDong = new Intl.NumberFormat('vi-VN').format(finalTotal) + 'đ';
        const formattedSymbol = new Intl.NumberFormat('vi-VN').format(finalTotal) + '₫';

        if (totalSpan) {
            totalSpan.textContent = formattedDong;
        }
        if (unitPriceSpan && priceRules.length > 0) {
            const avgHourPrice = Math.round(subtotal / Math.max(1, diffHours));
            unitPriceSpan.textContent = new Intl.NumberFormat('vi-VN').format(avgHourPrice) + 'đ / giờ';
        }

        // Cập nhật toàn bộ các vị trí hiển thị giá & lịch trong Checkout Wizard
        document.querySelectorAll('.total-display-tag').forEach(el => {
            el.textContent = formattedSymbol;
        });

        const scheduleText = sVal + ' - ' + eVal + ' (' + formatDateVN(dVal) + ')';
        const chkHeaderSlot = document.getElementById('chk-header-slot');
        if (chkHeaderSlot) chkHeaderSlot.textContent = scheduleText;

        const chkStep1Schedule = document.getElementById('chk-step1-schedule');
        if (chkStep1Schedule) chkStep1Schedule.textContent = scheduleText;

        const chkStep1Duration = document.getElementById('chk-step1-duration');
        if (chkStep1Duration) chkStep1Duration.textContent = diffHours + ' giờ thi đấu';

        const bankAmountEl = document.getElementById('bank-amount');
        if (bankAmountEl) bankAmountEl.textContent = formattedSymbol;

        const momoAmountEl = document.getElementById('momo-amount');
        if (momoAmountEl) momoAmountEl.textContent = formattedSymbol;

        const bankMemoEl = document.getElementById('bank-memo');
        if (bankMemoEl) bankMemoEl.textContent = bookingMemoCode;

        const momoMemoEl = document.getElementById('momo-memo');
        if (momoMemoEl) momoMemoEl.textContent = bookingMemoCode;
    }

    function showValidationError(message, focusEl) {
        if (alertBox && alertMsg) {
            alertMsg.textContent = message;
            alertBox.style.display = 'flex';
            window.scrollTo({ top: 100, behavior: 'smooth' });
        }
        if (focusEl) {
            focusEl.focus();
        }
    }

    function validateInitialStage() {
        if (alertBox) alertBox.style.display = 'none';

        const nameVal = nameInput ? nameInput.value.trim() : '';
        const phoneVal = phoneInput ? phoneInput.value.trim() : '';
        const emailVal = emailInput ? emailInput.value.trim() : '';
        const dVal = dateInput ? dateInput.value : '';
        const sVal = startInput ? startInput.value : '';
        const eVal = endInput ? endInput.value : '';

        if (!nameVal) {
            showValidationError('Vui lòng nhập họ và tên người đặt sân.', nameInput);
            return false;
        }
        if (!phoneVal || phoneVal.length < 9) {
            showValidationError('Vui lòng nhập số điện thoại liên hệ hợp lệ (tối thiểu 9 chữ số).', phoneInput);
            return false;
        }
        if (!emailVal || !emailVal.includes('@')) {
            showValidationError('Vui lòng nhập địa chỉ Email hợp lệ để nhận mã vé xác nhận.', emailInput);
            return false;
        }
        if (!dVal || !sVal || !eVal) {
            showValidationError('Vui lòng chọn đầy đủ ngày đặt sân, giờ bắt đầu và giờ kết thúc.', startInput);
            return false;
        }
        if (sVal >= eVal) {
            showValidationError('Giờ kết thúc phải lớn hơn giờ bắt đầu thi đấu.', endInput);
            return false;
        }
        if (agreeInput && !agreeInput.checked) {
            showValidationError('Vui lòng đồng ý với điều khoản sử dụng và chính sách đặt sân.', agreeInput);
            return false;
        }

        return true;
    }

    function populateConfirmationStep() {
        const cName = document.getElementById('confirm-customer-name');
        const cPhone = document.getElementById('confirm-customer-phone');
        const cEmail = document.getElementById('confirm-customer-email');
        const cSched = document.getElementById('confirm-schedule-text');
        const cMethod = document.getElementById('confirm-payment-method-text');

        if (cName && nameInput) cName.value = nameInput.value.trim();
        if (cPhone && phoneInput) cPhone.value = phoneInput.value.trim();
        if (cEmail && emailInput) cEmail.value = emailInput.value.trim();
        if (cSched && dateInput && startInput && endInput) {
            cSched.value = startInput.value + ' - ' + endInput.value + ' | Ngày ' + formatDateVN(dateInput.value) + ' (' + currentDurationHours + ' giờ)';
        }
        if (cMethod) {
            cMethod.value = paymentMethodLabels[selectedPayment] || selectedPayment.toUpperCase();
        }
    }

    // Chuyển giữa các bước trong Checkout Wizard (1 -> 2 -> 3)
    function goToStep(step) {
        const sec1 = document.getElementById('step-section-1');
        const sec2 = document.getElementById('step-section-2');
        const sec3 = document.getElementById('step-section-3');

        if (sec1) sec1.style.display = (step === 1) ? 'block' : 'none';
        if (sec2) sec2.style.display = (step === 2) ? 'block' : 'none';
        if (sec3) sec3.style.display = (step === 3) ? 'block' : 'none';

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

        if (step === 2) {
            populateConfirmationStep();
        } else if (step === 3) {
            calculateDynamicPrice();
            startCountdown();
        }

        window.scrollTo({ top: 100, behavior: 'smooth' });
    }

    // Khi nhấn "Tiến hành xác nhận & thanh toán" ở màn hình đầu
    const btnProceedCheckout = document.getElementById('btn-proceed-to-checkout');
    if (btnProceedCheckout) {
        btnProceedCheckout.addEventListener('click', function() {
            if (!validateInitialStage()) return;
            calculateDynamicPrice();
            if (stageInitial) stageInitial.style.display = 'none';
            if (stageWizard) stageWizard.style.display = 'block';
            goToStep(1);
        });
    }

    // Nút quay lại màn hình chỉnh sửa thông tin đặt sân
    function backToInitialStage() {
        if (stageWizard) stageWizard.style.display = 'none';
        if (stageInitial) stageInitial.style.display = 'block';
        window.scrollTo({ top: 100, behavior: 'smooth' });
    }

    const btnBackStage1 = document.getElementById('btn-back-stage1');
    if (btnBackStage1) btnBackStage1.addEventListener('click', backToInitialStage);

    const btnBreadcrumbBack = document.getElementById('btn-breadcrumb-back-stage1');
    if (btnBreadcrumbBack) btnBreadcrumbBack.addEventListener('click', backToInitialStage);

    // Các nút điều hướng trong 3 bước Checkout
    const btnToStep2 = document.getElementById('btn-to-step-2');
    const btnToStep3 = document.getElementById('btn-to-step-3');
    const btnBackStep1 = document.getElementById('btn-back-step-1');
    const btnBackStep2 = document.getElementById('btn-back-step-2');

    if (btnToStep2) btnToStep2.addEventListener('click', function() { goToStep(2); });
    if (btnToStep3) btnToStep3.addEventListener('click', function() { goToStep(3); });
    if (btnBackStep1) btnBackStep1.addEventListener('click', function() { goToStep(1); });
    if (btnBackStep2) btnBackStep2.addEventListener('click', function() { goToStep(2); });

    const stepNav1 = document.getElementById('step-nav-1');
    const stepNav2 = document.getElementById('step-nav-2');
    const stepNav3 = document.getElementById('step-nav-3');

    if (stepNav1) stepNav1.addEventListener('click', function() { goToStep(1); });
    if (stepNav2) stepNav2.addEventListener('click', function() { goToStep(2); });
    if (stepNav3) stepNav3.addEventListener('click', function() { goToStep(3); });

    // Chuyển đổi giữa QR Ngân Hàng (TPBank) và QR Ví MoMo
    function switchToMoMoQR() {
        const bankView = document.getElementById('bank-qr-view');
        const momoView = document.getElementById('momo-qr-view');
        const pillBank = document.getElementById('pill-bank');
        const pillMoMo = document.getElementById('pill-momo');
        const qrBox = document.getElementById('qr-display-box');
        const btnFinalText = document.getElementById('btn-final-text');

        if (bankView) bankView.style.display = 'none';
        if (momoView) momoView.style.display = 'block';
        if (pillBank) pillBank.classList.remove('active-bank');
        if (pillMoMo) pillMoMo.classList.add('active-momo');
        if (qrBox) {
            qrBox.style.borderColor = '#FBCFE8';
            qrBox.style.background = 'linear-gradient(135deg, #FFF1F2 0%, #FDF2F8 100%)';
        }
        if (btnFinalText) {
            btnFinalText.textContent = 'Tôi đã thanh toán MoMo — Xác nhận hoàn tất đặt sân';
        }
    }

    function switchToBankQR() {
        const bankView = document.getElementById('bank-qr-view');
        const momoView = document.getElementById('momo-qr-view');
        const pillBank = document.getElementById('pill-bank');
        const pillMoMo = document.getElementById('pill-momo');
        const qrBox = document.getElementById('qr-display-box');
        const btnFinalText = document.getElementById('btn-final-text');

        if (bankView) bankView.style.display = 'block';
        if (momoView) momoView.style.display = 'none';
        if (pillBank) pillBank.classList.add('active-bank');
        if (pillMoMo) pillMoMo.classList.remove('active-momo');
        if (qrBox) {
            qrBox.style.borderColor = '#BFDBFE';
            qrBox.style.background = 'linear-gradient(135deg, #F8FAFC 0%, #EFF6FF 100%)';
        }
        if (btnFinalText) {
            btnFinalText.textContent = 'Tôi đã chuyển khoản Ngân hàng — Xác nhận hoàn tất đặt sân';
        }
    }

    const pillBank = document.getElementById('pill-bank');
    if (pillBank) pillBank.addEventListener('click', switchToBankQR);

    const pillMoMo = document.getElementById('pill-momo');
    if (pillMoMo) pillMoMo.addEventListener('click', switchToMoMoQR);

    // Lắng nghe thay đổi phương thức thanh toán
    const radios = document.querySelectorAll('input[name="payment_method"]');
    radios.forEach(radio => {
        radio.addEventListener('change', function() {
            selectedPayment = this.value;
            document.querySelectorAll('.payment-option-card').forEach(card => card.classList.remove('selected'));
            const parent = this.closest('.payment-option-card');
            if (parent) parent.classList.add('selected');

            const qrBox = document.getElementById('qr-display-box');
            const codBox = document.getElementById('cod-display-box');
            const btnToStep3Text = document.getElementById('btn-to-step-3-text');
            const btnFinalText = document.getElementById('btn-final-text');

            if (selectedPayment === 'cod') {
                if (btnToStep3Text) btnToStep3Text.textContent = 'Tiếp tục: Xác nhận giữ chỗ COD';
                if (qrBox) qrBox.style.display = 'none';
                if (codBox) codBox.style.display = 'block';
                if (btnFinalText) btnFinalText.textContent = 'Xác nhận giữ chỗ (Thanh toán tại sân)';
            } else if (selectedPayment === 'momo') {
                if (btnToStep3Text) btnToStep3Text.textContent = 'Tiếp tục: Quét mã QR MoMo';
                if (qrBox) qrBox.style.display = 'block';
                if (codBox) codBox.style.display = 'none';
                switchToMoMoQR();
            } else {
                if (btnToStep3Text) btnToStep3Text.textContent = 'Tiếp tục: Quét mã QR thanh toán';
                if (qrBox) qrBox.style.display = 'block';
                if (codBox) codBox.style.display = 'none';
                switchToBankQR();
            }
        });
    });

    // Sao chép nhanh kèm thông báo Toast chuyên nghiệp
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
            copyText(currentTotalAmount.toString(), this, new Intl.NumberFormat('vi-VN').format(currentTotalAmount) + '₫');
        });
    }

    const btnCopyMemo = document.getElementById('btn-copy-memo');
    if (btnCopyMemo) {
        btnCopyMemo.addEventListener('click', function() {
            copyText(bookingMemoCode, this, 'Nội dung CK ' + bookingMemoCode);
        });
    }

    const btnCopyMoMoAcc = document.getElementById('btn-copy-momo-acc');
    if (btnCopyMoMoAcc) {
        btnCopyMoMoAcc.addEventListener('click', function() {
            copyText('PSP2615713900000057', this, 'Mã TK MoMo PSP2615713900000057');
        });
    }

    const btnCopyMoMoAmount = document.getElementById('btn-copy-momo-amount');
    if (btnCopyMoMoAmount) {
        btnCopyMoMoAmount.addEventListener('click', function() {
            copyText(currentTotalAmount.toString(), this, new Intl.NumberFormat('vi-VN').format(currentTotalAmount) + '₫');
        });
    }

    const btnCopyMoMoMemo = document.getElementById('btn-copy-momo-memo');
    if (btnCopyMoMoMemo) {
        btnCopyMoMoMemo.addEventListener('click', function() {
            copyText(bookingMemoCode, this, 'Nội dung MoMo ' + bookingMemoCode);
        });
    }

    // Đồng hồ đếm ngược 15 phút cho mã QR
    let timerInterval = null;
    function startCountdown() {
        if (timerInterval) clearInterval(timerInterval);
        let duration = 15 * 60 - 1;
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

    // Hiển thị popup thông báo đang xác thực thanh toán trước khi gửi form
    const masterForm = document.getElementById('booking-master-form');
    if (masterForm) {
        masterForm.addEventListener('submit', function() {
            const overlay = document.getElementById('payment-verifying-overlay');
            const memoDisplay = document.getElementById('verifying-memo-display');
            if (memoDisplay) {
                memoDisplay.textContent = 'Mã đối soát: ' + bookingMemoCode + ' • Số tiền: ' + new Intl.NumberFormat('vi-VN').format(currentTotalAmount) + '₫';
            }
            if (overlay) {
                overlay.style.display = 'flex';
            }
        });
    }

    if (dateInput) {
        dateInput.addEventListener('change', function() {
            const url = new URL(window.location.href);
            url.searchParams.set('date', this.value);
            window.location.href = url.toString();
        });
    }

    if (startInput) {
        startInput.addEventListener('change', function() {
            const sParts = this.value.split(':').map(Number);
            if (sParts.length >= 2 && endInput) {
                const nextH = (sParts[0] + 1) % 24;
                const nextHStr = (nextH < 10 ? '0' + nextH : nextH) + ':' + (sParts[1] < 10 ? '0' + sParts[1] : sParts[1]);
                endInput.value = nextHStr;
            }
            calculateDynamicPrice();
        });
    }

    if (endInput) {
        endInput.addEventListener('change', calculateDynamicPrice);
    }

    calculateDynamicPrice();
});
</script>
@endpush
@endsection
