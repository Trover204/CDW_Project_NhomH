@extends('web.layouts.app')

@section('title', __('Sân Nhóm H - Nền tảng đặt sân thể thao nhanh chóng & tiện lợi'))

@section('content')

    {{-- =====================================================
         HERO SECTION
    ====================================================== --}}
    <section class="hero hero-enhanced">
        <div class="wrap">
            <div class="hero-inner">
                <div class="hero-content-box">

                    <div class="eyebrow">
                        <i class="ti ti-trophy"></i> {{ __('Score Big with Sân Nhóm H') }}
                    </div>

                    <h1>
                        {{ __('Ra sân trong 30 giây, không cần gọi điện hỏi lịch') }}
                    </h1>

                    <p>
                        {{ __('Bóng đá, cầu lông, pickleball, bóng chuyền — hàng trăm sân quanh TP.HCM, giữ chỗ và thanh toán ngay trên điện thoại.') }}
                    </p>

                    {{-- CTA Buttons --}}
                    <div class="hero-actions-group">
                        <a href="{{ route('courts.index') }}" class="btn-hero">
                            <i class="ti ti-calendar-event"></i>
                            <span>{{ __('Đặt sân ngay') }}</span>
                        </a>
                        <a href="{{ route('plans.index') }}" class="btn-hero-ghost">
                            <i class="ti ti-crown"></i>
                            <span>{{ __('Xem gói ưu đãi') }}</span>
                        </a>
                    </div>

                    {{-- SEARCH BAR (PASTEL LIME PILL) --}}
                    <form action="{{ route('courts.search') }}" method="GET" class="search-card">
                        <div class="field">
                            <i class="ti ti-search" style="font-size:18px; color:var(--turf-dark); margin-right:4px;"></i>
                            <input type="text" name="q" value="{{ request('q') }}" placeholder="{{ __('Tìm kiếm sân thể thao, địa chỉ hoặc tin tức...') }}" autocomplete="off">
                        </div>

                        <button type="submit" class="search-btn">
                            <span>{{ __('Tìm kiếm') }}</span>
                            <i class="ti ti-arrow-right"></i>
                        </button>
                    </form>

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
            </div>
        </div>
    </section>

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
                                <span>{{ $court->sportType->name ?? __('Thể thao') }}</span>
                                @auth
                                    @php
                                        $isFavorited = auth()->user()->favoriteCourts->contains($court->id);
                                    @endphp
                                    <form action="{{ route('favorites.toggle', $court->id) }}" method="POST" style="margin:0;" data-stop-propagation>
                                        @csrf
                                        <button type="submit" style="background:none;border:none;cursor:pointer;padding:0;display:flex;align-items:center;">
                                            <i class="ti {{ $isFavorited ? 'ti-heart-filled' : 'ti-heart' }}" style="font-size:18px;color:{{ $isFavorited ? '#F97316' : '#6B7A70' }};" aria-hidden="true"></i>
                                        </button>
                                    </form>
                                @else
                                    <a href="{{ route('login') }}" style="background:none;border:none;cursor:pointer;padding:0;text-decoration:none;display:flex;align-items:center;" data-stop-propagation>
                                        <i class="ti ti-heart" style="font-size:18px;color:#6B7A70;" aria-hidden="true"></i>
                                    </a>
                                @endauth
                            </span>

                            {{-- ICON / MEDIA --}}
                            <div class="court-media" style="overflow: hidden; background: #E6F0FA; position: relative;">
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
.hero-actions-group {
    display: flex;
    align-items: center;
    gap: 14px;
    margin-bottom: 24px;
    flex-wrap: wrap;
}
.btn-hero {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 12px 26px;
    background: var(--turf-accent);
    color: var(--turf-accent-ink);
    border-radius: 12px;
    font-weight: 700;
    font-size: 15px;
    transition: all 0.2s ease;
    text-decoration: none;
}
.btn-hero:hover {
    background: var(--turf-accent-hover);
    transform: translateY(-2px);
}
.btn-hero-ghost {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 12px 24px;
    background: rgba(255, 255, 255, 0.1);
    color: #ffffff;
    border: 1px solid rgba(255, 255, 255, 0.25);
    border-radius: 12px;
    font-weight: 600;
    font-size: 15px;
    transition: all 0.2s ease;
    text-decoration: none;
    backdrop-filter: blur(4px);
}
.btn-hero-ghost:hover {
    background: rgba(255, 255, 255, 0.2);
    border-color: #ffffff;
}
.quick-sports-section {
    padding: 30px 0;
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
    background: #FEF3C7;
    color: #D97706;
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
    background: var(--turf-teal-soft);
    color: var(--turf-teal);
}
.head-sub-badge {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    font-size: 12px;
    font-weight: 700;
    color: var(--turf-teal);
    background: var(--turf-teal-soft);
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
    color: var(--turf-teal);
    text-decoration: none;
}
.view-all-link:hover {
    gap: 9px;
}
.court-slots {
    display: inline-flex;
    align-items: center;
    gap: 4px;
}
.home-features-banner {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 20px;
    margin: 40px 0 30px;
    padding-top: 30px;
    border-top: 1px solid var(--line);
}
.hf-item {
    background: var(--bg-page);
    border: 1px solid var(--line);
    border-radius: 16px;
    padding: 22px;
    display: flex;
    gap: 16px;
}
.hf-icon {
    width: 44px;
    height: 44px;
    border-radius: 12px;
    background: var(--surface);
    color: var(--turf-teal);
    font-size: 22px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    box-shadow: 0 4px 12px rgba(16, 103, 100, 0.08);
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
    box-shadow: 0 6px 24px rgba(13, 56, 46, 0.04);
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
    border-color: #B5D9C4;
}
.faq-item-accordion.active {
    border-color: var(--turf-teal);
    background: var(--surface);
    box-shadow: 0 4px 18px rgba(16, 103, 100, 0.06);
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
    background: var(--surface);
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

@media (max-width: 900px) {
    .home-features-banner {
        grid-template-columns: 1fr;
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
</style>
@endpush

@push('scripts')
<script nonce="{{ $cspNonce ?? '' }}">
document.addEventListener('DOMContentLoaded', function () {
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
