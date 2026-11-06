@extends('web.layouts.app')

@section('title', __('Gói thành viên ưu đãi') . ' - ' . config('app.name', 'Sân Nhóm H'))

@section('content')
<div class="plans-page-wrapper">
    {{-- Hero Section --}}
    <div class="plans-hero-banner">
        <div class="wrap">
            <div class="plans-hero-content">
                <span class="plans-badge-pill">
                    <i class="ti ti-crown"></i> {{ __('Đặc quyền thành viên VIP') }}
                </span>
                <h1>{{ __('Đăng ký gói thành viên — Tiết kiệm tối đa') }}</h1>
                <p>{{ __('Chủ động lịch tập luyện thể thao mỗi tuần với mức giá ưu đãi nhất, ưu tiên giữ khung giờ vàng và miễn phí đổi lịch linh hoạt.') }}</p>
            </div>
        </div>
    </div>

    {{-- Plans Container --}}
    <div class="wrap plans-main-container">
        @if(session('success'))
            <div class="plan-alert-success">
                <i class="ti ti-circle-check"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        {{-- Benefits Grid --}}
        <div class="plans-benefits-row">
            <div class="benefit-box">
                <div class="b-icon"><i class="ti ti-percentage"></i></div>
                <div class="b-text">
                    <h4>{{ __('Chiết khấu trực tiếp') }}</h4>
                    <p>{{ __('Giảm giá tự động trên mọi hóa đơn đặt sân tại tất cả hệ thống đối tác.') }}</p>
                </div>
            </div>
            <div class="benefit-box">
                <div class="b-icon"><i class="ti ti-star"></i></div>
                <div class="b-text">
                    <h4>{{ __('Ưu tiên giờ vàng') }}</h4>
                    <p>{{ __('Giữ chỗ trước các khung giờ cao điểm từ 17h-21h hàng ngày.') }}</p>
                </div>
            </div>
            <div class="benefit-box">
                <div class="b-icon"><i class="ti ti-headset"></i></div>
                <div class="b-text">
                    <h4>{{ __('Hỗ trợ chuyên biệt') }}</h4>
                    <p>{{ __('Kênh hotline riêng hỗ trợ đổi sân hoặc dời ngày thi đấu trong 30 giây.') }}</p>
                </div>
            </div>
        </div>

        {{-- Pricing Cards Grid --}}
        <div class="pricing-cards-grid">
            @foreach($plans as $index => $plan)
                @php
                    $isPopular = ($index == 1);
                @endphp
                <div class="pricing-card {{ $isPopular ? 'popular-card' : '' }}">
                    @if($isPopular)
                        <div class="popular-ribbon">
                            <i class="ti ti-flame"></i> {{ __('Gói phổ biến nhất') }}
                        </div>
                    @endif

                    <div class="card-top-info">
                        <span class="plan-type-tag">{{ __('Hạng thành viên') }}</span>
                        <h3 class="plan-name">{{ $plan->name }}</h3>
                        <div class="plan-price-block">
                            <span class="price-val">{{ number_format($plan->price, 0, ',', '.') }}đ</span>
                            <span class="price-period">/ {{ $plan->duration_days }} {{ __('ngày') }}</span>
                        </div>
                        <div class="plan-discount-callout">
                            <i class="ti ti-discount-2"></i>
                            <span>{{ __('Giảm :percent% cho mọi lượt đặt sân', ['percent' => $plan->discount_percent]) }}</span>
                        </div>
                    </div>

                    <div class="card-features-list">
                        <span class="features-title">{{ __('Quyền lợi bao gồm:') }}</span>
                        <div class="feature-line">
                            <div class="check-icon"><i class="ti ti-check"></i></div>
                            <span>{{ __('Giảm :percent% trực tiếp khi thanh toán', ['percent' => $plan->discount_percent]) }}</span>
                        </div>
                        <div class="feature-line">
                            <div class="check-icon"><i class="ti ti-check"></i></div>
                            <span>{{ __('Hủy miễn phí trước :hour giờ thi đấu', ['hour' => $isPopular ? '2' : '6']) }}</span>
                        </div>
                        @if($isPopular || $index > 1)
                            <div class="feature-line">
                                <div class="check-icon"><i class="ti ti-check"></i></div>
                                <span>{{ __('Ưu tiên giữ chỗ các khung giờ cao điểm') }}</span>
                            </div>
                        @endif
                        @if($index > 1)
                            <div class="feature-line">
                                <div class="check-icon"><i class="ti ti-check"></i></div>
                                <span>{{ __('Hỗ trợ đăng ký lịch thi đấu cố định hàng tuần') }}</span>
                            </div>
                            <div class="feature-line">
                                <div class="check-icon"><i class="ti ti-check"></i></div>
                                <span>{{ __('Đặc quyền CSKH riêng 24/7') }}</span>
                            </div>
                        @endif
                    </div>

                    <div class="card-action-wrap">
                        @auth
                            <a href="{{ route('plans.checkout', $plan->id) }}" class="btn-choose-plan {{ $isPopular ? 'btn-popular-action' : '' }}">
                                <span>{{ __('Đăng ký gói ngay') }}</span>
                                <i class="ti ti-arrow-right"></i>
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="btn-choose-plan {{ $isPopular ? 'btn-popular-action' : '' }}">
                                <span>{{ __('Đăng nhập để đăng ký') }}</span>
                                <i class="ti ti-login"></i>
                            </a>
                        @endauth
                    </div>
                </div>
            @endforeach
        </div>

        {{-- FAQ Mini Section --}}
        <div class="plans-faq-box">
            <h3 class="faq-box-title">{{ __('Câu hỏi thường gặp về gói thành viên') }}</h3>
            <div class="faq-grid">
                <div class="faq-item">
                    <h4>{{ __('Quyền lợi giảm giá áp dụng thế nào?') }}</h4>
                    <p>{{ __('Khi bạn đăng nhập tài khoản đã có gói thành viên, hệ thống sẽ tự động trừ phần trăm ưu đãi trực tiếp vào hóa đơn đặt sân tại bước thanh toán.') }}</p>
                </div>
                <div class="faq-item">
                    <h4>{{ __('Tôi có thể gia hạn hoặc nâng cấp gói không?') }}</h4>
                    <p>{{ __('Có. Bạn có thể gia hạn thêm ngày sử dụng hoặc nâng cấp lên gói thành viên cao cấp hơn bất kỳ lúc nào.') }}</p>
                </div>
            </div>
        </div>

    </div>
