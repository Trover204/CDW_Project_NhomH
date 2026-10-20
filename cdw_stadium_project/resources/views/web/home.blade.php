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
                    <form action="{{ route('courts.search') }}" method="GET" class="search-card">

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
                        <button type="submit" class="search-btn">
                            Tìm sân
                        </button>
                    </form>

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
    @include('layouts.footer')

    <script src="{{ asset('js/theme-toggle.js') }}"></script>
</body>

