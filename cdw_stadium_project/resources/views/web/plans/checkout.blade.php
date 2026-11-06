@extends('web.layouts.app')

@section('title', __('Thanh toán gói thành viên') . ' - ' . config('app.name', 'Sân Nhóm H'))

@section('content')
<div class="checkout-plan-page">
    <div class="wrap checkout-plan-wrap">
        
        <div class="checkout-plan-card">
            <div class="checkout-card-header">
                <span class="checkout-badge-pill"><i class="ti ti-shield-check"></i> {{ __('Thanh toán an toàn') }}</span>
                <h2>{{ __('Xác nhận đăng ký gói thành viên') }}</h2>
                <p>{{ __('Kích hoạt ngay các đặc quyền giảm giá và ưu tiên đặt sân từ Sân Nhóm H.') }}</p>
            </div>

            {{-- Plan Summary Box --}}
            <div class="plan-summary-detail-box">
                <div class="summary-top-row">
                    <div>
                        <span class="plan-sub-tag">{{ __('Gói thành viên lựa chọn') }}</span>
                        <h3 class="plan-display-name">{{ $plan->name }}</h3>
                    </div>
                    <div class="plan-price-tag">
                        <span class="plan-amount">{{ number_format($plan->price, 0, ',', '.') }}đ</span>
                    </div>
                </div>

                <div class="plan-details-table">
                    <div class="detail-row">
                        <span class="row-label"><i class="ti ti-calendar"></i> {{ __('Thời hạn sử dụng:') }}</span>
                        <strong class="row-val">{{ $plan->duration_days }} {{ __('ngày') }}</strong>
                    </div>
                    <div class="detail-row">
                        <span class="row-label"><i class="ti ti-percentage"></i> {{ __('Ưu đãi chiết khấu:') }}</span>
                        <strong class="row-val text-discount">{{ __('Giảm :percent% cho mỗi lượt đặt sân', ['percent' => $plan->discount_percent]) }}</strong>
                    </div>
                    <div class="detail-row">
                        <span class="row-label"><i class="ti ti-user"></i> {{ __('Tài khoản kích hoạt:') }}</span>
                        <span class="row-val">{{ Auth::user()->name }} ({{ Auth::user()->email }})</span>
                    </div>
                </div>

                <div class="plan-summary-foot">
                    <span>{{ __('Tổng thanh toán:') }}</span>
                    <span class="total-big">{{ number_format($plan->price, 0, ',', '.') }}đ</span>
                </div>
            </div>

            {{-- Payment Method Selection Form --}}
            <form action="{{ route('plans.subscribe', $plan->id) }}" method="POST" data-confirm="{{ __('Bạn có chắc chắn muốn đăng ký gói thành viên này không?') }}" class="payment-method-form">
                @csrf

                <div class="payment-selection-block">
                    <label class="block-title">{{ __('Chọn phương thức thanh toán:') }}</label>

                    <div class="methods-options-list">
                        <label class="payment-radio-option">
                            <input type="radio" name="payment_method" value="momo" required checked>
                            <div class="option-content">
                                <div class="option-icon momo-icon"><i class="ti ti-wallet"></i></div>
                                <div class="option-texts">
                                    <span class="method-title">{{ __('Ví điện tử MoMo') }}</span>
                                    <span class="method-desc">{{ __('Quét mã QR qua ứng dụng MoMo nhanh chóng') }}</span>
                                </div>
                                <span class="custom-radio-circle"></span>
                            </div>
                        </label>

                        <label class="payment-radio-option">
                            <input type="radio" name="payment_method" value="visa" required>
                            <div class="option-content">
                                <div class="option-icon card-icon"><i class="ti ti-credit-card"></i></div>
                                <div class="option-texts">
                                    <span class="method-title">{{ __('Thẻ Quốc Tế (Visa / MasterCard / JCB)') }}</span>
                                    <span class="method-desc">{{ __('Thanh toán an toàn qua cổng thẻ quốc tế') }}</span>
                                </div>
                                <span class="custom-radio-circle"></span>
                            </div>
                        </label>

                        <label class="payment-radio-option">
                            <input type="radio" name="payment_method" value="paypal" required>
                            <div class="option-content">
                                <div class="option-icon paypal-icon"><i class="ti ti-brand-paypal"></i></div>
                                <div class="option-texts">
                                    <span class="method-title">{{ __('Cổng thanh toán PayPal') }}</span>
                                    <span class="method-desc">{{ __('Thanh toán tiện lợi qua ví điện tử toàn cầu') }}</span>
                                </div>
                                <span class="custom-radio-circle"></span>
                            </div>
                        </label>
                    </div>

                    @error('payment_method')
                        <div class="payment-error-msg"><i class="ti ti-alert-triangle"></i> {{ $message }}</div>
                    @enderror
                </div>

                <div class="checkout-actions">
                    <button type="submit" class="btn-confirm-payment">
                        <i class="ti ti-check"></i>
                        <span>{{ __('Xác nhận & Kích hoạt gói') }}</span>
                    </button>
                    <a href="{{ route('plans.index') }}" class="btn-cancel-checkout">
                        <i class="ti ti-arrow-left"></i>
                        <span>{{ __('Quay lại danh sách gói') }}</span>
                    </a>
                </div>
            </form>

        </div>

    </div>
</div>