</div>

@push('styles')
<style>
.plans-page-wrapper {
    background: var(--bg-page);
    min-height: 85vh;
    padding-bottom: 70px;
}
.plans-hero-banner {
    background: linear-gradient(135deg, var(--turf-dark, #0D382E) 0%, #061B16 100%);
    color: #fff;
    padding: 60px 0 50px;
    border-bottom: 1px solid rgba(255,255,255,0.08);
}
.plans-hero-content {
    max-width: 760px;
    margin: 0 auto;
    text-align: center;
}
.plans-badge-pill {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: rgba(210, 248, 90, 0.15);
    color: var(--turf-accent, #D2F85A);
    border: 1px solid rgba(210, 248, 90, 0.3);
    padding: 6px 16px;
    border-radius: 999px;
    font-size: 13px;
    font-weight: 600;
    margin-bottom: 16px;
}
.plans-hero-content h1 {
    font-family: 'Oswald', sans-serif;
    font-size: 38px;
    font-weight: 700;
    margin: 0 0 12px;
    color: #ffffff;
    letter-spacing: 0.5px;
}
.plans-hero-content p {
    color: #C3D5CE;
    font-size: 16px;
    line-height: 1.6;
    margin: 0 auto;
    max-width: 640px;
}
.plans-main-container {
    margin-top: -30px;
}
.plan-alert-success {
    background: #DCFCE7;
    border: 1px solid #86EFAC;
    color: #166534;
    padding: 14px 20px;
    border-radius: 14px;
    margin-bottom: 30px;
    display: flex;
    align-items: center;
    gap: 10px;
    font-weight: 600;
}
.plans-benefits-row {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 20px;
    margin-bottom: 40px;
}
.benefit-box {
    background: var(--surface, #fff);
    border: 1px solid var(--line, #E3ECE6);
    border-radius: 18px;
    padding: 24px;
    display: flex;
    gap: 16px;
    box-shadow: 0 4px 18px rgba(13, 56, 46, 0.04);
}
.b-icon {
    width: 44px;
    height: 44px;
    border-radius: 12px;
    background: var(--turf-teal-soft, #E6F6F4);
    color: var(--turf-teal, #106764);
    font-size: 22px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}
.b-text h4 {
    margin: 0 0 4px;
    font-size: 15.5px;
    font-weight: 700;
    color: var(--turf-dark, #0D382E);
}
.b-text p {
    margin: 0;
    font-size: 13px;
    color: var(--ink-soft, #5E726A);
    line-height: 1.5;
}
.pricing-cards-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(290px, 1fr));
    gap: 28px;
    align-items: stretch;
}
.pricing-card {
    background: var(--surface, #fff);
    border: 1.5px solid var(--line, #E3ECE6);
    border-radius: 24px;
    padding: 32px 28px;
    display: flex;
    flex-direction: column;
    position: relative;
    box-shadow: 0 6px 24px rgba(13, 56, 46, 0.04);
    transition: all 0.3s ease;
}
.pricing-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 16px 36px rgba(13, 56, 46, 0.09);
    border-color: #B5D9C4;
}
.popular-card {
    border-color: var(--turf-teal, #106764);
    box-shadow: 0 12px 36px rgba(16, 103, 100, 0.12);
}
.popular-ribbon {
    position: absolute;
    top: -14px;
    left: 50%;
    transform: translateX(-50%);
    background: var(--turf-dark, #0D382E);
    color: var(--turf-accent, #D2F85A);
    font-size: 12px;
    font-weight: 700;
    padding: 5px 18px;
    border-radius: 999px;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}
.card-top-info {
    margin-bottom: 24px;
    padding-bottom: 20px;
    border-bottom: 1px solid var(--line, #E3ECE6);
}
.plan-type-tag {
    font-size: 12px;
    font-weight: 700;
    text-transform: uppercase;
    color: var(--ink-muted, #879C94);
    letter-spacing: 0.5px;
}
.plan-name {
    margin: 6px 0 14px;
    font-family: 'Oswald', sans-serif;
    font-size: 26px;
    font-weight: 700;
    color: var(--turf-dark, #0D382E);
}
.plan-price-block {
    display: flex;
    align-items: baseline;
    gap: 4px;
    margin-bottom: 12px;
}
.price-val {
    font-family: 'Oswald', sans-serif;
    font-size: 34px;
    font-weight: 800;
    color: var(--turf-dark, #0D382E);
}
.price-period {
    font-size: 14px;
    color: var(--ink-muted, #879C94);
}
.plan-discount-callout {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: #DCFCE7;
    color: #15803D;
    font-size: 12.5px;
    font-weight: 700;
    padding: 5px 12px;
    border-radius: 8px;
}
.card-features-list {
    display: flex;
    flex-direction: column;
    gap: 14px;
    flex: 1;
    margin-bottom: 28px;
}
.features-title {
    font-size: 12px;
    font-weight: 700;
    text-transform: uppercase;
    color: var(--ink-muted, #879C94);
    letter-spacing: 0.5px;
    margin-bottom: 4px;
}
.feature-line {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    font-size: 14px;
    color: var(--ink, #11201B);
    line-height: 1.45;
}
.check-icon {
    width: 20px;
    height: 20px;
    border-radius: 50%;
    background: var(--turf-teal-soft, #E6F6F4);
    color: var(--turf-teal, #106764);
    font-size: 13px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    margin-top: 1px;
}
.card-action-wrap {
    margin-top: auto;
}
.btn-choose-plan {
    width: 100%;
    height: 48px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    font-size: 15px;
    font-weight: 700;
    text-decoration: none;
    background: var(--bg-page, #F6F8F6);
    color: var(--turf-dark, #0D382E);
    border: 1px solid var(--line, #E3ECE6);
    transition: all 0.2s ease;
}
.btn-choose-plan:hover {
    background: var(--turf-dark, #0D382E);
    color: #fff;
    border-color: var(--turf-dark, #0D382E);
}
.btn-popular-action {
    background: #F97316;
    color: #fff;
    border: none;
    box-shadow: 0 4px 14px rgba(249, 115, 22, 0.3);
}
.btn-popular-action:hover {
    background: #ea580c;
    box-shadow: 0 6px 18px rgba(249, 115, 22, 0.4);
}
.plans-faq-box {
    margin-top: 60px;
    background: var(--surface, #fff);
    border: 1px solid var(--line, #E3ECE6);
    border-radius: 20px;
    padding: 36px;
}
.faq-box-title {
    font-family: 'Oswald', sans-serif;
    font-size: 22px;
    font-weight: 700;
    color: var(--turf-dark, #0D382E);
    margin: 0 0 24px;
    text-align: center;
}
.faq-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 30px;
}
.faq-item h4 {
    margin: 0 0 8px;
    font-size: 15.5px;
    font-weight: 700;
    color: var(--turf-dark, #0D382E);
}
.faq-item p {
    margin: 0;
    font-size: 14px;
    color: var(--ink-soft, #5E726A);
    line-height: 1.6;
}
@media (max-width: 900px) {
    .plans-benefits-row {
        grid-template-columns: 1fr;
    }
    .faq-grid {
        grid-template-columns: 1fr;
    }
}
</style>
@endpush
@endsection
