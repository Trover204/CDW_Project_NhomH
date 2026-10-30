@extends('web.layouts.app')


@section('content')
{{-- =====================================================

    ====================================================== --}}


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
                        {{ __('Ra sân trong 30 giây, không cần gọi điện hỏi lịch') }}
                    </h1>

                    <p>
                        {{ __('Bóng đá, cầu lông, pickleball, bóng chuyền — hàng trăm sân quanh TP.HCM, giữ chỗ và thanh toán ngay trên điện thoại.') }}
                    </p>

                    {{-- CALL TO ACTION (LIME BUTTON) --}}
                    <div>
                        <a href="{{ route('courts.index') }}" class="btn-hero">
                            {{ __('Đặt sân ngay') }}
                        </a>
                    </div>

                    {{-- SEARCH BAR (PASTEL LIME PILL) --}}
                    <form action="{{ route('courts.search') }}" method="GET" class="search-card">

                        {{-- TỪ KHÓA TÌM KIẾM --}}
                        <div class="field">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="11" cy="11" r="8"/>
                                <path d="m21 21-4.3-4.3"/>
                            </svg>
                            <input type="text" name="q" value="{{ request('q') }}" placeholder="{{ __('Tìm kiếm sân thể thao hoặc tin tức...') }}">
                        </div>

                        {{-- BUTTON --}}
                        <button type="submit" class="search-btn">
                            {{ __('Tìm kiếm') }}
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
                            <div class="stat-num">12k</div>
                            <div class="stat-label">{{ __('lượt đặt / tháng') }}</div>
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
                        <h2>{{ __('Sân nổi bật gần bạn') }}</h2>
                    </div>
                    <a href="{{ route('courts.index') }}">{{ __('Xem tất cả') }} &rarr;</a>
                </div>

                {{-- COURT GRID --}}
                <div class="grid">
                    @forelse ($courts as $court)
                        <div class="court-card">

                            {{-- TAG --}}
                            <span class="court-tag" style="display:flex; justify-content:space-between; width: calc(100% - 40px);">
                                <span>{{ $court->sportType->name ?? __('Thể thao') }}</span>
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
                            <div class="court-media" style="overflow: hidden;">
                                @if($court->images && $court->images->isNotEmpty())
                                    @php
                                        $primaryImg = $court->images->firstWhere('is_primary', true) ?? $court->images->first();
                                    @endphp
                                    <img src="{{ asset($primaryImg->image_path) }}" alt="{{ $court->name }}" style="width:100%;height:100%;object-fit:cover;">
                                @else
                                    <i class="ti ti-ball-football" style="font-size:48px;color:#178A4C;" aria-hidden="true"></i>
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
                                            {{ number_format($court->price ?? 150000, 0, ',', '.') }}đ/h
                                        </div>
                                        <span class="court-slots">
                                            {{ $court->capacity ?? 0 }} {{ __('khung trống') }}
                                        </span>
                                    </div>

                                    <a href="#" class="btn-book">
                                        {{ __('Đặt ngay') }}
                                    </a>
                                </div>
                            </div>

                        </div>
                    @empty
                        <p>{{ __('Hiện chưa có sân nào.') }}</p>
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
                        <h2>{{ __('Là chủ sân? Đưa sân của bạn lên Sân Nhóm H') }}</h2>
                        <p>
                            {{ __('Quản lý lịch đặt, thanh toán và khách hàng ở một nơi duy nhất — miễn phí đăng ký trong tháng đầu.') }}
                        </p>
                    </div>

                    <a href="{{ route('partnership.index') }}" class="btn btn-solid" style="padding:12px 28px;">
                        {{ __('Đăng ký làm đối tác') }}
                    </a>
                </div>

            </div>
        </div>
    </section>

    {{-- =====================================================
         TESTIMONIAL CAROUSEL SECTION (RANDOM REVIEWS)
    ====================================================== --}}
    <section class="testimonial-section" id="testimonials-section">
        <div class="wrap">
            <div class="panel testimonial-panel">
                <div class="section-head testimonial-head">
                    <div>
                        <span class="testimonial-eyebrow">{{ __('Cảm nhận khách hàng') }}</span>
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
         PRE-FOOTER SECTION (SIMULATED CONTACT FORM)
    ====================================================== --}}
    <section class="prefooter-section" id="prefooter-section">
        <div class="wrap">
            <div class="prefooter-grid">
                <div class="prefooter-info">
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
                                {{ __('Gửi liên hệ') }}
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
@endsection

