@extends('web.layouts.app')

@section('title', __('Danh sách sân thể thao') . ' - ' . config('app.name', 'Sân Nhóm H'))

@section('content')
<div class="courts-page">
    {{-- Header Banner / Hero --}}
    <div class="courts-hero">
        <div class="wrap">
            <div class="courts-hero-content">
                <span class="courts-badge"><i class="ti ti-map-pin"></i> {{ __('Hệ thống sân bãi hiện đại') }}</span>
                <h1>{{ __('Đặt sân thể thao nhanh chóng & tiện lợi') }}</h1>
                <p>{{ __('Chọn môn thể thao bạn yêu thích, tìm sân gần nhất và đặt chỗ ngay chỉ với vài thao tác đơn giản.') }}</p>
                
                {{-- Search Bar Inside Hero --}}
                <form action="{{ route('courts.index') }}" method="GET" class="courts-search-box">
                    @foreach(request('sport_types', []) as $st)
                        <input type="hidden" name="sport_types[]" value="{{ $st }}">
                    @endforeach
                    <div class="search-input-wrap">
                        <i class="ti ti-search search-icon"></i>
                        <input type="text" name="q" value="{{ $keyword ?? request('q') }}" placeholder="{{ __('Tìm theo tên sân, địa điểm, môn thể thao...') }}" autocomplete="off">
                        @if($keyword ?? request('q'))
                            <a href="{{ route('courts.index') }}" class="clear-search-btn" title="{{ __('Xóa tìm kiếm') }}"><i class="ti ti-x"></i></a>
                        @endif
                    </div>
                    <button type="submit" class="btn-search-submit">
                        <i class="ti ti-search"></i>
                        <span>{{ __('Tìm kiếm') }}</span>
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- Main Container --}}
    <div class="wrap courts-main-wrap">
        <div class="courts-layout">
            
            {{-- Filter Sidebar --}}
            <aside class="courts-sidebar">
                <div class="filter-card">
                    <div class="filter-card-header">
                        <div class="filter-title">
                            <i class="ti ti-adjustments-horizontal"></i>
                            <span>{{ __('Bộ lọc thể thao') }}</span>
                        </div>
                        @if(!empty(request('sport_types')) || request('q'))
                            <a href="{{ route('courts.index') }}" class="filter-reset-btn">{{ __('Đặt lại') }}</a>
                        @endif
                    </div>

                    <form id="filter-form" action="{{ route('courts.index') }}" method="GET" class="filter-form">
                        @if(request('q'))
                            <input type="hidden" name="q" value="{{ request('q') }}">
                        @endif

                        <div class="filter-group">
                            <span class="filter-group-title">{{ __('Môn thể thao') }}</span>
                            <div class="filter-options-list">
                                @foreach($sportTypes as $type)
                                    @php
                                        $isChecked = in_array($type->id, request('sport_types', []));
                                    @endphp
                                    <label class="filter-checkbox-item {{ $isChecked ? 'active' : '' }}">
                                        <input type="checkbox" name="sport_types[]" value="{{ $type->id }}"
                                               {{ $isChecked ? 'checked' : '' }}
                                               data-auto-submit>
                                        <span class="custom-checkbox">
                                            <i class="ti ti-check"></i>
                                        </span>
                                        <span class="filter-label-text">{{ $type->name }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        @if(isset($locations) && $locations->isNotEmpty())
                        <div class="filter-group" style="margin-top: 24px; padding-top: 20px; border-top: 1px solid var(--line);">
                            <span class="filter-group-title">{{ __('Khu vực hoạt động') }}</span>
                            <div class="filter-locations-tags">
                                @foreach($locations as $loc)
                                    <span class="loc-tag"><i class="ti ti-map-pin"></i> {{ $loc->district }}</span>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    </form>
                </div>

                {{-- Support Info Box --}}
                <div class="sidebar-help-box">
                    <div class="help-icon"><i class="ti ti-headset"></i></div>
                    <h4>{{ __('Cần hỗ trợ đặt sân?') }}</h4>
                    <p>{{ __('Đội ngũ trực ban sẵn sàng tư vấn và giải đáp thắc mắc 24/7.') }}</p>
                    <a href="tel:19006868" class="help-phone-btn"><i class="ti ti-phone"></i> 1900.6868.88</a>
                </div>
            </aside>

            {{-- Main Content Column --}}
            <div class="courts-content">
                {{-- Active Filters / Stats Bar --}}
                <div class="results-bar">
                    <div class="results-info">
                        <span class="results-count">
                            <i class="ti ti-trophy"></i>
                            @if($courts instanceof \Illuminate\Pagination\LengthAwarePaginator)
                                {{ __('Tìm thấy :count sân thể thao', ['count' => $courts->total()]) }}
                            @else
                                {{ __('Hiển thị :count sân thể thao nổi bật', ['count' => $courts->count()]) }}
                            @endif
                        </span>
                        @if(!empty(request('q')))
                            <span class="results-keyword">{{ __('Từ khóa:') }} <strong>"{{ request('q') }}"</strong></span>
                        @endif
                    </div>
                </div>

                @php
                    $groupedCourts = $courts->groupBy('sport_type_id');
                @endphp

                @forelse ($groupedCourts as $sportTypeId => $group)
                    @php
                        $sportName = $group->first()->sportType->name ?? __('Thể thao');
                    @endphp
                    <div class="sport-group-section">
                        <div class="sport-group-title-row">
                            <div class="sport-title-wrap">
                                <span class="sport-pill-icon"><i class="ti ti-flame"></i></span>
                                <h3>{{ $sportName }}</h3>
                            </div>
                            <span class="sport-item-count">{{ $group->count() }} {{ __('sân') }}</span>
                        </div>

                        <div class="courts-modern-grid">
                            @foreach ($group as $court)
                                @php
                                    $primaryImg = $court->images ? ($court->images->firstWhere('is_primary', true) ?? $court->images->first()) : null;
                                @endphp
                                <div class="court-card-modern" data-href="{{ route('courts.show', $court->id) }}">
                                    <div class="card-media-wrapper">
                                        @if($primaryImg)
                                            <img src="{{ asset($primaryImg->image_path) }}" alt="{{ $court->name }}" loading="lazy">
                                        @else
                                            <div class="media-placeholder">
                                                <i class="ti ti-ball-football"></i>
                                            </div>
                                        @endif
                                        <div class="court-badges">
                                            <span class="badge-sport">{{ $court->sportType->name ?? __('Thể thao') }}</span>
                                            @php
                                                $isCourtOpen = in_array($court->status ?? 'available', ['available', 'active'], true);
                                            @endphp
                                            <span class="badge-status {{ $isCourtOpen ? 'status-open' : 'status-busy' }}">
                                                <span class="status-dot"></span>
                                                {{ $isCourtOpen ? __('Đang hoạt động') : __('Bảo trì') }}
                                            </span>
                                        </div>
                                    </div>

                                    <div class="card-content">
                                        <h4 class="court-card-title">{{ $court->name }}</h4>
                                        <div class="court-card-location">
                                            <i class="ti ti-map-pin"></i>
                                            <span>{{ $court->facility->address ?? __('Đang cập nhật địa chỉ') }}</span>
                                        </div>

                                        <div class="court-features">
                                            <span class="feature-tag"><i class="ti ti-clock"></i> {{ $court->capacity ?? 0 }} {{ __('khung giờ/ngày') }}</span>
                                            <span class="feature-tag rating-tag"><i class="ti ti-star-filled"></i> 4.9</span>
                                        </div>

                                        <div class="card-footer-action">
                                            <div class="price-box">
                                                <span class="price-label">{{ __('Giá từ') }}</span>
                                                <span class="price-val">{{ number_format($court->price ?? 120000, 0, ',', '.') }}đ<small>/h</small></span>
                                            </div>
                                            <a href="{{ route('courts.show', $court->id) }}" class="btn-view-court">
                                                <span>{{ __('Đặt sân') }}</span>
                                                <i class="ti ti-arrow-right"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @empty
                    <div class="empty-state-card">
                        <div class="empty-icon"><i class="ti ti-mood-empty"></i></div>
                        <h3>{{ __('Không tìm thấy sân thể thao phù hợp') }}</h3>
                        <p>{{ __('Hãy thử xóa bộ lọc môn thể thao hoặc tìm kiếm bằng từ khóa khác.') }}</p>
                        <a href="{{ route('courts.index') }}" class="btn-empty-reset">{{ __('Xem tất cả sân') }}</a>
                    </div>
                @endforelse

                @if(method_exists($courts, 'links'))
                    <div class="courts-pagination">
                        {{ $courts->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</div>

@push('styles')
<style>
.courts-page {
    background: var(--bg-page);
    min-height: 85vh;
    padding-bottom: 70px;
}
.courts-hero {
    background:
        radial-gradient(circle at 85% 20%, rgba(255, 46, 147, 0.25) 0%, transparent 45%),
        linear-gradient(135deg, #181824 0%, #101018 100%);
    color: #fff;
    padding: 60px 0 50px;
    position: relative;
    border-bottom: 2px solid rgba(255, 46, 147, 0.3);
}
.courts-hero-content {
    max-width: 820px;
    margin: 0 auto;
    text-align: center;
}
.courts-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: rgba(255, 46, 147, 0.16);
    color: #F472B6;
    border: 1px solid rgba(255, 46, 147, 0.35);
    padding: 6px 16px;
    border-radius: 999px;
    font-size: 13px;
    font-weight: 600;
    margin-bottom: 16px;
}
.courts-hero h1 {
    font-family: 'Oswald', sans-serif;
    font-size: 38px;
    font-weight: 700;
    margin: 0 0 12px;
    color: #ffffff;
    letter-spacing: 0.5px;
}
.courts-hero p {
    color: #D8CAD3;
    font-size: 16px;
    line-height: 1.6;
    margin: 0 auto 30px;
    max-width: 640px;
}
.courts-search-box {
    display: flex;
    align-items: center;
    background: #ffffff;
    border-radius: 16px;
    padding: 6px 6px 6px 18px;
    box-shadow: 0 12px 35px rgba(0,0,0,0.28);
    max-width: 680px;
    margin: 0 auto;
    border: 2px solid #FCE7F3;
}
.search-input-wrap {
    display: flex;
    align-items: center;
    flex: 1;
    gap: 12px;
}
.search-input-wrap .search-icon {
    font-size: 20px;
    color: var(--turf-teal, #EC4899);
}
.search-input-wrap input {
    width: 100%;
    border: none;
    outline: none;
    font-size: 15px;
    color: var(--ink, #181824);
    background: transparent;
    font-family: inherit;
}
.clear-search-btn {
    color: var(--ink-muted, #8E8EA8);
    font-size: 18px;
    padding: 4px;
    display: flex;
    align-items: center;
    border-radius: 50%;
}
.clear-search-btn:hover {
    color: #ef4444;
}
.btn-search-submit {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: linear-gradient(135deg, #FF2E93 0%, #DB2777 100%);
    color: #FFFFFF;
    border: none;
    font-weight: 700;
    font-size: 15px;
    padding: 12px 24px;
    border-radius: 12px;
    cursor: pointer;
    transition: all 0.2s ease;
    white-space: nowrap;
    box-shadow: 0 4px 14px rgba(236, 72, 153, 0.35);
}
.btn-search-submit:hover {
    background: linear-gradient(135deg, #E01A70 0%, #BE185D 100%);
    transform: translateY(-1px);
}
.courts-main-wrap {
    margin-top: 40px;
}
.courts-layout {
    display: flex;
    gap: 32px;
    align-items: flex-start;
}
.courts-sidebar {
    width: 290px;
    flex-shrink: 0;
    display: flex;
    flex-direction: column;
    gap: 20px;
}
.filter-card {
    background: var(--surface, #ffffff);
    border: 1px solid var(--line, #F1E4EC);
    border-radius: 20px;
    padding: 24px;
    box-shadow: 0 4px 20px rgba(24, 24, 36, 0.04);
}
.filter-card-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
    padding-bottom: 14px;
    border-bottom: 1px solid var(--line, #F1E4EC);
}
.filter-title {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 17px;
    font-weight: 700;
    color: var(--turf-dark, #181824);
}
.filter-reset-btn {
    font-size: 12.5px;
    color: #ef4444;
    font-weight: 600;
}
.filter-reset-btn:hover {
    text-decoration: underline;
}
.filter-group-title {
    display: block;
    font-size: 13px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: var(--ink-soft, #5A5A72);
    margin-bottom: 14px;
}
.filter-options-list {
    display: flex;
    flex-direction: column;
    gap: 10px;
}
.filter-checkbox-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 8px 10px;
    border-radius: 10px;
    cursor: pointer;
    transition: background 0.15s ease;
    user-select: none;
}
.filter-checkbox-item:hover {
    background: var(--line-light, #F9F1F6);
}
.filter-checkbox-item.active {
    background: var(--turf-teal-soft, #FDF2F8);
}
.filter-checkbox-item input {
    display: none;
}
.custom-checkbox {
    width: 20px;
    height: 20px;
    border-radius: 6px;
    border: 2px solid #E5D4E0;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 12px;
    color: #fff;
    transition: all 0.2s ease;
    flex-shrink: 0;
}
.filter-checkbox-item input:checked + .custom-checkbox {
    background: var(--turf-teal, #EC4899);
    border-color: var(--turf-teal, #EC4899);
}
.filter-checkbox-item.active .filter-label-text {
    font-weight: 600;
    color: var(--turf-teal, #EC4899);
}
.filter-label-text {
    font-size: 14.5px;
    color: var(--ink, #181824);
}
.filter-locations-tags {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
}
.loc-tag {
    font-size: 12.5px;
    background: var(--bg-page, #FAF7F9);
    border: 1px solid var(--line, #F1E4EC);
    color: var(--ink-soft, #5A5A72);
    padding: 5px 12px;
    border-radius: 20px;
    display: inline-flex;
    align-items: center;
    gap: 5px;
}
.sidebar-help-box {
    background: linear-gradient(135deg, #FDF2F8 0%, #FCE7F3 100%);
    border: 1px solid #F9A8D4;
    border-radius: 20px;
    padding: 22px;
    text-align: center;
}
.help-icon {
    width: 48px;
    height: 48px;
    border-radius: 50%;
    background: #fff;
    color: var(--turf-teal, #EC4899);
    font-size: 24px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 12px;
    box-shadow: 0 4px 12px rgba(236, 72, 153, 0.14);
}
.sidebar-help-box h4 {
    margin: 0 0 6px;
    font-size: 16px;
    color: var(--turf-dark, #181824);
}
.sidebar-help-box p {
    font-size: 13px;
    color: var(--ink-soft, #5A5A72);
    margin: 0 0 14px;
    line-height: 1.5;
}
.help-phone-btn {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 14px;
    font-weight: 700;
    color: var(--turf-teal, #EC4899);
    background: #fff;
    padding: 8px 18px;
    border-radius: 999px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.06);
}
.courts-content {
    flex: 1;
    min-width: 0;
}
.results-bar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 24px;
    padding-bottom: 16px;
    border-bottom: 1px solid var(--line, #F1E4EC);
}
.results-count {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    font-size: 16px;
    font-weight: 700;
    color: var(--turf-dark, #181824);
}
.results-count i {
    color: #EC4899;
}
.results-keyword {
    margin-left: 12px;
    font-size: 14px;
    color: var(--ink-soft, #5A5A72);
}
.sport-group-section {
    margin-bottom: 40px;
}
.sport-group-title-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 20px;
}
.sport-title-wrap {
    display: flex;
    align-items: center;
    gap: 10px;
}
.sport-pill-icon {
    width: 32px;
    height: 32px;
    border-radius: 8px;
    background: #FCE7F3;
    color: #DB2777;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
}
.sport-group-title-row h3 {
    margin: 0;
    font-size: 22px;
    font-weight: 700;
    color: var(--turf-dark, #181824);
}
.sport-item-count {
    font-size: 13.5px;
    color: var(--ink-muted, #8E8EA8);
    font-weight: 600;
    background: var(--surface, #fff);
    border: 1px solid var(--line, #F1E4EC);
    padding: 3px 12px;
    border-radius: 20px;
}
.courts-modern-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(270px, 1fr));
    gap: 24px;
}
.court-card-modern {
    background: var(--surface, #ffffff);
    border: 1px solid var(--line, #F1E4EC);
    border-radius: 18px;
    overflow: hidden;
    display: flex;
    flex-direction: column;
    transition: all 0.25s ease;
    cursor: pointer;
    box-shadow: 0 4px 16px rgba(24, 24, 36, 0.04);
}
.court-card-modern:hover {
    transform: translateY(-4px);
    box-shadow: 0 16px 32px rgba(236, 72, 153, 0.14);
    border-color: #F472B6;
}
.card-media-wrapper {
    height: 180px;
    position: relative;
    background: #FDF2F8;
    overflow: hidden;
}
.card-media-wrapper img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.35s ease;
}
.court-card-modern:hover .card-media-wrapper img {
    transform: scale(1.05);
}
.media-placeholder {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 48px;
    color: var(--turf-teal, #EC4899);
}
.court-badges {
    position: absolute;
    inset: 12px 12px auto 12px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    pointer-events: none;
}
.badge-sport {
    background: linear-gradient(135deg, #FF2E93 0%, #DB2777 100%);
    color: #FFFFFF;
    font-size: 12px;
    font-weight: 700;
    padding: 4px 10px;
    border-radius: 999px;
    box-shadow: 0 2px 8px rgba(236,72,153,0.3);
}
.badge-status {
    font-size: 11.5px;
    font-weight: 600;
    padding: 4px 9px;
    border-radius: 999px;
    display: inline-flex;
    align-items: center;
    gap: 5px;
    backdrop-filter: blur(4px);
}
.status-open {
    background: rgba(24, 24, 36, 0.85);
    color: #fff;
}
.status-busy {
    background: rgba(234, 88, 12, 0.9);
    color: #fff;
}
.status-dot {
    width: 6px;
    height: 6px;
    border-radius: 50%;
    background: #FF2E93;
}
.card-content {
    padding: 18px;
    display: flex;
    flex-direction: column;
    flex: 1;
}
.court-card-title {
    margin: 0 0 6px;
    font-size: 16.5px;
    font-weight: 700;
    color: var(--turf-dark, #181824);
    line-height: 1.35;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.court-card-location {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 13px;
    color: var(--ink-soft, #5A5A72);
    margin-bottom: 14px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.court-card-location i {
    flex-shrink: 0;
    color: var(--turf-teal, #EC4899);
}
.court-features {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 16px;
}
.feature-tag {
    font-size: 12px;
    padding: 4px 8px;
    border-radius: 6px;
    background: var(--bg-page, #FAF7F9);
    color: var(--ink-soft, #5A5A72);
    display: inline-flex;
    align-items: center;
    gap: 4px;
}
.rating-tag {
    background: #FEF3C7;
    color: #B45309;
    font-weight: 600;
}
.card-footer-action {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-top: 14px;
    border-top: 1px solid var(--line, #F1E4EC);
    margin-top: auto;
}
.price-box {
    display: flex;
    flex-direction: column;
}
.price-label {
    font-size: 11px;
    color: var(--ink-muted, #8E8EA8);
    text-transform: uppercase;
}
.price-val {
    font-size: 17px;
    font-weight: 800;
    color: #EC4899;
    font-family: 'Oswald', sans-serif;
}
.price-val small {
    font-size: 12px;
    color: var(--ink-soft, #5A5A72);
    font-family: inherit;
    font-weight: 500;
}
.btn-view-court {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: linear-gradient(135deg, #FF2E93 0%, #DB2777 100%);
    color: #fff;
    padding: 8px 16px;
    border-radius: 999px;
    font-size: 13px;
    font-weight: 700;
    transition: all 0.2s ease;
    box-shadow: 0 4px 10px rgba(236, 72, 153, 0.28);
}
.btn-view-court:hover {
    background: linear-gradient(135deg, #E01A70 0%, #BE185D 100%);
    transform: translateX(2px);
}
.empty-state-card {
    background: var(--surface, #fff);
    border: 1px dashed var(--line, #F1E4EC);
    border-radius: 20px;
    padding: 60px 20px;
    text-align: center;
}
.empty-icon {
    font-size: 48px;
    color: var(--ink-muted, #988F9E);
    margin-bottom: 12px;
}
.empty-state-card h3 {
    margin: 0 0 8px;
    font-size: 18px;
    color: var(--turf-dark, #181824);
}
.empty-state-card p {
    color: var(--ink-soft, #5A5565);
    font-size: 14px;
    margin: 0 0 20px;
}
.btn-empty-reset {
    display: inline-block;
    background: linear-gradient(135deg, #FF2E93 0%, #DB2777 100%);
    color: #fff;
    padding: 10px 22px;
    border-radius: 999px;
    font-weight: 700;
    font-size: 14px;
    box-shadow: 0 4px 14px rgba(236, 72, 153, 0.35);
}
.courts-pagination {
    margin-top: 30px;
    display: flex;
    justify-content: center;
}

@media (max-width: 992px) {
    .courts-layout {
        flex-direction: column;
    }
    .courts-sidebar {
        width: 100%;
    }
    .filter-options-list {
        flex-direction: row;
        flex-wrap: wrap;
    }
    .filter-checkbox-item {
        background: var(--bg-page);
    }
}
@media (max-width: 640px) {
    .courts-hero h1 {
        font-size: 28px;
    }
    .courts-search-box {
        flex-direction: column;
        padding: 12px;
        gap: 10px;
    }
    .btn-search-submit {
        width: 100%;
        justify-content: center;
    }
}
</style>
@endpush
@endsection
