@extends('web.layouts.app')

@section('title', $court->name . ' - ' . config('app.name', 'Sân Nhóm H'))

@section('content')
<div class="court-detail-page">
    {{-- Breadcrumb Navigation --}}
    <div class="breadcrumb-container">
        <div class="wrap">
            <nav class="court-breadcrumb" aria-label="breadcrumb">
                <a href="{{ route('home') }}"><i class="ti ti-home"></i> {{ __('Trang chủ') }}</a>
                <span class="bc-sep"><i class="ti ti-chevron-right"></i></span>
                <a href="{{ route('courts.index') }}">{{ __('Danh sách sân') }}</a>
                <span class="bc-sep"><i class="ti ti-chevron-right"></i></span>
                <span class="bc-sport">{{ $court->sportType->name ?? __('Thể thao') }}</span>
                <span class="bc-sep"><i class="ti ti-chevron-right"></i></span>
                <span class="bc-current">{{ $court->name }}</span>
            </nav>
        </div>
    </div>

    {{-- Main Detail Layout --}}
    <div class="wrap detail-wrap">
        <div class="court-detail-grid">

            {{-- Left Column: Images, Info, Description, Reviews --}}
            <div class="court-info-col">
                {{-- Court Header Title & Rating --}}
                <div class="detail-title-card">
                    <div class="title-meta-row">
                        <span class="sport-badge-pill">
                            <i class="ti ti-activity"></i> {{ $court->sportType->name ?? __('Thể thao') }}
                        </span>
                        <div class="quick-status-chip">
                            <span class="dot-online"></span>
                            <span>{{ __('Đang nhận đặt lịch') }}</span>
                        </div>
                    </div>

                    <h1 class="court-main-title">{{ $court->name }}</h1>

                    <div class="court-location-line">
                        <i class="ti ti-map-pin"></i>
                        <span>{{ $court->facility->address ?? __('Đang cập nhật địa chỉ') }}</span>
                    </div>

                    <div class="stats-pills-row">
                        <div class="stat-bubble">
                            <i class="ti ti-star-filled star-gold"></i>
                            <span class="rating-number">5.0</span>
                            <span class="rating-sub">({{ $court->reviews->count() }} {{ __('đánh giá') }})</span>
                        </div>
                        <div class="stat-bubble">
                            <i class="ti ti-clock"></i>
                            <span>{{ $court->capacity ?? 12 }} {{ __('khung giờ trống') }}</span>
                        </div>
                        <div class="stat-bubble">
                            <i class="ti ti-shield-check"></i>
                            <span>{{ __('Đối tác xác thực') }}</span>
                        </div>
                    </div>
                </div>

                {{-- Image Gallery Showcase --}}
                <div class="gallery-card">
                    @php
                        $primaryImg = $court->images ? ($court->images->firstWhere('is_primary', true) ?? $court->images->first()) : null;
                    @endphp

                    @if($primaryImg)
                        <div class="main-image-viewer">
                            <img id="main-court-img" src="{{ asset($primaryImg->image_path) }}" alt="{{ $court->name }}">
                        </div>

                        @if($court->images->count() > 1)
                            <div class="thumbnails-track">
                                @foreach($court->images as $img)
                                    <div class="thumb-item {{ $img->id === $primaryImg->id ? 'active' : '' }}" 
                                         data-gallery-target="main-court-img" 
                                         data-gallery-src="{{ asset($img->image_path) }}">
                                        <img src="{{ asset($img->image_path) }}" alt="{{ $court->name }}">
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    @else
                        <div class="gallery-empty-placeholder">
                            <i class="ti ti-ball-football"></i>
                            <span>{{ __('Hình ảnh sân đang được cập nhật') }}</span>
                        </div>
                    @endif
                </div>

                {{-- Court Description --}}
                <div class="content-box-card">
                    <div class="box-card-header">
                        <i class="ti ti-notes"></i>
                        <h3>{{ __('Mô tả & Thông tin chi tiết') }}</h3>
                    </div>
                    <div class="box-card-body court-description-text">
                        {{ $court->description ?? __('Chưa có mô tả chi tiết cho sân này. Sân được trang bị hệ thống chiếu sáng chuẩn thi đấu, bề mặt thảm chất lượng cao và có chỗ đỗ xe rộng rãi tiện lợi.') }}
                    </div>

                    {{-- Court Amenities --}}
                    <div class="amenities-grid">
                        <div class="amenity-item">
                            <i class="ti ti-bulb"></i>
                            <span>{{ __('Đèn chiếu sáng tiêu chuẩn') }}</span>
                        </div>
                        <div class="amenity-item">
                            <i class="ti ti-wifi"></i>
                            <span>{{ __('Wifi miễn phí') }}</span>
                        </div>
                        <div class="amenity-item">
                            <i class="ti ti-parking"></i>
                            <span>{{ __('Bãi giữ xe rộng rãi') }}</span>
                        </div>
                        <div class="amenity-item">
                            <i class="ti ti-bottle"></i>
                            <span>{{ __('Quầy nước giải khát') }}</span>
                        </div>
                    </div>
                </div>

                {{-- Reviews Section --}}
                <div class="content-box-card reviews-box-card">
                    <div class="box-card-header">
                        <i class="ti ti-message-2"></i>
                        <h3>{{ __('Đánh giá từ người chơi') }} ({{ $court->reviews->count() }})</h3>
                    </div>

                    <div class="reviews-list-container">
                        @forelse ($court->reviews as $review)
                            <div class="user-review-item">
                                <div class="review-avatar">
                                    {{ strtoupper(mb_substr($review->user->name ?? 'U', 0, 1, 'UTF-8')) }}
                                </div>
                                <div class="review-content-col">
                                    <div class="review-top-meta">
                                        <strong class="user-name">{{ $review->user->name ?? __('Người chơi') }}</strong>
                                        <div class="star-rating" title="{{ $review->rating ?? 5 }}/5">
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="ti {{ $i <= ($review->rating ?? 5) ? 'ti-star-filled' : 'ti-star' }}"></i>
                                            @endfor
                                        </div>
                                    </div>
                                    <p class="review-text-message">{{ $review->content }}</p>
                                    <span class="review-date">{{ $review->created_at ? $review->created_at->format('d/m/Y') : __('Gần đây') }}</span>
                                </div>
                            </div>
                        @empty
                            <div class="no-reviews-box">
                                <i class="ti ti-messages"></i>
                                <p>{{ __('Chưa có đánh giá nào cho sân này. Hãy là người đầu tiên trải nghiệm và để lại nhận xét!') }}</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- Right Sticky Column: Booking Action Card --}}
            <aside class="court-booking-col">
                <div class="sticky-booking-card">
                    <div class="booking-card-head">
                        <span class="booking-badge"><i class="ti ti-bolt"></i> {{ __('Giữ chỗ ngay lập tức') }}</span>
                        <div class="card-price-display">
                            <span class="curr-price">{{ number_format($court->price ?? 120000, 0, ',', '.') }}đ</span>
                            <span class="unit-text">/{{ __('giờ') }}</span>
                        </div>
                    </div>

                    <form action="{{ route('bookings.create', $court->id) }}" method="GET" class="booking-selection-form" id="courtBookingForm">
                        <div class="form-input-group">
                            <label class="group-label">
                                <i class="ti ti-calendar-event"></i>
                                <span>{{ __('Chọn ngày muốn chơi') }}</span>
                            </label>
                            <div class="date-picker-wrap">
                                <input type="date" name="date" id="courtBookingDate" value="{{ $selectedDate ?? request('date', \Carbon\Carbon::now()->format('Y-m-d')) }}" required min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}">
                            </div>
                        </div>

                        <div class="form-input-group">
                            <div class="group-label-between">
                                <label class="group-label">
                                    <i class="ti ti-clock-hour-4"></i>
                                    <span>{{ __('Khung giờ có sẵn') }}</span>
                                </label>
                                <span class="slot-legend-online">{{ __('Khung 60 phút') }}</span>
                            </div>

                            <div class="time-slots-modern-grid">
                                @php
                                    $allPossibleSlots = [
                                        '06:00', '07:00', '08:00', '09:00', '10:00',
                                        '14:00', '15:00', '16:00', '17:00', '18:00',
                                        '19:00', '20:00', '21:00', '22:00'
                                    ];
                                    $bookedSet = isset($bookedSlots) && is_array($bookedSlots) ? $bookedSlots : [];
                                    $isToday = ($selectedDate ?? date('Y-m-d')) === date('Y-m-d');
                                    $currentH = (int) date('H');
                                    $foundFirstAvailable = false;
                                @endphp

                                @foreach($allPossibleSlots as $time)
                                    @php
                                        $slotH = (int) substr($time, 0, 2);
                                        $isPastTime = $isToday && ($slotH <= $currentH);
                                        $isAlreadyBooked = in_array($time, $bookedSet, true);
                                        $isAvailable = !$isPastTime && !$isAlreadyBooked;
                                        $shouldCheck = false;
                                        if ($isAvailable && !$foundFirstAvailable) {
                                            $shouldCheck = true;
                                            $foundFirstAvailable = true;
                                        }
                                    @endphp

                                    @if($isAvailable)
                                        <label class="slot-tile">
                                            <input type="radio" name="time" value="{{ $time }}" {{ $shouldCheck ? 'checked' : '' }} required>
                                            <span class="slot-tile-inner">
                                                <span class="slot-time">{{ $time }}</span>
                                                <span class="slot-hint">{{ __('Còn chỗ') }}</span>
                                            </span>
                                        </label>
                                    @else
                                        <div class="slot-tile slot-disabled" title="{{ $isAlreadyBooked ? __('Khung giờ này đã có người đặt') : __('Khung giờ này đã qua') }}">
                                            <span class="slot-tile-inner">
                                                <span class="slot-time">{{ $time }}</span>
                                                <span class="slot-hint">{{ $isAlreadyBooked ? __('Đã kín') : __('Đã qua') }}</span>
                                            </span>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>

                        <div class="booking-price-breakdown">
                            <div class="breakdown-row">
                                <span id="detail-slot-label">{{ __('Đơn giá sân (1 giờ)') }}</span>
                                <strong id="detail-unit-price">{{ number_format($court->price ?? 120000, 0, ',', '.') }}đ</strong>
                            </div>
                            <div class="breakdown-row">
                                <span>{{ __('Phí dịch vụ & tiện ích') }}</span>
                                <span class="free-badge">{{ __('Miễn phí') }}</span>
                            </div>
                            @if(isset($subscription) && $subscription && $subscription->plan && $subscription->plan->discount_percent > 0)
                                <div class="breakdown-row">
                                    <span style="color: #DB2777; font-weight: 600;">
                                        <i class="ti ti-crown"></i> {{ __('Ưu đãi VIP (:name)', ['name' => $subscription->plan->name]) }}
                                    </span>
                                    <span style="color: #DB2777; font-weight: 700;">-{{ $subscription->plan->discount_percent }}%</span>
                                </div>
                            @endif
                            <div class="breakdown-total">
                                <span>{{ __('Tạm tính tổng cộng') }}</span>
                                <span class="total-number" id="detail-total-price">{{ number_format($court->price ?? 120000, 0, ',', '.') }}đ</span>
                            </div>
                        </div>

                        <button type="submit" class="btn-proceed-booking">
                            <span>{{ __('Tiến hành xác nhận & thanh toán') }}</span>
                            <i class="ti ti-arrow-right"></i>
                        </button>

                        <div style="display: flex; align-items: center; justify-content: center; gap: 6px; flex-wrap: wrap; margin-top: 12px; padding-top: 12px; border-top: 1px dashed var(--line, #F1E4EC);">
                            <span style="font-size: 11px; font-weight: 700; background: #FCE7F3; color: #BE185D; padding: 3px 8px; border-radius: 6px; display: inline-flex; align-items: center; gap: 3px;">
                                <i class="ti ti-qrcode"></i> VietQR 24/7
                            </span>
                            <span style="font-size: 11px; font-weight: 700; background: #FDF2F8; color: #A50064; padding: 3px 8px; border-radius: 6px;">
                                MoMo
                            </span>
                            <span style="font-size: 11px; font-weight: 700; background: #EFF6FF; color: #1D4ED8; padding: 3px 8px; border-radius: 6px;">
                                Visa/Master
                            </span>
                            <span style="font-size: 11px; font-weight: 700; background: #FEF3C7; color: #B45309; padding: 3px 8px; border-radius: 6px;">
                                Tiền mặt (COD)
                            </span>
                        </div>
                    </form>

                    <div class="booking-guarantees">
                        <div class="guarantee-item">
                            <i class="ti ti-circle-check"></i>
                            <span>{{ __('Xác nhận giữ chỗ tự động qua SMS/Email') }}</span>
                        </div>
                        <div class="guarantee-item">
                            <i class="ti ti-refresh"></i>
                            <span>{{ __('Hỗ trợ đổi dời lịch theo quy định sân') }}</span>
                        </div>
                    </div>
                </div>
            </aside>

        </div>
    </div>
