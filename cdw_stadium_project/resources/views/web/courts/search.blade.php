@extends('web.layouts.app')

@section('title', 'Kết quả tìm kiếm - Sân Nhóm H')

@section('content')


<section class="list" id="courts-section" style="padding-top: 40px; min-height: 80vh;">
    <div class="wrap">
        <div class="panel">

            {{-- TITLE --}}
            <div class="section-head">
                <div>
                    <h2>Kết quả tìm kiếm cho "{{ $keyword }}"</h2>
                </div>
            </div>

            {{-- SEARCH BAR --}}
            <form action="{{ route('courts.search') }}" method="GET" class="search-card" style="margin-bottom: 30px;">

                {{-- TỪ KHÓA TÌM KIẾM --}}
                <div class="field">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="11" cy="11" r="8"/>
                        <path d="m21 21-4.3-4.3"/>
                    </svg>
                    <input type="text" name="q" value="{{ $keyword ?? request('q') }}" placeholder="Tìm kiếm sân thể thao hoặc tin tức...">
                </div>

                {{-- BUTTON --}}
                <button type="submit" class="search-btn">
                    Tìm kiếm
                </button>
            </form>

            <h3 style="margin: 20px 0 15px; font-size: 22px; color: var(--turf-dark);">Sân thể thao ({{ $courts->total() }})</h3>
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
                    <p style="color: var(--ink-soft);">Không tìm thấy sân nào phù hợp với từ khóa "{{ $keyword }}".</p>
                @endforelse
            </div>

            <div style="margin-top: 20px;">
                {{ $courts->links() }}
            </div>

            {{-- NEWS SEARCH RESULTS --}}
            @if(isset($news))
                <h3 style="margin: 40px 0 15px; font-size: 22px; color: var(--turf-dark);">Tin tức & Bài viết ({{ $news->total() }})</h3>
                <div class="grid">
                    @forelse ($news as $item)
                        <a href="{{ route('news.show', $item->slug) }}" class="court-card" style="text-decoration: none; color: inherit;">
                            @if($item->category)
                                <span class="court-tag">
                                    {{ $item->category }}
                                </span>
                            @endif

                            <div class="court-media" style="display: flex; align-items: center; justify-content: center; background: #EEF7F2;">
                                @if($item->image)
                                    <img src="{{ $item->image_url }}" alt="{{ $item->title }}" style="width: 100%; height: 100%; object-fit: cover;">
                                @else
                                    <i class="ti ti-news" style="font-size: 40px; color: var(--turf-teal);"></i>
                                @endif
                            </div>

                            <div class="court-body">
                                <div class="court-name">
                                    {{ $item->title }}
                                </div>

                                <div class="court-loc" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                    <span>{{ $item->summary }}</span>
                                </div>

                                <div class="court-foot">
                                    <span style="font-size: 12px; color: var(--ink-muted);">
                                        {{ $item->created_at->format('d/m/Y') }}
                                    </span>
                                    <span class="btn-book">
                                        Đọc tin
                                    </span>
                                </div>
                            </div>
                        </a>
                    @empty
                        <p style="color: var(--ink-soft);">Không tìm thấy bài viết tin tức nào phù hợp với từ khóa "{{ $keyword }}".</p>
                    @endforelse
                </div>

                <div style="margin-top: 20px;">
                    {{ $news->links() }}
                </div>
            @endif
        </div>
    </div>
</section>
@endsection
