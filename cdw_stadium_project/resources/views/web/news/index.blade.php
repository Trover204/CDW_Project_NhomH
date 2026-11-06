@extends('web.layouts.app')

@section('title', __('Tin tức & Mẹo thể thao') . ' - ' . config('app.name', 'Sân Nhóm H'))

@section('content')
<div class="news-page-wrapper">
    {{-- News Hero Banner --}}
    <div class="news-hero-section">
        <div class="wrap">
            <div class="news-hero-inner">
                <span class="news-eyebrow-badge">
                    <i class="ti ti-flame"></i> {{ __('Cẩm nang & Bản tin thể thao') }}
                </span>
                <h1>{{ __('Tin tức, Sự kiện & Mẹo tập luyện') }}</h1>
                <p>{{ __('Cập nhật các giải đấu sôi động, chương trình ưu đãi đặc quyền và những bí quyết nâng cao thể lực mỗi ngày từ các chuyên gia.') }}</p>

                {{-- Search Box --}}
                <form action="{{ route('news.index') }}" method="GET" class="news-search-bar">
                    <div class="search-input-box">
                        <i class="ti ti-search"></i>
                        <input type="text" name="q" value="{{ $keyword ?? request('q') }}" placeholder="{{ __('Tìm kiếm bài viết, mẹo đá bóng, cầu lông, giải đấu...') }}" autocomplete="off">
                        @if($keyword ?? request('q'))
                            <a href="{{ route('news.index') }}" class="clear-search-btn" title="{{ __('Bỏ tìm') }}"><i class="ti ti-x"></i></a>
                        @endif
                    </div>
                    <button type="submit" class="news-btn-search">
                        <span>{{ __('Tìm kiếm') }}</span>
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- Main News Content --}}
    <div class="wrap news-content-container">
        
        @if($news->isEmpty())
            <div class="news-empty-box">
                <div class="empty-icon"><i class="ti ti-news-off"></i></div>
                <h3>{{ __('Chưa có bài viết phù hợp') }}</h3>
                <p>{{ __('Không tìm thấy tin tức nào khớp với tiêu chí tìm kiếm của bạn. Hãy thử từ khóa khác nhé!') }}</p>
                <a href="{{ route('news.index') }}" class="btn-reset-news">{{ __('Xem tất cả bài viết') }}</a>
            </div>
        @else

            {{-- Featured Top Article (If on first page and not searching) --}}
            @php
                $isSearching = !empty($keyword ?? request('q'));
                $currentPage = method_exists($news, 'currentPage') ? $news->currentPage() : 1;
                $firstPost = ($currentPage == 1 && !$isSearching) ? $news->first() : null;
                $remainingPosts = ($currentPage == 1 && !$isSearching) ? $news->slice(1) : $news;
            @endphp

            @if($firstPost)
                <div class="featured-post-banner">
                    <a href="{{ route('news.show', $firstPost->slug) }}" class="featured-post-card">
                        <div class="featured-media">
                            @if($firstPost->image)
                                <img src="{{ $firstPost->image_url }}" alt="{{ $firstPost->title }}">
                            @else
                                <div class="featured-placeholder"><i class="ti ti-news"></i></div>
                            @endif
                            <span class="featured-label"><i class="ti ti-star-filled"></i> {{ __('Bài viết nổi bật') }}</span>
                        </div>
                        <div class="featured-content">
                            @if($firstPost->category)
                                <span class="news-cat-tag">{{ $firstPost->category }}</span>
                            @endif
                            <h2 class="featured-title">{{ $firstPost->title }}</h2>
                            <p class="featured-summary">{{ $firstPost->summary }}</p>
                            <div class="featured-meta">
                                <span><i class="ti ti-calendar"></i> {{ $firstPost->created_at->format('d/m/Y') }}</span>
                                <span><i class="ti ti-eye"></i> {{ number_format($firstPost->views ?? 0) }} {{ __('lượt xem') }}</span>
                                <span class="btn-read-featured">
                                    {{ __('Đọc bài viết') }} <i class="ti ti-arrow-right"></i>
                                </span>
                            </div>
                        </div>
                    </a>
                </div>
            @endif

            {{-- Grid of articles --}}
            <div class="news-articles-grid">
                @foreach($remainingPosts as $item)
                    <article class="news-card-modern">
                        <a href="{{ route('news.show', $item->slug) }}" class="card-media-anchor">
                            <div class="news-card-media">
                                @if($item->image)
                                    <img src="{{ $item->image_url }}" alt="{{ $item->title }}" loading="lazy">
                                @else
                                    <div class="news-media-ph"><i class="ti ti-news"></i></div>
                                @endif
                                @if($item->category)
                                    <span class="card-cat-pill">{{ $item->category }}</span>
                                @endif
                            </div>
                        </a>
                        <div class="news-card-body">
                            <div class="card-date-meta">
                                <i class="ti ti-calendar-event"></i>
                                <span>{{ $item->created_at->format('d/m/Y') }}</span>
                                <span class="meta-dot">•</span>
                                <i class="ti ti-eye"></i>
                                <span>{{ $item->views ?? 0 }}</span>
                            </div>
                            <h3 class="news-card-heading">
                                <a href="{{ route('news.show', $item->slug) }}">{{ $item->title }}</a>
                            </h3>
                            <p class="news-card-snippet">{{ $item->summary }}</p>
                            <div class="news-card-footer">
                                <a href="{{ route('news.show', $item->slug) }}" class="read-more-link">
                                    <span>{{ __('Đọc chi tiết') }}</span>
                                    <i class="ti ti-chevron-right"></i>
                                </a>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>

            <div class="news-pagination-row">
                {{ $news->links() }}
            </div>
        @endif

    </div>
