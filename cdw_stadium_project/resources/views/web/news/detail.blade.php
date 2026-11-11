@extends('web.layouts.app')

@php
    $news = $news ?? $newsItem;
    $commentsList = $comments ?? ($news->comments ?? collect());
    $commentsCount = $commentsList->count();
    $avgRating = $commentsCount > 0 ? round((float) $commentsList->avg(fn ($c) => $c->rating ?? 5), 1) : 5.0;
@endphp

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
        flex-wrap: wrap;
        gap: 20px;
        color: var(--ink-muted);
        font-size: 13.5px;
        padding-top: 14px;
        border-top: 1px solid var(--line);
    }
    .article-meta-row span,
    .article-meta-row a {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        color: var(--ink-muted);
        text-decoration: none;
    }
    .article-meta-row a:hover {
        color: var(--turf-teal);
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
        flex-wrap: wrap;
        gap: 12px;
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

    /* Reviews & Comments Section */
    .news-reviews-card {
        margin-top: 32px;
        background: var(--surface, #fff);
        border: 1px solid var(--line, #F1E4EC);
        border-radius: 24px;
        padding: 36px 40px;
        box-shadow: 0 6px 24px rgba(13, 56, 46, 0.04);
    }
    .reviews-section-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
        padding-bottom: 18px;
        margin-bottom: 24px;
        border-bottom: 1px solid var(--line, #F1E4EC);
    }
    .reviews-head-left {
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .reviews-head-left i {
        font-size: 24px;
        color: var(--turf-teal, #EC4899);
    }
    .reviews-head-left h3 {
        font-family: 'Oswald', sans-serif;
        font-size: 23px;
        font-weight: 700;
        color: var(--turf-dark, #181824);
        margin: 0;
    }
    .reviews-summary-pills {
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .rating-summary-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        background: #FEF3C7;
        color: #B45309;
        font-weight: 700;
        font-size: 13px;
        padding: 5px 12px;
        border-radius: 999px;
    }
    .count-summary-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        background: var(--turf-teal-soft, #FDF2F8);
        color: var(--turf-teal, #EC4899);
        font-weight: 700;
        font-size: 13px;
        padding: 5px 12px;
        border-radius: 999px;
    }
    .comment-form-box {
        background: var(--bg-page, #FAF7F9);
        border: 1px solid var(--line, #F1E4EC);
        border-radius: 18px;
        padding: 22px 24px;
        margin-bottom: 28px;
    }
    .comment-form-top {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
        margin-bottom: 14px;
    }
    .commenter-identity {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 14px;
        font-weight: 600;
        color: var(--turf-dark, #181824);
    }
    .commenter-mini-avatar {
        width: 34px;
        height: 34px;
        border-radius: 50%;
        background: linear-gradient(135deg, #FF2E93 0%, #DB2777 100%);
        color: #fff;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 14px;
    }
    .star-rating-picker {
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    .star-rating-picker .picker-label {
        font-size: 13px;
        font-weight: 600;
        color: var(--ink-soft, #5A5A72);
    }
    .star-radio-group {
        display: inline-flex;
        flex-direction: row-reverse;
        gap: 4px;
    }
    .star-radio-group input {
        display: none;
    }
    .star-radio-group label {
        cursor: pointer;
        font-size: 20px;
        color: #D1D5DB;
        transition: color 0.15s ease, transform 0.15s ease;
        line-height: 1;
    }
    .star-radio-group label:hover,
    .star-radio-group label:hover ~ label,
    .star-radio-group input:checked ~ label {
        color: #F59E0B;
    }
    .star-radio-group label:hover {
        transform: scale(1.15);
    }
    .comment-textarea {
        width: 100%;
        border: 1.5px solid var(--line, #E5D4E0);
        border-radius: 14px;
        padding: 14px 16px;
        font-size: 14.5px;
        font-family: inherit;
        color: var(--ink, #181824);
        background: #fff;
        resize: vertical;
        min-height: 96px;
        outline: none;
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }
    .comment-textarea:focus {
        border-color: var(--turf-teal, #EC4899);
        box-shadow: 0 0 0 3px rgba(236, 72, 153, 0.12);
    }
    .comment-form-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 10px;
        margin-top: 12px;
    }
    .comment-hint {
        font-size: 12.5px;
        color: var(--ink-muted, #8E8EA8);
    }
    .btn-submit-comment {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: linear-gradient(135deg, #FF2E93 0%, #DB2777 100%);
        color: #fff;
        border: none;
        border-radius: 12px;
        padding: 11px 22px;
        font-size: 14px;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.2s ease;
        box-shadow: 0 4px 14px rgba(236, 72, 153, 0.25);
    }
    .btn-submit-comment:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 18px rgba(236, 72, 153, 0.35);
    }
    .guest-comment-prompt {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 14px;
        background: var(--bg-page, #FAF7F9);
        border: 1px dashed var(--line, #E5D4E0);
        border-radius: 16px;
        padding: 18px 22px;
        margin-bottom: 26px;
    }
    .guest-prompt-text {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 14.5px;
        color: var(--ink-soft, #5A5A72);
        font-weight: 500;
    }
    .guest-prompt-text i {
        font-size: 20px;
        color: var(--turf-teal, #EC4899);
    }
    .btn-login-to-comment {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: linear-gradient(135deg, #FF2E93 0%, #DB2777 100%);
        color: #fff;
        text-decoration: none;
        padding: 9px 18px;
        border-radius: 10px;
        font-size: 13.5px;
        font-weight: 700;
    }
    .news-comments-list {
        display: flex;
        flex-direction: column;
        gap: 18px;
    }
    .news-comment-item {
        display: flex;
        gap: 16px;
        align-items: flex-start;
        padding-bottom: 18px;
        border-bottom: 1px solid var(--line, #F1E4EC);
    }
    .news-comment-item:last-child {
        border-bottom: none;
        padding-bottom: 0;
    }
    .comment-user-avatar {
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
        overflow: hidden;
    }
    .comment-user-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .comment-body-col {
        flex: 1;
        min-width: 0;
    }
    .comment-header-meta {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 8px;
        margin-bottom: 6px;
    }
    .comment-author-wrap {
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .comment-author-name {
        font-size: 14.5px;
        font-weight: 700;
        color: var(--turf-dark, #181824);
    }
    .comment-date-text {
        font-size: 12px;
        color: var(--ink-muted, #8E8EA8);
    }
    .comment-stars {
        color: #F59E0B;
        font-size: 13.5px;
        display: inline-flex;
        align-items: center;
        gap: 2px;
    }
    .comment-content-text {
        font-size: 14.5px;
        color: var(--ink-soft, #5A5A72);
        line-height: 1.65;
        margin: 0;
        word-break: break-word;
    }
    .no-comments-empty {
        text-align: center;
        padding: 28px 16px;
        color: var(--ink-soft, #5A5A72);
    }
    .no-comments-empty i {
        font-size: 38px;
        color: var(--ink-muted, #8E8EA8);
        margin-bottom: 8px;
        display: block;
    }

    /* Related Articles */
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
        .news-article-card,
        .news-reviews-card {
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
                    <a href="#news-comments">
                        <i class="ti ti-message-circle"></i>
                        <span>{{ $commentsCount }} {{ __('đánh giá & bình luận') }}</span>
                    </a>
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

        {{-- Reviews & Comments Section --}}
        <section id="news-comments" class="news-reviews-card">
            <div class="reviews-section-head">
                <div class="reviews-head-left">
                    <i class="ti ti-messages"></i>
                    <h3>{{ __('Đánh giá & Bình luận bài viết') }}</h3>
                </div>
                <div class="reviews-summary-pills">
                    @if($commentsCount > 0)
                        <span class="rating-summary-badge">
                            ★ {{ number_format($avgRating, 1) }}/5.0
                        </span>
                    @endif
                    <span class="count-summary-badge">
                        <i class="ti ti-message-2"></i> {{ $commentsCount }} {{ __('bình luận') }}
                    </span>
                </div>
            </div>

            {{-- Form bình luận cho người dùng đã đăng nhập / Thông báo đăng nhập cho khách --}}
            @auth
                <form action="{{ route('news.comments.store', $news->slug) }}" method="POST" class="comment-form-box">
                    @csrf
                    <div class="comment-form-top">
                        <div class="commenter-identity">
                            <span class="commenter-mini-avatar">
                                {{ strtoupper(mb_substr(auth()->user()->name ?? 'U', 0, 1, 'UTF-8')) }}
                            </span>
                            <span>{{ auth()->user()->name }}</span>
                        </div>

                        <div class="star-rating-picker">
                            <span class="picker-label">{{ __('Đánh giá bài viết:') }}</span>
                            <div class="star-radio-group" title="{{ __('Chọn số sao đánh giá') }}">
                                @for($star = 5; $star >= 1; $star--)
                                    <input type="radio" id="news-star-{{ $star }}" name="rating" value="{{ $star }}" @checked((int) old('rating', 5) === $star)>
                                    <label for="news-star-{{ $star }}" title="{{ $star }} sao">★</label>
                                @endfor
                            </div>
                        </div>
                    </div>

                    <textarea name="content" class="comment-textarea" maxlength="500" required
                              placeholder="{{ __('Viết bình luận của bạn...') }}">{{ old('content') }}</textarea>

                    @error('content')
                        <div style="color: #DC2626; font-size: 13px; font-weight: 600; margin-top: 6px;">{{ $message }}</div>
                    @enderror
                    @error('rating')
                        <div style="color: #DC2626; font-size: 13px; font-weight: 600; margin-top: 6px;">{{ $message }}</div>
                    @enderror

                    <div class="comment-form-footer">
                        <span class="comment-hint">{{ __('Tối đa 500 ký tự. Bình luận văn minh, lịch sự.') }}</span>
                        <button type="submit" class="btn-submit-comment">
                            <i class="ti ti-send"></i>
                            <span>{{ __('Gửi bình luận') }}</span>
                        </button>
                    </div>
                </form>
            @else
                <div class="guest-comment-prompt">
                    <div class="guest-prompt-text">
                        <i class="ti ti-lock"></i>
                        <span>{{ __('Vui lòng đăng nhập để bình luận.') }}</span>
                    </div>
                    <a href="{{ route('login') }}" class="btn-login-to-comment">
                        <i class="ti ti-login"></i>
                        <span>{{ __('Đăng nhập ngay') }}</span>
                    </a>
                </div>
            @endauth

            {{-- Danh sách bình luận & đánh giá --}}
            <div class="news-comments-list">
                @forelse($commentsList as $comment)
                    <div class="news-comment-item">
                        <div class="comment-user-avatar">
                            {{ strtoupper(mb_substr($comment->user->name ?? 'U', 0, 1, 'UTF-8')) }}
                        </div>
                        <div class="comment-body-col">
                            <div class="comment-header-meta">
                                <div class="comment-author-wrap">
                                    <strong class="comment-author-name">{{ $comment->user->name ?? __('Độc giả') }}</strong>
                                    <span class="comment-date-text">
                                        • {{ $comment->created_at ? $comment->created_at->format('d/m/Y H:i') : __('Gần đây') }}
                                    </span>
                                </div>
                                <div class="comment-stars" title="{{ $comment->rating ?? 5 }}/5">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="ti {{ $i <= ($comment->rating ?? 5) ? 'ti-star-filled' : 'ti-star' }}"></i>
                                    @endfor
                                </div>
                            </div>
                            <p class="comment-content-text">{{ $comment->content }}</p>
                        </div>
                    </div>
                @empty
                    <div class="no-comments-empty">
                        <i class="ti ti-messages"></i>
                        <p>{{ __('Chưa có bình luận nào cho bài viết này. Hãy là người đầu tiên chia sẻ cảm nghĩ của bạn!') }}</p>
                    </div>
                @endforelse
            </div>
        </section>

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
                                <span class="related-card-date">
                                    {{ $item->created_at->format('d/m/Y') }}
                                    @if(isset($item->comments_count))
                                        • <i class="ti ti-message-circle"></i> {{ $item->comments_count }}
                                    @endif
                                </span>
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
