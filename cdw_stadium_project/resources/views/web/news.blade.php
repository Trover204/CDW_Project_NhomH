<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tin tức - Sân Nhóm H</title>

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    {{-- Stylesheet --}}
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
    <style>
        .news-hero {
            padding: 80px 0 60px;
            background: linear-gradient(135deg, #106764 0%, #0D382E 100%);
            color: white;
            text-align: center;
        }
        .news-hero h1 {
            font-size: 42px;
            font-weight: 700;
            margin-bottom: 16px;
        }
        .news-hero p {
            font-size: 16px;
            color: #CFEBD8;
            max-width: 600px;
            margin: 0 auto;
        }

        .news-list {
            padding: 60px 0;
            background-color: var(--bg-page);
        }

        .news-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 30px;
        }

        .news-card {
            background: #FFFFFF;
            border: 1px solid var(--line);
            border-radius: 18px;
            overflow: hidden;
            transition: all 0.25s ease;
            box-shadow: 0 6px 18px rgba(13, 56, 46, 0.04);
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        .news-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 16px 36px rgba(13, 56, 46, 0.12);
            border-color: #B5D9C4;
        }

        .news-image {
            width: 100%;
            height: 200px;
            background: #EEF7F2;
            position: relative;
        }

        .news-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .news-category {
            position: absolute;
            top: 15px;
            left: 15px;
            padding: 4px 12px;
            background: var(--turf-teal);
            color: white;
            font-size: 12px;
            font-weight: 600;
            border-radius: 9999px;
            text-transform: uppercase;
        }

        .news-content {
            padding: 24px;
            display: flex;
            flex-direction: column;
            flex-grow: 1;
        }

        .news-date {
            font-size: 13px;
            color: var(--ink-soft);
            margin-bottom: 12px;
        }

        .news-title {
            font-size: 20px;
            font-weight: 700;
            color: var(--turf-dark);
            margin-bottom: 12px;
            line-height: 1.4;
        }

        .news-summary {
            font-size: 14px;
            color: var(--ink-soft);
            line-height: 1.6;
            margin-bottom: 20px;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .news-footer {
            margin-top: auto;
            padding-top: 16px;
            border-top: 1px solid var(--line-light);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .read-more {
            color: var(--turf-teal);
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 6px;
            transition: color 0.2s;
        }

        .read-more:hover {
            color: var(--turf-dark);
        }

        .news-views {
            font-size: 13px;
            color: var(--ink-muted);
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .pagination-container {
            margin-top: 40px;
            display: flex;
            justify-content: center;
        }

        @media (max-width: 960px) {
            .news-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 720px) {
            .news-grid {
                grid-template-columns: 1fr;
            }
            .news-hero h1 {
                font-size: 32px;
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
                    <a href="{{ route('home') }}">Trang chủ</a>
                    <a href="#">Tìm sân</a>
                    <a href="{{ route('news.index') }}" class="active">Tin tức</a>
                    <a href="#">Gói thành viên</a>
                </nav>

                {{-- LOGIN / REGISTER --}}
                <div class="nav-actions">
                    @guest
                        <a href="{{ route('login') }}" class="btn btn-ghost">Đăng nhập</a>
                        <a href="{{ route('login') }}?tab=register" class="btn btn-solid">Đăng ký</a>
                    @else
                        <span style="margin-right: 15px; font-weight: 500;">Xin chào, {{ Auth::user()->name }}</span>
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
         HERO SECTION
    ====================================================== --}}
    <section class="news-hero">
        <div class="wrap">
            <h1>Tin tức & Sự kiện</h1>
            <p>Cập nhật những thông tin mới nhất về thể thao, các giải đấu và hoạt động từ Sân Nhóm H.</p>
        </div>
    </section>

    {{-- =====================================================
         NEWS LIST
    ====================================================== --}}
    <section class="news-list">
        <div class="wrap">
            <div class="news-grid">
                @forelse($news as $item)
                    <div class="news-card">
                        <div class="news-image">
                            @if($item->category)
                                <span class="news-category">{{ $item->category }}</span>
                            @endif
                            @if($item->image)
                                <img src="{{ Storage::url($item->image) }}" alt="{{ $item->title }}">
                            @else
                                <div style="width:100%; height:100%; display:flex; align-items:center; justify-content:center; background:#E2F1E8; color:var(--turf-teal);">
                                    <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                        <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                                        <circle cx="8.5" cy="8.5" r="1.5"></circle>
                                        <polyline points="21 15 16 10 5 21"></polyline>
                                    </svg>
                                </div>
                            @endif
                        </div>
                        <div class="news-content">
                            <div class="news-date">{{ $item->created_at->format('d/m/Y') }}</div>
                            <h3 class="news-title">{{ $item->title }}</h3>
                            <div class="news-summary">
                                {{ $item->summary ?? Str::limit(strip_tags($item->content), 120) }}
                            </div>
                            <div class="news-footer">
                                <a href="{{ route('news.show', $item->slug) }}" class="read-more">
                                    Đọc tiếp
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M5 12h14M12 5l7 7-7 7"/>
                                    </svg>
                                </a>
                                <div class="news-views">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                        <circle cx="12" cy="12" r="3"></circle>
                                    </svg>
                                    {{ $item->views }}
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div style="grid-column: 1 / -1; text-align: center; padding: 40px; background: white; border-radius: 12px;">
                        <p style="color: var(--ink-soft); font-size: 16px;">Hiện chưa có bài viết nào.</p>
                    </div>
                @endforelse
            </div>

            <div class="pagination-container">
                {{ $news->links() }}
            </div>
        </div>
    </section>

    {{-- =====================================================
         FOOTER
    ====================================================== --}}
    <footer>
        <div class="wrap foot-row">
            <span>
                © {{ date('Y') }} Sân Nhóm H. All rights reserved.
            </span>

            <div class="foot-links">
                <a href="#">Về chúng tôi</a>
                <a href="#">Liên hệ</a>
                <a href="#">Điều khoản</a>
            </div>
        </div>
    </footer>

</body>
</html>