</div>

@push('styles')
<style>
.news-page-wrapper {
    background: var(--bg-page);
    min-height: 85vh;
    padding-bottom: 70px;
}
.news-hero-section {
    background: linear-gradient(135deg, var(--turf-dark, #0D382E) 0%, #061B16 100%);
    color: #fff;
    padding: 60px 0 50px;
    border-bottom: 1px solid rgba(255,255,255,0.08);
}
.news-hero-inner {
    max-width: 760px;
    margin: 0 auto;
    text-align: center;
}
.news-eyebrow-badge {
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
.news-hero-inner h1 {
    font-family: 'Oswald', sans-serif;
    font-size: 38px;
    font-weight: 700;
    margin: 0 0 12px;
    color: #ffffff;
    letter-spacing: 0.5px;
}
.news-hero-inner p {
    color: #C3D5CE;
    font-size: 16px;
    line-height: 1.6;
    margin: 0 auto 28px;
    max-width: 640px;
}
.news-search-bar {
    display: flex;
    align-items: center;
    background: #ffffff;
    border-radius: 14px;
    padding: 6px 6px 6px 16px;
    box-shadow: 0 12px 35px rgba(0,0,0,0.22);
    max-width: 640px;
    margin: 0 auto;
}
.search-input-box {
    display: flex;
    align-items: center;
    flex: 1;
    gap: 10px;
}
.search-input-box i {
    font-size: 18px;
    color: var(--ink-muted, #879C94);
}
.search-input-box input {
    width: 100%;
    border: none;
    outline: none;
    font-size: 15px;
    color: var(--ink, #11201B);
    background: transparent;
    font-family: inherit;
}
.clear-search-btn {
    color: var(--ink-muted, #879C94);
    padding: 4px;
    display: flex;
}
.clear-search-btn:hover {
    color: #ef4444;
}
.news-btn-search {
    background: var(--turf-accent, #D2F85A);
    color: var(--turf-accent-ink, #0B3026);
    border: none;
    font-weight: 700;
    font-size: 14.5px;
    padding: 10px 22px;
    border-radius: 10px;
    cursor: pointer;
    transition: all 0.2s ease;
    white-space: nowrap;
}
.news-btn-search:hover {
    background: var(--turf-accent-hover, #C0E846);
}
.news-content-container {
    margin-top: 40px;
}
.featured-post-banner {
    margin-bottom: 40px;
}
.featured-post-card {
    display: grid;
    grid-template-columns: 1.2fr 1fr;
    background: var(--surface, #fff);
    border: 1px solid var(--line, #E3ECE6);
    border-radius: 24px;
    overflow: hidden;
    text-decoration: none;
    color: inherit;
    box-shadow: 0 6px 24px rgba(13, 56, 46, 0.05);
    transition: all 0.3s ease;
}
.featured-post-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 16px 36px rgba(13, 56, 46, 0.1);
    border-color: #B5D9C4;
}
.featured-media {
    position: relative;
    height: 340px;
    background: #0D382E;
    overflow: hidden;
}
.featured-media img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.4s ease;
}
.featured-post-card:hover .featured-media img {
    transform: scale(1.04);
}
.featured-placeholder {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 64px;
    color: var(--turf-teal, #106764);
}
.featured-label {
    position: absolute;
    top: 16px;
    left: 16px;
    background: rgba(13, 56, 46, 0.85);
    backdrop-filter: blur(4px);
    color: var(--turf-accent, #D2F85A);
    font-size: 12px;
    font-weight: 700;
    padding: 6px 14px;
    border-radius: 999px;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}
.featured-content {
    padding: 36px;
    display: flex;
    flex-direction: column;
    justify-content: center;
}
.news-cat-tag {
    display: inline-block;
    align-self: flex-start;
    background: var(--turf-teal-soft, #E6F6F4);
    color: var(--turf-teal, #106764);
    font-size: 12px;
    font-weight: 700;
    padding: 4px 12px;
    border-radius: 8px;
    margin-bottom: 12px;
}
.featured-title {
    font-family: 'Oswald', sans-serif;
    font-size: 26px;
    font-weight: 700;
    color: var(--turf-dark, #0D382E);
    line-height: 1.35;
    margin: 0 0 12px;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
.featured-summary {
    font-size: 15px;
    color: var(--ink-soft, #5E726A);
    line-height: 1.6;
    margin: 0 0 24px;
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
.featured-meta {
    display: flex;
    align-items: center;
    gap: 16px;
    font-size: 13px;
    color: var(--ink-muted, #879C94);
    padding-top: 16px;
    border-top: 1px solid var(--line, #E3ECE6);
}
.featured-meta span {
    display: inline-flex;
    align-items: center;
    gap: 5px;
}
.btn-read-featured {
    margin-left: auto;
    color: var(--turf-teal, #106764) !important;
    font-weight: 700;
    font-size: 14px;
}
.news-articles-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
    gap: 28px;
}
.news-card-modern {
    background: var(--surface, #fff);
    border: 1px solid var(--line, #E3ECE6);
    border-radius: 20px;
    overflow: hidden;
    display: flex;
    flex-direction: column;
    box-shadow: 0 4px 18px rgba(13, 56, 46, 0.03);
    transition: all 0.25s ease;
}
.news-card-modern:hover {
    transform: translateY(-4px);
    box-shadow: 0 14px 32px rgba(13, 56, 46, 0.08);
    border-color: #B5D9C4;
}
.card-media-anchor {
    text-decoration: none;
}
.news-card-media {
    height: 200px;
    position: relative;
    background: #EEF7F2;
    overflow: hidden;
}
.news-card-media img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.35s ease;
}
.news-card-modern:hover .news-card-media img {
    transform: scale(1.05);
}
.news-media-ph {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 44px;
    color: var(--turf-teal, #106764);
}
.card-cat-pill {
    position: absolute;
    top: 12px;
    left: 12px;
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(4px);
    color: var(--turf-dark, #0D382E);
    font-size: 11.5px;
    font-weight: 700;
    padding: 4px 12px;
    border-radius: 999px;
}
.news-card-body {
    padding: 22px;
    display: flex;
    flex-direction: column;
    flex: 1;
}
.card-date-meta {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 12.5px;
    color: var(--ink-muted, #879C94);
    margin-bottom: 8px;
}
.meta-dot {
    color: var(--line, #E3ECE6);
}
.news-card-heading {
    margin: 0 0 8px;
    font-size: 17.5px;
    font-weight: 700;
    line-height: 1.4;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
.news-card-heading a {
    color: var(--turf-dark, #0D382E);
    text-decoration: none;
    transition: color 0.15s;
}
.news-card-heading a:hover {
    color: var(--turf-teal, #106764);
}
.news-card-snippet {
    font-size: 14px;
    color: var(--ink-soft, #5E726A);
    line-height: 1.55;
    margin: 0 0 16px;
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
    flex: 1;
}
.news-card-footer {
    padding-top: 14px;
    border-top: 1px solid var(--line, #E3ECE6);
    margin-top: auto;
}
.read-more-link {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    font-size: 13.5px;
    font-weight: 700;
    color: var(--turf-teal, #106764);
    text-decoration: none;
}
.read-more-link:hover {
    gap: 7px;
}
.news-empty-box {
    background: var(--surface, #fff);
    border: 1px dashed var(--line, #E3ECE6);
    border-radius: 20px;
    padding: 60px 20px;
    text-align: center;
}
.news-empty-box .empty-icon {
    font-size: 48px;
    color: var(--ink-muted, #879C94);
    margin-bottom: 12px;
}
.news-empty-box h3 {
    margin: 0 0 8px;
    font-size: 18px;
    color: var(--turf-dark, #0D382E);
}
.news-empty-box p {
    color: var(--ink-soft, #5E726A);
    font-size: 14px;
    margin: 0 0 20px;
}
.btn-reset-news {
    display: inline-block;
    background: var(--turf-teal, #106764);
    color: #fff;
    padding: 10px 22px;
    border-radius: 10px;
    font-weight: 600;
    font-size: 14px;
}
.news-pagination-row {
    margin-top: 40px;
    display: flex;
    justify-content: center;
}
@media (max-width: 900px) {
    .featured-post-card {
        grid-template-columns: 1fr;
    }
    .featured-media {
        height: 220px;
    }
}
@media (max-width: 640px) {
    .news-hero-inner h1 {
        font-size: 28px;
    }
    .news-search-bar {
        flex-direction: column;
        padding: 10px;
        gap: 8px;
    }
    .news-btn-search {
        width: 100%;
    }
}
</style>
@endpush
@endsection
