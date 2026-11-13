@extends('web.layouts.app')

@section('title', __('Kết quả tìm kiếm') . ' - ' . config('app.name', 'Sân Nhóm H'))

@section('content')
<div class="search-page-wrapper">
    {{-- Search Page Header --}}
    <div class="search-header-hero">
        <div class="wrap">
            <div class="search-hero-inner">
                <span class="search-eyebrow"><i class="ti ti-search"></i> {{ __('Kết quả tra cứu') }}</span>
                <h1>{{ __('Tìm kiếm:') }} <span class="highlight-query">"{{ $keyword }}"</span></h1>
                <p>{{ __('Dưới đây là các cụm sân thể thao, sản phẩm chính hãng và bài viết tin tức liên quan phù hợp nhất với từ khóa của bạn.') }}</p>

                {{-- Interactive Search Box --}}
                <form action="{{ route('courts.search') }}" method="GET" class="search-page-form">
                    @if(isset($activeType) && $activeType !== 'all')
                        <input type="hidden" name="type" value="{{ $activeType }}">
                    @endif
                    <div class="search-input-field">
                        <i class="ti ti-search"></i>
                        <input type="text" name="q" value="{{ $keyword ?? request('q') }}" placeholder="{{ __('Nhập tên sân, sản phẩm, địa chỉ hoặc tin tức...') }}" required>
                        @if($keyword)
                            <a href="{{ route('courts.index') }}" class="clear-btn" title="{{ __('Bỏ tìm kiếm') }}"><i class="ti ti-x"></i></a>
                        @endif
                    </div>
                    <button type="submit" class="search-submit-btn">
                        <span>{{ __('Tìm lại') }}</span>
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- Main Results Section --}}
    @php
        $currentType = $activeType ?? request('type', 'all');
        $courtsTotal = $courts->total();
        $productsTotal = isset($products) ? $products->total() : 0;
        $newsTotal = isset($news) ? $news->total() : 0;
        $allTotal = $courtsTotal + $productsTotal + $newsTotal;
    @endphp
    <div class="wrap search-content-wrap">
        {{-- Tabs / Navigation Pills --}}
        <div class="search-stats-summary">
            <a href="{{ route('courts.search', array_filter(['q' => $keyword, 'type' => 'all', 'mode' => $searchMode !== 'elasticsearch' ? $searchMode : null])) }}"
               class="stat-pill {{ $currentType === 'all' ? 'active' : '' }}">
                <i class="ti ti-apps"></i>
                <span>{{ __('Tất cả') }} ({{ $allTotal }})</span>
            </a>
            <a href="{{ route('courts.search', array_filter(['q' => $keyword, 'type' => 'courts', 'mode' => $searchMode !== 'elasticsearch' ? $searchMode : null])) }}"
               class="stat-pill {{ $currentType === 'courts' ? 'active' : '' }}">
                <i class="ti ti-trophy"></i>
                <span>{{ __('Sân thể thao') }} ({{ $courtsTotal }})</span>
            </a>
            @if(isset($products))
                <a href="{{ route('courts.search', array_filter(['q' => $keyword, 'type' => 'products', 'mode' => $searchMode !== 'elasticsearch' ? $searchMode : null])) }}"
                   class="stat-pill {{ $currentType === 'products' ? 'active' : '' }}">
                    <i class="ti ti-shirt"></i>
                    <span>{{ __('Sản phẩm') }} ({{ $productsTotal }})</span>
                </a>
            @endif
            @if(isset($news))
                <a href="{{ route('courts.search', array_filter(['q' => $keyword, 'type' => 'news', 'mode' => $searchMode !== 'elasticsearch' ? $searchMode : null])) }}"
                   class="stat-pill {{ $currentType === 'news' ? 'active' : '' }}">
                    <i class="ti ti-news"></i>
                    <span>{{ __('Bài viết tin tức') }} ({{ $newsTotal }})</span>
                </a>
            @endif
        </div>

        {{-- COURTS SECTION --}}
        @if($currentType === 'all' || $currentType === 'courts')
        <div class="search-section-block">
            <div class="section-heading-row">
                <div class="heading-with-icon">
                    <span class="heading-icon-badge"><i class="ti ti-ball-football"></i></span>
                    <h2>{{ __('Sân thể thao phù hợp') }}</h2>
                </div>
                <span class="heading-badge">{{ $courts->total() }} {{ __('kết quả') }}</span>
            </div>

            @if($courts->isEmpty())
                <div class="search-empty-state">
                    <div class="empty-icon"><i class="ti ti-search-off"></i></div>
                    <h3>{{ __('Không tìm thấy sân thể thao nào') }}</h3>
                    <p>{{ __('Không có sân nào phù hợp với từ khóa ":keyword". Hãy thử đổi sang từ khóa khác hoặc duyệt danh mục sân.', ['keyword' => $keyword]) }}</p>
                    <a href="{{ route('courts.index') }}" class="btn-primary-action">{{ __('Xem toàn bộ sân') }}</a>
                </div>
            @else
                <div class="search-courts-grid">
                    @foreach ($courts as $court)
                        @php
                            $primaryImg = $court->images ? ($court->images->firstWhere('is_primary', true) ?? $court->images->first()) : null;
                        @endphp
                        <div class="search-court-card" data-href="{{ route('courts.show', $court->id) }}">
                            <div class="card-media">
                                @if($primaryImg)
                                    <img src="{{ asset($primaryImg->image_path) }}" alt="{{ $court->name }}" loading="lazy">
                                @else
                                    <div class="media-placeholder">
                                        <i class="ti ti-ball-football"></i>
                                    </div>
                                @endif

                                <div class="card-media-tags">
                                    <span class="tag-sport">{{ $court->sportType->name ?? __('Thể thao') }}</span>
                                    @auth
                                        @php
                                            $isFavorited = auth()->user()->favoriteCourts->contains($court->id);
                                        @endphp
                                        <form action="{{ route('favorites.toggle', $court->id) }}" method="POST" class="fav-form-tag">
                                            @csrf
                                            <button type="submit" class="btn-favorite-heart {{ $isFavorited ? 'is-favorited' : '' }}" title="{{ $isFavorited ? __('Bỏ thích') : __('Yêu thích') }}" data-stop-propagation>
                                                <i class="ti {{ $isFavorited ? 'ti-heart-filled' : 'ti-heart' }}"></i>
                                            </button>
                                        </form>
                                    @else
                                        <a href="{{ route('login') }}" class="btn-favorite-heart" title="{{ __('Đăng nhập để lưu yêu thích') }}" data-stop-propagation>
                                            <i class="ti ti-heart"></i>
                                        </a>
                                    @endauth
                                </div>
                            </div>

                            <div class="card-info">
                                <h3 class="court-title">{{ $court->name }}</h3>
                                <div class="court-addr">
                                    <i class="ti ti-map-pin"></i>
                                    <span>{{ $court->facility->address ?? __('Không rõ địa chỉ') }}</span>
                                </div>

                                <div class="court-chips">
                                    <span class="chip-item"><i class="ti ti-clock"></i> {{ $court->capacity ?? 0 }} {{ __('khung giờ trống') }}</span>
                                </div>

                                <div class="card-foot-pricing">
                                    <div class="price-holder">
                                        <span class="price-val">{{ number_format($court->price ?? 120000, 0, ',', '.') }}đ</span>
                                        <span class="price-unit">/{{ __('giờ') }}</span>
                                    </div>
                                    <a href="{{ route('courts.show', $court->id) }}" class="btn-action-book">
                                        <span>{{ __('Xem chi tiết') }}</span>
                                        <i class="ti ti-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="search-pagination">
                    {{ $courts->links() }}
                </div>
            @endif
        </div>
        @endif

        {{-- PRODUCTS SECTION --}}
        @if(isset($products) && ($currentType === 'all' || $currentType === 'products'))
            <div class="search-section-block" style="{{ $currentType === 'all' ? 'margin-top: 50px;' : '' }}">
                <div class="section-heading-row">
                    <div class="heading-with-icon">
                        <span class="heading-icon-badge product-badge"><i class="ti ti-shirt"></i></span>
                        <h2>{{ __('Sản phẩm & Phụ kiện thể thao (Merch)') }}</h2>
                    </div>
                    <div style="display: flex; align-items: center; gap: 12px;">
                        <span class="heading-badge">{{ $products->total() }} {{ __('sản phẩm') }}</span>
                        <a href="{{ route('products.index', ['search' => $keyword]) }}" class="section-view-all-link">
                            <span>{{ __('Cửa hàng Merch') }}</span>
                            <i class="ti ti-arrow-right"></i>
                        </a>
                    </div>
                </div>

                @if($products->isEmpty())
                    <div class="search-empty-state" style="padding: 35px 20px;">
                        <p>{{ __('Không tìm thấy sản phẩm nào phù hợp với từ khóa ":keyword".', ['keyword' => $keyword]) }}</p>
                        <a href="{{ route('products.index') }}" class="btn-primary-action">{{ __('Khám phá cửa hàng Merch') }}</a>
                    </div>
                @else
                    <div class="search-products-grid">
                        @foreach ($products as $product)
                            <div class="search-product-card">
                                <div class="product-media-box">
                                    @if($product->season)
                                        <span class="product-season-pill">{{ $product->season }}</span>
                                    @endif

                                    @if($product->is_in_stock && $product->stock_quantity > 0)
                                        <span class="product-stock-pill in-stock">
                                            <i class="ti ti-check"></i> {{ __('Còn') }} {{ $product->stock_quantity }}
                                        </span>
                                    @else
                                        <span class="product-stock-pill out-stock">
                                            <i class="ti ti-x"></i> {{ __('Hết hàng') }}
                                        </span>
                                    @endif

                                    @if($product->image_url)
                                        <img src="{{ $product->image_url }}" alt="{{ $product->name }}" loading="lazy">
                                    @else
                                        <div class="product-media-placeholder">
                                            <i class="ti ti-shirt"></i>
                                        </div>
                                    @endif
                                </div>

                                <div class="product-body-content">
                                    @if($product->team)
                                        <div class="product-team-badge">
                                            <i class="ti ti-shield"></i>
                                            <span>{{ $product->team }}</span>
                                        </div>
                                    @endif

                                    <h3 class="product-heading" title="{{ $product->name }}">{{ $product->name }}</h3>

                                    @if($product->description)
                                        <p class="product-snippet">{{ $product->description }}</p>
                                    @endif

                                    <div class="product-foot-row">
                                        <div class="product-price-box">
                                            <span class="product-price-val">{{ number_format((float) $product->cost_price, 0, ',', '.') }}đ</span>
                                            <span class="product-authentic-tag"><i class="ti ti-rosette-discount-check"></i> {{ __('Chính hãng') }}</span>
                                        </div>

                                        <div class="product-cta-wrap">
                                            @auth
                                                @if($product->is_in_stock && $product->stock_quantity > 0)
                                                    <a href="{{ route('products.checkout', $product->id) }}" class="btn-action-book product-buy-btn">
                                                        <i class="ti ti-shopping-cart"></i>
                                                        <span>{{ __('Mua ngay') }}</span>
                                                    </a>
                                                @else
                                                    <span class="product-disabled-btn">
                                                        <i class="ti ti-ban"></i>
                                                        <span>{{ __('Tạm hết hàng') }}</span>
                                                    </span>
                                                @endif
                                            @else
                                                <a href="{{ route('login') }}" class="product-login-btn">
                                                    <i class="ti ti-login"></i>
                                                    <span>{{ __('Đăng nhập để mua') }}</span>
                                                </a>
                                            @endauth
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="search-pagination">
                        {{ $products->links() }}
                    </div>
                @endif
            </div>
        @endif

        {{-- NEWS SECTION --}}
        @if(isset($news) && ($currentType === 'all' || $currentType === 'news'))
            <div class="search-section-block" style="{{ $currentType === 'all' ? 'margin-top: 50px;' : '' }}">
                <div class="section-heading-row">
                    <div class="heading-with-icon">
                        <span class="heading-icon-badge news-badge"><i class="ti ti-article"></i></span>
                        <h2>{{ __('Bài viết tin tức liên quan') }}</h2>
                    </div>
                    <span class="heading-badge">{{ $news->total() }} {{ __('bài viết') }}</span>
                </div>

                @if($news->isEmpty())
                    <div class="search-empty-state" style="padding: 35px 20px;">
                        <p>{{ __('Không tìm thấy tin tức nào phù hợp với từ khóa ":keyword".', ['keyword' => $keyword]) }}</p>
                    </div>
                @else
                    <div class="search-news-grid">
                        @foreach ($news as $item)
                            <a href="{{ route('news.show', $item->slug) }}" class="search-news-card">
                                <div class="news-media-box">
                                    @if($item->image)
                                        <img src="{{ $item->image_url }}" alt="{{ $item->title }}" loading="lazy">
                                    @else
                                        <div class="news-media-placeholder">
                                            <i class="ti ti-news"></i>
                                        </div>
                                    @endif
                                    @if($item->category)
                                        <span class="news-cat-pill">{{ $item->category }}</span>
                                    @endif
                                </div>

                                <div class="news-body-content">
                                    <div class="news-date-meta">
                                        <i class="ti ti-calendar"></i>
                                        <span>{{ $item->created_at->format('d/m/Y') }}</span>
                                    </div>
                                    <h3 class="news-heading">{{ $item->title }}</h3>
                                    <p class="news-snippet">{{ $item->summary }}</p>
                                    <span class="news-read-more">
                                        <span>{{ __('Đọc tiếp') }}</span>
                                        <i class="ti ti-chevron-right"></i>
                                    </span>
                                </div>
                            </a>
                        @endforeach
                    </div>

                    <div class="search-pagination">
                        {{ $news->links() }}
                    </div>
                @endif
            </div>
        @endif

    </div>
