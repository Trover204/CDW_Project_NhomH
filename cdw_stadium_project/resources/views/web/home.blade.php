@extends('web.layouts.app')

@section('title', __('Sân Nhóm H - Nền tảng đặt sân thể thao nhanh chóng & tiện lợi'))

@section('content')

    {{-- =====================================================
         HERO SECTION (BACKGROUND CAROUSEL + FLOATING SEARCH BAR)
    ====================================================== --}}
    @php
        $heroBannerImages = [
            asset('img_new/banner.jpg'),
            asset('img_new/banner1.jpg'),
            asset('img_new/banner2.jpg'),
            asset('img_new/banner3.jpg'),
            asset('img_new/banner4.jpg'),
        ];
    @endphp
    <section
        class="hero hero-enhanced hero-nhomh-banner"
        id="heroBannerSection"
        data-hero-carousel
        data-interval="5000"
        data-loop="true"
    >
        {{-- BACKGROUND SWIPE CAROUSEL --}}
        <div class="hero-bg-carousel" id="heroBgCarousel" aria-hidden="true">
            <div class="hero-bg-track" id="heroBgTrack">
                @foreach($heroBannerImages as $bannerIndex => $bannerUrl)
                    <div
                        class="hero-bg-slide {{ $bannerIndex === 0 ? 'is-active' : '' }}"
                        data-hero-slide="{{ $bannerIndex }}"
                        style="background-image: url('{{ $bannerUrl }}');"
                    ></div>
                @endforeach
                {{-- Seamless infinite loop clone of the first banner --}}
                <div
                    class="hero-bg-slide is-clone"
                    data-hero-slide="clone"
                    style="background-image: url('{{ $heroBannerImages[0] }}');"
                ></div>
            </div>
            <div class="hero-bg-overlay"></div>
        </div>

        <div class="hero-slash-top" aria-hidden="true"></div>
        <div class="hero-slash-bottom-left" aria-hidden="true"></div>
        <div class="hero-slash-bottom-right" aria-hidden="true"></div>

        <div class="wrap">
            <div class="hero-inner hero-banner-layout">
                {{-- LEFT COLUMN: BRAND BADGE, SKEWED HEADING, SUBTITLE, FEATURE ICONS, CTA & STATS --}}
                <div class="hero-content-box">

                    {{-- Brand Badge & Eyebrow --}}
                    <div class="hero-brand-row">
                        <div class="hero-brand-mark">
                            <span class="hero-h-icon">H</span>
                            <div class="hero-brand-text">
                                <strong>NHOM H</strong>
                                <small>Play • Book • Shop</small>
                            </div>
                        </div>

                        <div class="eyebrow">
                            <i class="ti ti-trophy"></i> {{ __('Score Big with Sân Nhóm H') }}
                        </div>
                    </div>

                    {{-- Skewed Brush Banner Title + Highlighted Subtitle matching banner.jpg --}}
                    <div class="hero-banner-headings">
                        <div class="hero-brush-tag">
                            <span>{{ __('Đặt sân thể thao') }}</span>
                        </div>
                        <h1>
                            {{ __('Ra sân trong 30 giây, không cần gọi điện hỏi lịch') }}
                        </h1>
                    </div>

                    <p class="hero-script-lead">
                        {{ __('Bóng đá, cầu lông, pickleball, bóng chuyền — hàng trăm sân quanh TP.HCM, giữ chỗ và thanh toán ngay trên điện thoại.') }}
                    </p>

                    {{-- 3 Feature Pills with Circular Icons (Exact layout from banner.jpg) --}}
                    <div class="hero-banner-perks">
                        <div class="hero-perk-item">
                            <div class="hero-perk-icon">
                                <i class="ti ti-ball-football"></i>
                            </div>
                            <div class="hero-perk-label">
                                <strong>{{ __('Đặt sân') }}</strong>
                                <span>{{ __('nhanh chóng') }}</span>
                            </div>
                        </div>

                        <div class="hero-perk-divider"></div>

                        <div class="hero-perk-item">
                            <div class="hero-perk-icon">
                                <i class="ti ti-shirt-sport"></i>
                            </div>
                            <div class="hero-perk-label">
                                <strong>{{ __('Quần áo') }}</strong>
                                <span>{{ __('thể thao chất lượng') }}</span>
                            </div>
                        </div>

                        <div class="hero-perk-divider"></div>

                        <div class="hero-perk-item">
                            <div class="hero-perk-icon">
                                <i class="ti ti-calendar-Bolt"></i>
                                <i class="ti ti-calendar-event"></i>
                            </div>
                            <div class="hero-perk-label">
                                <strong>{{ __('Lịch sân') }}</strong>
                                <span>{{ __('linh hoạt') }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- CTA Buttons (Pink Pill Button with streak lines like banner.jpg) --}}
                    <div class="hero-actions-group">
                        <div class="hero-cta-streak-wrap">
                            <span class="cta-speed-lines" aria-hidden="true"></span>
                            <a href="{{ route('courts.index') }}" class="btn-hero">
                                <i class="ti ti-calendar-event"></i>
                                <span>{{ __('Đặt sân ngay') }}</span>
                                <i class="ti ti-chevron-right"></i>
                            </a>
                        </div>
                        <a href="{{ route('plans.index') }}" class="btn-hero-ghost">
                            <i class="ti ti-crown"></i>
                            <span>{{ __('Xem gói ưu đãi') }}</span>
                        </a>
                    </div>

                    {{-- STATISTICS --}}
                    <div class="scoreboard">
                        <div class="stat">
                            <div class="stat-num">320+</div>
                            <div class="stat-label">{{ __('sân đối tác') }}</div>
                        </div>

                        <div class="stat">
                            <div class="stat-num">4.8/5</div>
                            <div class="stat-label">{{ __('đánh giá trung bình') }}</div>
                        </div>

                        <div class="stat">
                            <div class="stat-num">12k+</div>
                            <div class="stat-label">{{ __('lượt đặt / tháng') }}</div>
                        </div>
                    </div>

                </div>

                {{-- RIGHT COLUMN: VISUAL SHOWCASE & CAROUSEL CONTROLS --}}
                <div class="hero-visual-showcase">
                    <div class="hero-slogan-badge" aria-hidden="true">
                        <i class="ti ti-crown hero-crown-icon"></i>
                        <div class="hero-slogan-title">NHÓM H</div>
                        <div class="hero-slogan-sub">Play Hard<br>Live Strong</div>
                    </div>

                    <div class="hero-carousel-controls" aria-label="{{ __('Điều khiển trình chiếu banner') }}">
                        <button
                            type="button"
                            class="hero-carousel-arrow"
                            id="heroCarouselPrev"
                            data-hero-prev
                            aria-label="{{ __('Banner trước') }}"
                        >
                            <i class="ti ti-chevron-left"></i>
                        </button>

                        <div class="hero-carousel-dots" id="heroCarouselDots" role="tablist">
                            @foreach($heroBannerImages as $bannerIndex => $bannerUrl)
                                <button
                                    type="button"
                                    class="hero-carousel-dot {{ $bannerIndex === 0 ? 'active' : '' }}"
                                    data-hero-dot="{{ $bannerIndex }}"
                                    aria-label="Banner {{ $bannerIndex + 1 }}"
                                    aria-selected="{{ $bannerIndex === 0 ? 'true' : 'false' }}"
                                >
                                    <span class="hero-dot-progress"></span>
                                </button>
                            @endforeach
                        </div>

                        <button
                            type="button"
                            class="hero-carousel-arrow"
                            id="heroCarouselNext"
                            data-hero-next
                            aria-label="{{ __('Banner tiếp theo') }}"
                        >
                            <i class="ti ti-chevron-right"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- FLOATING QUICK SEARCH BAR OVERLAPPING BOTTOM OF HERO (LIKE HOME.JPG) --}}
    <div class="hero-floating-search-wrap">
        <div class="wrap">
            <form action="{{ route('courts.search') }}" method="GET" class="search-card search-card-sportzone">
                <div class="search-quick-badge">
                    <span>{{ __('Đặt sân nhanh') }}</span>
                </div>

                <div class="field">
                    <i class="ti ti-search" style="font-size:18px; color:var(--turf-teal); margin-right:4px;"></i>
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="{{ __('Tìm kiếm sân thể thao, sản phẩm, địa chỉ hoặc tin tức...') }}" autocomplete="off">
                </div>

                <button type="submit" class="search-btn">
                    <i class="ti ti-search"></i>
                    <span>{{ __('Tìm kiếm') }}</span>
                    <i class="ti ti-arrow-right"></i>
                </button>
            </form>
        </div>
    </div>

    {{-- =====================================================
         SPORTS QUICK CATEGORIES
    ====================================================== --}}
    @if(isset($sportTypes) && $sportTypes->isNotEmpty())
    <section class="quick-sports-section">
        <div class="wrap">
            <div class="quick-sports-row">
                <div class="quick-title-block">
                    <span class="sub-label">{{ __('Môn thể thao thịnh hành') }}</span>
                    <h3>{{ __('Bạn muốn chơi gì hôm nay?') }}</h3>
                </div>
                <div class="sports-pills-container">
                    @foreach($sportTypes as $st)
                        <a href="{{ route('courts.index', ['sport_types' => [$st->id]]) }}" class="sport-quick-pill">
                            <div class="sport-pill-icon"><i class="ti ti-flame"></i></div>
                            <div class="sport-pill-text">
                                <strong>{{ $st->name }}</strong>
                                <span>{{ __('Xem sân trống') }}</span>
                            </div>
                        </a>
                    @endforeach
                    <a href="{{ route('courts.index') }}" class="sport-quick-pill view-all-pill">
                        <div class="sport-pill-icon"><i class="ti ti-grid-dots"></i></div>
                        <div class="sport-pill-text">
                            <strong>{{ __('Tất cả bộ môn') }}</strong>
                            <span>{{ __('Xem ngay') }} &rarr;</span>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </section>
    @endif

    {{-- =====================================================
         COURTS LIST SECTION
    ====================================================== --}}
    <section class="list" id="courts-section">
        <div class="wrap">
            <div class="panel">

                {{-- TITLE --}}
                <div class="section-head">
                    <div>
                        <span class="head-sub-badge"><i class="ti ti-sparkles"></i> {{ __('Gợi ý dành cho bạn') }}</span>
                        <h2>{{ __('Sân nổi bật gần bạn') }}</h2>
                    </div>
                    <a href="{{ route('courts.index') }}" class="view-all-link">
                        <span>{{ __('Xem tất cả sân') }}</span>
                        <i class="ti ti-arrow-right"></i>
                    </a>
                </div>

                {{-- COURT GRID --}}
                <div class="grid">
                    @forelse ($courts as $court)
                        <div class="court-card" data-href="{{ route('courts.show', $court->id) }}">

                            {{-- TAG --}}
                            <span class="court-tag" style="display:flex; justify-content:space-between; width: calc(100% - 40px); z-index: 2;">
                                <span><i class="ti ti-star-filled" style="font-size:11px;margin-right:3px;"></i> {{ $court->sportType->name ?? __('Thể thao') }}</span>
                                @auth
                                    @php
                                        $isFavorited = auth()->user()->favoriteCourts->contains($court->id);
                                    @endphp
                                    <form action="{{ route('favorites.toggle', $court->id) }}" method="POST" style="margin:0;" data-stop-propagation>
                                        @csrf
                                        <button type="submit" style="background:none;border:none;cursor:pointer;padding:0;display:flex;align-items:center;">
                                            <i class="ti {{ $isFavorited ? 'ti-heart-filled' : 'ti-heart' }}" style="font-size:18px;color:{{ $isFavorited ? '#FFE4E6' : '#FFFFFF' }};" aria-hidden="true"></i>
                                        </button>
                                    </form>
                                @else
                                    <a href="{{ route('login') }}" style="background:none;border:none;cursor:pointer;padding:0;text-decoration:none;display:flex;align-items:center;" data-stop-propagation>
                                        <i class="ti ti-heart" style="font-size:18px;color:#FFFFFF;" aria-hidden="true"></i>
                                    </a>
                                @endauth
                            </span>

                            {{-- ICON / MEDIA --}}
                            <div class="court-media" style="overflow: hidden; background: #FDF2F8; position: relative;">
                                @if($court->images && $court->images->isNotEmpty())
                                    @php
                                        $primaryImg = $court->images->firstWhere('is_primary', true) ?? $court->images->first();
                                    @endphp
                                    <img src="{{ asset($primaryImg->image_path) }}" alt="{{ $court->name }}" style="width:100%;height:100%;object-fit:cover;">
                                @else
                                    <i class="ti ti-ball-football" style="font-size:48px;color:var(--turf-teal);" aria-hidden="true"></i>
                                @endif
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
                                    <span>{{ $court->facility->address ?? __('Không rõ địa chỉ') }}</span>
                                </div>

                                <div class="court-foot">
                                    <div>
                                        <div class="court-price">
                                            {{ number_format($court->price ?? 120000, 0, ',', '.') }}đ/h
                                        </div>
                                        <span class="court-slots">
                                            <i class="ti ti-clock"></i> {{ $court->capacity ?? 0 }} {{ __('khung trống') }}
                                        </span>
                                    </div>

                                    <a href="{{ route('courts.show', $court->id) }}" class="btn-book">
                                        <span>{{ __('Đặt ngay') }}</span>
                                        <i class="ti ti-chevron-right"></i>
                                    </a>
                                </div>
                            </div>

                        </div>
                    @empty
                        <p style="grid-column: 1 / -1; text-align: center; color: var(--ink-soft); padding: 30px;">{{ __('Hiện chưa có sân nào.') }}</p>
                    @endforelse
                </div>

                {{-- =================================================
                     HOW IT WORKS / VALUE PROPOSITION
                ================================================== --}}
                <div class="home-features-banner">
                    <div class="hf-item">
                        <div class="hf-icon"><i class="ti ti-map-search"></i></div>
                        <div class="hf-content">
                            <h4>{{ __('Tìm kiếm thông minh') }}</h4>
                            <p>{{ __('Dễ dàng định vị sân theo môn thể thao, quận huyện và khoảng cách gần nhất.') }}</p>
                        </div>
                    </div>
                    <div class="hf-item">
                        <div class="hf-icon"><i class="ti ti-calendar-time"></i></div>
                        <div class="hf-content">
                            <h4>{{ __('Lịch trống thời gian thực') }}</h4>
                            <p>{{ __('Khung giờ cập nhật trực tiếp từ chủ sân, tránh tuyệt đối tình trạng chồng lịch.') }}</p>
                        </div>
                    </div>
                    <div class="hf-item">
                        <div class="hf-icon"><i class="ti ti-shield-check"></i></div>
                        <div class="hf-content">
                            <h4>{{ __('Xác nhận & Hoàn tiền linh hoạt') }}</h4>
                            <p>{{ __('Mã đặt chỗ gửi tức thì qua SMS/Email, hỗ trợ đổi lịch thi đấu tiện lợi.') }}</p>
                        </div>
                    </div>
                </div>

                {{-- =================================================
                     PARTNER CTA
                ================================================== --}}
                <div class="cta">
                    <div>
                        <h2>{{ __('Là chủ sân? Đưa sân của bạn lên Sân Nhóm H') }}</h2>
                        <p>
                            {{ __('Quản lý lịch đặt, thanh toán và khách hàng ở một nơi duy nhất — miễn phí đăng ký trong tháng đầu.') }}
                        </p>
                    </div>

                    <a href="{{ route('partnership.index') }}" class="btn btn-solid" style="padding:14px 30px; display:inline-flex; align-items:center; gap:8px;">
                        <span>{{ __('Đăng ký làm đối tác') }}</span>
                        <i class="ti ti-arrow-right"></i>
                    </a>
                </div>

            </div>
        </div>
    </section>

    {{-- =====================================================
         TESTIMONIAL CAROUSEL SECTION
    ====================================================== --}}
    <section class="testimonial-section" id="testimonials-section">
        <div class="wrap">
            <div class="panel testimonial-panel">
                <div class="section-head testimonial-head">
                    <div>
                        <span class="testimonial-eyebrow"><i class="ti ti-star-filled" style="color:#F59E0B;"></i> {{ __('Cảm nhận khách hàng') }}</span>
                        <h2>{{ __('Người chơi nói gì về Sân Nhóm H') }}</h2>
                    </div>

                    @if(isset($reviews) && $reviews->count() > 1)
                        <div class="testimonial-controls">
                            <button type="button" class="testimonial-nav-btn" data-testimonial-prev aria-label="{{ __('Trước') }}">
                                <i class="ti ti-chevron-left"></i>
                            </button>
                            <button type="button" class="testimonial-nav-btn" data-testimonial-next aria-label="{{ __('Tiếp') }}">
                                <i class="ti ti-chevron-right"></i>
                            </button>
                        </div>
                    @endif
                </div>

                @if(isset($reviews) && $reviews->isNotEmpty())
                    <div class="testimonial-viewport" id="testimonialViewport">
                        <div class="testimonial-track" id="testimonialTrack">
                            @foreach($reviews as $index => $review)
                                <div class="testimonial-slide" data-slide-index="{{ $index }}">
                                    <article class="testimonial-card">
                                        <div class="testimonial-card-top">
                                            <div class="testimonial-stars" aria-label="{{ $review->rating }}/5">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <i class="ti {{ $i <= (int) $review->rating ? 'ti-star-filled' : 'ti-star' }}"></i>
                                                @endfor
                                            </div>
                                            <i class="ti ti-quote testimonial-quote-icon" aria-hidden="true"></i>
                                        </div>

                                        <p class="testimonial-text">“{{ $review->content }}”</p>

                                        <div class="testimonial-author">
                                            <div class="testimonial-avatar">
                                                {{ strtoupper(mb_substr($review->user->name ?? 'U', 0, 1, 'UTF-8')) }}
                                            </div>
                                            <div class="testimonial-meta">
                                                <div class="testimonial-name">{{ $review->user->name ?? __('Khách hàng') }}</div>
                                                <div class="testimonial-court">
                                                    <i class="ti ti-map-pin"></i>
                                                    <span>{{ $review->court->name ?? __('Sân thể thao') }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </article>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="carousel-dots testimonial-dots" id="testimonialDots">
                        @foreach($reviews as $index => $review)
                            <button
                                type="button"
                                class="dot testimonial-dot {{ $index === 0 ? 'active' : '' }}"
                                data-testimonial-dot="{{ $index }}"
                                aria-label="Slide {{ $index + 1 }}"
                            ></button>
                        @endforeach
                    </div>
                @else
                    <p class="testimonial-empty">{{ __('Hiện chưa có đánh giá nào từ người chơi.') }}</p>
                @endif
            </div>
        </div>
    </section>

    {{-- =====================================================
         FAQ ACCORDION SECTION (CÂU HỎI THƯỜNG GẶP)
    ====================================================== --}}
    <section class="faq-section" id="faq-section">
        <div class="wrap">
            <div class="panel faq-panel">
                <div class="section-head faq-head">
                    <div>
                        <span class="head-sub-badge"><i class="ti ti-help-circle"></i> {{ __('Hỗ trợ & Giải đáp') }}</span>
                        <h2>{{ __('Một số câu hỏi thường gặp') }}</h2>
                    </div>
                    <span class="faq-sub-note">{{ __('Mọi thắc mắc của bạn đều được chúng tôi giải đáp chi tiết') }}</span>
                </div>

                <div class="faq-accordion-container">
                    {{-- FAQ Item 1 --}}
                    <div class="faq-item-accordion active">
                        <button type="button" class="faq-question-btn" aria-expanded="true">
                            <span class="faq-q-text">
                                <i class="ti ti-circle-check-filled faq-bullet-icon"></i>
                                {{ __('Làm thế nào để tôi đặt sân và giữ chỗ thành công?') }}
                            </span>
                            <span class="faq-chevron-icon"><i class="ti ti-chevron-down"></i></span>
                        </button>
                        <div class="faq-answer-content">
                            <p>
                                {{ __('Rất đơn giản! Bạn chỉ cần vào mục "Sân", chọn môn thể thao (bóng đá, cầu lông, pickleball,...), chọn ngày và khung giờ mong muốn, sau đó điền thông tin và xác nhận. Hệ thống sẽ giữ chỗ tức thì và gửi thông tin xác nhận qua Email / SMS cho bạn mà không cần phải gọi điện thoại hỏi lịch.') }}
                            </p>
                        </div>
                    </div>

                    {{-- FAQ Item 2 --}}
                    <div class="faq-item-accordion">
                        <button type="button" class="faq-question-btn" aria-expanded="false">
                            <span class="faq-q-text">
                                <i class="ti ti-clock-check faq-bullet-icon"></i>
                                {{ __('Tôi có thể hủy lịch hoặc dời giờ chơi sau khi đã đặt không?') }}
                            </span>
                            <span class="faq-chevron-icon"><i class="ti ti-chevron-down"></i></span>
                        </button>
                        <div class="faq-answer-content">
                            <p>
                                {{ __('Có! Bạn hoàn toàn có thể hủy hoặc dời lịch chơi theo chính sách linh hoạt của từng cụm sân. Đối với thành viên sở hữu Gói thành viên VIP, bạn được quyền hủy miễn phí trước từ 2 đến 6 giờ thi đấu hoặc dời lịch thi đấu mà không mất thêm bất kỳ chi phí nào.') }}
                            </p>
                        </div>
                    </div>

                    {{-- FAQ Item 3 --}}
                    <div class="faq-item-accordion">
                        <button type="button" class="faq-question-btn" aria-expanded="false">
                            <span class="faq-q-text">
                                <i class="ti ti-wallet faq-bullet-icon"></i>
                                {{ __('Sân Nhóm H hỗ trợ những phương thức thanh toán nào?') }}
                            </span>
                            <span class="faq-chevron-icon"><i class="ti ti-chevron-down"></i></span>
                        </button>
                        <div class="faq-answer-content">
                            <p>
                                {{ __('Chúng tôi hỗ trợ đa dạng phương thức tiện lợi: Quét mã QR chuyển khoản ngân hàng (VietQR 24/7), Ví điện tử MoMo, Thẻ quốc tế Visa/MasterCard và PayPal. Mọi giao dịch đều được mã hóa bảo mật chuẩn ngân hàng.') }}
                            </p>
                        </div>
                    </div>

                    {{-- FAQ Item 4 --}}
                    <div class="faq-item-accordion">
                        <button type="button" class="faq-question-btn" aria-expanded="false">
                            <span class="faq-q-text">
                                <i class="ti ti-crown faq-bullet-icon"></i>
                                {{ __('Gói thành viên mang lại những quyền lợi gì cho người chơi?') }}
                            </span>
                            <span class="faq-chevron-icon"><i class="ti ti-chevron-down"></i></span>
                        </button>
                        <div class="faq-answer-content">
                            <p>
                                {{ __('Khi đăng ký Gói thành viên, bạn sẽ nhận được chiết khấu trực tiếp từ 10% đến 25% cho tất cả các lần đặt sân, được ưu tiên giữ chỗ các khung giờ cao điểm ("giờ vàng" 17h-21h), và được đội ngũ CSKH hỗ trợ dời lịch riêng 24/7.') }}
                            </p>
                        </div>
                    </div>

                    {{-- FAQ Item 5 --}}
                    <div class="faq-item-accordion">
                        <button type="button" class="faq-question-btn" aria-expanded="false">
                            <span class="faq-q-text">
                                <i class="ti ti-building-stadium faq-bullet-icon"></i>
                                {{ __('Tôi là chủ cụm sân thể thao, làm sao để hợp tác đưa sân lên hệ thống?') }}
                            </span>
                            <span class="faq-chevron-icon"><i class="ti ti-chevron-down"></i></span>
                        </button>
                        <div class="faq-answer-content">
                            <p>
                                {{ __('Bạn vui lòng nhấp vào nút "Đăng ký làm đối tác" hoặc truy cập mục Hợp tác. Đội ngũ phát triển Sân Nhóm H sẽ liên hệ tư vấn, thiết lập hệ thống quản lý lịch đặt sân và khách hàng cho cụm sân của bạn hoàn toàn miễn phí trong tháng đầu tiên!') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- =====================================================
         PRE-FOOTER SECTION (SIMULATED CONTACT FORM)
    ====================================================== --}}
    <section class="prefooter-section" id="prefooter-section">
        <div class="wrap">
            <div class="prefooter-grid">
                <div class="prefooter-info">
                    <span class="head-sub-badge"><i class="ti ti-headset"></i> {{ __('Hỗ trợ 24/7') }}</span>
                    <h2 class="prefooter-title">{{ __('Liên hệ với chúng tôi') }}</h2>
                    <p class="prefooter-lead">
                        {{ __('Giúp việc đặt sân thể thao trở nên đơn giản hơn bao giờ hết.') }}
                    </p>
                    <p class="prefooter-desc">
                        {{ __('Hãy chia sẻ chi tiết nhu cầu đặt sân hoặc hợp tác của bạn. Đừng lo, chúng tôi cam kết không gửi tin nhắn rác.') }}
                    </p>
                </div>

                <div class="prefooter-form-wrap">
                    <form id="prefooterContactForm" class="prefooter-form" data-prefooter-form novalidate>
                        <div class="prefooter-field">
                            <input
                                type="text"
                                name="name"
                                id="prefooterName"
                                class="prefooter-input"
                                placeholder="{{ __('Nguyễn Văn An') }}"
                                required
                                minlength="2"
                                maxlength="100"
                                aria-label="{{ __('Họ và tên') }}"
                            >
                        </div>

                        <div class="prefooter-field">
                            <input
                                type="email"
                                name="email"
                                id="prefooterEmail"
                                class="prefooter-input"
                                placeholder="{{ __('Địa chỉ Email') }}"
                                required
                                aria-label="{{ __('Địa chỉ Email') }}"
                            >
                        </div>

                        <div class="prefooter-field">
                            <textarea
                                name="message"
                                id="prefooterMessage"
                                class="prefooter-textarea"
                                placeholder="{{ __('Nội dung tin nhắn hoặc yêu cầu tư vấn đặt sân...') }}"
                                rows="5"
                                required
                                minlength="5"
                                maxlength="1000"
                                aria-label="{{ __('Nội dung tin nhắn') }}"
                            ></textarea>
                        </div>

                        <div
                            id="prefooterFeedback"
                            class="prefooter-feedback"
                            role="status"
                            aria-live="polite"
                            data-error-msg="{{ __('Vui lòng nhập đầy đủ họ tên hợp lệ, đúng định dạng Email và nội dung tối thiểu 5 ký tự.') }}"
                            data-success-msg="{{ __('Cảm ơn bạn! Thông tin liên hệ đã được kiểm tra và gửi thành công.') }}"
                            hidden
                        ></div>

                        <div class="prefooter-actions">
                            <button type="submit" class="prefooter-submit-btn">
                                <span>{{ __('Gửi liên hệ') }}</span>
                                <i class="ti ti-send"></i>
                            </button>

                            <div class="prefooter-phone">
                                <span class="prefooter-phone-label">{{ __('hoặc gọi ngay hotline') }}</span>
                                <a href="tel:19006868" class="prefooter-phone-number">1900.6868.88</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

@push('styles')
<style>
/* =====================================================
   NHOM H BANNER CAROUSEL HERO LAYOUT & SPORTZONE SEARCH BAR
===================================================== */
.hero-nhomh-banner {
    position: relative;
    padding: 54px 0 88px;
    background-color: #12121C;
    background-image: none;
    border-bottom: 3px solid rgba(255, 46, 147, 0.35);
    overflow: hidden;
    user-select: none;
}

.hero-bg-carousel {
    position: absolute;
    inset: 0;
    z-index: 0;
    overflow: hidden;
    background-color: #12121C;
}

.hero-bg-track {
    display: flex;
    width: 100%;
    height: 100%;
    will-change: transform;
    transform: translate3d(0%, 0, 0);
    transition: transform 0.85s cubic-bezier(0.22, 1, 0.36, 1);
}

.hero-bg-track.no-transition {
    transition: none !important;
}

.hero-bg-slide {
    flex: 0 0 100%;
    width: 100%;
    height: 100%;
    background-size: cover;
    background-position: center center;
    background-repeat: no-repeat;
    transform: scale(1.015);
    transition: transform 5.2s ease-out;
}

.hero-bg-slide.is-active {
    transform: scale(1.055);
}

.hero-bg-overlay {
    position: absolute;
    inset: 0;
    z-index: 1;
    pointer-events: none;
    background: linear-gradient(
        90deg,
        rgba(10, 10, 18, 0.92) 0%,
        rgba(15, 15, 25, 0.72) 46%,
        rgba(15, 15, 25, 0.22) 78%,
        rgba(15, 15, 25, 0.08) 100%
    );
}

.hero-slash-top {
    position: absolute;
    top: 0;
    left: 29%;
    width: 115px;
    height: 22px;
    background: repeating-linear-gradient(
        -45deg,
        #FF2E93,
        #FF2E93 12px,
        transparent 12px,
        transparent 20px
    );
    z-index: 2;
    pointer-events: none;
}

.hero-slash-bottom-left {
    position: absolute;
    bottom: -15px;
    left: -30px;
    width: 240px;
    height: 75px;
    background: linear-gradient(135deg, #FF2E93 0%, #12121C 75%);
    transform: rotate(14deg);
    opacity: 0.75;
    z-index: 2;
    pointer-events: none;
    border-top: 4px solid #FFFFFF;
}

.hero-slash-bottom-right {
    position: absolute;
    bottom: -25px;
    right: -40px;
    width: 340px;
    height: 110px;
    background: linear-gradient(135deg, rgba(255, 46, 147, 0.85) 0%, rgba(219, 39, 119, 0.92) 100%);
    transform: rotate(-16deg);
    z-index: 2;
    pointer-events: none;
    box-shadow: 0 -8px 28px rgba(255, 46, 147, 0.35);
}

.hero-banner-layout {
    display: grid;
    grid-template-columns: 1.2fr 0.8fr;
    gap: 32px;
    align-items: center;
    position: relative;
    z-index: 3;
}

.hero-brand-row {
    display: flex;
    align-items: center;
    gap: 16px;
    flex-wrap: wrap;
    margin-bottom: 18px;
}

.hero-brand-mark {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    padding: 6px 14px 6px 8px;
    background: rgba(18, 18, 28, 0.78);
    border: 1px solid rgba(255, 46, 147, 0.45);
    border-radius: 12px;
    backdrop-filter: blur(8px);
}

.hero-h-icon {
    width: 36px;
    height: 36px;
    border-radius: 8px;
    background: linear-gradient(135deg, #FF2E93 0%, #BE185D 100%);
    color: #FFFFFF;
    font-family: 'Oswald', sans-serif;
    font-size: 22px;
    font-weight: 700;
    font-style: italic;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 4px 12px rgba(255, 46, 147, 0.45);
    transform: skewX(-10deg);
}

.hero-brand-text {
    display: flex;
    flex-direction: column;
    line-height: 1.15;
}

.hero-brand-text strong {
    font-family: 'Oswald', sans-serif;
    font-size: 18px;
    font-weight: 700;
    font-style: italic;
    letter-spacing: 0.8px;
    color: #FF2E93;
}

.hero-brand-text small {
    font-size: 11px;
    color: #E2E8F0;
    letter-spacing: 0.5px;
}

.hero-brand-row .eyebrow {
    margin-bottom: 0;
}

.hero-banner-headings {
    margin-bottom: 14px;
}

.hero-brush-tag {
    display: inline-block;
    background: linear-gradient(90deg, #FF1E8A 0%, #EC4899 88%, rgba(236, 72, 153, 0.2) 100%);
    padding: 8px 34px 8px 22px;
    transform: skewX(-12deg) rotate(-1.5deg);
    border-radius: 6px;
    margin-bottom: 12px;
    box-shadow: 0 8px 24px rgba(255, 30, 138, 0.42);
    border-left: 4px solid #FFFFFF;
}

.hero-brush-tag span {
    display: inline-block;
    transform: skewX(12deg);
    font-family: 'Oswald', 'Be Vietnam Pro', sans-serif;
    font-size: 24px;
    font-weight: 800;
    color: #FFFFFF;
    text-transform: uppercase;
    letter-spacing: 0.6px;
    text-shadow: 0 2px 6px rgba(0, 0, 0, 0.25);
}

.hero-nhomh-banner h1 {
    font-family: 'Oswald', 'Be Vietnam Pro', sans-serif;
    font-size: 40px;
    line-height: 1.18;
    font-weight: 800;
    font-style: italic;
    margin: 0;
    color: #FF47A3;
    text-shadow: 0 4px 18px rgba(0, 0, 0, 0.75);
    position: relative;
    padding-bottom: 10px;
}

.hero-nhomh-banner h1::after {
    content: "";
    display: block;
    width: 240px;
    height: 4px;
    margin-top: 10px;
    border-radius: 999px;
    background: linear-gradient(90deg, #FFFFFF 0%, rgba(255, 255, 255, 0.1) 100%);
}

.hero-script-lead {
    font-size: 16px !important;
    font-style: italic;
    font-weight: 500;
    color: #F8FAFC !important;
    margin: 0 0 22px !important;
    text-shadow: 0 2px 10px rgba(0, 0, 0, 0.65);
}

/* 3 Horizontal Feature Perks from banner.jpg */
.hero-banner-perks {
    display: inline-flex;
    align-items: center;
    gap: 16px;
    flex-wrap: wrap;
    padding: 12px 18px;
    margin-bottom: 24px;
    background: rgba(15, 15, 24, 0.68);
    border: 1px solid rgba(255, 255, 255, 0.14);
    border-radius: 16px;
    backdrop-filter: blur(8px);
}

.hero-perk-item {
    display: flex;
    align-items: center;
    gap: 10px;
}

.hero-perk-icon {
    width: 38px;
    height: 38px;
    border-radius: 50%;
    border: 1.5px solid #FF2E93;
    background: rgba(255, 46, 147, 0.15);
    color: #FFFFFF;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 19px;
    box-shadow: 0 0 12px rgba(255, 46, 147, 0.35);
    flex-shrink: 0;
}

.hero-perk-icon .ti-calendar-Bolt {
    display: none;
}

.hero-perk-label {
    display: flex;
    flex-direction: column;
    line-height: 1.25;
}

.hero-perk-label strong {
    font-size: 13px;
    font-weight: 700;
    color: #FFFFFF;
}

.hero-perk-label span {
    font-size: 11.5px;
    color: #E2E8F0;
}

.hero-perk-divider {
    width: 1px;
    height: 28px;
    background: rgba(255, 255, 255, 0.22);
}

.hero-actions-group {
    display: flex;
    align-items: center;
    gap: 14px;
    margin-bottom: 10px;
    flex-wrap: wrap;
}

.hero-cta-streak-wrap {
    display: inline-flex;
    align-items: center;
    position: relative;
}

.cta-speed-lines {
    width: 38px;
    height: 22px;
    margin-right: -10px;
    background:
        linear-gradient(90deg, transparent 0%, #FF2E93 100%) 0 2px / 100% 3px no-repeat,
        linear-gradient(90deg, transparent 20%, #FF2E93 100%) 0 10px / 85% 3px no-repeat,
        linear-gradient(90deg, transparent 10%, #FF2E93 100%) 0 18px / 95% 3px no-repeat;
    opacity: 0.85;
    pointer-events: none;
}

.btn-hero {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 13px 30px;
    margin-bottom: 0;
    background: linear-gradient(90deg, #FF2E93 0%, #EC4899 100%);
    color: #FFFFFF;
    border-radius: 9999px;
    font-weight: 800;
    font-size: 15px;
    text-transform: uppercase;
    letter-spacing: 0.6px;
    transition: all 0.2s ease;
    text-decoration: none;
    box-shadow: 0 8px 25px rgba(255, 46, 147, 0.48);
}

.btn-hero:hover {
    background: linear-gradient(90deg, #E01A70 0%, #DB2777 100%);
    transform: translateY(-2px);
    box-shadow: 0 12px 30px rgba(255, 46, 147, 0.6);
}

.btn-hero-ghost {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 13px 24px;
    background: rgba(18, 18, 28, 0.65);
    color: #ffffff;
    border: 1.5px solid rgba(255, 46, 147, 0.5);
    border-radius: 9999px;
    font-weight: 700;
    font-size: 14.5px;
    transition: all 0.2s ease;
    text-decoration: none;
    backdrop-filter: blur(6px);
}

.btn-hero-ghost:hover {
    background: rgba(255, 46, 147, 0.22);
    border-color: #FF2E93;
}

/* Right Showcase Column in Hero */
.hero-visual-showcase {
    display: flex;
    flex-direction: column;
    align-items: flex-end;
    justify-content: space-between;
    min-height: 360px;
    position: relative;
}

.hero-slogan-badge {
    text-align: right;
    padding: 14px 20px;
    background: rgba(15, 15, 24, 0.45);
    border-radius: 18px;
    border: 1px solid rgba(255, 46, 147, 0.3);
    backdrop-filter: blur(4px);
    transform: rotate(-4deg);
}

.hero-crown-icon {
    font-size: 32px;
    color: #FFFFFF;
    display: inline-block;
    margin-bottom: 2px;
    filter: drop-shadow(0 2px 8px rgba(255, 46, 147, 0.6));
}

.hero-slogan-title {
    font-family: 'Oswald', sans-serif;
    font-size: 34px;
    font-weight: 800;
    font-style: italic;
    color: #FF2E93;
    line-height: 1;
    letter-spacing: 1px;
    text-shadow: 0 3px 12px rgba(0, 0, 0, 0.65);
}

.hero-slogan-sub {
    font-size: 18px;
    font-weight: 700;
    font-style: italic;
    color: #FFFFFF;
    line-height: 1.2;
    margin-top: 4px;
    text-shadow: 0 2px 8px rgba(0, 0, 0, 0.65);
}

/* Hero Background Carousel Controls */
.hero-carousel-controls {
    display: inline-flex;
    align-items: center;
    gap: 12px;
    padding: 8px 14px;
    background: rgba(15, 15, 24, 0.68);
    border: 1px solid rgba(255, 255, 255, 0.16);
    border-radius: 999px;
    backdrop-filter: blur(10px);
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.35);
    margin-top: auto;
}

.hero-carousel-arrow {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    border: 1px solid rgba(255, 46, 147, 0.45);
    background: rgba(255, 46, 147, 0.14);
    color: #FFFFFF;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 16px;
    cursor: pointer;
    transition: all 0.2s ease;
    padding: 0;
}

.hero-carousel-arrow:hover {
    background: linear-gradient(135deg, #FF2E93 0%, #DB2777 100%);
    border-color: #FF2E93;
    transform: scale(1.06);
    box-shadow: 0 4px 12px rgba(255, 46, 147, 0.45);
}

.hero-carousel-dots {
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

.hero-carousel-dot {
    position: relative;
    width: 10px;
    height: 10px;
    border-radius: 999px;
    border: none;
    background: rgba(255, 255, 255, 0.32);
    cursor: pointer;
    padding: 0;
    overflow: hidden;
    transition: width 0.35s cubic-bezier(0.22, 1, 0.36, 1), background 0.25s ease;
}

.hero-carousel-dot:hover {
    background: rgba(255, 255, 255, 0.58);
}

.hero-carousel-dot.active {
    width: 34px;
    background: rgba(255, 255, 255, 0.22);
}

.hero-dot-progress {
    position: absolute;
    inset: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, #FF2E93 0%, #EC4899 100%);
    border-radius: 999px;
    transform: scaleX(0);
    transform-origin: left center;
}

.hero-carousel-dot.active .hero-dot-progress {
    animation: heroDotProgress 5s linear forwards;
}

@keyframes heroDotProgress {
    from {
        transform: scaleX(0);
    }
    to {
        transform: scaleX(1);
    }
}

/* Floating Quick Search Bar (Like home.jpg) */
.hero-floating-search-wrap {
    position: relative;
    z-index: 15;
    margin-top: -36px;
    margin-bottom: 18px;
}

.search-card-sportzone {
    display: grid;
    grid-template-columns: auto 1fr auto;
    gap: 14px;
    padding: 10px 14px 10px 10px;
    background: #FFFFFF;
    border-radius: 9999px;
    box-shadow:
        0 18px 40px -10px rgba(24, 24, 36, 0.18),
        0 4px 16px rgba(236, 72, 153, 0.15);
    border: 2px solid #FCE7F3;
    align-items: center;
}

[data-theme="dark"] .search-card-sportzone {
    background: #181824;
    border-color: #2D2D42;
}

.search-quick-badge {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    height: 46px;
    padding: 0 26px 0 22px;
    background: linear-gradient(135deg, #FF2E93 0%, #EC4899 100%);
    color: #FFFFFF;
    font-size: 14px;
    font-weight: 800;
    border-radius: 9999px 24px 9999px 9999px;
    white-space: nowrap;
    box-shadow: 0 6px 16px rgba(255, 46, 147, 0.32);
}

.quick-sports-section {
    padding: 26px 0;
    background: var(--surface);
    border-bottom: 1px solid var(--line);
}
.quick-sports-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 24px;
    flex-wrap: wrap;
}
.quick-title-block .sub-label {
    font-size: 12px;
    font-weight: 700;
    text-transform: uppercase;
    color: var(--turf-teal);
    letter-spacing: 0.5px;
}
.quick-title-block h3 {
    margin: 4px 0 0;
    font-size: 20px;
    font-weight: 700;
    color: var(--turf-dark);
}
.sports-pills-container {
    display: flex;
    align-items: center;
    gap: 14px;
    flex-wrap: wrap;
}
.sport-quick-pill {
    display: flex;
    align-items: center;
    gap: 12px;
    background: var(--bg-page);
    border: 1px solid var(--line);
    border-radius: 14px;
    padding: 10px 18px;
    text-decoration: none;
    color: var(--ink);
    transition: all 0.2s ease;
}
.sport-quick-pill:hover {
    border-color: var(--turf-teal);
    background: var(--turf-teal-soft);
    transform: translateY(-2px);
}
.sport-pill-icon {
    width: 36px;
    height: 36px;
    border-radius: 10px;
    background: #FCE7F3;
    color: #DB2777;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
}
.sport-pill-text {
    display: flex;
    flex-direction: column;
}
.sport-pill-text strong {
    font-size: 14px;
    color: var(--turf-dark);
}
.sport-pill-text span {
    font-size: 11.5px;
    color: var(--ink-soft);
}
.view-all-pill .sport-pill-icon {
    background: linear-gradient(135deg, #FF2E93 0%, #DB2777 100%);
    color: #FFFFFF;
}
.head-sub-badge {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    font-size: 12px;
    font-weight: 700;
    color: var(--turf-teal);
    background: var(--turf-teal-soft);
    border: 1px solid #FBCFE8;
    padding: 3px 10px;
    border-radius: 999px;
    margin-bottom: 6px;
}
.view-all-link {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 14px;
    font-weight: 700;
    color: var(--turf-dark);
    text-decoration: none;
}
.view-all-link:hover {
    color: var(--turf-teal);
    gap: 9px;
}
.court-slots {
    display: inline-flex;
    align-items: center;
    gap: 4px;
}

/* Soft Pink Value Proposition Strip (Matching "VÌ SAO CHỌN SPORTZONE?" in home.jpg) */
.home-features-banner {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 20px;
    margin: 40px 0 30px;
    padding: 28px;
    background: linear-gradient(90deg, #FDF2F8 0%, #FCE7F3 50%, #FBCFE8 100%);
    border: 1px solid #F9A8D4;
    border-radius: 20px;
    box-shadow: 0 8px 24px rgba(236, 72, 153, 0.08);
}

[data-theme="dark"] .home-features-banner {
    background: linear-gradient(90deg, #1E1E2E 0%, #261B2A 100%);
    border-color: rgba(236, 72, 153, 0.3);
}

.hf-item {
    background: rgba(255, 255, 255, 0.85);
    border: 1px solid rgba(236, 72, 153, 0.18);
    border-radius: 16px;
    padding: 22px;
    display: flex;
    gap: 16px;
    backdrop-filter: blur(4px);
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

[data-theme="dark"] .hf-item {
    background: rgba(24, 24, 36, 0.85);
}

.hf-item:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 24px rgba(236, 72, 153, 0.14);
}

.hf-icon {
    width: 48px;
    height: 48px;
    border-radius: 14px;
    background: linear-gradient(135deg, #FF2E93 0%, #DB2777 100%);
    color: #FFFFFF;
    font-size: 24px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    box-shadow: 0 6px 14px rgba(236, 72, 153, 0.3);
}
.hf-content h4 {
    margin: 0 0 6px;
    font-size: 15px;
    font-weight: 700;
    color: var(--turf-dark);
}
.hf-content p {
    margin: 0;
    font-size: 13px;
    color: var(--ink-soft);
    line-height: 1.5;
}

/* =====================================================
   FAQ ACCORDION SECTION STYLES
===================================================== */
.faq-section {
    padding: 20px 0 40px;
}
.faq-panel {
    background: var(--surface);
    border: 1px solid var(--line);
    border-radius: 24px;
    padding: 36px 40px;
    box-shadow: 0 6px 24px rgba(24, 24, 36, 0.04);
}
.faq-head {
    display: flex;
    justify-content: space-between;
    align-items: flex-end;
    margin-bottom: 28px;
    padding-bottom: 18px;
    border-bottom: 1px solid var(--line);
    flex-wrap: wrap;
    gap: 12px;
}
.faq-head h2 {
    margin: 0;
    font-size: 28px;
    color: var(--turf-dark);
}
.faq-sub-note {
    font-size: 14px;
    color: var(--ink-soft);
}
.faq-accordion-container {
    display: flex;
    flex-direction: column;
    gap: 14px;
}
.faq-item-accordion {
    background: var(--bg-page);
    border: 1.5px solid var(--line);
    border-radius: 16px;
    overflow: hidden;
    transition: all 0.25s ease;
}
.faq-item-accordion:hover {
    border-color: #F472B6;
}
.faq-item-accordion.active {
    border-color: var(--turf-teal);
    background: var(--surface);
    box-shadow: 0 4px 18px rgba(236, 72, 153, 0.08);
}
.faq-question-btn {
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 18px 22px;
    background: transparent;
    border: none;
    cursor: pointer;
    text-align: left;
    font-family: inherit;
    gap: 16px;
}
.faq-q-text {
    font-size: 16px;
    font-weight: 700;
    color: var(--turf-dark);
    display: flex;
    align-items: center;
    gap: 12px;
    line-height: 1.4;
}
.faq-bullet-icon {
    font-size: 18px;
    color: var(--turf-teal);
    flex-shrink: 0;
}
.faq-chevron-icon {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: var(--turf-teal-soft);
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--turf-teal);
    font-size: 16px;
    flex-shrink: 0;
    transition: transform 0.3s ease, background 0.2s ease;
    box-shadow: 0 2px 6px rgba(0,0,0,0.04);
}
.faq-item-accordion.active .faq-chevron-icon {
    transform: rotate(180deg);
    background: var(--turf-teal);
    color: #ffffff;
}
.faq-answer-content {
    max-height: 0;
    overflow: hidden;
    transition: max-height 0.35s cubic-bezier(0.4, 0, 0.2, 1);
}
.faq-item-accordion.active .faq-answer-content {
    max-height: 250px;
}
.faq-answer-content p {
    margin: 0;
    padding: 0 22px 20px 52px;
    font-size: 14.5px;
    line-height: 1.7;
    color: var(--ink-soft);
}

@media (max-width: 960px) {
    .hero-banner-layout {
        grid-template-columns: 1fr;
    }
    .hero-visual-showcase {
        min-height: auto;
        align-items: flex-start;
    }
    .hero-slogan-badge {
        display: none;
    }
    .search-card-sportzone {
        grid-template-columns: 1fr auto;
        border-radius: 24px;
    }
    .search-quick-badge {
        display: none;
    }
}

@media (max-width: 900px) {
    .home-features-banner {
        grid-template-columns: 1fr;
        padding: 18px;
    }
    .faq-panel {
        padding: 24px 20px;
    }
    .faq-head h2 {
        font-size: 22px;
    }
    .faq-answer-content p {
        padding-left: 22px;
    }
}

@media (max-width: 600px) {
    .hero-perk-divider {
        display: none;
    }
    .hero-brush-tag span {
        font-size: 19px;
    }
    .hero-nhomh-banner h1 {
        font-size: 30px;
    }
    .search-card-sportzone {
        grid-template-columns: 1fr;
    }
}
</style>
@endpush

@push('scripts')
<script nonce="{{ $cspNonce ?? '' }}">
document.addEventListener('DOMContentLoaded', function () {
    // =====================================================
    // HERO BACKGROUND SWIPE CAROUSEL (5S INTERVAL + INFINITE LOOP)
    // =====================================================
    const heroSection = document.getElementById('heroBannerSection');
    const heroTrack = document.getElementById('heroBgTrack');
    const heroDots = Array.from(document.querySelectorAll('[data-hero-dot]'));
    const heroPrevBtn = document.querySelector('[data-hero-prev]');
    const heroNextBtn = document.querySelector('[data-hero-next]');

    if (heroSection && heroTrack) {
        const allSlides = Array.from(heroTrack.querySelectorAll('.hero-bg-slide'));
        const realSlidesCount = heroDots.length > 0 ? heroDots.length : Math.max(1, allSlides.length - 1);
        const swipeIntervalMs = parseInt(heroSection.dataset.interval || '5000', 10);

        let currentIndex = 0;
        let isTransitioning = false;
        let autoSwipeTimer = null;
        let touchStartX = null;

        function syncActiveState(realIndex) {
            const normalizedIdx = ((realIndex % realSlidesCount) + realSlidesCount) % realSlidesCount;

            allSlides.forEach((slide, idx) => {
                const isSlideActive = idx === realIndex || (realIndex === realSlidesCount && idx === 0);
                slide.classList.toggle('is-active', isSlideActive);
            });

            heroDots.forEach((dot, idx) => {
                const isDotActive = idx === normalizedIdx;
                dot.classList.toggle('active', isDotActive);
                dot.setAttribute('aria-selected', isDotActive ? 'true' : 'false');

                const progressEl = dot.querySelector('.hero-dot-progress');
                if (progressEl && isDotActive) {
                    progressEl.style.animation = 'none';
                    void progressEl.offsetWidth;
                    progressEl.style.animation = '';
                }
            });
        }

        function moveToSlide(targetIndex, withAnimation = true) {
            if (!withAnimation) {
                heroTrack.classList.add('no-transition');
                heroTrack.style.transform = `translate3d(-${targetIndex * 100}%, 0, 0)`;
                void heroTrack.offsetWidth;
                heroTrack.classList.remove('no-transition');
                isTransitioning = false;
            } else {
                isTransitioning = true;
                heroTrack.classList.remove('no-transition');
                heroTrack.style.transform = `translate3d(-${targetIndex * 100}%, 0, 0)`;
            }

            currentIndex = targetIndex;
            syncActiveState(currentIndex);
        }

        function swipeNext() {
            if (isTransitioning || realSlidesCount <= 1) {
                return;
            }
            moveToSlide(currentIndex + 1, true);
        }

        function swipePrev() {
            if (isTransitioning || realSlidesCount <= 1) {
                return;
            }

            if (currentIndex === 0) {
                // Jump seamlessly to the cloned first slide at the end, then swipe back to last real slide
                heroTrack.classList.add('no-transition');
                heroTrack.style.transform = `translate3d(-${realSlidesCount * 100}%, 0, 0)`;
                void heroTrack.offsetWidth;
                heroTrack.classList.remove('no-transition');
                moveToSlide(realSlidesCount - 1, true);
            } else {
                moveToSlide(currentIndex - 1, true);
            }
        }

        heroTrack.addEventListener('transitionend', function (e) {
            if (e.target !== heroTrack || e.propertyName !== 'transform') {
                return;
            }

            isTransitioning = false;

            // Seamless loop: when reaching the cloned slide at index === realSlidesCount, snap back to 0 without transition
            if (currentIndex >= realSlidesCount) {
                moveToSlide(0, false);
            }
        });

        function startAutoSwipe() {
            stopAutoSwipe();
            if (realSlidesCount > 1) {
                autoSwipeTimer = window.setInterval(function () {
                    // Fallback safety if tab was backgrounded and transitionend didn't fire
                    if (isTransitioning) {
                        isTransitioning = false;
                        if (currentIndex >= realSlidesCount) {
                            moveToSlide(0, false);
                        }
                    }
                    swipeNext();
                }, swipeIntervalMs);
            }
        }

        function stopAutoSwipe() {
            if (autoSwipeTimer) {
                window.clearInterval(autoSwipeTimer);
                autoSwipeTimer = null;
            }
        }

        if (heroNextBtn) {
            heroNextBtn.addEventListener('click', function () {
                swipeNext();
                startAutoSwipe();
            });
        }

        if (heroPrevBtn) {
            heroPrevBtn.addEventListener('click', function () {
                swipePrev();
                startAutoSwipe();
            });
        }

        heroDots.forEach(function (dot) {
            dot.addEventListener('click', function () {
                const targetIdx = parseInt(dot.dataset.heroDot || '0', 10);
                if (isTransitioning || targetIdx === (currentIndex % realSlidesCount)) {
                    return;
                }
                if (currentIndex >= realSlidesCount) {
                    moveToSlide(0, false);
                }
                moveToSlide(targetIdx, true);
                startAutoSwipe();
            });
        });

        // Touch / Mouse drag swipe support on Hero banner
        heroSection.addEventListener('touchstart', function (e) {
            if (e.touches && e.touches.length === 1) {
                touchStartX = e.touches[0].clientX;
            }
        }, { passive: true });

        heroSection.addEventListener('touchend', function (e) {
            if (touchStartX === null || !e.changedTouches || e.changedTouches.length === 0) {
                return;
            }
            const deltaX = e.changedTouches[0].clientX - touchStartX;
            touchStartX = null;

            if (Math.abs(deltaX) > 45) {
                if (deltaX < 0) {
                    swipeNext();
                } else {
                    swipePrev();
                }
                startAutoSwipe();
            }
        }, { passive: true });

        document.addEventListener('visibilitychange', function () {
            if (document.hidden) {
                stopAutoSwipe();
            } else {
                if (currentIndex >= realSlidesCount) {
                    moveToSlide(0, false);
                }
                isTransitioning = false;
                startAutoSwipe();
            }
        });

        syncActiveState(0);
        startAutoSwipe();
    }

    // =====================================================
    // FAQ ACCORDION
    // =====================================================
    const faqItems = document.querySelectorAll('.faq-item-accordion');
    
    faqItems.forEach(item => {
        const btn = item.querySelector('.faq-question-btn');
        btn.addEventListener('click', function () {
            const isActive = item.classList.contains('active');
            
            // Close other items for single-open accordion feel (or keep multiple open)
            faqItems.forEach(other => {
                other.classList.remove('active');
                other.querySelector('.faq-question-btn').setAttribute('aria-expanded', 'false');
            });

            if (!isActive) {
                item.classList.add('active');
                btn.setAttribute('aria-expanded', 'true');
            }
        });
    });
});
</script>
@endpush

@endsection