</div>

@push('styles')
<style>
.court-detail-page {
    background: var(--bg-page);
    min-height: 85vh;
    padding-bottom: 70px;
}
.breadcrumb-container {
    background: var(--surface, #fff);
    border-bottom: 1px solid var(--line, #F1E4EC);
    padding: 14px 0;
}
.court-breadcrumb {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 8px;
    font-size: 13.5px;
    color: var(--ink-soft, #5A5A72);
}
.court-breadcrumb a {
    color: var(--turf-dark, #181824);
    display: inline-flex;
    align-items: center;
    gap: 4px;
    font-weight: 500;
}
.court-breadcrumb a:hover {
    color: var(--turf-teal, #EC4899);
    text-decoration: underline;
}
.bc-sep {
    font-size: 12px;
    color: var(--ink-muted, #8E8EA8);
    display: flex;
    align-items: center;
}
.bc-sport {
    font-weight: 600;
    color: var(--turf-teal, #EC4899);
}
.bc-current {
    color: var(--ink-soft, #5A5A72);
    font-weight: 500;
}
.detail-wrap {
    margin-top: 30px;
}
.court-detail-grid {
    display: grid;
    grid-template-columns: 1.6fr 1fr;
    gap: 32px;
    align-items: flex-start;
}
.court-info-col {
    display: flex;
    flex-direction: column;
    gap: 24px;
}
.detail-title-card {
    background: var(--surface, #fff);
    border: 1px solid var(--line, #F1E4EC);
    border-radius: 20px;
    padding: 26px 28px;
    box-shadow: 0 4px 18px rgba(24, 24, 36, 0.04);
}
.title-meta-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 12px;
}
.sport-badge-pill {
    background: var(--turf-teal-soft, #FDF2F8);
    color: var(--turf-teal, #EC4899);
    font-size: 13px;
    font-weight: 700;
    padding: 4px 14px;
    border-radius: 999px;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}
.quick-status-chip {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 12.5px;
    color: #BE185D;
    font-weight: 600;
    background: #FCE7F3;
    padding: 4px 10px;
    border-radius: 999px;
}
.dot-online {
    width: 7px;
    height: 7px;
    border-radius: 50%;
    background: #FF2E93;
    box-shadow: 0 0 0 3px rgba(255, 46, 147, 0.25);
}
.court-main-title {
    font-family: 'Oswald', sans-serif;
    font-size: 32px;
    font-weight: 700;
    color: var(--turf-dark, #181824);
    margin: 0 0 10px;
    line-height: 1.25;
}
.court-location-line {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 14.5px;
    color: var(--ink-soft, #5A5A72);
    margin-bottom: 18px;
}
.court-location-line i {
    color: var(--turf-teal, #EC4899);
    font-size: 17px;
}
.stats-pills-row {
    display: flex;
    flex-wrap: wrap;
    gap: 12px;
    padding-top: 14px;
    border-top: 1px solid var(--line, #F1E4EC);
}
.stat-bubble {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: var(--bg-page, #FAF7F9);
    border: 1px solid var(--line, #F1E4EC);
    padding: 6px 14px;
    border-radius: 999px;
    font-size: 13px;
    font-weight: 600;
    color: var(--ink, #181824);
}
.star-gold {
    color: #F59E0B;
}
.rating-number {
    font-weight: 700;
}
.rating-sub {
    color: var(--ink-muted, #8E8EA8);
    font-weight: normal;
}
.gallery-card {
    background: var(--surface, #fff);
    border: 1px solid var(--line, #F1E4EC);
    border-radius: 20px;
    padding: 16px;
    box-shadow: 0 4px 18px rgba(24, 24, 36, 0.04);
}
.main-image-viewer {
    height: 380px;
    border-radius: 14px;
    overflow: hidden;
    background: #181824;
}
.main-image-viewer img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: opacity 0.2s ease;
}
.thumbnails-track {
    display: flex;
    gap: 12px;
    margin-top: 12px;
    overflow-x: auto;
    padding-bottom: 4px;
}
.thumb-item {
    width: 80px;
    height: 60px;
    border-radius: 10px;
    overflow: hidden;
    cursor: pointer;
    border: 2px solid transparent;
    opacity: 0.7;
    transition: all 0.2s ease;
    flex-shrink: 0;
}
.thumb-item:hover, .thumb-item.active {
    opacity: 1;
    border-color: var(--turf-teal, #EC4899);
    transform: translateY(-2px);
}
.thumb-item img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}
.gallery-empty-placeholder {
    height: 240px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 12px;
    background: #FDF2F8;
    border-radius: 14px;
    color: var(--turf-teal, #EC4899);
    font-size: 15px;
    font-weight: 500;
}
.gallery-empty-placeholder i {
    font-size: 48px;
}
.content-box-card {
    background: var(--surface, #fff);
    border: 1px solid var(--line, #F1E4EC);
    border-radius: 20px;
    padding: 26px 28px;
    box-shadow: 0 4px 18px rgba(24, 24, 36, 0.04);
}
.box-card-header {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 18px;
    padding-bottom: 12px;
    border-bottom: 1px solid var(--line, #F1E4EC);
}
.box-card-header i {
    font-size: 22px;
    color: var(--turf-teal, #EC4899);
}
.box-card-header h3 {
    margin: 0;
    font-size: 19px;
    font-weight: 700;
    color: var(--turf-dark, #181824);
}
.court-description-text {
    font-size: 15px;
    line-height: 1.7;
    color: var(--ink-soft, #5A5A72);
}
.amenities-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 16px;
    margin-top: 24px;
    padding-top: 20px;
    border-top: 1px dashed var(--line, #F1E4EC);
}
.amenity-item {
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 14px;
    font-weight: 600;
    color: var(--turf-dark, #181824);
}
.amenity-item i {
    font-size: 18px;
    color: var(--turf-teal, #EC4899);
}
.reviews-list-container {
    display: flex;
    flex-direction: column;
    gap: 18px;
}
.user-review-item {
    display: flex;
    gap: 16px;
    align-items: flex-start;
    padding-bottom: 18px;
    border-bottom: 1px solid var(--line, #F1E4EC);
}
.user-review-item:last-child {
    border-bottom: none;
    padding-bottom: 0;
}
.review-avatar {
    width: 44px;
    height: 44px;
    border-radius: 50%;
    background: var(--turf-teal-soft, #FDF2F8);
    color: var(--turf-teal, #EC4899);
    font-weight: 700;
    font-size: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}
.review-content-col {
    flex: 1;
}
.review-top-meta {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 6px;
}
.user-name {
    font-size: 14.5px;
    color: var(--turf-dark, #181824);
}
.star-rating {
    color: #F59E0B;
    font-size: 14px;
}
.review-text-message {
    font-size: 14px;
    color: var(--ink-soft, #5A5A72);
    line-height: 1.6;
    margin: 0 0 6px;
}
.review-date {
    font-size: 12px;
    color: var(--ink-muted, #8E8EA8);
}
.no-reviews-box {
    text-align: center;
    padding: 30px 10px;
    color: var(--ink-soft, #5A5A72);
}
.no-reviews-box i {
    font-size: 40px;
    color: var(--ink-muted, #8E8EA8);
    margin-bottom: 8px;
}
.court-booking-col {
    position: relative;
}
.sticky-booking-card {
    position: sticky;
    top: 90px;
    background: var(--surface, #fff);
    border: 1px solid var(--line, #F1E4EC);
    border-radius: 24px;
    padding: 26px;
    box-shadow: 0 8px 30px rgba(24, 24, 36, 0.08);
}
.booking-card-head {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 22px;
    padding-bottom: 18px;
    border-bottom: 1px solid var(--line, #F1E4EC);
}
.booking-badge {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    font-size: 12px;
    font-weight: 700;
    color: var(--turf-teal, #EC4899);
    background: var(--turf-teal-soft, #FDF2F8);
    padding: 5px 10px;
    border-radius: 999px;
}
.card-price-display {
    display: flex;
    align-items: baseline;
    gap: 2px;
}
.curr-price {
    font-family: 'Oswald', sans-serif;
    font-size: 26px;
    font-weight: 800;
    color: #EC4899;
}
.unit-text {
    font-size: 13px;
    color: var(--ink-soft, #5A5A72);
}
.form-input-group {
    margin-bottom: 20px;
}
.group-label {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 13.5px;
    font-weight: 700;
    color: var(--turf-dark, #181824);
    margin-bottom: 10px;
}
.group-label-between {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 10px;
}
.slot-legend-online {
    font-size: 11.5px;
    color: var(--ink-muted, #8E8EA8);
}
.date-picker-wrap {
    background: var(--bg-page, #FAF7F9);
    border: 1px solid #E5D4E0;
    border-radius: 12px;
    padding: 10px 14px;
}
.date-picker-wrap input {
    width: 100%;
    border: none;
    outline: none;
    background: transparent;
    font-size: 14.5px;
    font-weight: 600;
    color: var(--turf-dark, #181824);
    font-family: inherit;
    cursor: pointer;
}
.time-slots-modern-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 8px;
}
.slot-tile {
    cursor: pointer;
    position: relative;
    user-select: none;
}
.slot-tile input {
    display: none;
}
.slot-tile-inner {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 10px 6px;
    border-radius: 10px;
    border: 1.5px solid var(--line, #F1E4EC);
    background: var(--surface, #fff);
    transition: all 0.2s ease;
}
.slot-time {
    font-size: 13.5px;
    font-weight: 700;
    color: var(--turf-dark, #181824);
}
.slot-hint {
    font-size: 10.5px;
    color: #DB2777;
    margin-top: 2px;
}
.slot-tile:hover .slot-tile-inner {
    border-color: var(--turf-teal, #EC4899);
}
.slot-tile input:checked + .slot-tile-inner {
    background: linear-gradient(135deg, #FF2E93 0%, #DB2777 100%);
    border-color: #EC4899;
    box-shadow: 0 4px 12px rgba(236, 72, 153, 0.3);
}
.slot-tile input:checked + .slot-tile-inner .slot-time,
.slot-tile input:checked + .slot-tile-inner .slot-hint {
    color: #ffffff;
}
.slot-disabled {
    cursor: not-allowed;
    opacity: 0.6;
}
.slot-disabled .slot-tile-inner {
    background: var(--bg-page, #FAF7F9);
    border: 1px dashed var(--line, #F1E4EC);
}
.slot-disabled .slot-time {
    text-decoration: line-through;
    color: var(--ink-muted, #8E8EA8);
}
.slot-disabled .slot-hint {
    color: #ef4444;
}
.booking-price-breakdown {
    background: var(--bg-page, #FAF7F9);
    border-radius: 14px;
    padding: 16px;
    margin-bottom: 20px;
    display: flex;
    flex-direction: column;
    gap: 10px;
}
.breakdown-row {
    display: flex;
    justify-content: space-between;
    font-size: 13px;
    color: var(--ink-soft, #5A5A72);
}
.free-badge {
    color: #DB2777;
    font-weight: 600;
}
.breakdown-total {
    display: flex;
    justify-content: space-between;
    align-items: baseline;
    font-size: 14px;
    font-weight: 700;
    color: var(--turf-dark, #181824);
    padding-top: 10px;
    border-top: 1px dashed var(--line, #F1E4EC);
}
.total-number {
    font-size: 20px;
    color: #EC4899;
    font-family: 'Oswald', sans-serif;
    font-weight: 800;
}
.btn-proceed-booking {
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    background: linear-gradient(135deg, #FF2E93 0%, #DB2777 100%);
    color: #fff;
    border: none;
    font-size: 16px;
    font-weight: 700;
    padding: 14px;
    border-radius: 999px;
    cursor: pointer;
    transition: all 0.2s ease;
    box-shadow: 0 6px 18px rgba(236, 72, 153, 0.35);
}
.btn-proceed-booking:hover {
    background: linear-gradient(135deg, #E01A70 0%, #BE185D 100%);
    transform: translateY(-2px);
    box-shadow: 0 8px 22px rgba(236, 72, 153, 0.48);
}
.booking-guarantees {
    margin-top: 18px;
    display: flex;
    flex-direction: column;
    gap: 8px;
}
.guarantee-item {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 12px;
    color: var(--ink-soft, #5A5A72);
}
.guarantee-item i {
    color: #EC4899;
    font-size: 15px;
}

@media (max-width: 992px) {
    .court-detail-grid {
        grid-template-columns: 1fr;
    }
    .sticky-booking-card {
        position: static;
    }
    .main-image-viewer {
        height: 280px;
    }
}
</style>
@endpush

@push('scripts')
<script nonce="{{ $cspNonce ?? '' }}">
document.addEventListener('DOMContentLoaded', function() {
    const dateInput = document.getElementById('courtBookingDate');
    const slotRadios = document.querySelectorAll('input[name="time"]');
    const slotLabel = document.getElementById('detail-slot-label');
    const unitPriceEl = document.getElementById('detail-unit-price');
    const totalPriceEl = document.getElementById('detail-total-price');

    const priceRules = @json($priceRules ?? []);
    const fallbackPrice = {{ (int) ($court->price ?? 120000) }};
    const discountPercent = {{ (int) (isset($subscription) && $subscription && $subscription->plan ? $subscription->plan->discount_percent : 0) }};

    function isWeekend(dateStr) {
        if (!dateStr) return false;
        const d = new Date(dateStr);
        const day = d.getDay();
        return day === 0 || day === 6;
    }

    function updateSlotPrice() {
        const checkedRadio = document.querySelector('input[name="time"]:checked');
        if (!checkedRadio) return;

        const timeVal = checkedRadio.value;
        const dVal = dateInput ? dateInput.value : '';
        const h = parseInt(timeVal.split(':')[0], 10);
        const nextH = (h + 1) % 24;
        const nextTimeStr = (nextH < 10 ? '0' + nextH : nextH) + ':00';

        if (slotLabel) {
            slotLabel.textContent = 'Đơn giá (' + timeVal + ' - ' + nextTimeStr + ')';
        }

        const dayType = isWeekend(dVal) ? 'weekend' : 'weekday';
        const hourStr = (h < 10 ? '0' + h : h) + ':00:00';
        let slotPrice = fallbackPrice;

        if (priceRules && priceRules.length > 0) {
            for (let r of priceRules) {
                if (r.day_type === dayType && r.start_time <= hourStr && r.end_time > hourStr) {
                    slotPrice = Number(r.price);
                    break;
                }
            }
        }

        const discount = discountPercent > 0 ? Math.round(slotPrice * (discountPercent / 100)) : 0;
        const finalTotal = Math.max(0, slotPrice - discount);

        if (unitPriceEl) {
            unitPriceEl.textContent = new Intl.NumberFormat('vi-VN').format(slotPrice) + 'đ';
        }
        if (totalPriceEl) {
            totalPriceEl.textContent = new Intl.NumberFormat('vi-VN').format(finalTotal) + 'đ';
        }
    }

    slotRadios.forEach(r => r.addEventListener('change', updateSlotPrice));
    updateSlotPrice();

    // Gallery thumbnail switcher
    const thumbs = document.querySelectorAll('.thumb-item[data-gallery-src]');
    const mainImg = document.getElementById('main-court-img');
    thumbs.forEach(item => {
        item.addEventListener('click', function() {
            thumbs.forEach(t => t.classList.remove('active'));
            this.classList.add('active');
            if (mainImg && this.dataset.gallerySrc) {
                mainImg.src = this.dataset.gallerySrc;
            }
        });
    });

    if (dateInput) {
        dateInput.addEventListener('change', function() {
            const url = new URL(window.location.href);
            url.searchParams.set('date', this.value);
            window.location.href = url.toString();
        });
    }
});
</script>
@endpush
@endsection
