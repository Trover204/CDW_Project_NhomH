<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sân yêu thích - Sân Nhóm H</title>

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Oswald:wght@500;600;700&family=Be+Vietnam+Pro:wght@400;500;600;700;800&display=swap"
        rel="stylesheet"
    >

    {{-- Tabler Icons --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css">

    {{-- Home Stylesheet --}}
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
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
                    <a href="#">Tin tức</a>
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

    {{-- =====================================================
         MAIN CONTENT
    ====================================================== --}}
    <main style="padding: 40px 0; background: #F6F8F6; min-height: 70vh;">
        <div class="wrap">
            <div style="font-family:Arial,Helvetica,sans-serif;border:0.5px solid #E1E8E3;border-radius:12px;overflow:hidden;background:#FFFFFF;max-width:900px;margin:0 auto;">
                <div style="display:flex;align-items:center;gap:6px;padding:8px 12px;border-bottom:0.5px solid #E1E8E3;background:#F7FAF8;">
                    <div style="width:8px;height:8px;border-radius:50%;background:#D8E2D9;"></div>
                    <div style="width:8px;height:8px;border-radius:50%;background:#D8E2D9;"></div>
                    <div style="width:8px;height:8px;border-radius:50%;background:#D8E2D9;"></div>
                    <div style="flex:1;margin-left:8px;height:18px;border-radius:6px;background:#FFFFFF;border:0.5px solid #E1E8E3;font-size:11px;color:#6B7A70;display:flex;align-items:center;padding:0 8px;">sannhomh.vn/san-yeu-thich</div>
                </div>
                <div style="padding:18px 20px;">
                    <div style="font-size:15px;font-weight:700;color:#16241C;margin-bottom:4px;">Sân yêu thích</div>
                    <div style="font-size:11.5px;color:#6B7A70;margin-bottom:16px;">Bạn đã lưu {{ $courts->total() }} sân — đặt lại nhanh chỉ với một chạm</div>

                    @if($courts->count() > 0)
                        <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:12px;">
                            @foreach($courts as $court)
                                @php
                                    $sportType = strtolower($court->sportType->name ?? '');
                                    $bgClass = 'E6F5EB';
                                    $iconColor = '#178A4C';
                                    $iconName = 'ti-ball-football';

                                    if(str_contains($sportType, 'cầu lông')) {
                                        $bgClass = 'E6F0FA';
                                        $iconColor = '#2563EB';
                                        $iconName = 'ti-ball-badminton';
                                    } elseif(str_contains($sportType, 'pickleball')) {
                                        $bgClass = 'FBEFE3';
                                        $iconColor = '#C2570A';
                                        $iconName = 'ti-ball-tennis';
                                    } elseif(str_contains($sportType, 'bóng rổ')) {
                                        $bgClass = 'FBEFE3';
                                        $iconColor = '#EA580C';
                                        $iconName = 'ti-ball-basketball';
                                    }
                                @endphp
                                <div style="border:0.5px solid #E1E8E3;border-radius:10px;overflow:hidden;position:relative;">
                                    <div style="height:66px;background:#{{ $bgClass }};position:relative;display:flex;align-items:center;justify-content:center;">
                                        <i class="ti {{ $iconName }}" style="font-size:24px;color:{{ $iconColor }};" aria-hidden="true"></i>
                                        <form action="{{ route('favorites.toggle', $court->id) }}" method="POST" style="position:absolute;top:6px;right:6px;margin:0;">
                                            @csrf
                                            <button type="submit" style="background:none;border:none;cursor:pointer;padding:0;">
                                                <i class="ti ti-heart-filled" style="font-size:15px;color:#F97316;" aria-hidden="true"></i>
                                            </button>
                                        </form>
                                    </div>
                                    <div style="padding:8px;">
                                        <div style="font-size:12px;font-weight:600;color:#16241C;">{{ $court->name }}</div>
                                        <div style="font-size:10.5px;color:#6B7A70;margin:3px 0 8px;">{{ $court->facility->name ?? 'Không rõ' }} · {{ number_format($court->price ?? 150000, 0, ',', '.') }}đ/giờ</div>
                                        <a href="#" style="text-decoration:none;display:block;">
                                            <div style="height:28px;border-radius:6px;background:#F97316;color:#fff;font-size:11px;font-weight:600;display:flex;align-items:center;justify-content:center;">Đặt lại</div>
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div style="margin-top: 20px;">
                            {{ $courts->links() }}
                        </div>
                    @else
                        <div style="padding: 30px; text-align: center; color: #6B7A70; background: #F7FAF8; border-radius: 8px;">
                            Bạn chưa có sân yêu thích nào.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </main>

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
                <a href="#">Về chúng tôi</a>
                <a href="#">Liên hệ</a>
                <a href="#">Điều khoản</a>
            </div>
        </div>
    </footer>

</body>

</html>
