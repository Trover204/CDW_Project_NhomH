```blade
<!DOCTYPE html>
<html lang="vi">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1"
    >

    <title>Sân Nhóm H - Đặt sân thể thao</title>

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">

    <link
        rel="preconnect"
        href="https://fonts.gstatic.com"
        crossorigin
    >

    <link
        href="https://fonts.googleapis.com/css2?family=Oswald:wght@500;600;700&family=Be+Vietnam+Pro:wght@400;500;600;700&display=swap"
        rel="stylesheet"
    >

    <style>

        /* =====================================================
           ROOT
        ===================================================== */

        :root {
            --pitch-dark: #0B3D24;
            --pitch: #15723F;
            --pitch-light: #E7F3EA;

            --ink: #132025;
            --ink-soft: #526572;

            --line: #D3E1EA;

            --amber: #F97316;
            --amber-ink: #8A3A0A;

            --nav-bg: rgba(255,255,255,.94);
        }


        /* =====================================================
           RESET
        ===================================================== */

        * {
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            margin: 0;

            color: var(--ink);

            font-family:
                'Be Vietnam Pro',
                sans-serif;

            -webkit-font-smoothing: antialiased;

            background-color: #0B3D24;

            background-image:
                linear-gradient(
                    180deg,
                    rgba(6,26,15,.55),
                    rgba(6,26,15,.78)
                ),
                url('{{ asset("images/home-bg.jpg") }}');

            background-size: cover;

            background-position: center;

            background-attachment: fixed;
        }


        a {
            color: inherit;

            text-decoration: none;
        }


        h1,
        h2,
        .brand,
        .stat-num {
            font-family: 'Oswald', sans-serif;

            letter-spacing: .2px;
        }


        .wrap {
            width: 100%;

            max-width: 1120px;

            margin: 0 auto;

            padding: 0 24px;
        }


        /* =====================================================
           HEADER
        ===================================================== */

        header {
            position: sticky;

            top: 0;

            z-index: 100;

            padding-top: 18px;
        }


        .nav {
            display: flex;

            align-items: center;

            justify-content: space-between;

            gap: 20px;

            padding: 14px 22px;

            background: var(--nav-bg);

            backdrop-filter: blur(10px);

            -webkit-backdrop-filter: blur(10px);

            border: 1px solid rgba(255,255,255,.6);

            border-radius: 18px;

            box-shadow:
                0 22px 44px -18px rgba(0,0,0,.45),
                0 2px 10px rgba(0,0,0,.08);
        }


        /* =====================================================
           LOGO
        ===================================================== */

        .brand {
            display: flex;

            align-items: center;

            gap: 8px;

            font-size: 21px;

            font-weight: 700;

            color: var(--pitch-dark);

            white-space: nowrap;
        }


        .brand span {
            color: var(--amber);
        }


        /* =====================================================
           MENU
        ===================================================== */

        .nav-links {
            display: flex;

            align-items: center;

            gap: 28px;

            font-size: 14px;

            font-weight: 500;

            color: var(--ink-soft);
        }


        .nav-links a {
            transition: .2s;
        }


        .nav-links a:hover {
            color: var(--pitch);
        }


        /* =====================================================
           BUTTON
        ===================================================== */

        .nav-actions {
            display: flex;

            align-items: center;

            gap: 10px;
        }


        .btn {
            display: inline-flex;

            align-items: center;

            justify-content: center;

            border-radius: 8px;

            font-size: 13.5px;

            font-weight: 600;

            transition: .2s;

            cursor: pointer;
        }


        .btn-ghost {
            padding: 9px 18px;

            background: white;

            border: 1px solid var(--line);

            color: var(--ink);
        }


        .btn-ghost:hover {
            border-color: var(--pitch);

            color: var(--pitch);
        }


        .btn-solid {
            padding: 9px 18px;

            background: var(--amber);

            color: white;

            border: none;
        }


        .btn-solid:hover {
            background: var(--amber-ink);
        }


        /* =====================================================
           HERO
        ===================================================== */

        .hero {
            position: relative;

            padding: 70px 0 75px;

            color: #F3FBF5;
        }


        .hero-inner {
            display: grid;

            grid-template-columns: 1.1fr .9fr;

            gap: 40px;

            align-items: center;
        }


        .eyebrow {
            margin-bottom: 10px;

            font-size: 13.5px;

            color: #BEE6CB;
        }


        .hero h1 {
            max-width: 560px;

            margin: 0 0 14px;

            font-size: 42px;

            line-height: 1.15;

            font-weight: 700;
        }


        .hero p {
            max-width: 500px;

            margin: 0 0 28px;

            font-size: 15px;

            line-height: 1.7;

            color: #DCEFE1;
        }


        /* =====================================================
           SEARCH
        ===================================================== */

        .search-card {
            display: grid;

            grid-template-columns:
                1fr
                1fr
                1fr
                auto;

            gap: 8px;

            padding: 14px;

            background: rgba(255,255,255,.96);

            border-radius: 12px;

            box-shadow:
                0 18px 40px -18px rgba(0,0,0,.5);
        }


        .field {
            display: flex;

            align-items: center;

            gap: 8px;

            min-height: 44px;

            padding: 10px 12px;

            background: white;

            border: 1px solid var(--line);

            border-radius: 8px;

            font-size: 13px;

            color: var(--ink-soft);
        }


        .field svg {
            flex-shrink: 0;

            color: var(--pitch);
        }


        .search-btn {
            min-height: 44px;

            padding: 0 20px;

            border: none;

            border-radius: 8px;

            background: var(--amber);

            color: white;

            font-size: 13.5px;

            font-weight: 600;

            cursor: pointer;

            transition: .2s;
        }


        .search-btn:hover {
            background: var(--amber-ink);
        }


        /* =====================================================
           STATISTICS
        ===================================================== */

        .scoreboard {
            display: flex;

            margin-top: 34px;

            border-top:
                1px solid
                rgba(255,255,255,.3);
        }


        .stat {
            flex: 1;

            padding: 18px 0 0;

            border-right:
                1px solid
                rgba(255,255,255,.3);
        }


        .stat:last-child {
            border-right: none;
        }


        .stat-num {
            font-size: 27px;

            font-weight: 600;

            color: white;
        }


        .stat-label {
            margin-top: 2px;

            font-size: 12px;

            color: #CFEBD8;
        }


        /* =====================================================
           COURT SECTION
        ===================================================== */

        section.list {
            padding: 44px 0 56px;
        }


        .panel {
            padding: 32px;

            background: rgba(255,255,255,.95);

            border-radius: 20px;

            backdrop-filter: blur(6px);

            box-shadow:
                0 20px 60px
                rgba(0,0,0,.18);
        }


        .section-head {
            display: flex;

            align-items: baseline;

            justify-content: space-between;

            gap: 20px;

            margin-bottom: 26px;
        }


        .section-head h2 {
            margin: 0;

            font-size: 25px;

            font-weight: 600;

            color: var(--pitch-dark);
        }


        .section-head a {
            font-size: 13.5px;

            color: var(--ink-soft);

            border-bottom:
                1px solid
                var(--line);
        }


        /* =====================================================
           COURT GRID
        ===================================================== */

        .grid {
            display: grid;

            grid-template-columns:
                repeat(4, 1fr);

            gap: 18px;
        }


        .court-card {
            position: relative;

            overflow: hidden;

            background: white;

            border:
                1px solid
                var(--line);

            border-radius: 10px;

            transition: .2s;
        }


        .court-card:hover {
            transform: translateY(-4px);

            box-shadow:
                0 12px 30px
                rgba(0,0,0,.12);
        }


        .court-tag {
            position: absolute;

            top: 10px;

            left: 0;

            z-index: 2;

            padding:
                4px
                10px
                4px
                12px;

            background: var(--pitch-dark);

            color: white;

            font-size: 11px;

            font-weight: 600;

            border-radius:
                0
                6px
                6px
                0;
        }


        .court-media {
            height: 120px;

            display: flex;

            align-items: center;

            justify-content: center;

            background: var(--pitch-light);
        }


        .court-media svg {
            width: 38px;

            height: 38px;

            color: var(--pitch);
        }


        .court-body {
            padding: 14px;
        }


        .court-name {
            font-size: 14.5px;

            font-weight: 600;

            color: var(--ink);
        }


        .court-loc {
            margin: 4px 0 10px;

            font-size: 12px;

            color: var(--ink-soft);
        }


        .court-foot {
            display: flex;

            align-items: center;

            justify-content: space-between;

            gap: 8px;
        }


        .court-price {
            font-size: 13.5px;

            font-weight: 700;

            color: var(--amber-ink);
        }


        .court-slots {
            font-size: 11px;

            color: var(--pitch);
        }


        /* =====================================================
           CTA
        ===================================================== */

        .cta {
            display: flex;

            align-items: center;

            justify-content: space-between;

            gap: 24px;

            flex-wrap: wrap;

            margin-top: 24px;

            padding: 36px 32px;

            background: var(--pitch-light);

            border-radius: 14px;
        }


        .cta h2 {
            margin: 0 0 6px;

            font-size: 22px;

            color: var(--pitch-dark);
        }


        .cta p {
            max-width: 520px;

            margin: 0;

            font-size: 13.5px;

            line-height: 1.6;

            color: var(--ink-soft);
        }


        /* =====================================================
           FOOTER
        ===================================================== */

        footer {
            padding: 24px 0;
        }


        .foot-row {
            display: flex;

            align-items: center;

            justify-content: space-between;

            flex-wrap: wrap;

            gap: 12px;

            font-size: 12.5px;

            color: #DCEFE1;
        }


        .foot-links {
            display: flex;

            gap: 20px;
        }


        .foot-links a:hover {
            color: white;
        }


        /* =====================================================
           RESPONSIVE - TABLET
        ===================================================== */

        @media (max-width: 900px) {

            .hero-inner {
                grid-template-columns: 1fr;
            }


            .search-card {
                grid-template-columns: 1fr 1fr;
            }


            .search-btn {
                grid-column: 1 / -1;
            }


            .grid {
                grid-template-columns:
                    repeat(2, 1fr);
            }

        }


        /* =====================================================
           RESPONSIVE - MOBILE
        ===================================================== */

        @media (max-width: 720px) {

            header {
                padding-top: 10px;
            }


            .nav {
                padding: 12px 15px;
            }


            .nav-links {
                display: none;
            }


            .brand {
                font-size: 18px;
            }


            .btn-ghost,
            .btn-solid {
                padding: 8px 12px;
            }


            .hero {
                padding:
                    50px
                    0
                    55px;
            }


            .hero h1 {
                font-size: 34px;
            }


            .scoreboard {
                flex-wrap: wrap;
            }


            .stat {
                flex:
                    1 1 50%;

                border-right: none;

                margin-bottom: 14px;
            }


            .panel {
                padding: 22px;
            }


            .cta {
                padding: 26px 22px;
            }

        }


        /* =====================================================
           RESPONSIVE - PHONE
        ===================================================== */

        @media (max-width: 520px) {

            .wrap {
                padding: 0 15px;
            }


            .nav-actions .btn-ghost {
                display: none;
            }


            .search-card {
                grid-template-columns: 1fr;
            }


            .search-btn {
                grid-column: auto;
            }


            .grid {
                grid-template-columns: 1fr;
            }


            .section-head {
                align-items: flex-start;
            }


            .section-head h2 {
                font-size: 21px;
            }


            .foot-row {
                flex-direction: column;

                align-items: flex-start;
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

                <a
                    href="{{ route('home') }}"
                    class="brand"
                >
                    Sân Nhóm <span>H</span>
                </a>


                {{-- MENU --}}

                <nav class="nav-links">

                    <a href="{{ route('home') }}">
                        Trang chủ
                    </a>

                    <a href="#">
                        Tìm sân
                    </a>

                    <a href="#">
                        Tin tức
                    </a>

                    <a href="#">
                        Gói thành viên
                    </a>

                </nav>


                {{-- LOGIN / REGISTER --}}

                <div class="nav-actions">

                    <a
                        href="#"
                        class="btn btn-ghost"
                    >
                        Đăng nhập
                    </a>

                    <a
                        href="#"
                        class="btn btn-solid"
                    >
                        Đăng ký
                    </a>

                </div>

            </div>

        </div>

    </header>



    {{-- =====================================================
         HERO
    ====================================================== --}}

    <section class="hero">

        <div class="wrap">

            <div class="hero-inner">

                <div>


                    <div class="eyebrow">
                        Đặt sân trực tuyến, xem lịch trống theo thời gian thực
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


                    {{-- SEARCH --}}

                    <div class="search-card">


                        {{-- LOẠI SÂN --}}

                        <div class="field">

                            <svg
                                width="15"
                                height="15"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2"
                            >

                                <circle
                                    cx="12"
                                    cy="12"
                                    r="9"
                                />

                                <path
                                    d="M12 3v18M3 12h18"
                                />

                            </svg>

                            <span>
                                Loại sân
                            </span>

                        </div>


                        {{-- KHU VỰC --}}

                        <div class="field">

                            <svg
                                width="15"
                                height="15"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2"
                            >

                                <path
                                    d="M12 21s-7-6.2-7-11a7 7 0 0 1 14 0c0 4.8-7 11-7 11z"
                                />

                                <circle
                                    cx="12"
                                    cy="10"
                                    r="2.5"
                                />

                            </svg>

                            <span>
                                Khu vực
                            </span>

                        </div>


                        {{-- NGÀY --}}

                        <div class="field">

                            <svg
                                width="15"
                                height="15"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2"
                            >

                                <rect
                                    x="3"
                                    y="5"
                                    width="18"
                                    height="16"
                                    rx="2"
                                />

                                <path
                                    d="M3 10h18M8 3v4M16 3v4"
                                />

                            </svg>

                            <span>
                                Hôm nay
                            </span>

                        </div>


                        {{-- BUTTON --}}

                        <button
                            type="button"
                            class="search-btn"
                        >
                            Tìm sân
                        </button>

                    </div>



                    {{-- STATISTICS --}}

                    <div class="scoreboard">


                        <div class="stat">

                            <div class="stat-num">
                                320+
                            </div>

                            <div class="stat-label">
                                sân đối tác
                            </div>

                        </div>


                        <div class="stat">

                            <div class="stat-num">
                                4.8/5
                            </div>

                            <div class="stat-label">
                                đánh giá trung bình
                            </div>

                        </div>


                        <div class="stat">

                            <div class="stat-num">
                                12k
                            </div>

                            <div class="stat-label">
                                lượt đặt / tháng
                            </div>

                        </div>


                    </div>

                </div>

            </div>

        </div>

    </section>



    {{-- =====================================================
         COURTS
    ====================================================== --}}

    <section class="list">

        <div class="wrap">

            <div class="panel">


                {{-- TITLE --}}

                <div class="section-head">

                    <h2>
                        Sân nổi bật gần bạn
                    </h2>

                    <a href="#">
                        Xem tất cả
                    </a>

                </div>



                {{-- COURT LIST --}}

                <div class="grid">

                    @forelse ($courts as $court)


                        <div class="court-card">


                            {{-- TAG --}}

                            <span class="court-tag">
                                {{ $court['tag'] }}
                            </span>


                            {{-- ICON --}}

                            <div class="court-media">

                                <svg
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="1.6"
                                >

                                    {!! $court['icon'] !!}

                                </svg>

                            </div>


                            {{-- INFORMATION --}}

                            <div class="court-body">


                                <div class="court-name">
                                    {{ $court['name'] }}
                                </div>


                                <div class="court-loc">
                                    {{ $court['loc'] }}
                                </div>


                                <div class="court-foot">


                                    <span class="court-price">
                                        {{ $court['price_formatted'] }}/giờ
                                    </span>


                                    <span class="court-slots">
                                        {{ $court['slots'] }}
                                        khung trống
                                    </span>


                                </div>

                            </div>

                        </div>


                    @empty


                        <p>
                            Hiện chưa có sân nào.
                        </p>


                    @endforelse

                </div>



                {{-- =================================================
                     PARTNER CTA
                ================================================== --}}

                <div class="cta">


                    <div>

                        <h2>
                            Là chủ sân?
                            Đưa sân của bạn lên Sân Nhóm H
                        </h2>


                        <p>
                            Quản lý lịch đặt, thanh toán và khách hàng
                            ở một nơi duy nhất — miễn phí đăng ký
                            trong tháng đầu.
                        </p>

                    </div>


                    <a
                        href="#"
                        class="btn btn-solid"
                        style="padding:12px 24px;"
                    >
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


            <span>
                © {{ date('Y') }} Sân Nhóm H
            </span>


            <div class="foot-links">

                <a href="#">
                    Về chúng tôi
                </a>

                <a href="#">
                    Liên hệ
                </a>

                <a href="#">
                    Điều khoản
                </a>

            </div>


        </div>

    </footer>


</body>

</html>
```
