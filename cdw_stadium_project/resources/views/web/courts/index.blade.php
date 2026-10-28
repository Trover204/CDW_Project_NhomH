@extends('web.layouts.app')

@section('title', 'Danh sách sân - Sân Nhóm H')

@section('content')


<section class="list" id="courts-section" style="padding-top: 40px; min-height: 80vh;">
    <div class="wrap">
        <div style="display: flex; gap: 30px; flex-wrap: wrap;">

            {{-- SIDEBAR --}}
            <aside style="width: 250px; flex-shrink: 0;" class="sidebar-desktop">
                <div class="panel" style="padding: 24px;">
                    <h3 style="margin-top: 0; margin-bottom: 20px; font-size: 18px; color: var(--turf-dark);">Danh mục</h3>
                    <form id="filter-form" action="{{ route('courts.index') }}" method="GET">
                        @if(request('q'))
                            <input type="hidden" name="q" value="{{ request('q') }}">
                        @endif

                        <div style="display: flex; flex-direction: column; gap: 12px;">
                            @foreach($sportTypes as $type)
                                <label style="display: flex; align-items: center; gap: 10px; cursor: pointer;">
                                    <input type="checkbox" name="sport_types[]" value="{{ $type->id }}"
                                           {{ in_array($type->id, request('sport_types', [])) ? 'checked' : '' }}
                                           data-auto-submit
                                           style="width: 18px; height: 18px; cursor: pointer; accent-color: var(--turf-teal);">
                                    <span style="font-size: 15px; color: var(--ink);">{{ $type->name }}</span>
                                </label>
                            @endforeach
                        </div>
                    </form>
                </div>
            </aside>

            {{-- MAIN CONTENT --}}
            <div style="flex: 1; min-width: 0;">
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
                    @foreach(request('sport_types', []) as $st)
                        <input type="hidden" name="sport_types[]" value="{{ $st }}">
                    @endforeach
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
                        <div class="court-card" data-href="{{ route('courts.show', $court->id) }}" style="cursor: pointer;">
                            {{-- TAG --}}
                            <span class="court-tag">
                                {{ $court->sportType->name ?? 'Thể thao' }}
                            </span>

                            {{-- ICON / MEDIA --}}
                            <div class="court-media" style="display: flex; align-items: center; justify-content: center; background: #E6F0FA; overflow: hidden;">
                                @if($court->images && $court->images->isNotEmpty())
                                    @php
                                        $primaryImg = $court->images->firstWhere('is_primary', true) ?? $court->images->first();
                                    @endphp
                                    <img src="{{ asset($primaryImg->image_path) }}" alt="{{ $court->name }}" style="width:100%;height:100%;object-fit:cover;">
                                @else
                                    <i class="ti ti-ball-football" style="font-size: 40px; color: #2563EB;"></i>
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
        </div>
    </div>
</section>

<style>
    @media (max-width: 768px) {
        .sidebar-desktop {
            width: 100% !important;
        }
    }
</style>
@endsection
