<<<<<<< HEAD
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tin tức thể thao - Sân Nhóm H</title>

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    {{-- Stylesheet --}}
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
    <style>
        .news-page {
            padding: 40px 0 80px;
            background-color: var(--bg-page, #F7FAF8);
            min-height: 80vh;
        }

        .news-header-title {
            font-size: 28px;
            font-weight: 700;
            color: var(--turf-dark, #16241C);
            margin-bottom: 24px;
        }

        .news-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 20px;
        }

        .news-card {
            background: #FFFFFF;
            border: 1px solid #E1E8E3;
            border-radius: 12px;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            text-decoration: none;
            color: inherit;
            transition: all 0.2s ease;
        }

        .news-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 24px rgba(13, 56, 46, 0.08);
            border-color: #B5D9C4;
        }

        .news-card-img {
            height: 180px;
            background: #E6F5EB;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        .news-card-img img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .news-card-body {
            padding: 16px;
            display: flex;
            flex-direction: column;
            flex: 1;
        }

        .news-card-tag {
            align-self: flex-start;
            background: #FBEFE3;
            color: #C2570A;
            font-size: 11px;
            font-weight: 600;
            padding: 3px 10px;
            border-radius: 20px;
            margin-bottom: 10px;
        }

        .news-card-title {
            font-size: 16px;
            font-weight: 700;
            color: #16241C;
            line-height: 1.4;
            margin-bottom: 8px;
        }

        .news-card-summary {
            font-size: 13px;
            color: #6B7A70;
            line-height: 1.5;
            margin-bottom: 12px;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .news-card-meta {
            margin-top: auto;
            font-size: 12px;
            color: #6B7A70;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .pagination-container {
            margin-top: 32px;
            display: flex;
            justify-content: center;
        }
    </style>
</head>

<body>
    {{-- HEADER --}}
    <header>
        <div class="wrap">
            <div class="nav">
                <a href="{{ route('home') }}" class="brand">
                    Sân Nhóm <span>H</span>
                </a>
                <nav class="nav-links">
                    <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">Trang chủ</a>
                    <a href="{{ route('courts.index') }}" class="{{ request()->routeIs('courts.*') ? 'active' : '' }}">Tìm sân</a>
                    <a href="{{ route('news.index') }}" class="{{ request()->routeIs('news.*') ? 'active' : '' }}">Tin tức</a>
                    <a href="#">Gói thành viên</a>
                </nav>
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

    {{-- MAIN CONTENT --}}
    <main class="news-page">
        <div class="wrap">
            <h1 class="news-header-title">Tin tức & Mẹo chơi thể thao</h1>

            <div class="news-grid">
                @forelse ($news as $item)
                    <a href="{{ route('news.show', $item->slug) }}" class="news-card">
                        <div class="news-card-img">
                            @if ($item->image)
                                <img src="{{ Storage::url($item->image) }}" alt="{{ $item->title }}">
                            @else
                                <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="#178A4C" stroke-width="2">
                                    <path d="M4 22h16a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2H8a2 2 0 0 0-2 2v16a2 2 0 0 1-2 2Zm0 0a2 2 0 0 1-2-2v-9c0-1.1.9-2 2-2h2"></path>
                                    <path d="M18 14h-8"></path>
                                    <path d="M15 18h-5"></path>
                                    <path d="M10 6h8v4h-8V6Z"></path>
                                </svg>
                            @endif
                        </div>
                        <div class="news-card-body">
                            @if ($item->category)
                                <span class="news-card-tag">{{ $item->category }}</span>
                            @endif
                            <div class="news-card-title">{{ $item->title }}</div>
                            @if ($item->summary)
                                <div class="news-card-summary">{{ $item->summary }}</div>
                            @endif
                            <div class="news-card-meta">
                                <span>{{ $item->created_at ? $item->created_at->format('d/m/Y') : '' }}</span>
                                @if ($item->user)
                                    <span>{{ $item->user->name }}</span>
                                @endif
                            </div>
                        </div>
                    </a>
                @empty
                    <div style="grid-column: 1 / -1; padding: 40px; text-align: center; color: #6B7A70; background: #FFFFFF; border-radius: 12px; border: 1px solid #E1E8E3;">
                        Hiện chưa có bài viết tin tức nào được đăng tải.
                    </div>
                @endforelse
            </div>

            <div class="pagination-container">
                {{ $news->links() }}
            </div>
        </div>
    </main>

    {{-- FOOTER --}}
    <footer>
        <div class="wrap foot-row">
            <span>© {{ date('Y') }} Sân Nhóm H. All rights reserved.</span>
            <div class="foot-links">
                <a href="#">Về chúng tôi</a>
                <a href="#">Liên hệ</a>
                <a href="#">Điều khoản</a>
            </div>
        </div>
    </footer>
</body>
</html>
=======
@extends('web.layouts.app')

@section('title', 'Tin tức & Khuyến mãi — Sân Nhóm H')

@section('content')
<div style="font-family:Arial,Helvetica,sans-serif;max-width:1200px;margin:24px auto;padding:0 16px;">
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;">
        <h1 style="font-size:22px;font-weight:700;color:#16241C;margin:0;">Tin tức &amp; mẹo chơi thể thao</h1>
    </div>

    @if(isset($news) && $news->count() > 0)
        <div style="display:grid;grid-template-columns:repeat(auto-fill, minmax(280px, 1fr));gap:20px;">
            @foreach($news as $item)
                <div style="border:0.5px solid #E1E8E3;border-radius:12px;overflow:hidden;background:#FFFFFF;display:flex;flex-direction:column;box-shadow:0 2px 6px rgba(0,0,0,0.02);">
                    <div style="height:140px;background:#E6F5EB;display:flex;align-items:center;justify-content:center;">
                        @if($item->thumbnail)
                            <img src="{{ asset($item->thumbnail) }}" alt="{{ $item->title }}" style="width:100%;height:100%;object-fit:cover;">
                        @else
                            <i class="ti ti-news" style="font-size:36px;color:#178A4C;" aria-hidden="true"></i>
                        @endif
                    </div>
                    <div style="padding:16px;display:flex;flex-direction:column;flex:1;">
                        @if($item->category)
                            <span style="display:inline-block;align-self:flex-start;background:#FBEFE3;color:#C2570A;font-size:11px;font-weight:600;padding:2px 8px;border-radius:20px;margin-bottom:8px;">
                                {{ $item->category }}
                            </span>
                        @endif
                        <h3 style="font-size:15px;font-weight:700;color:#16241C;margin:0 0 8px;line-height:1.4;">
                            <a href="{{ route('news.show', $item->slug) }}" style="color:inherit;text-decoration:none;">
                                {{ $item->title }}
                            </a>
                        </h3>
                        <p style="font-size:13px;color:#6B7A70;margin:0 0 12px;flex:1;line-height:1.5;">
                            {{ Str::limit(strip_tags($item->summary ?? $item->content), 90) }}
                        </p>
                        <div style="font-size:11px;color:#8AA89B;display:flex;justify-content:space-between;align-items:center;border-top:1px solid #F0F4F1;padding-top:10px;">
                            <span>{{ $item->created_at ? $item->created_at->format('d/m/Y') : '' }}</span>
                            <a href="{{ route('news.show', $item->slug) }}" style="color:#178A4C;font-weight:600;text-decoration:none;font-size:12px;">Đọc tiếp &rarr;</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div style="margin-top:24px;">
            {{ $news->links() }}
        </div>
    @else
        <div style="text-align:center;padding:48px;background:#FFFFFF;border-radius:12px;border:1px solid #E1E8E3;">
            <p style="color:#6B7A70;font-size:14px;margin:0;">Chưa có bài viết nào được đăng.</p>
        </div>
    @endif
</div>
@endsection
>>>>>>> hai/feature/thongke