</div>

@push('styles')
<style>
.search-page-wrapper {
    background: var(--bg-page);
    min-height: 85vh;
    padding-bottom: 70px;
}
.search-header-hero {
    background:
        radial-gradient(circle at 85% 20%, rgba(255, 46, 147, 0.25) 0%, transparent 45%),
        linear-gradient(135deg, #181824 0%, #101018 100%);
    color: #fff;
    padding: 50px 0 45px;
    border-bottom: 2px solid rgba(255, 46, 147, 0.3);
}
.search-hero-inner {
    max-width: 760px;
    margin: 0 auto;
    text-align: center;
}
.search-eyebrow {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: rgba(255, 46, 147, 0.16);
    color: #F472B6;
    border: 1px solid rgba(255, 46, 147, 0.35);
    padding: 5px 14px;
    border-radius: 999px;
    font-size: 13px;
    font-weight: 600;
    margin-bottom: 14px;
}
.search-hero-inner h1 {
    font-family: 'Oswald', sans-serif;
    font-size: 34px;
    font-weight: 700;
    margin: 0 0 10px;
    color: #ffffff;
}
.highlight-query {
    color: #FF2E93;
}
.search-hero-inner p {
    color: #D8CAD3;
    font-size: 15px;
    margin: 0 auto 24px;
    max-width: 600px;
    line-height: 1.6;
}
.search-page-form {
    display: flex;
    align-items: center;
    background: #ffffff;
    border-radius: 14px;
    padding: 6px 6px 6px 16px;
    box-shadow: 0 12px 30px rgba(0,0,0,0.25);
    max-width: 640px;
    margin: 0 auto;
    border: 2px solid #FCE7F3;
}
.search-input-field {
    display: flex;
    align-items: center;
    flex: 1;
    gap: 10px;
}
.search-input-field i {
    font-size: 18px;
    color: var(--turf-teal, #EC4899);
}
.search-input-field input {
    width: 100%;
    border: none;
    outline: none;
    font-size: 15px;
    color: var(--ink, #181824);
    background: transparent;
    font-family: inherit;
}
.clear-btn {
    color: var(--ink-muted, #8E8EA8);
    padding: 4px;
    display: flex;
    align-items: center;
}
.clear-btn:hover {
    color: #ef4444;
}
.search-submit-btn {
    background: linear-gradient(135deg, #FF2E93 0%, #DB2777 100%);
    color: #FFFFFF;
    border: none;
    font-weight: 700;
    font-size: 14.5px;
    padding: 10px 22px;
    border-radius: 10px;
    cursor: pointer;
    transition: all 0.2s;
    white-space: nowrap;
    box-shadow: 0 4px 12px rgba(236, 72, 153, 0.35);
}
.search-submit-btn:hover {
    background: linear-gradient(135deg, #E01A70 0%, #BE185D 100%);
}
.search-content-wrap {
    margin-top: 35px;
}
.search-stats-summary {
    display: flex;
    flex-wrap: wrap;
    gap: 12px;
    margin-bottom: 30px;
}
.stat-pill {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 8px 18px;
    border-radius: 999px;
    font-size: 14px;
    font-weight: 600;
    background: var(--surface, #fff);
    border: 1px solid var(--line, #F1E4EC);
    color: var(--ink-soft, #5A5A72);
    text-decoration: none;
    transition: all 0.2s ease;
}
.stat-pill:hover {
    border-color: var(--turf-teal, #EC4899);
    color: var(--turf-teal, #EC4899);
}
.stat-pill.active {
    background: var(--turf-teal-soft, #FDF2F8);
    border-color: var(--turf-teal, #EC4899);
    color: var(--turf-teal, #EC4899);
}
.search-section-block {
    background: var(--surface, #fff);
    border: 1px solid var(--line, #F1E4EC);
    border-radius: 20px;
    padding: 30px;
    box-shadow: 0 4px 20px rgba(24, 24, 36, 0.04);
}
.section-heading-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 24px;
    padding-bottom: 16px;
    border-bottom: 1px solid var(--line, #F1E4EC);
}
.heading-with-icon {
    display: flex;
    align-items: center;
    gap: 12px;
}
.heading-icon-badge {
    width: 36px;
    height: 36px;
    border-radius: 10px;
    background: #FCE7F3;
    color: var(--turf-teal, #EC4899);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 19px;
}
.heading-icon-badge.news-badge {
    background: #FDF2F8;
    color: #DB2777;
}
.heading-icon-badge.product-badge {
    background: #FCE7F3;
    color: #BE185D;
}
.section-view-all-link {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    font-size: 13.5px;
    font-weight: 700;
    color: var(--turf-teal, #EC4899);
    text-decoration: none;
    transition: gap 0.2s ease;
}
.section-view-all-link:hover {
    gap: 8px;
}
.search-products-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
    gap: 24px;
}
.search-product-card {
    background: var(--surface, #fff);
    border: 1px solid var(--line, #F1E4EC);
    border-radius: 18px;
    padding: 16px;
    display: flex;
    flex-direction: column;
    transition: all 0.25s ease;
}
.search-product-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 14px 30px rgba(236, 72, 153, 0.14);
    border-color: #F472B6;
}
.product-media-box {
    height: 210px;
    position: relative;
    background: linear-gradient(180deg, #FDF2F8 0%, #F9FAFB 100%);
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
    margin-bottom: 14px;
    border: 1px solid rgba(236, 72, 153, 0.08);
}
[data-theme="dark"] .product-media-box {
    background: linear-gradient(180deg, #242436 0%, #181824 100%);
    border-color: #2D2D42;
}
.product-media-box img {
    max-width: 85%;
    max-height: 85%;
    object-fit: contain;
    transition: transform 0.35s ease;
    filter: drop-shadow(0 6px 14px rgba(0, 0, 0, 0.08));
}
.search-product-card:hover .product-media-box img {
    transform: scale(1.06);
}
.product-media-placeholder {
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 46px;
    color: var(--turf-teal, #EC4899);
    opacity: 0.5;
}
.product-season-pill {
    position: absolute;
    top: 10px;
    left: 10px;
    background: rgba(24, 24, 36, 0.88);
    color: #FFFFFF;
    font-size: 11px;
    font-weight: 700;
    padding: 4px 9px;
    border-radius: 6px;
    z-index: 2;
}
.product-stock-pill {
    position: absolute;
    top: 10px;
    right: 10px;
    font-size: 11px;
    font-weight: 600;
    padding: 4px 10px;
    border-radius: 999px;
    display: inline-flex;
    align-items: center;
    gap: 4px;
    z-index: 2;
}
.product-stock-pill.in-stock {
    background: #FCE7F3;
    color: #BE185D;
    border: 1px solid #FBCFE8;
}
.product-stock-pill.out-stock {
    background: #FEE2E2;
    color: #B91C1C;
    border: 1px solid #FECACA;
}
.product-body-content {
    display: flex;
    flex-direction: column;
    flex: 1;
}
.product-team-badge {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    font-size: 12px;
    font-weight: 600;
    color: #DB2777;
    background: #FDF2F8;
    padding: 3px 10px;
    border-radius: 6px;
    border: 1px solid #FCE7F3;
    margin-bottom: 8px;
    align-self: flex-start;
}
[data-theme="dark"] .product-team-badge {
    background: rgba(236, 72, 153, 0.15);
    border-color: rgba(236, 72, 153, 0.3);
    color: #F472B6;
}
.product-heading {
    margin: 0 0 6px;
    font-size: 16px;
    font-weight: 700;
    color: var(--turf-dark, #181824);
    line-height: 1.4;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    min-height: 44px;
}
.product-snippet {
    margin: 0 0 12px;
    font-size: 12.5px;
    color: var(--ink-soft, #5A5A72);
    line-height: 1.45;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
.product-foot-row {
    margin-top: auto;
    padding-top: 12px;
    border-top: 1px dashed var(--line, #F1E4EC);
    display: flex;
    flex-direction: column;
    gap: 12px;
}
.product-price-box {
    display: flex;
    align-items: baseline;
    justify-content: space-between;
}
.product-price-val {
    font-size: 20px;
    font-weight: 800;
    color: #EC4899;
    font-family: 'Oswald', sans-serif;
}
.product-authentic-tag {
    font-size: 12px;
    font-weight: 500;
    color: var(--ink-muted, #8E8EA8);
    display: inline-flex;
    align-items: center;
    gap: 4px;
}
.product-authentic-tag i {
    color: #EC4899;
    font-size: 14px;
}
.product-buy-btn {
    width: 100%;
    justify-content: center;
    padding: 10px 16px;
    border-radius: 10px;
}
.product-login-btn {
    width: 100%;
    height: 38px;
    border-radius: 10px;
    border: 1.5px solid #EC4899;
    color: #EC4899;
    background: transparent;
    font-size: 13px;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    text-decoration: none;
    transition: all 0.2s ease;
}
.product-login-btn:hover {
    background: #FDF2F8;
    color: #DB2777;
}
.product-disabled-btn {
    width: 100%;
    height: 38px;
    border-radius: 10px;
    background: var(--bg-page, #F3F4F6);
    color: var(--ink-muted, #9CA3AF);
    border: 1px solid var(--line, #E5E7EB);
    font-size: 13px;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
}
.section-heading-row h2 {
    margin: 0;
    font-size: 20px;
    font-weight: 700;
    color: var(--turf-dark, #181824);
}
.heading-badge {
    font-size: 13px;
    font-weight: 600;
    color: var(--ink-muted, #8E8EA8);
    background: var(--bg-page, #FAF7F9);
    padding: 4px 12px;
    border-radius: 20px;
}
.search-courts-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 24px;
}
.search-court-card {
    background: var(--surface, #fff);
    border: 1px solid var(--line, #F1E4EC);
    border-radius: 16px;
    overflow: hidden;
    display: flex;
    flex-direction: column;
    transition: all 0.25s ease;
    cursor: pointer;
}
.search-court-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 14px 30px rgba(236, 72, 153, 0.14);
    border-color: #F472B6;
}
.card-media {
    height: 180px;
    position: relative;
    background: #FDF2F8;
    overflow: hidden;
}
.card-media img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.35s ease;
}
.search-court-card:hover .card-media img {
    transform: scale(1.05);
}
.card-media-tags {
    position: absolute;
    inset: 12px 12px auto 12px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.tag-sport {
    background: linear-gradient(135deg, #FF2E93 0%, #DB2777 100%);
    color: #FFFFFF;
    font-size: 12px;
    font-weight: 700;
    padding: 4px 10px;
    border-radius: 999px;
    box-shadow: 0 2px 8px rgba(236, 72, 153, 0.3);
}
.fav-form-tag {
    margin: 0;
}
.btn-favorite {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.9);
    backdrop-filter: blur(4px);
    display: flex;
    align-items: center;
    justify-content: center;
    border: none;
    cursor: pointer;
    color: var(--ink-soft, #5A5A72);
    font-size: 16px;
    transition: all 0.2s ease;
}
.btn-favorite:hover, .btn-favorite.is-favorited {
    color: #FF2E93;
    background: #ffffff;
    box-shadow: 0 2px 8px rgba(236,72,153,0.2);
}
.card-info {
    padding: 16px;
    display: flex;
    flex-direction: column;
    flex: 1;
}
.court-title {
    margin: 0 0 6px;
    font-size: 16.5px;
    font-weight: 700;
    color: var(--turf-dark, #181824);
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.court-addr {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 13px;
    color: var(--ink-soft, #5A5A72);
    margin-bottom: 12px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.court-addr i {
    color: var(--turf-teal, #EC4899);
    flex-shrink: 0;
}
.court-chips {
    display: flex;
    gap: 8px;
    margin-bottom: 16px;
}
.chip-item {
    font-size: 12px;
    background: var(--bg-page, #FAF7F9);
    color: var(--ink-soft, #5A5A72);
    padding: 4px 8px;
    border-radius: 6px;
    display: inline-flex;
    align-items: center;
    gap: 4px;
}
.card-foot-pricing {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-top: 12px;
    border-top: 1px solid var(--line, #F1E4EC);
    margin-top: auto;
}
.price-holder {
    display: flex;
    align-items: baseline;
    gap: 2px;
}
.price-val {
    font-size: 18px;
    font-weight: 800;
    color: #EC4899;
    font-family: 'Oswald', sans-serif;
}
.price-unit {
    font-size: 12px;
    color: var(--ink-soft, #5A5A72);
}
.btn-action-book {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: linear-gradient(135deg, #FF2E93 0%, #DB2777 100%);
    color: #fff;
    padding: 8px 14px;
    border-radius: 999px;
    font-size: 13px;
    font-weight: 700;
    transition: all 0.2s;
    box-shadow: 0 4px 10px rgba(236,72,153,0.28);
}
.btn-action-book:hover {
    background: linear-gradient(135deg, #E01A70 0%, #BE185D 100%);
}
.search-news-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(290px, 1fr));
    gap: 24px;
}
.search-news-card {
    background: var(--surface, #fff);
    border: 1px solid var(--line, #F1E4EC);
    border-radius: 16px;
    overflow: hidden;
    display: flex;
    flex-direction: column;
    text-decoration: none;
    color: inherit;
    transition: all 0.25s ease;
}
.search-news-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 28px rgba(236, 72, 153, 0.12);
    border-color: #F472B6;
}
.news-media-box {
    height: 160px;
    position: relative;
    background: #FDF2F8;
    overflow: hidden;
}
.news-media-box img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}
.news-media-placeholder {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 40px;
    color: var(--turf-teal, #EC4899);
}
.news-cat-pill {
    position: absolute;
    top: 10px;
    left: 10px;
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(4px);
    color: var(--turf-dark, #181824);
    font-size: 11px;
    font-weight: 700;
    padding: 3px 10px;
    border-radius: 20px;
}
.news-body-content {
    padding: 16px;
    display: flex;
    flex-direction: column;
    flex: 1;
}
.news-date-meta {
    display: flex;
    align-items: center;
    gap: 5px;
    font-size: 12px;
    color: var(--ink-muted, #988F9E);
    margin-bottom: 6px;
}
.news-heading {
    font-size: 15.5px;
    font-weight: 700;
    color: var(--turf-dark, #181824);
    line-height: 1.4;
    margin: 0 0 8px;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
.news-snippet {
    font-size: 13px;
    color: var(--ink-soft, #5A5565);
    line-height: 1.5;
    margin: 0 0 14px;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    flex: 1;
}
.news-read-more {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    font-size: 13px;
    font-weight: 700;
    color: var(--turf-teal, #EC4899);
    margin-top: auto;
}
.search-empty-state {
    text-align: center;
    padding: 50px 20px;
}
.search-empty-state .empty-icon {
    font-size: 48px;
    color: var(--ink-muted, #988F9E);
    margin-bottom: 12px;
}
.search-empty-state h3 {
    margin: 0 0 8px;
    font-size: 18px;
    color: var(--turf-dark, #181824);
}
.search-empty-state p {
    color: var(--ink-soft, #5A5565);
    font-size: 14px;
    margin: 0 0 20px;
}
.btn-primary-action {
    display: inline-block;
    background: linear-gradient(135deg, #FF2E93 0%, #DB2777 100%);
    color: #fff;
    padding: 10px 24px;
    border-radius: 999px;
    font-weight: 700;
    font-size: 14px;
    box-shadow: 0 4px 14px rgba(236, 72, 153, 0.35);
}
.search-pagination {
    margin-top: 24px;
    display: flex;
    justify-content: center;
}
@media (max-width: 640px) {
    .search-hero-inner h1 {
        font-size: 26px;
    }
    .search-page-form {
        flex-direction: column;
        padding: 10px;
        gap: 8px;
    }
    .search-submit-btn {
        width: 100%;
    }
    .search-section-block {
        padding: 20px;
    }
}
</style>
@endpush
@endsection
