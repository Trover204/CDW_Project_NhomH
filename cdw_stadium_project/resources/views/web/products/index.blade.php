@extends('web.layouts.app')

@section('title', 'Cửa Hàng Merch - Áo Đấu Bóng Đá Chính Hãng')

@push('styles')
<style>
    /* =====================================================
       MERCH STORE CUSTOM STYLES
    ===================================================== */
    .merch-container {
        max-width: 1240px;
        margin: 32px auto 60px;
        padding: 0 20px;
    }

    /* Hero Banner */
    .merch-hero {
        position: relative;
        background: linear-gradient(135deg, #092820 0%, #0D382E 40%, #178A4C 100%);
        border-radius: 24px;
        padding: 48px 40px;
        color: #FFFFFF;
        overflow: hidden;
        margin-bottom: 28px;
        box-shadow: 0 12px 36px rgba(13, 56, 46, 0.25);
    }
    .merch-hero::before {
        content: "";
        position: absolute;
        top: -60px;
        right: -60px;
        width: 320px;
        height: 320px;
        background: radial-gradient(circle, rgba(210, 248, 90, 0.18) 0%, rgba(210, 248, 90, 0) 70%);
        border-radius: 50%;
        pointer-events: none;
    }
    .merch-hero-content {
        position: relative;
        z-index: 2;
        max-width: 720px;
    }
    .merch-hero-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: rgba(255, 255, 255, 0.14);
        backdrop-filter: blur(8px);
        color: #D2F85A;
        font-size: 12.5px;
        font-weight: 700;
        letter-spacing: 0.8px;
        text-transform: uppercase;
        padding: 6px 14px;
        border-radius: 30px;
        margin-bottom: 14px;
        border: 1px solid rgba(210, 248, 90, 0.3);
    }
    .merch-hero-title {
        font-family: 'Oswald', sans-serif;
        font-size: 38px;
        font-weight: 700;
        line-height: 1.15;
        margin: 0 0 12px 0;
        color: #FFFFFF;
        letter-spacing: 0.5px;
    }
    .merch-hero-desc {
        font-size: 16px;
        line-height: 1.6;
        color: rgba(255, 255, 255, 0.9);
        margin: 0;
    }
    .merch-hero-bg-icon {
        position: absolute;
        right: 24px;
        bottom: -20px;
        font-size: 220px;
        color: rgba(255, 255, 255, 0.07);
        pointer-events: none;
        line-height: 1;
    }

    /* Trust / Value Props Bar */
    .merch-features {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 16px;
        margin-bottom: 28px;
    }
    .merch-feature-item {
        background: var(--bg-white, #FFFFFF);
        border: 1px solid var(--line, #E3ECE6);
        border-radius: 16px;
        padding: 16px 18px;
        display: flex;
        align-items: center;
        gap: 14px;
        transition: all 0.2s ease;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.02);
    }
    .merch-feature-item:hover {
        transform: translateY(-2px);
        border-color: #178A4C;
        box-shadow: 0 6px 16px rgba(23, 138, 76, 0.08);
    }
    .merch-feature-icon {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        background: #F0FDF4;
        color: #178A4C;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
        flex-shrink: 0;
    }
    .merch-feature-text h4 {
        margin: 0 0 2px 0;
        font-size: 14px;
        font-weight: 700;
        color: var(--ink, #11201B);
    }
    .merch-feature-text p {
        margin: 0;
        font-size: 12px;
        color: var(--ink-soft, #5E726A);
    }

    /* Filter Toolbar */
    .merch-filter-card {
        background: var(--bg-white, #FFFFFF);
        border: 1px solid var(--line, #E3ECE6);
        border-radius: 20px;
        padding: 22px;
        margin-bottom: 24px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.03);
    }
    .merch-filter-row {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        align-items: center;
    }
    .merch-search-wrap {
        flex: 1 1 280px;
        position: relative;
    }
    .merch-search-icon {
        position: absolute;
        left: 16px;
        top: 50%;
        transform: translateY(-50%);
        color: #879C94;
        font-size: 18px;
    }
    .merch-search-input {
        width: 100%;
        height: 46px;
        padding: 0 16px 0 46px;
        border: 1.5px solid var(--line, #E3ECE6);
        border-radius: 12px;
        font-size: 14.5px;
        background: var(--bg-white, #FFFFFF);
        color: var(--ink, #11201B);
        outline: none;
        transition: border-color 0.2s, box-shadow 0.2s;
    }
    .merch-search-input:focus {
        border-color: #178A4C;
        box-shadow: 0 0 0 3px rgba(23, 138, 76, 0.12);
    }
    .merch-select-wrap {
        flex: 0 1 200px;
    }
    .merch-select {
        width: 100%;
        height: 46px;
        padding: 0 14px;
        border: 1.5px solid var(--line, #E3ECE6);
        border-radius: 12px;
        font-size: 14px;
        background: var(--bg-white, #FFFFFF);
        color: var(--ink, #11201B);
        outline: none;
        cursor: pointer;
        transition: border-color 0.2s;
    }
    .merch-select:focus {
        border-color: #178A4C;
    }
    .merch-btn-filter {
        height: 46px;
        padding: 0 22px;
        background: #178A4C;
        color: #FFFFFF;
        border: none;
        border-radius: 12px;
        font-size: 14.5px;
        font-weight: 600;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.2s;
    }
    .merch-btn-filter:hover {
        background: #12723E;
        box-shadow: 0 4px 12px rgba(23, 138, 76, 0.25);
    }
    .merch-btn-reset {
        height: 46px;
        padding: 0 18px;
        background: var(--bg-page, #F6F8F6);
        color: var(--ink-soft, #5E726A);
        border: 1.5px solid var(--line, #E3ECE6);
        border-radius: 12px;
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        text-decoration: none;
        transition: all 0.2s;
    }
    .merch-btn-reset:hover {
        background: var(--line, #E3ECE6);
        color: var(--ink, #11201B);
    }

    /* Quick Filter Chips */
    .merch-chips-wrap {
        display: flex;
        gap: 8px;
        overflow-x: auto;
        padding-top: 14px;
        margin-top: 14px;
        border-top: 1px dashed var(--line, #E3ECE6);
        scrollbar-width: thin;
    }
    .merch-chip {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 14px;
        border-radius: 30px;
        font-size: 13px;
        font-weight: 600;
        white-space: nowrap;
        background: var(--bg-page, #F6F8F6);
        color: var(--ink-soft, #5E726A);
        border: 1px solid var(--line, #E3ECE6);
        text-decoration: none;
        transition: all 0.2s;
    }
    .merch-chip:hover {
        border-color: #178A4C;
        color: #178A4C;
        background: #F0FDF4;
    }
    .merch-chip.active {
        background: #178A4C;
        color: #FFFFFF;
        border-color: #178A4C;
        box-shadow: 0 2px 8px rgba(23, 138, 76, 0.25);
    }

    /* Product Grid & Cards */
    .merch-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(270px, 1fr));
        gap: 24px;
        margin-bottom: 40px;
    }
    .merch-card {
        background: var(--bg-white, #FFFFFF);
        border: 1px solid var(--line, #E3ECE6);
        border-radius: 20px;
        padding: 18px;
        display: flex;
        flex-direction: column;
        position: relative;
        transition: all 0.3s cubic-bezier(0.165, 0.84, 0.44, 1);
        overflow: hidden;
    }
    .merch-card:hover {
        transform: translateY(-6px);
        border-color: rgba(23, 138, 76, 0.4);
        box-shadow: 0 16px 32px rgba(13, 56, 46, 0.1);
    }

    /* Card Image */
    .merch-card-img-wrap {
        background: linear-gradient(180deg, #F9FAFB 0%, #EEF2F6 100%);
        border-radius: 14px;
        height: 240px;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        position: relative;
        margin-bottom: 16px;
        border: 1px solid rgba(0, 0, 0, 0.03);
    }
    [data-theme="dark"] .merch-card-img-wrap {
        background: linear-gradient(180deg, #242424 0%, #1a1a1a 100%);
        border-color: #333333;
    }
    .merch-card-img {
        max-width: 86%;
        max-height: 86%;
        object-fit: contain;
        transition: transform 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
        filter: drop-shadow(0 8px 16px rgba(0, 0, 0, 0.08));
    }
    .merch-card:hover .merch-card-img {
        transform: scale(1.08);
    }

    /* Card Floating Badges */
    .merch-badge-season {
        position: absolute;
        top: 12px;
        left: 12px;
        background: rgba(17, 32, 27, 0.85);
        backdrop-filter: blur(6px);
        color: #FFFFFF;
        font-size: 11px;
        font-weight: 700;
        padding: 4px 9px;
        border-radius: 6px;
        letter-spacing: 0.3px;
        z-index: 2;
    }
    .merch-badge-stock {
        position: absolute;
        top: 12px;
        right: 12px;
        font-size: 11.5px;
        font-weight: 600;
        padding: 4px 10px;
        border-radius: 20px;
        z-index: 2;
    }
    .merch-badge-stock.in-stock {
        background: #DCFCE7;
        color: #15803D;
        border: 1px solid #BBF7D0;
    }
    .merch-badge-stock.out-stock {
        background: #FEE2E2;
        color: #B91C1C;
        border: 1px solid #FECACA;
    }

    /* Card Details */
    .merch-card-team {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        font-size: 12.5px;
        font-weight: 600;
        color: #178A4C;
        background: #F0FDF4;
        padding: 3px 10px;
        border-radius: 6px;
        border: 1px solid #DCFCE7;
        margin-bottom: 8px;
        align-self: flex-start;
    }
    [data-theme="dark"] .merch-card-team {
        background: rgba(23, 138, 76, 0.15);
        border-color: rgba(23, 138, 76, 0.3);
        color: #4ADE80;
    }
    .merch-card-title {
        font-size: 16.5px;
        font-weight: 700;
        color: var(--ink, #11201B);
        line-height: 1.4;
        margin-bottom: 12px;
        flex-grow: 1;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        min-height: 46px;
    }
    .merch-card-pricing {
        display: flex;
        align-items: baseline;
        justify-content: space-between;
        padding-top: 10px;
        margin-bottom: 14px;
        border-top: 1px dashed var(--line, #E3ECE6);
    }
    .merch-card-price {
        font-size: 22px;
        font-weight: 800;
        color: #EA580C;
        font-family: 'Oswald', sans-serif;
        letter-spacing: 0.3px;
    }
    .merch-card-tag {
        font-size: 12px;
        font-weight: 500;
        color: var(--ink-muted, #879C94);
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }

    /* Card Action Buttons */
    .merch-btn-buy {
        width: 100%;
        height: 42px;
        border-radius: 10px;
        background: linear-gradient(135deg, #178A4C 0%, #12723E 100%);
        color: #FFFFFF;
        font-size: 14.5px;
        font-weight: 600;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        text-decoration: none;
        border: none;
        cursor: pointer;
        transition: all 0.2s;
        box-shadow: 0 2px 8px rgba(23, 138, 76, 0.2);
    }
    .merch-btn-buy:hover {
        background: linear-gradient(135deg, #137740 0%, #0D562E 100%);
        box-shadow: 0 6px 16px rgba(23, 138, 76, 0.35);
        transform: translateY(-1px);
        color: #FFFFFF;
    }
    .merch-btn-login {
        width: 100%;
        height: 42px;
        border-radius: 10px;
        background: var(--bg-white, #FFFFFF);
        color: #178A4C;
        border: 1.5px solid #178A4C;
        font-size: 14px;
        font-weight: 600;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        text-decoration: none;
        transition: all 0.2s;
    }
    .merch-btn-login:hover {
        background: #F0FDF4;
        color: #12723E;
        transform: translateY(-1px);
    }
    .merch-btn-disabled {
        width: 100%;
        height: 42px;
        border-radius: 10px;
        background: var(--bg-page, #F3F4F6);
        color: var(--ink-muted, #9CA3AF);
        border: 1px solid var(--line, #E5E7EB);
        font-size: 14px;
        font-weight: 600;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        cursor: not-allowed;
    }

    /* PAGINATION OVERRIDES (GUARANTEES PERFECT STYLING) */
    .admin-pagination,
    nav.admin-pagination {
        display: flex !important;
        flex-direction: row !important;
        align-items: center !important;
        justify-content: center !important;
        flex-wrap: wrap !important;
        gap: 16px !important;
        padding: 16px 0 !important;
        margin: 20px auto 0 !important;
        list-style: none !important;
    }
    .admin-pagination .pagination-info {
        font-size: 13.5px !important;
        color: var(--ink-soft, #5E726A) !important;
        font-weight: 600 !important;
        background: var(--bg-white, #FFFFFF) !important;
        padding: 8px 18px !important;
        border-radius: 30px !important;
        border: 1px solid var(--line, #E3ECE6) !important;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.03) !important;
        white-space: nowrap !important;
        margin: 0 !important;
    }
    .admin-pagination .pagination-list,
    ul.pagination-list {
        display: inline-flex !important;
        flex-direction: row !important;
        align-items: center !important;
        gap: 6px !important;
        list-style: none !important;
        margin: 0 !important;
        padding: 0 !important;
    }
    .admin-pagination .pagination-list li,
    .admin-pagination .page-item {
        display: inline-block !important;
        list-style: none !important;
        margin: 0 !important;
        padding: 0 !important;
    }
    .admin-pagination .pagination-list li::before,
    .admin-pagination .pagination-list li::marker {
        display: none !important;
        content: "" !important;
    }
    .admin-pagination .page-link {
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        min-width: 42px !important;
        height: 42px !important;
        padding: 0 14px !important;
        border-radius: 12px !important;
        font-size: 14.5px !important;
        font-weight: 600 !important;
        color: var(--ink, #11201B) !important;
        background: var(--bg-white, #FFFFFF) !important;
        border: 1.5px solid var(--line, #E3ECE6) !important;
        text-decoration: none !important;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1) !important;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04) !important;
    }
    .admin-pagination .page-link:hover {
        color: #178A4C !important;
        border-color: #178A4C !important;
        background: var(--bg-white, #FFFFFF) !important;
        transform: translateY(-2px) !important;
        box-shadow: 0 4px 12px rgba(23, 138, 76, 0.18) !important;
    }
    .admin-pagination .page-item.active .page-link {
        color: #FFFFFF !important;
        background: linear-gradient(135deg, #178A4C 0%, #0D562E 100%) !important;
        border-color: #178A4C !important;
        font-weight: 700 !important;
        box-shadow: 0 4px 14px rgba(23, 138, 76, 0.35) !important;
        transform: translateY(-1px) !important;
    }
    .admin-pagination .page-item.disabled .page-link {
        color: var(--ink-muted, #9CA3AF) !important;
        background: var(--bg-page, #F3F4F6) !important;
        border-color: var(--line, #E5E7EB) !important;
        cursor: not-allowed !important;
        opacity: 0.55 !important;
        box-shadow: none !important;
        transform: none !important;
    }
    .admin-pagination .page-link.dots {
        border: none !important;
        background: transparent !important;
        color: var(--ink-muted, #9CA3AF) !important;
        cursor: default !important;
        box-shadow: none !important;
    }

    /* Responsive */
    @media (max-width: 992px) {
        .merch-features {
            grid-template-columns: repeat(2, 1fr);
        }
        .merch-hero-title {
            font-size: 30px;
        }
    }
    @media (max-width: 640px) {
        .merch-hero {
            padding: 32px 24px;
        }
        .merch-hero-title {
            font-size: 26px;
        }
        .merch-features {
            grid-template-columns: 1fr;
        }
        .merch-grid {
            grid-template-columns: 1fr;
        }
        .merch-select-wrap {
            flex: 1 1 100%;
        }
    }
</style>
@endpush

@section('content')

<div class="merch-container">

    {{-- Flash Notifications --}}
    @if(session('success'))
        <div style="background: #178A4C; color: white; padding: 14px 22px; border-radius: 14px; margin-bottom: 24px; text-align: center; font-weight: 600; display: flex; align-items: center; justify-content: center; gap: 8px; box-shadow: 0 4px 16px rgba(23,138,76,0.2);">
            <i class="ti ti-circle-check" style="font-size: 20px;"></i>
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div style="background: #E53E3E; color: white; padding: 14px 22px; border-radius: 14px; margin-bottom: 24px; text-align: center; font-weight: 600; display: flex; align-items: center; justify-content: center; gap: 8px; box-shadow: 0 4px 16px rgba(229,62,62,0.2);">
            <i class="ti ti-alert-triangle" style="font-size: 20px;"></i>
            {{ session('error') }}
        </div>
    @endif

    {{-- Hero Banner --}}
    <div class="merch-hero">
        <div class="merch-hero-content">
            <div class="merch-hero-badge">
                <i class="ti ti-badge-check"></i> Bộ Sưu Tập Chính Hãng 2024
            </div>
            <h1 class="merch-hero-title">
                Sản phẩm chính hãng (Merch)
            </h1>
            <p class="merch-hero-desc">
                Cung cấp các mẫu áo đấu bóng đá câu lạc bộ hàng đầu thế giới, đội tuyển quốc gia và phụ kiện thể thao chính hãng, thoáng mát, thấm hút mồ hôi tối ưu.
            </p>
        </div>
        <div class="merch-hero-bg-icon">
            <i class="ti ti-shirt"></i>
        </div>
    </div>

    {{-- Trust / Value Proposition Bar --}}
    <div class="merch-features">
        <div class="merch-feature-item">
            <div class="merch-feature-icon">
                <i class="ti ti-truck-delivery"></i>
            </div>
            <div class="merch-feature-text">
                <h4>Giao hàng hỏa tốc</h4>
                <p>Toàn quốc từ 2 - 3 ngày</p>
            </div>
        </div>
        <div class="merch-feature-item">
            <div class="merch-feature-icon">
                <i class="ti ti-shield-check"></i>
            </div>
            <div class="merch-feature-text">
                <h4>Cam kết 100%</h4>
                <p>Chính hãng, đầy đủ tem mác</p>
            </div>
        </div>
        <div class="merch-feature-item">
            <div class="merch-feature-icon">
                <i class="ti ti-rotate-clockwise"></i>
            </div>
            <div class="merch-feature-text">
                <h4>Đổi size miễn phí</h4>
                <p>Hỗ trợ đổi trong 7 ngày</p>
            </div>
        </div>
        <div class="merch-feature-item">
            <div class="merch-feature-icon">
                <i class="ti ti-credit-card"></i>
            </div>
            <div class="merch-feature-text">
                <h4>Thanh toán an toàn</h4>
                <p>MoMo, Visa, PayPal linh hoạt</p>
            </div>
        </div>
    </div>

    {{-- Filter & Search Toolbar --}}
    <div class="merch-filter-card">
        <form action="{{ route('products.index') }}" method="GET">
            <div class="merch-filter-row">
                {{-- Search Box --}}
                <div class="merch-search-wrap">
                    <i class="ti ti-search merch-search-icon"></i>
                    <input type="text" name="search" value="{{ request('search') }}"
                           class="merch-search-input"
                           placeholder="Tìm theo tên áo, câu lạc bộ, giải đấu...">
                </div>

                {{-- Team Filter Dropdown --}}
                <div class="merch-select-wrap">
                    <select name="team" id="merch-filter-team" class="merch-select">
                        <option value="">-- Tất cả CLB / ĐTQG --</option>
                        @if(isset($teams))
                            @foreach($teams as $t)
                                <option value="{{ $t }}" {{ request('team') === $t ? 'selected' : '' }}>{{ $t }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>

                {{-- Sort Dropdown --}}
                <div class="merch-select-wrap">
                    <select name="sort" id="merch-filter-sort" class="merch-select">
                        <option value="newest" {{ request('sort') === 'newest' ? 'selected' : '' }}>Mới nhất</option>
                        <option value="price_asc" {{ request('sort') === 'price_asc' ? 'selected' : '' }}>Giá: Thấp đến Cao</option>
                        <option value="price_desc" {{ request('sort') === 'price_desc' ? 'selected' : '' }}>Giá: Cao đến Thấp</option>
                        <option value="name_asc" {{ request('sort') === 'name_asc' ? 'selected' : '' }}>Tên: A &rarr; Z</option>
                    </select>
                </div>

                {{-- Buttons --}}
                <div style="display: flex; gap: 8px;">
                    <button type="submit" class="merch-btn-filter">
                        <i class="ti ti-filter"></i> Lọc
                    </button>
                    @if(request('search') || request('team') || request('sort'))
                        <a href="{{ route('products.index') }}" class="merch-btn-reset">
                            <i class="ti ti-x"></i> Xóa lọc
                        </a>
                    @endif
                </div>
            </div>

            {{-- Quick Filter Chips for Popular Teams --}}
            <div class="merch-chips-wrap">
                <a href="{{ route('products.index', array_merge(request()->except('team', 'page'), [])) }}"
                   class="merch-chip {{ !request('team') ? 'active' : '' }}">
                    Tất cả ({{ $products->total() }})
                </a>
                @php
                    $popularTeams = [
                        'ĐTQG Việt Nam', 'Real Madrid', 'Manchester United', 'Arsenal FC',
                        'FC Barcelona', 'Manchester City', 'Liverpool FC', 'Bayern Munich',
                        'Paris Saint-Germain', 'Juventus', 'ĐTQG Argentina', 'ĐTQG Bồ Đào Nha',
                        'Inter Miami CF', 'Al Nassr FC'
                    ];
                @endphp
                @foreach($popularTeams as $pTeam)
                    @if(isset($teams) && $teams->contains($pTeam))
                        <a href="{{ route('products.index', array_merge(request()->except('page'), ['team' => $pTeam])) }}"
                           class="merch-chip {{ request('team') === $pTeam ? 'active' : '' }}">
                            {{ $pTeam }}
                        </a>
                    @endif
                @endforeach
            </div>
        </form>
    </div>

    {{-- Result stats --}}
    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px; font-size: 14px; color: var(--ink-soft, #5E726A);">
        <div>
            Hiển thị <strong>{{ $products->firstItem() ?? 0 }}-{{ $products->lastItem() ?? 0 }}</strong> trên tổng số <strong>{{ $products->total() }}</strong> sản phẩm
            @if(request('team'))
                &bull; Đội bóng: <span style="color: #178A4C; font-weight: 700;">{{ request('team') }}</span>
            @endif
            @if(request('search'))
                &bull; Từ khóa: "<span style="color: #178A4C; font-weight: 700;">{{ request('search') }}</span>"
            @endif
        </div>
    </div>

    {{-- Product Cards Grid --}}
    <div class="merch-grid">
        @forelse($products as $product)
            <div class="merch-card">

                {{-- Image Box with Badges --}}
                <div class="merch-card-img-wrap">
                    @if($product->season)
                        <span class="merch-badge-season">{{ $product->season }}</span>
                    @endif

                    @if($product->is_in_stock && $product->stock_quantity > 0)
                        <span class="merch-badge-stock in-stock">
                            <i class="ti ti-check" style="font-size: 11px;"></i> Còn {{ $product->stock_quantity }}
                        </span>
                    @else
                        <span class="merch-badge-stock out-stock">
                            <i class="ti ti-x" style="font-size: 11px;"></i> Hết hàng
                        </span>
                    @endif

                    @if($product->image_url)
                        <img src="{{ $product->image_url }}" alt="{{ $product->name }}"
                             class="merch-card-img"
                             loading="lazy">
                    @else
                        <div style="display:flex; flex-direction:column; align-items:center; color:#9CA3AF; gap:6px;">
                            <i class="ti ti-shirt" style="font-size:48px; opacity:0.4;"></i>
                            <span style="font-size:12px;">Chưa có ảnh</span>
                        </div>
                    @endif
                </div>

                {{-- Team Badge --}}
                @if($product->team)
                    <div class="merch-card-team">
                        <i class="ti ti-shield"></i> {{ $product->team }}
                    </div>
                @endif

                {{-- Product Title --}}
                <h3 class="merch-card-title" title="{{ $product->name }}">
                    {{ $product->name }}
                </h3>

                {{-- Price & Guarantee Tag --}}
                <div class="merch-card-pricing">
                    <span class="merch-card-price">
                        {{ number_format($product->cost_price, 0, ',', '.') }}₫
                    </span>
                    <span class="merch-card-tag">
                        <i class="ti ti-rosette-discount-check" style="color:#178A4C; font-size:14px;"></i> Chính hãng
                    </span>
                </div>

                {{-- Purchase CTA --}}
                <div>
                    @auth
                        @if($product->is_in_stock && $product->stock_quantity > 0)
                            <a href="{{ route('products.checkout', $product->id) }}" class="merch-btn-buy">
                                <i class="ti ti-shopping-cart"></i> Mua ngay
                            </a>
                        @else
                            <button disabled class="merch-btn-disabled">
                                <i class="ti ti-ban"></i> Tạm hết hàng
                            </button>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="merch-btn-login">
                            <i class="ti ti-login"></i> Đăng nhập để mua
                        </a>
                    @endauth
                </div>
            </div>
        @empty
            <div style="grid-column: 1 / -1; text-align: center; padding: 70px 20px; background: var(--bg-white, #FFFFFF); border: 2px dashed var(--line, #D1D5DB); border-radius: 20px;">
                <div style="width: 72px; height: 72px; border-radius: 50%; background: #F3F4F6; color: #9CA3AF; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px; font-size: 36px;">
                    <i class="ti ti-shopping-bag-x"></i>
                </div>
                <h3 style="font-size: 19px; font-weight: 700; color: var(--ink, #11201B); margin: 0 0 8px;">Không tìm thấy sản phẩm phù hợp</h3>
                <p style="font-size: 14.5px; color: var(--ink-soft, #5E726A); margin: 0 0 20px;">Hãy thử điều chỉnh lại bộ lọc hoặc tìm kiếm với từ khóa khác.</p>
                <a href="{{ route('products.index') }}" class="merch-btn-filter" style="display: inline-flex; text-decoration: none;">
                    <i class="ti ti-refresh"></i> Khám phá tất cả sản phẩm
                </a>
            </div>
        @endforelse
    </div>

    {{-- Pagination Bar --}}
    @if($products->hasPages())
        <div style="margin-top: 40px; margin-bottom: 20px; display: flex; justify-content: center;">
            {{ $products->links() }}
        </div>
    @endif

</div>

@push('scripts')
<script nonce="{{ $cspNonce ?? '' }}">
    document.addEventListener('DOMContentLoaded', function() {
        const teamSelect = document.getElementById('merch-filter-team');
        const sortSelect = document.getElementById('merch-filter-sort');

        if (teamSelect) {
            teamSelect.addEventListener('change', function() {
                this.form.submit();
            });
        }

        if (sortSelect) {
            sortSelect.addEventListener('change', function() {
                this.form.submit();
            });
        }
    });
</script>
@endpush

@endsection
