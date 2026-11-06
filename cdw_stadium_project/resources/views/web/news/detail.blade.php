@extends('web.layouts.app')

@section('title', $news->title . ' - ' . config('app.name', 'Sân Nhóm H'))

@push('styles')
<style>
    .news-detail-page {
        padding: 40px 0 70px;
        background-color: var(--bg-page);
        min-height: 85vh;
    }
    .news-detail-container {
        max-width: 860px;
        margin: 0 auto;
    }
    .news-detail-breadcrumb {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 8px;
        font-size: 13.5px;
        color: var(--ink-soft);
        margin-bottom: 24px;
    }
    .news-detail-breadcrumb a {
        color: var(--turf-dark);
        display: inline-flex;
        align-items: center;
        gap: 4px;
        text-decoration: none;
        font-weight: 500;
    }
    .news-detail-breadcrumb a:hover {
        color: var(--turf-teal);
        text-decoration: underline;
    }
    .news-detail-breadcrumb .sep {
        color: var(--ink-muted);
        font-size: 12px;
    }
    .news-detail-breadcrumb .current {
        color: var(--ink-soft);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 320px;
    }
    .news-article-card {
        background: var(--surface);
        border: 1px solid var(--line);
        border-radius: 24px;
        padding: 44px 48px;
        box-shadow: 0 6px 24px rgba(13, 56, 46, 0.04);
    }
    .article-top-header {
        margin-bottom: 30px;
        text-align: center;
    }
    .article-category-badge {
        display: inline-block;
        background: var(--turf-teal-soft);
        color: var(--turf-teal);
        font-size: 13px;
        font-weight: 700;
        padding: 5px 16px;
        border-radius: 999px;
        margin-bottom: 16px;
    }
    .article-title {
        font-family: 'Oswald', sans-serif;
        font-size: 36px;
        font-weight: 700;
        color: var(--turf-dark);
        line-height: 1.3;
        margin: 0 0 16px;
    }
    .article-meta-row {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 20px;
        color: var(--ink-muted);
        font-size: 13.5px;
        padding-top: 14px;
        border-top: 1px solid var(--line);
    }
    .article-meta-row span {
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    .article-featured-media {
        width: 100%;
        max-height: 440px;
        border-radius: 16px;
        overflow: hidden;
        margin-bottom: 32px;
        background: #181824;
    }
    .article-featured-media img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .article-lead-summary {
        font-size: 17px;
        font-weight: 500;
        line-height: 1.7;
        color: var(--turf-dark);
        background: var(--bg-page);
        border-left: 4px solid var(--turf-teal);
        padding: 18px 22px;
        border-radius: 0 14px 14px 0;
        margin-bottom: 30px;
    }
    .article-body-content {
        font-size: 16px;
        line-height: 1.85;
        color: var(--ink);
    }
    .article-body-content p {
        margin-bottom: 20px;
    }
    .article-body-content h2, .article-body-content h3 {
        font-family: 'Oswald', sans-serif;
        color: var(--turf-dark);
        margin: 36px 0 16px;
    }
    .article-body-content img {
        max-width: 100%;
        border-radius: 12px;
        margin: 20px 0;
    }
    .article-share-bar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 40px;
        padding-top: 24px;
        border-top: 1px solid var(--line);
    }
    .share-tags {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 13.5px;
        color: var(--ink-soft);
    }
    .btn-back-news-list {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        color: var(--turf-teal);
        font-weight: 700;
        font-size: 14px;
        text-decoration: none;
    }
    .btn-back-news-list:hover {
        gap: 9px;
    }
    .related-articles-section {
        margin-top: 50px;
    }
    .related-section-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 24px;
    }
    .related-section-header h3 {
        font-family: 'Oswald', sans-serif;
        font-size: 24px;
        font-weight: 700;
        color: var(--turf-dark);
        margin: 0;
    }
    .related-cards-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
        gap: 20px;
    }
    .related-card {
        background: var(--surface);
        border: 1px solid var(--line);
        border-radius: 16px;
        overflow: hidden;
        text-decoration: none;
        color: inherit;
        display: flex;
        flex-direction: column;
        transition: all 0.25s ease;
    }
    .related-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 28px rgba(236, 72, 153, 0.12);
        border-color: #F472B6;
    }
    .related-card-img {
        height: 150px;
        background: #FDF2F8;
        overflow: hidden;
    }
    .related-card-img img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .related-card-body {
        padding: 16px;
        flex: 1;
        display: flex;
        flex-direction: column;
    }
    .related-card-date {
        font-size: 12px;
        color: var(--ink-muted);
        margin-bottom: 6px;
    }
    .related-card-title {
        font-size: 15px;
        font-weight: 700;
        color: var(--turf-dark);
        line-height: 1.4;
        margin: 0;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    @media (max-width: 640px) {
        .news-article-card {
            padding: 24px 20px;
        }
        .article-title {
            font-size: 26px;
        }
    }
</style>
@endpush

@section('content')
<div class="news-detail-page">
    <div class="wrap news-detail-container">
        
        {{-- Breadcrumb --}}
        <nav class="news-detail-breadcrumb" aria-label="breadcrumb">
            <a href="{{ route('home') }}"><i class="ti ti-home"></i> {{ __('Trang chủ') }}</a>
            <span class="sep"><i class="ti ti-chevron-right"></i></span>
            <a href="{{ route('news.index') }}">{{ __('Tin tức') }}</a>
            <span class="sep"><i class="ti ti-chevron-right"></i></span>
            @if($news->category)
                <span>{{ $news->category }}</span>
                <span class="sep"><i class="ti ti-chevron-right"></i></span>
            @endif
            <span class="current">{{ $news->title }}</span>
        </nav>

        {{-- Main Article Card --}}
        <article class="news-article-card">
            <header class="article-top-header">
                @if($news->category)
                    <span class="article-category-badge">{{ $news->category }}</span>
                @endif
                <h1 class="article-title">{{ $news->title }}</h1>
                <div class="article-meta-row">
                    <span><i class="ti ti-user"></i> {{ $news->user->name ?? __('Ban Biên Tập') }}</span>
                    <span><i class="ti ti-calendar"></i> {{ $news->created_at->format('d/m/Y') }}</span>
                    <span><i class="ti ti-eye"></i> {{ number_format($news->views ?? 0) }} {{ __('lượt xem') }}</span>
                </div>
            </header>

            @if($news->image)
                <div class="article-featured-media">
                    <img src="{{ $news->image_url }}" alt="{{ $news->title }}">
                </div>
            @endif

            @if(!empty($news->summary))
                <div class="article-lead-summary">
                    {{ $news->summary }}
                </div>
            @endif

            <div class="article-body-content">
                {!! $news->content !!}
            </div>

            <footer class="article-share-bar">
                <div class="share-tags">
                    <i class="ti ti-tag"></i>
                    <span>{{ __('Chủ đề: Thể thao, Sân bãi, Tập luyện') }}</span>
                </div>
                <a href="{{ route('news.index') }}" class="btn-back-news-list">
                    <i class="ti ti-arrow-left"></i>
                    <span>{{ __('Quay lại mục tin tức') }}</span>
                </a>
            </footer>
        </article>

        {{-- Related Articles Section --}}
        @if(isset($relatedNews) && $relatedNews->isNotEmpty())
            <div class="related-articles-section">
                <div class="related-section-header">
                    <h3>{{ __('Bài viết cùng chuyên mục') }}</h3>
                    <a href="{{ route('news.index') }}" class="btn-back-news-list">{{ __('Xem thêm') }} &rarr;</a>
                </div>

                <div class="related-cards-grid">
                    @foreach($relatedNews as $item)
                        <a href="{{ route('news.show', $item->slug) }}" class="related-card">
                            <div class="related-card-img">
                                @if($item->image)
                                    <img src="{{ $item->image_url }}" alt="{{ $item->title }}" loading="lazy">
                                @else
                                    <div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;color:var(--turf-teal);font-size:32px;">
                                        <i class="ti ti-news"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="related-card-body">
                                <span class="related-card-date">{{ $item->created_at->format('d/m/Y') }}</span>
                                <h4 class="related-card-title">{{ $item->title }}</h4>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

    </div>
</div>
@endsection
