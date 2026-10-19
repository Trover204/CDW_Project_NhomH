@extends('web.layouts.app')

@section('title', 'Danh sách sân - Sân Nhóm H')

@section('content')
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
                <a href="#">Gói thành viên</a>
            </nav>

            {{-- LOGIN / REGISTER --}}
            <div class="nav-actions">
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

<section class="list" id="courts-section" style="padding-top: 40px; min-height: 80vh;">
    <div class="wrap">
        <div class="panel">

            {{-- TITLE --}}
            <div class="section-head">
                <div>
                    <h2>Danh sách sân</h2>
                </div>
            </div>

            {{-- COURT GRID --}}
            <div class="grid">
                @forelse ($courts as $court)
                    <div class="court-card" onclick="window.location.href='{{ route('courts.show', $court->id) }}'" style="cursor: pointer;">
                        {{-- TAG --}}
                        <span class="court-tag">
                            {{ $court->sportType->name ?? 'Thể thao' }}
                        </span>

                        {{-- ICON / MEDIA --}}
                        <div class="court-media" style="display: flex; align-items: center; justify-content: center; background: #E6F0FA;">
                            <i class="ti ti-ball-football" style="font-size: 40px; color: #2563EB;"></i>
                        </div>

                        {{-- INFORMATION --}}
                        <div class="court-body">
                            <div class="court-name">
                                {{ $court->name }}
                            </div>

                            <div class="court-loc">
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M12 21s-7-6.2-7-11a7 7 0 0 1 14 0c0 4.8-7 11-7 11z"/>
                                    <circle cx="12" cy="10" r="2.5"/>
                                </svg>
                                <span>{{ $court->facility->address ?? 'Không rõ' }}</span>
                            </div>

                            <div class="court-foot">
                                <div>
                                    <div class="court-price">
                                        Chi tiết
                                    </div>
                                    <span class="court-slots">
                                        {{ $court->capacity }} khung trống
                                    </span>
                                </div>

                                <a href="{{ route('courts.show', $court->id) }}" class="btn-book">
                                    Xem ngay
                                </a>
                            </div>
                        </div>

                    </div>
                @empty
                    <p>Hiện chưa có sân nào.</p>
                @endforelse
            </div>

            <div style="margin-top: 20px;">
                {{ $courts->links() }}
            </div>
        </div>
    </div>
</section>

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
@endsection
