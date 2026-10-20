<!DOCTYPE html>
<html lang="vi">
<head>
    <script>if(localStorage.getItem("theme")==="dark") document.documentElement.setAttribute("data-theme", "dark");</script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Sân Nhóm H')</title>

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Oswald:wght@500;600;700&family=Be+Vietnam+Pro:wght@400;500;600;700;800&display=swap"
        rel="stylesheet"
    >

    {{-- Tabler Icons --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css">
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
    @stack('styles')
</head>

<body>
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

    <main>
        @yield('content')
    </main>

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

    @stack('scripts')
    <script src="{{ asset('js/theme-toggle.js') }}"></script>
</body>

</html>
