@extends('web.layouts.app')

@section('title', 'Danh sách sân - Sân Nhóm H')

@section('content')


<section class="list" id="courts-section" style="padding-top: 40px; min-height: 80vh;">
    <div class="wrap">
        <div class="panel">

            {{-- TITLE --}}
            <div class="section-head">
                <div>
                    <h2>Danh sách sân</h2>
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

            @php
                $groupedCourts = $courts->groupBy('sport_type_id');
            @endphp

            @forelse ($groupedCourts as $sportTypeId => $group)
                @php
                    $sportName = $group->first()->sportType->name ?? 'Thể thao';
                @endphp
                <h3 style="margin: 20px 0 15px; font-size: 22px; color: var(--turf-dark);">{{ $sportName }}</h3>
                <div class="grid">
                    @foreach ($group as $court)
                        <div class="court-card" onclick="window.location.href='{{ route('courts.show', $court->id) }}'" style="cursor: pointer;">
                            {{-- TAG --}}
                            <span class="court-tag">
                                {{ $court->sportType->name ?? 'Thể thao' }}
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
                                            Chi tiết
                                        </div>
                                        <span class="court-slots">
                                            {{ $court->capacity }} khung trống
                                        </span>
                                    </div>

                                    <a href="{{ route('courts.show', $court->id) }}" class="btn-book">
                                        Xem ngay
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @empty
                <p>Hiện chưa có sân nào.</p>
            @endforelse

            <div style="margin-top: 20px;">
                @if(method_exists($courts, 'links'))
                    {{ $courts->links() }}
                @endif
            </div>
        </div>
    </div>
</section>
@endsection
