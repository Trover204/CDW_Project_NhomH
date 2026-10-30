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
@endsection

