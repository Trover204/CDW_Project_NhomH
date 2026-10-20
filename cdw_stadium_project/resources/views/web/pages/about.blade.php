@extends('web.layouts.app')

@section('title', 'Về chúng tôi - Sân Nhóm H')

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

    <section style="padding: 60px 0; background-color: var(--bg-page); min-height: calc(100vh - 200px);">
        <div class="wrap">
            <div style="max-width: 800px; margin: 0 auto; background: var(--surface); padding: 40px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.05);">
                <h1 style="font-size: 36px; color: var(--turf-dark); margin-bottom: 24px; font-family: 'Oswald', sans-serif; text-align: center;">Về chúng tôi</h1>

                <div style="font-size: 16px; line-height: 1.6; color: var(--ink);">
                    <p style="margin-bottom: 16px;">Chào mừng bạn đến với <strong>Sân Nhóm H</strong> - nền tảng đặt sân thể thao hàng đầu, giúp kết nối những người đam mê thể thao với các hệ thống sân bãi chất lượng trên toàn quốc.</p>

                    <h2 style="font-size: 24px; color: var(--turf-dark); margin-top: 32px; margin-bottom: 16px;">Sứ mệnh của chúng tôi</h2>
                    <p style="margin-bottom: 16px;">Chúng tôi ra đời với sứ mệnh đơn giản hóa việc tìm kiếm và đặt sân thể thao. Không còn những cuộc gọi dài dòng, không còn phải chờ đợi, chỉ với vài cú click chuột, bạn đã có thể sở hữu sân chơi ưng ý cho trận đấu của mình.</p>

                    <h2 style="font-size: 24px; color: var(--turf-dark); margin-top: 32px; margin-bottom: 16px;">Dịch vụ cung cấp</h2>
                    <ul style="list-style-type: disc; margin-left: 20px; margin-bottom: 16px;">
                        <li style="margin-bottom: 8px;">Hệ thống đặt sân trực tuyến 24/7.</li>
                        <li style="margin-bottom: 8px;">Cung cấp thông tin chi tiết về từng sân (giá cả, tiện ích, vị trí).</li>
                        <li style="margin-bottom: 8px;">Hỗ trợ thanh toán an toàn, linh hoạt.</li>
                        <li style="margin-bottom: 8px;">Chương trình thẻ thành viên với nhiều ưu đãi hấp dẫn.</li>
                    </ul>

                    <h2 style="font-size: 24px; color: var(--turf-dark); margin-top: 32px; margin-bottom: 16px;">Tại sao chọn chúng tôi?</h2>
                    <p style="margin-bottom: 16px;">Với hệ thống quản lý chuyên nghiệp, giao diện thân thiện và đội ngũ hỗ trợ nhiệt tình, <strong>Sân Nhóm H</strong> cam kết mang lại trải nghiệm tốt nhất cho người dùng và các đối tác quản lý sân.</p>
                </div>
            </div>
        </div>
    </section>

    @include('layouts.footer')
@endsection
