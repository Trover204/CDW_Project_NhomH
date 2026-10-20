<!DOCTYPE html>
<html lang="vi">

<head>
    <script>if(localStorage.getItem("theme")==="dark") document.documentElement.setAttribute("data-theme", "dark");</script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sân Nhóm H - Đặt sân thể thao trực tuyến</title>

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
         HERO SECTION
    ====================================================== --}}
    <section class="hero">
        <div class="wrap">
            <div class="hero-inner">
                <div>

                    <div class="eyebrow">
                        Score Big with Sân Nhóm H
                    </div>

                    <h1>
                        Ra sân trong 30 giây,
                        không cần gọi điện hỏi lịch
                    </h1>

                    <p>
                        Bóng đá, cầu lông, pickleball, bóng chuyền —
                        hàng trăm sân quanh TP.HCM, giữ chỗ và thanh toán
                        ngay trên điện thoại.
                    </p>

                    {{-- CALL TO ACTION (LIME BUTTON) --}}
                    <div>
                        <a href="{{ route('courts.index') }}" class="btn-hero">
                            Đặt sân ngay
                        </a>
                    </div>

                    {{-- SEARCH BAR (PASTEL LIME PILL) --}}
                    <div class="search-card">

                        {{-- LOẠI SÂN --}}
                        <div class="field">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="12" r="9"/>
                                <path d="M12 3v18M3 12h18"/>
                            </svg>
                            <select name="sport_type">
                                <option value="">Loại sân</option>
                                @foreach($sportTypes as $sportType)
                                    <option value="{{ $sportType->id }}">{{ $sportType->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- KHU VỰC --}}
                        <div class="field">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M12 21s-7-6.2-7-11a7 7 0 0 1 14 0c0 4.8-7 11-7 11z"/>
                                <circle cx="12" cy="10" r="2.5"/>
                            </svg>
                            <select name="location">
                                <option value="">Khu vực</option>
                                @foreach($locations as $location)
                                    <option value="{{ $location->district }}">{{ $location->district }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- NGÀY --}}
                        <div class="field">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <rect x="3" y="5" width="18" height="16" rx="2"/>
                                <path d="M3 10h18M8 3v4M16 3v4"/>
                            </svg>
                            <input type="date" name="date" value="{{ date('Y-m-d') }}">
                        </div>

                        {{-- BUTTON --}}
                        <button type="button" class="search-btn">
                            Tìm sân
                        </button>
                    </div>

                    {{-- STATISTICS --}}
                    <div class="scoreboard">
                        <div class="stat">
                            <div class="stat-num">320+</div>
                            <div class="stat-label">sân đối tác</div>
                        </div>

                        <div class="stat">
                            <div class="stat-num">4.8/5</div>
                            <div class="stat-label">đánh giá trung bình</div>
                        </div>

                        <div class="stat">
                            <div class="stat-num">12k</div>
                            <div class="stat-label">lượt đặt / tháng</div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>

    {{-- =====================================================
         COURTS LIST SECTION
    ====================================================== --}}
    <section class="list" id="courts-section">
        <div class="wrap">
            <div class="panel">

                {{-- TITLE --}}
                <div class="section-head">
                    <div>
                        <h2>Sân nổi bật gần bạn</h2>
                    </div>
                    <a href="#">Xem tất cả &rarr;</a>
                </div>

                {{-- COURT GRID --}}
                <div class="grid">
                    @forelse ($courts as $court)
                        <div class="court-card">

                            {{-- TAG --}}
                            <span class="court-tag" style="display:flex; justify-content:space-between; width: calc(100% - 40px);">
                                <span>{{ $court->sportType->name ?? 'Thể thao' }}</span>
                                @auth
                                    @php
                                        $isFavorited = auth()->user()->favoriteCourts->contains($court->id);
                                    @endphp
                                    <form action="{{ route('favorites.toggle', $court->id) }}" method="POST" style="margin:0;">
                                        @csrf
                                        <button type="submit" style="background:none;border:none;cursor:pointer;padding:0;">
                                            <i class="ti {{ $isFavorited ? 'ti-heart-filled' : 'ti-heart' }}" style="font-size:18px;color:{{ $isFavorited ? '#F97316' : '#6B7A70' }};" aria-hidden="true"></i>
                                        </button>
                                    </form>
                                @else
                                    <a href="{{ route('login') }}" style="background:none;border:none;cursor:pointer;padding:0;text-decoration:none;">
                                        <i class="ti ti-heart" style="font-size:18px;color:#6B7A70;" aria-hidden="true"></i>
                                    </a>
                                @endauth
                            </span>

                            {{-- ICON / MEDIA --}}
                            <div class="court-media">
                                <i class="ti ti-ball-football" style="font-size:48px;color:#178A4C;" aria-hidden="true"></i>
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
                                    <span>{{ $court->facility->address ?? 'Không rõ địa chỉ' }}</span>
                                </div>

                                <div class="court-foot">
                                    <div>
                                        <div class="court-price">
                                            {{ number_format($court->price ?? 150000, 0, ',', '.') }}đ/h
                                        </div>
                                        <span class="court-slots">
                                            {{ $court->capacity ?? 0 }} khung trống
                                        </span>
                                    </div>

                                    <a href="#" class="btn-book">
                                        Đặt ngay
                                    </a>
                                </div>
                            </div>

                        </div>
                    @empty
                        <p>Hiện chưa có sân nào.</p>
                    @endforelse
                </div>

                {{-- CAROUSEL PAGINATION DOTS (MATCHING SAMPLE) --}}
                <div class="carousel-dots">
                    <span class="dot active"></span>
                    <span class="dot"></span>
                    <span class="dot"></span>
                </div>

                {{-- =================================================
                     PARTNER CTA
                ================================================== --}}
                <div class="cta">
                    <div>
                        <h2>Là chủ sân? Đưa sân của bạn lên Sân Nhóm H</h2>
                        <p>
                            Quản lý lịch đặt, thanh toán và khách hàng
                            ở một nơi duy nhất — miễn phí đăng ký
                            trong tháng đầu.
                        </p>
                    </div>

                    <a href="#" class="btn btn-solid" style="padding:12px 28px;">
                        Đăng ký làm đối tác
                    </a>
                </div>

            </div>
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
                <a href="#">Về chúng tôi</a>
                <a href="#">Liên hệ</a>
                <a href="#">Điều khoản</a>
            </div>
        </div>
    </footer>

    <script src="{{ asset('js/theme-toggle.js') }}"></script>
</body>

</html>
