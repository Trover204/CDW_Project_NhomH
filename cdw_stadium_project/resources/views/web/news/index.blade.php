<!DOCTYPE html>
<html lang="vi">

<head>
    <script>
        if (localStorage.getItem("theme") === "dark") document.documentElement.setAttribute("data-theme", "dark");
    </script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tin tức &amp; Mẹo thể thao - Sân Nhóm H</title>

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
        .news-page-container {
            padding: 60px 0;
            background-color: var(--bg-page);
            min-height: calc(100vh - 200px);
        }

        .news-header {
            text-align: center;
            margin-bottom: 40px;
        }

        .news-header h1 {
            font-size: 36px;
            color: var(--turf-dark);
            margin-bottom: 12px;
            font-weight: 700;
        }

        .news-header p {
            color: var(--ink-muted);
            font-size: 16px;
            max-width: 600px;
            margin: 0 auto;
        }

        .news-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 24px;
        }

        .news-card {
            background: var(--surface);
            border: 1px solid var(--line);
            border-radius: 16px;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            text-decoration: none;
            color: inherit;
            transition: all 0.2s;
        }

        .news-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 36px rgba(13, 56, 46, 0.08);
            border-color: #B5D9C4;
        }

        .news-img-wrap {
            height: 200px;
            background: #EEF7F2;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .news-img-wrap img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .news-category {
            position: absolute;
            top: 12px;
            left: 12px;
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(4px);
            color: var(--turf-dark);
            font-size: 12px;
            font-weight: 600;
            padding: 4px 12px;
            border-radius: 20px;
        }

        .news-content {
            padding: 20px;
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .news-title {
            font-size: 18px;
            font-weight: 700;
            color: var(--turf-dark);
            line-height: 1.4;
            margin-bottom: 8px;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .news-summary {
            font-size: 14px;
            color: var(--ink-soft);
            line-height: 1.5;
            margin-bottom: 16px;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
            flex: 1;
        }

        .news-meta {
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 13px;
            color: var(--ink-muted);
            border-top: 1px solid var(--line);
            padding-top: 12px;
        }

        /* Pagination styling */
        .pagination-wrap {
            margin-top: 40px;
            display: flex;
            justify-content: center;
        }

        .pagination-wrap nav p {
            display: none;
        }

        @media (max-width: 900px) {
            .news-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 600px) {
            .news-grid {
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
                    <a href="{{ route('courts.index') }}" class="{{ request()->routeIs('courts.*') ? 'active' : '' }}">Sân</a>
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
                    <a href="{{ route('profile.edit') }}" style="margin-right: 15px; font-weight: 600; color: #124C3E; display: inline-flex; align-items: center; gap: 6px; text-decoration: none;">
                        <i class="ti ti-user-circle" style="font-size: 19px;"></i>
                        <span>{{ Auth::user()->name }}</span>
                    </a>
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
         NEWS LIST
    ====================================================== --}}
    <section class="news-page-container">
        <div class="wrap">
            <div class="news-header">
                <h1>Tin tức & Mẹo thể thao</h1>
                <p>Cập nhật thông tin khuyến mãi, mẹo chơi thể thao và các hoạt động mới nhất tại Sân Nhóm H.</p>
            </div>

            @if($news->isEmpty())
            <div style="text-align: center; padding: 40px; background: #fff; border-radius: 12px; color: var(--ink-soft);">
                Chưa có bài viết nào được đăng.
            </div>
            @else
            <div class="news-grid">
                @foreach($news as $item)
                <a href="{{ route('news.show', $item->slug) }}" class="news-card">
                    <div class="news-img-wrap">
                        @if($item->category)
                        <span class="news-category">{{ $item->category }}</span>
                        @endif

                        @if($item->image)
                        <img src="{{ Storage::url($item->image) }}" alt="{{ $item->title }}">
                        @else
                        {{-- Placeholder icon if no image --}}
                        <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" style="color: var(--turf-teal);">
                            <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                            <line x1="3" y1="9" x2="21" y2="9"></line>
                            <line x1="9" y1="21" x2="9" y2="9"></line>
                        </svg>
                        @endif
                    </div>
                    <div class="news-content">
                        <h3 class="news-title">{{ $item->title }}</h3>
                        <div class="news-summary">{{ $item->summary }}</div>
                        <div class="news-meta">
                            <span>{{ $item->created_at->format('d/m/Y') }}</span>
                            <span>{{ $item->views }} lượt xem</span>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>

            <div class="pagination-wrap">
                {{ $news->links() }}
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