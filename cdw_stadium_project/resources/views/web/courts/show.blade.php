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

                    <form action="{{ route('bookings.create', $court->id) }}" method="GET" class="booking-selection-form">
                        <div class="form-input-group">
                            <label class="group-label">
                                <i class="ti ti-calendar-event"></i>
                                <span>{{ __('Chọn ngày muốn chơi') }}</span>
                            </label>
                            <div class="date-picker-wrap">
                                <input type="date" name="date" value="{{ request('date', \Carbon\Carbon::now()->format('Y-m-d')) }}" required min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}">
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
                                    $slots = [
                                        '16:00' => true,
                                        '17:00' => true,
                                        '18:00' => true,
                                        '19:00' => true,
                                        '20:00' => false, // booked slot simulation
                                        '21:00' => true,
                                        '22:00' => true,
                                    ];
                                @endphp

                                @foreach($slots as $time => $available)
                                    @if($available)
                                        <label class="slot-tile">
                                            <input type="radio" name="time" value="{{ $time }}" {{ $loop->first ? 'checked' : '' }} required>
                                            <span class="slot-tile-inner">
                                                <span class="slot-time">{{ $time }}</span>
                                                <span class="slot-hint">{{ __('Còn chỗ') }}</span>
                                            </span>
                                        </label>
                                    @else
                                        <div class="slot-tile slot-disabled" title="{{ __('Khung giờ này đã có người đặt') }}">
                                            <span class="slot-tile-inner">
                                                <span class="slot-time">{{ $time }}</span>
                                                <span class="slot-hint">{{ __('Đã kín') }}</span>
                                            </span>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>

                        <div class="booking-price-breakdown">
                            <div class="breakdown-row">
                                <span>{{ __('Đơn giá sân (1 giờ)') }}</span>
                                <strong>{{ number_format($court->price ?? 120000, 0, ',', '.') }}đ</strong>
                            </div>
                            <div class="breakdown-row">
                                <span>{{ __('Phí dịch vụ & tiện ích') }}</span>
                                <span class="free-badge">{{ __('Miễn phí') }}</span>
                            </div>
                            <div class="breakdown-total">
                                <span>{{ __('Tạm tính tổng cộng') }}</span>
                                <span class="total-number">{{ number_format($court->price ?? 120000, 0, ',', '.') }}đ</span>
                            </div>
                        </div>

                        <button type="submit" class="btn-proceed-booking">
                            <span>{{ __('Tiến hành đặt sân') }}</span>
                            <i class="ti ti-arrow-right"></i>
                        </button>
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
    border-bottom: 1px solid var(--line, #E3ECE6);
    padding: 14px 0;
}
.court-breadcrumb {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 8px;
    font-size: 13.5px;
    color: var(--ink-soft, #5E726A);
}
.court-breadcrumb a {
    color: var(--turf-dark, #0D382E);
    display: inline-flex;
    align-items: center;
    gap: 4px;
    font-weight: 500;
}
.court-breadcrumb a:hover {
    color: var(--turf-teal, #106764);
    text-decoration: underline;
}
.bc-sep {
    font-size: 12px;
    color: var(--ink-muted, #879C94);
    display: flex;
    align-items: center;
}
.bc-sport {
    font-weight: 600;
    color: var(--turf-teal, #106764);
}
.bc-current {
    color: var(--ink-soft, #5E726A);
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
    border: 1px solid var(--line, #E3ECE6);
    border-radius: 20px;
    padding: 26px 28px;
    box-shadow: 0 4px 18px rgba(13, 56, 46, 0.03);
}
.title-meta-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 12px;
}
.sport-badge-pill {
    background: var(--turf-teal-soft, #E6F6F4);
    color: var(--turf-teal, #106764);
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
    color: #16A34A;
    font-weight: 600;
    background: #DCFCE7;
    padding: 4px 10px;
    border-radius: 999px;
}
.dot-online {
    width: 7px;
    height: 7px;
    border-radius: 50%;
    background: #16A34A;
    box-shadow: 0 0 0 3px rgba(22, 163, 74, 0.2);
}
.court-main-title {
    font-family: 'Oswald', sans-serif;
    font-size: 32px;
    font-weight: 700;
    color: var(--turf-dark, #0D382E);
    margin: 0 0 10px;
    line-height: 1.25;
}
.court-location-line {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 14.5px;
    color: var(--ink-soft, #5E726A);
    margin-bottom: 18px;
}
.court-location-line i {
    color: var(--turf-teal, #106764);
    font-size: 17px;
}
.stats-pills-row {
    display: flex;
    flex-wrap: wrap;
    gap: 12px;
    padding-top: 14px;
    border-top: 1px solid var(--line, #E3ECE6);
}
.stat-bubble {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: var(--bg-page, #F6F8F6);
    border: 1px solid var(--line, #E3ECE6);
    padding: 6px 14px;
    border-radius: 999px;
    font-size: 13px;
    font-weight: 600;
    color: var(--ink, #11201B);
}
.star-gold {
    color: #F59E0B;
}
.rating-number {
    font-weight: 700;
}
.rating-sub {
    color: var(--ink-muted, #879C94);
    font-weight: normal;
}
.gallery-card {
    background: var(--surface, #fff);
    border: 1px solid var(--line, #E3ECE6);
    border-radius: 20px;
    padding: 16px;
    box-shadow: 0 4px 18px rgba(13, 56, 46, 0.03);
}
.main-image-viewer {
    height: 380px;
    border-radius: 14px;
    overflow: hidden;
    background: #0D382E;
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
    border-color: var(--turf-teal, #106764);
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
    background: #E6F0FA;
    border-radius: 14px;
    color: var(--turf-teal, #106764);
    font-size: 15px;
    font-weight: 500;
}
.gallery-empty-placeholder i {
    font-size: 48px;
}
.content-box-card {
    background: var(--surface, #fff);
    border: 1px solid var(--line, #E3ECE6);
    border-radius: 20px;
    padding: 26px 28px;
    box-shadow: 0 4px 18px rgba(13, 56, 46, 0.03);
}
.box-card-header {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 18px;
    padding-bottom: 12px;
    border-bottom: 1px solid var(--line, #E3ECE6);
}
.box-card-header i {
    font-size: 22px;
    color: var(--turf-teal, #106764);
}
.box-card-header h3 {
    margin: 0;
    font-size: 19px;
    font-weight: 700;
    color: var(--turf-dark, #0D382E);
}
.court-description-text {
    font-size: 15px;
    line-height: 1.7;
    color: var(--ink-soft, #5E726A);
}
.amenities-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 16px;
    margin-top: 24px;
    padding-top: 20px;
    border-top: 1px dashed var(--line, #E3ECE6);
}
.amenity-item {
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 14px;
    font-weight: 600;
    color: var(--turf-dark, #0D382E);
}
.amenity-item i {
    font-size: 18px;
    color: var(--turf-teal, #106764);
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
    border-bottom: 1px solid var(--line, #E3ECE6);
}
.user-review-item:last-child {
    border-bottom: none;
    padding-bottom: 0;
}
.review-avatar {
    width: 44px;
    height: 44px;
    border-radius: 50%;
    background: var(--turf-teal-soft, #E6F6F4);
    color: var(--turf-teal, #106764);
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
    color: var(--turf-dark, #0D382E);
}
.star-rating {
    color: #F59E0B;
    font-size: 14px;
}
.review-text-message {
    font-size: 14px;
    color: var(--ink-soft, #5E726A);
    line-height: 1.6;
    margin: 0 0 6px;
}
.review-date {
    font-size: 12px;
    color: var(--ink-muted, #879C94);
}
.no-reviews-box {
    text-align: center;
    padding: 30px 10px;
    color: var(--ink-soft, #5E726A);
}
.no-reviews-box i {
    font-size: 40px;
    color: var(--ink-muted, #879C94);
    margin-bottom: 8px;
}
.court-booking-col {
    position: relative;
}
.sticky-booking-card {
    position: sticky;
    top: 90px;
    background: var(--surface, #fff);
    border: 1px solid var(--line, #E3ECE6);
    border-radius: 24px;
    padding: 26px;
    box-shadow: 0 8px 30px rgba(13, 56, 46, 0.08);
}
.booking-card-head {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 22px;
    padding-bottom: 18px;
    border-bottom: 1px solid var(--line, #E3ECE6);
}
.booking-badge {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    font-size: 12px;
    font-weight: 700;
    color: var(--turf-teal, #106764);
    background: var(--turf-teal-soft, #E6F6F4);
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
    color: #F97316;
}
.unit-text {
    font-size: 13px;
    color: var(--ink-soft, #5E726A);
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
    color: var(--turf-dark, #0D382E);
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
    color: var(--ink-muted, #879C94);
}
.date-picker-wrap {
    background: var(--bg-page, #F6F8F6);
    border: 1px solid #C7D3CA;
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
    color: var(--turf-dark, #0D382E);
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
    border: 1.5px solid var(--line, #E3ECE6);
    background: var(--surface, #fff);
    transition: all 0.2s ease;
}
.slot-time {
    font-size: 13.5px;
    font-weight: 700;
    color: var(--turf-dark, #0D382E);
}
.slot-hint {
    font-size: 10.5px;
    color: #16A34A;
    margin-top: 2px;
}
.slot-tile:hover .slot-tile-inner {
    border-color: var(--turf-teal, #106764);
}
.slot-tile input:checked + .slot-tile-inner {
    background: var(--turf-dark, #0D382E);
    border-color: var(--turf-dark, #0D382E);
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
    background: var(--bg-page, #F6F8F6);
    border: 1px dashed var(--line, #E3ECE6);
}
.slot-disabled .slot-time {
    text-decoration: line-through;
    color: var(--ink-muted, #879C94);
}
.slot-disabled .slot-hint {
    color: #ef4444;
}
.booking-price-breakdown {
    background: var(--bg-page, #F6F8F6);
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
    color: var(--ink-soft, #5E726A);
}
.free-badge {
    color: #16A34A;
    font-weight: 600;
}
.breakdown-total {
    display: flex;
    justify-content: space-between;
    align-items: baseline;
    font-size: 14px;
    font-weight: 700;
    color: var(--turf-dark, #0D382E);
    padding-top: 10px;
    border-top: 1px dashed var(--line, #E3ECE6);
}
.total-number {
    font-size: 20px;
    color: #F97316;
    font-family: 'Oswald', sans-serif;
    font-weight: 800;
}
.btn-proceed-booking {
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    background: #F97316;
    color: #fff;
    border: none;
    font-size: 16px;
    font-weight: 700;
    padding: 14px;
    border-radius: 12px;
    cursor: pointer;
    transition: all 0.2s ease;
    box-shadow: 0 4px 14px rgba(249, 115, 22, 0.3);
}
.btn-proceed-booking:hover {
    background: #ea580c;
    transform: translateY(-2px);
    box-shadow: 0 6px 18px rgba(249, 115, 22, 0.4);
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
    color: var(--ink-soft, #5E726A);
}
.guarantee-item i {
    color: #16A34A;
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
@endsection
