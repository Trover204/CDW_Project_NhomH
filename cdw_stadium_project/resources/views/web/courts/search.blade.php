@extends('web.layouts.app')

@section('title', 'Kết quả tìm kiếm - Sân Nhóm H')

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

<section class="list" id="courts-section" style="padding-top: 40px; min-height: 80vh;">
    <div class="wrap">
        <div class="panel">

            {{-- TITLE --}}
            <div class="section-head">
                <div>
                    <h2>Kết quả tìm kiếm</h2>
                </div>
            </div>

            {{-- SEARCH BAR --}}
            <form action="{{ route('courts.search') }}" method="GET" class="search-card" style="margin-bottom: 30px;">

                {{-- LOẠI SÂN --}}
                <div class="field">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="9"/>
                        <path d="M12 3v18M3 12h18"/>
                    </svg>
                    <select name="sport_type">
                        <option value="">Loại sân</option>
                        @foreach($sportTypes as $sportType)
                            <option value="{{ $sportType->id }}" {{ request('sport_type') == $sportType->id ? 'selected' : '' }}>{{ $sportType->name }}</option>
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
                            <option value="{{ $location->district }}" {{ request('location') == $location->district ? 'selected' : '' }}>{{ $location->district }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- NGÀY --}}
                <div class="field">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="5" width="18" height="16" rx="2"/>
                        <path d="M3 10h18M8 3v4M16 3v4"/>
                    </svg>
                    <input type="date" name="date" value="{{ request('date', date('Y-m-d')) }}">
                </div>

                {{-- BUTTON --}}
                <button type="submit" class="search-btn">
                    Tìm sân
                </button>
            </form>

            <div class="grid">
                @forelse ($courts as $court)
                    <div class="court-card" onclick="window.location.href='{{ route('courts.show', $court->id) }}'" style="cursor: pointer;">
                        {{-- TAG --}}
                        <span class="court-tag" style="display:flex; justify-content:space-between; width: calc(100% - 40px);">
                            <span>{{ $court->sportType->name ?? 'Thể thao' }}</span>
                            @auth
                                @php
                                    $isFavorited = auth()->user()->favoriteCourts->contains($court->id);
                                @endphp
                                <form action="{{ route('favorites.toggle', $court->id) }}" method="POST" style="margin:0;">
                                    @csrf
                                    <button type="submit" style="background:none;border:none;cursor:pointer;padding:0;" onclick="event.stopPropagation();">
                                        <i class="ti {{ $isFavorited ? 'ti-heart-filled' : 'ti-heart' }}" style="font-size:18px;color:{{ $isFavorited ? '#F97316' : '#6B7A70' }};" aria-hidden="true"></i>
                                    </button>
                                </form>
                            @else
                                <a href="{{ route('login') }}" style="background:none;border:none;cursor:pointer;padding:0;text-decoration:none;" onclick="event.stopPropagation();">
                                    <i class="ti ti-heart" style="font-size:18px;color:#6B7A70;" aria-hidden="true"></i>
                                </a>
                            @endauth
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
                                        {{ number_format($court->price ?? 150000, 0, ',', '.') }}đ/h
                                    </div>
                                    <span class="court-slots">
                                        {{ $court->capacity ?? 0 }} khung trống
                                    </span>
                                </div>

                                <a href="{{ route('courts.show', $court->id) }}" class="btn-book">
                                    Xem ngay
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <p>Không tìm thấy sân nào phù hợp với tiêu chí tìm kiếm.</p>
                @endforelse
            </div>

            <div style="margin-top: 20px;">
                {{ $courts->links() }}
            </div>
        </div>
    </div>
</section>

    @include('layouts.footer')
@endsection
