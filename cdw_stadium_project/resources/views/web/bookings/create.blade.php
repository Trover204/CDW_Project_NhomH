@extends('web.layouts.app')

@section('title', __('Xác nhận đặt sân') . ' - ' . config('app.name', 'Sân Nhóm H'))

@section('content')
<div class="booking-flow-page">
    <div class="wrap booking-container">
        
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
        @endphp

        <form action="{{ route('bookings.store', $court->id) }}" method="POST" class="booking-main-form">
            @csrf
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
                                    <input type="text" name="customer_name" value="{{ old('customer_name', Auth::user()->name) }}" placeholder="{{ __('Ví dụ: Nguyễn Văn An') }}" required>
                                </div>
                            </div>
                            <div class="input-field-group">
                                <label class="form-label">{{ __('Số điện thoại liên hệ') }} <span class="req">*</span></label>
                                <div class="input-with-icon">
                                    <i class="ti ti-phone"></i>
                                    <input type="tel" name="customer_phone" value="{{ old('customer_phone') }}" placeholder="{{ __('Ví dụ: 0912345678') }}" required>
                                </div>
                            </div>
                        </div>

                        <div class="input-field-group">
                            <label class="form-label">{{ __('Địa chỉ Email nhận thông tin xác nhận') }} <span class="req">*</span></label>
                            <div class="input-with-icon">
                                <i class="ti ti-mail"></i>
                                <input type="email" name="customer_email" value="{{ old('customer_email', Auth::user()->email) }}" placeholder="{{ __('name@example.com') }}" required>
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
                            <div class="booked-slots-warning-box" style="margin-top: 14px; background: #FEF2F2; border: 1.5px dashed #FCA5A5; border-radius: 12px; padding: 12px 16px; display: flex; align-items: flex-start; gap: 10px;">
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
                            <textarea name="note" rows="3" placeholder="{{ __('Ví dụ: Chuẩn bị thêm 2 bộ áo lưới, bóng thi đấu số 5...') }}">{{ old('note') }}</textarea>
                        </div>

                        <div class="agreement-checkbox-row">
                            <label class="checkbox-container">
                                <input type="checkbox" required checked>
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
                            @php
                                $primaryImg = $court->images ? ($court->images->firstWhere('is_primary', true) ?? $court->images->first()) : null;
                            @endphp
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
                            <button type="submit" class="btn-submit-order">
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
        </form>

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
    border: 1px solid var(--line, #E3ECE6);
    border-radius: 20px;
    padding: 20px 32px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 30px;
    box-shadow: 0 4px 18px rgba(13, 56, 46, 0.03);
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
    background: var(--bg-page, #F6F8F6);
    color: var(--ink-muted, #879C94);
    font-size: 15px;
    font-weight: 700;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 2px solid var(--line, #E3ECE6);
}
.step-node.active .step-num {
    background: var(--turf-dark, #0D382E);
    color: #fff;
    border-color: var(--turf-dark, #0D382E);
}
.step-text {
    display: flex;
    flex-direction: column;
}
.step-title {
    font-size: 11.5px;
    font-weight: 700;
    text-transform: uppercase;
    color: var(--ink-muted, #879C94);
    letter-spacing: 0.5px;
}
.step-node.active .step-title {
    color: var(--turf-teal, #106764);
}
.step-sub {
    font-size: 14px;
    font-weight: 600;
    color: var(--ink, #11201B);
}
.step-connector {
    flex: 1;
    height: 2px;
    background: var(--line, #E3ECE6);
    margin: 0 20px;
}
.step-connector.active {
    background: var(--turf-teal, #106764);
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
    border: 1px solid var(--line, #E3ECE6);
    border-radius: 20px;
    padding: 26px 28px;
    box-shadow: 0 4px 18px rgba(13, 56, 46, 0.03);
}
.card-section-head {
    display: flex;
    align-items: flex-start;
    gap: 14px;
    margin-bottom: 22px;
    padding-bottom: 16px;
    border-bottom: 1px solid var(--line, #E3ECE6);
}
.card-section-head i {
    font-size: 28px;
    color: var(--turf-teal, #106764);
}
.card-section-head h3 {
    margin: 0 0 4px;
    font-size: 18px;
    font-weight: 700;
    color: var(--turf-dark, #0D382E);
}
.card-section-head p {
    margin: 0;
    font-size: 13px;
    color: var(--ink-soft, #5E726A);
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
    color: var(--turf-dark, #0D382E);
}
.form-label .req {
    color: #ef4444;
}
.input-with-icon {
    display: flex;
    align-items: center;
    background: var(--bg-page, #F6F8F6);
    border: 1px solid #C7D3CA;
    border-radius: 12px;
    padding: 0 12px;
    transition: all 0.2s ease;
}
.input-with-icon i {
    font-size: 18px;
    color: var(--ink-muted, #879C94);
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
    color: var(--ink, #11201B);
    font-family: inherit;
}
.input-with-icon:focus-within {
    border-color: var(--turf-teal, #106764);
    background: #ffffff;
    box-shadow: 0 0 0 3px rgba(16, 103, 100, 0.12);
}
.input-field-group textarea {
    width: 100%;
    border: 1px solid #C7D3CA;
    background: var(--bg-page, #F6F8F6);
    border-radius: 12px;
    padding: 12px;
    font-size: 13.5px;
    color: var(--ink, #11201B);
    outline: none;
    font-family: inherit;
    resize: vertical;
    transition: all 0.2s;
}
.input-field-group textarea:focus {
    border-color: var(--turf-teal, #106764);
    background: #ffffff;
    box-shadow: 0 0 0 3px rgba(16, 103, 100, 0.12);
}
.agreement-checkbox-row {
    margin-top: 14px;
    padding-top: 14px;
    border-top: 1px dashed var(--line, #E3ECE6);
}
.checkbox-container {
    display: flex;
    align-items: flex-start;
    gap: 10px;
    cursor: pointer;
    user-select: none;
    font-size: 13px;
    color: var(--ink-soft, #5E726A);
    line-height: 1.5;
}
.checkbox-container input {
    margin-top: 3px;
    width: 16px;
    height: 16px;
    accent-color: var(--turf-teal, #106764);
}
.agreement-text a {
    color: var(--turf-teal, #106764);
    font-weight: 600;
    text-decoration: underline;
}
.summary-card {
    background: var(--surface, #fff);
    border: 1px solid var(--line, #E3ECE6);
    border-radius: 20px;
    padding: 24px;
    box-shadow: 0 6px 24px rgba(13, 56, 46, 0.05);
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
    color: var(--turf-teal, #106764);
    background: var(--turf-teal-soft, #E6F6F4);
    padding: 4px 10px;
    border-radius: 20px;
    margin-bottom: 8px;
}
.summary-head h4 {
    margin: 0;
    font-size: 18px;
    font-weight: 700;
    color: var(--turf-dark, #0D382E);
}
.court-summary-item {
    display: flex;
    gap: 14px;
    align-items: center;
    background: var(--bg-page, #F6F8F6);
    border: 1px solid var(--line, #E3ECE6);
    border-radius: 14px;
    padding: 12px;
    margin-bottom: 20px;
}
.court-summary-thumb {
    width: 64px;
    height: 64px;
    border-radius: 10px;
    overflow: hidden;
    background: #E6F0FA;
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
    color: var(--turf-teal, #106764);
}
.court-summary-info {
    flex: 1;
    min-width: 0;
}
.sport-chip {
    font-size: 11px;
    font-weight: 700;
    color: var(--turf-teal, #106764);
}
.court-summary-title {
    margin: 2px 0 4px;
    font-size: 14.5px;
    font-weight: 700;
    color: var(--turf-dark, #0D382E);
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.court-summary-loc {
    display: flex;
    align-items: center;
    gap: 4px;
    font-size: 12px;
    color: var(--ink-soft, #5E726A);
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
    color: var(--ink-soft, #5E726A);
}
.fee-value {
    font-weight: 600;
    color: var(--ink, #11201B);
}
.text-green {
    color: #16A34A !important;
}
.fee-divider {
    height: 1px;
    background: var(--line, #E3ECE6);
    margin: 4px 0;
}
.fee-total-row {
    align-items: baseline;
    font-size: 15px;
    font-weight: 700;
    color: var(--turf-dark, #0D382E);
}
.total-amount {
    font-size: 22px;
    font-weight: 800;
    color: #F97316;
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
    background: #F97316;
    color: #fff;
    border: none;
    font-size: 15px;
    font-weight: 700;
    padding: 14px;
    border-radius: 12px;
    cursor: pointer;
    box-shadow: 0 4px 14px rgba(249, 115, 22, 0.3);
    transition: all 0.2s ease;
}
.btn-submit-order:hover {
    background: #ea580c;
    transform: translateY(-2px);
    box-shadow: 0 6px 18px rgba(249, 115, 22, 0.4);
}
.btn-back-link {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    font-size: 13px;
    color: var(--ink-soft, #5E726A);
    padding: 8px;
}
.btn-back-link:hover {
    color: var(--turf-dark, #0D382E);
}
.summary-security-note {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-top: 18px;
    padding-top: 14px;
    border-top: 1px solid var(--line, #E3ECE6);
    font-size: 11.5px;
    color: var(--ink-muted, #879C94);
    line-height: 1.4;
}
.summary-security-note i {
    font-size: 16px;
    color: var(--turf-teal, #106764);
    flex-shrink: 0;
}

@media (max-width: 900px) {
    .booking-layout-grid {
        grid-template-columns: 1fr;
    }
    .summary-card {
        position: static;
    }
    .form-grid-3 {
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
}
</style>
@endpush

@push('scripts')
<script nonce="{{ $cspNonce ?? '' }}">
document.addEventListener('DOMContentLoaded', function() {
    const dateInput = document.getElementById('booking_date_input');
    const startInput = document.getElementById('start_time_input');
    const endInput = document.getElementById('end_time_input');
    const durationSpan = document.getElementById('summary-duration');
    const totalSpan = document.getElementById('summary-total-price');
    const unitPriceSpan = document.getElementById('summary-unit-price');

    // Mảng quy tắc tính giá từ server
    const priceRules = @json($priceRules ?? []);
    const fallbackPrice = {{ (int) ($court->price ?? 120000) }};
    const discountPercent = {{ (int) (($subscription && $subscription->plan) ? $subscription->plan->discount_percent : 0) }};

    function isWeekend(dateStr) {
        if (!dateStr) return false;
        const d = new Date(dateStr);
        const day = d.getDay();
        return day === 0 || day === 6; // 0: Chủ nhật, 6: Thứ 7
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
            return;
        }

        const diffHours = (eMinutes - sMinutes) / 60;
        if (durationSpan) {
            durationSpan.textContent = diffHours + ' giờ';
        }

        // Tính subtotal theo rules
        const dayType = isWeekend(dVal) ? 'weekend' : 'weekday';
        let subtotal = 0;

        // Lặp qua từng block 1 giờ hoặc nửa giờ
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

            if (matchedRule) {
                subtotal += Number(matchedRule.price);
            } else {
                subtotal += fallbackPrice;
            }
        }

        // Tính giảm giá hội viên nếu có
        let discount = 0;
        if (discountPercent > 0) {
            discount = Math.round(subtotal * (discountPercent / 100));
        }

        const finalTotal = Math.max(0, subtotal - discount);

        if (totalSpan) {
            totalSpan.textContent = new Intl.NumberFormat('vi-VN').format(finalTotal) + 'đ';
        }
        if (unitPriceSpan && priceRules.length > 0) {
            const avgHourPrice = Math.round(subtotal / Math.max(1, diffHours));
            unitPriceSpan.textContent = new Intl.NumberFormat('vi-VN').format(avgHourPrice) + 'đ / giờ';
        }
    }

    if (dateInput) {
        dateInput.addEventListener('change', function() {
            // Khi đổi ngày thì reload lại param date để cập nhật danh sách bookedSlots
            const url = new URL(window.location.href);
            url.searchParams.set('date', this.value);
            window.location.href = url.toString();
        });
    }

    if (startInput) {
        startInput.addEventListener('change', function() {
            // Tự động nhảy end_time = start_time + 1h nếu end <= start
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

    // Initial calculation
    calculateDynamicPrice();
});
</script>
@endpush
@endsection
