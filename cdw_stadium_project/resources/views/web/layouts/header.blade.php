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
                    <a href="{{ route('profile.edit') }}" style="margin-right: 15px; font-weight: 600; color: var(--turf-dark); display: inline-flex; align-items: center; gap: 6px; text-decoration: none;">
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
