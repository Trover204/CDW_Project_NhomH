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
            <form action="{{ route('courts.index') }}" method="GET" class="search-card" style="margin-bottom: 30px;">

                {{-- TỪ KHÓA TÌM KIẾM --}}
                <div class="field">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="11" cy="11" r="8"/>
                        <path d="m21 21-4.3-4.3"/>
                    </svg>
                    <input type="text" name="q" value="{{ $keyword ?? request('q') }}" placeholder="Nhập tên sân hoặc mô tả sân...">
                </div>

                {{-- BUTTON --}}
                <button type="submit" class="search-btn">
                    Tìm kiếm
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