@push('styles')
<style>
.checkout-plan-page {
    background: var(--bg-page);
    min-height: 85vh;
    padding: 50px 0 80px;
}
.checkout-plan-wrap {
    max-width: 640px;
    margin: 0 auto;
}
.checkout-plan-card {
    background: var(--surface, #fff);
    border: 1px solid var(--line, #E3ECE6);
    border-radius: 24px;
    padding: 36px 32px;
    box-shadow: 0 8px 30px rgba(13, 56, 46, 0.05);
}
.checkout-card-header {
    text-align: center;
    margin-bottom: 28px;
}
.checkout-badge-pill {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: var(--turf-teal-soft, #E6F6F4);
    color: var(--turf-teal, #106764);
    font-size: 12px;
    font-weight: 700;
    padding: 5px 14px;
    border-radius: 999px;
    margin-bottom: 12px;
}
.checkout-card-header h2 {
    font-family: 'Oswald', sans-serif;
    font-size: 26px;
    font-weight: 700;
    color: var(--turf-dark, #0D382E);
    margin: 0 0 6px;
}
.checkout-card-header p {
    margin: 0;
    font-size: 14px;
    color: var(--ink-soft, #5E726A);
}
.plan-summary-detail-box {
    background: var(--bg-page, #F6F8F6);
    border: 1px solid var(--line, #E3ECE6);
    border-radius: 18px;
    padding: 22px;
    margin-bottom: 28px;
}
.summary-top-row {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    padding-bottom: 16px;
    border-bottom: 1px solid var(--line, #E3ECE6);
    margin-bottom: 16px;
}
.plan-sub-tag {
    font-size: 11.5px;
    font-weight: 700;
    text-transform: uppercase;
    color: var(--ink-muted, #879C94);
    letter-spacing: 0.5px;
}
.plan-display-name {
    margin: 4px 0 0;
    font-size: 20px;
    font-weight: 700;
    color: var(--turf-dark, #0D382E);
}
.plan-amount {
    font-family: 'Oswald', sans-serif;
    font-size: 24px;
    font-weight: 800;
    color: #F97316;
}
.plan-details-table {
    display: flex;
    flex-direction: column;
    gap: 10px;
    margin-bottom: 16px;
}
.detail-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: 13.5px;
}
.row-label {
    display: flex;
    align-items: center;
    gap: 6px;
    color: var(--ink-soft, #5E726A);
}
.row-val {
    color: var(--ink, #11201B);
}
.text-discount {
    color: #16A34A !important;
}
.plan-summary-foot {
    display: flex;
    justify-content: space-between;
    align-items: baseline;
    padding-top: 14px;
    border-top: 1px dashed var(--line, #E3ECE6);
    font-size: 14.5px;
    font-weight: 700;
    color: var(--turf-dark, #0D382E);
}
.total-big {
    font-family: 'Oswald', sans-serif;
    font-size: 26px;
    font-weight: 800;
    color: #F97316;
}
.payment-selection-block {
    margin-bottom: 26px;
}
.block-title {
    display: block;
    font-size: 14px;
    font-weight: 700;
    color: var(--turf-dark, #0D382E);
    margin-bottom: 14px;
}
.methods-options-list {
    display: flex;
    flex-direction: column;
    gap: 12px;
}
.payment-radio-option {
    cursor: pointer;
    display: block;
    user-select: none;
}
.payment-radio-option input {
    display: none;
}
.option-content {
    display: flex;
    align-items: center;
    gap: 14px;
    background: var(--surface, #fff);
    border: 1.5px solid var(--line, #E3ECE6);
    border-radius: 14px;
    padding: 14px 18px;
    transition: all 0.2s ease;
}
.payment-radio-option:hover .option-content {
    border-color: #B5D9C4;
}
.payment-radio-option input:checked + .option-content {
    border-color: var(--turf-teal, #106764);
    background: var(--turf-teal-soft, #E6F6F4);
}
.option-icon {
    width: 38px;
    height: 38px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    flex-shrink: 0;
}
.momo-icon {
    background: #FCE7F3;
    color: #DB2777;
}
.card-icon {
    background: #E0E7FF;
    color: #4F46E5;
}
.paypal-icon {
    background: #E0F2FE;
    color: #0284C7;
}
.option-texts {
    flex: 1;
    display: flex;
    flex-direction: column;
}
.method-title {
    font-size: 14px;
    font-weight: 700;
    color: var(--turf-dark, #0D382E);
}
.method-desc {
    font-size: 12px;
    color: var(--ink-soft, #5E726A);
}
.custom-radio-circle {
    width: 20px;
    height: 20px;
    border-radius: 50%;
    border: 2px solid #C7D3CA;
    position: relative;
    transition: all 0.2s ease;
    flex-shrink: 0;
}
.payment-radio-option input:checked + .option-content .custom-radio-circle {
    border-color: var(--turf-teal, #106764);
}
.payment-radio-option input:checked + .option-content .custom-radio-circle::after {
    content: '';
    position: absolute;
    top: 3px;
    left: 3px;
    width: 10px;
    height: 10px;
    border-radius: 50%;
    background: var(--turf-teal, #106764);
}
.payment-error-msg {
    margin-top: 10px;
    font-size: 13px;
    color: #ef4444;
    display: flex;
    align-items: center;
    gap: 6px;
}
.checkout-actions {
    display: flex;
    flex-direction: column;
    gap: 12px;
}
.btn-confirm-payment {
    width: 100%;
    height: 50px;
    background: #16A34A;
    color: #fff;
    border: none;
    border-radius: 12px;
    font-size: 16px;
    font-weight: 700;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    cursor: pointer;
    box-shadow: 0 4px 14px rgba(22, 163, 74, 0.3);
    transition: all 0.2s ease;
}
.btn-confirm-payment:hover {
    background: #15803D;
    transform: translateY(-2px);
    box-shadow: 0 6px 18px rgba(22, 163, 74, 0.4);
}
.btn-cancel-checkout {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    font-size: 13.5px;
    color: var(--ink-soft, #5E726A);
    text-decoration: none;
    padding: 8px;
}
.btn-cancel-checkout:hover {
    color: var(--turf-dark, #0D382E);
}
</style>
@endpush
@endsection
