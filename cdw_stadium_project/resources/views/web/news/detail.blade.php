<!DOCTYPE html>
<html lang="vi">

<head>
    <script>if(localStorage.getItem("theme")==="dark") document.documentElement.setAttribute("data-theme", "dark");</script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $newsItem->title }} - Sân Nhóm H</title>

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Oswald:wght@500;600;700&family=Be+Vietnam+Pro:wght@400;500;600;700;800&display=swap"
        rel="stylesheet"
    >

    {{-- Tabler Icons --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css">

    {{-- Stylesheet --}}
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
    <style>
        .news-detail-container {
            padding: 60px 0;
            background-color: var(--bg-page);
        }

        .news-detail-content {
            background: var(--surface);
            border-radius: 24px;
            padding: 40px;
            box-shadow: 0 12px 36px rgba(13, 56, 46, 0.04);
            max-width: 800px;
            margin: 0 auto;
        }

        .breadcrumb {
            margin-bottom: 24px;
            font-size: 14px;
            color: var(--ink-soft);
        }

        .breadcrumb a {
            color: var(--turf-teal);
            text-decoration: none;
        }

        .breadcrumb a:hover {
            text-decoration: underline;
        }

        .news-header {
            margin-bottom: 30px;
            text-align: center;
        }

        .news-title {
            font-size: 32px;
            font-weight: 700;
            color: var(--turf-dark);
            margin-bottom: 16px;
            line-height: 1.3;
        }

        .news-meta {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 20px;
            color: var(--ink-muted);
            font-size: 14px;
        }

        .news-meta span {
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .news-featured-image {
            width: 100%;
            height: auto;
            max-height: 400px;
            object-fit: cover;
            border-radius: 12px;
            margin-bottom: 30px;
        }

        .news-body {
            font-size: 16px;
            line-height: 1.8;
            color: var(--ink);
        }

        .news-body h2, .news-body h3 {
            color: var(--turf-dark);
            margin-top: 30px;
            margin-bottom: 16px;
        }

        .news-body img {
            max-width: 100%;
            border-radius: 8px;
            margin: 20px 0;
        }

        .related-news-section {
            max-width: 800px;
            margin: 40px auto 0;
        }

        .related-news-title {
            font-size: 24px;
            font-weight: 700;
            color: var(--turf-dark);
            margin-bottom: 24px;
        }

        .related-news-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
        }

        .related-card {
            background: var(--surface);
            border: 1px solid var(--line);
            border-radius: 12px;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            text-decoration: none;
            color: inherit;
            transition: all 0.2s;
        }

        .related-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 24px rgba(13, 56, 46, 0.08);
            border-color: #B5D9C4;
        }

        .related-img {
            height: 120px;
            background: #EEF7F2;
            width: 100%;
            object-fit: cover;
        }

        .related-content {
            padding: 16px;
        }

        .related-title {
            font-size: 15px;
            font-weight: 600;
            color: var(--turf-dark);
            line-height: 1.4;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        @media (max-width: 720px) {
            .news-detail-content {
                padding: 24px;
            }
            .news-title {
                font-size: 24px;
            }
            .related-news-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>

    {{-- =====================================================
         HEADER
    ====================================================== --}}
    <header>
        <div class="wrap">
            <div class="nav">
                {{-- LOGO --}}
                <a href="{{ route('home') }}" class="brand">
                    Sân Nhóm <span>H</span>
                </a>

                {{-- MENU --}}
                <nav class="nav-links">
                    <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">Trang chủ</a>
                    <a href="{{ route('courts.index') }}" class="{{ request()->routeIs('courts.*') ? 'active' : '' }}">Tìm sân</a>
                    <a href="{{ route('news.index') }}" class="{{ request()->routeIs('news.*') ? 'active' : '' }}">Tin tức</a>
                    <a href="{{ route('plans.index') }}" class="{{ request()->routeIs('plans.*') ? 'active' : '' }}">Gói thành viên</a>
                </nav>

                {{-- LOGIN / REGISTER --}}
                <div class="nav-actions">
                <button class="theme-toggle-btn" aria-label="Toggle Theme" style="background: none; border: none; cursor: pointer; color: var(--ink); margin-right: 15px; font-size: 20px;"><i class="ti ti-moon"></i></button>

                    @guest
                        <a href="{{ route('login') }}" class="btn btn-ghost">Đăng nhập</a>
                        <a href="{{ route('login') }}?tab=register" class="btn btn-solid">Đăng ký</a>
                    @else
                        <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="btn btn-ghost">Đăng xuất</a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    @endguest
                </div>
            </div>
        </div>
    </header>

    {{-- =====================================================
         NEWS DETAIL
    ====================================================== --}}
    <section class="news-detail-container">
        <div class="wrap">
            <div class="news-detail-content">

                <div class="breadcrumb">
                    <a href="{{ route('home') }}">Trang chủ</a> /
                    <a href="{{ route('news.index') }}">Tin tức</a> /
                    <span style="color: var(--ink);">{{ Str::limit($newsItem->title, 40) }}</span>
                </div>

                <div class="news-header">
                    @if($newsItem->category)
                        <div style="margin-bottom: 12px;">
                            <span style="padding: 4px 12px; background: var(--turf-lime-bg); color: var(--turf-dark); border-radius: 9999px; font-size: 12px; font-weight: 600;">{{ $newsItem->category }}</span>
                        </div>
                    @endif
                    <h1 class="news-title">{{ $newsItem->title }}</h1>

                    <div class="news-meta">
                        <span>
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                <line x1="16" y1="2" x2="16" y2="6"></line>
                                <line x1="8" y1="2" x2="8" y2="6"></line>
                                <line x1="3" y1="10" x2="21" y2="10"></line>
                            </svg>
                            {{ $newsItem->created_at->format('d/m/Y') }}
                        </span>
                        @if($newsItem->user)
                            <span>
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                    <circle cx="12" cy="7" r="4"></circle>
                                </svg>
                                {{ $newsItem->user->name }}
                            </span>
                        @endif
                        <span>
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                <circle cx="12" cy="12" r="3"></circle>
                            </svg>
                            {{ $newsItem->views }} lượt xem
                        </span>
                    </div>
                </div>

                @if($newsItem->image)
                    <img src="{{ Storage::url($newsItem->image) }}" alt="{{ $newsItem->title }}" class="news-featured-image">
                @endif

                @if($newsItem->summary)
                    <div style="font-size: 18px; font-weight: 500; margin-bottom: 24px; padding-left: 16px; border-left: 4px solid var(--turf-teal); color: var(--turf-dark);">
                        {{ $newsItem->summary }}
                    </div>
                @endif

                <div class="news-body">
                    {!! nl2br(e($newsItem->content)) !!}
                </div>

            </div>

            {{-- RELATED NEWS --}}
            @if($relatedNews->isNotEmpty())
                <div class="related-news-section">
                    <h3 class="related-news-title">Bài viết liên quan</h3>
                    <div class="related-news-grid">
                        @foreach($relatedNews as $related)
                            <a href="{{ route('news.show', $related->slug) }}" class="related-card">
                                @if($related->image)
                                    <img src="{{ Storage::url($related->image) }}" alt="{{ $related->title }}" class="related-img">
                                @else
                                    <div class="related-img" style="display:flex; align-items:center; justify-content:center; color:var(--turf-teal);">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                            <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                                        </svg>
                                    </div>
                                @endif
                                <div class="related-content">
                                    <div class="related-title">{{ $related->title }}</div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

        </div>
    </section>

    {{-- =====================================================
         FOOTER
    ====================================================== --}}
    <footer>
        <div class="wrap foot-row">
            <div class="foot-content">
                <span>
                    © {{ date('Y') }} Sân Nhóm H. All rights reserved.
                </span>
                <div class="social-links">
                    <a href="#" aria-label="Facebook"><i class="ti ti-brand-facebook"></i></a>
                    <a href="#" aria-label="Instagram"><i class="ti ti-brand-instagram"></i></a>
                    <a href="#" aria-label="Twitter"><i class="ti ti-brand-twitter"></i></a>
                </div>
            </div>

                        <div class="foot-links">
                <a href="{{ route('pages.about') }}">Về chúng tôi</a>
                <a href="{{ route('pages.contact') }}">Liên hệ</a>
                <a href="{{ route('pages.privacy') }}">Điều khoản</a>
            </div>
        </div>
    </footer>

    <script src="{{ asset('js/theme-toggle.js') }}"></script>
</body>
</html>
