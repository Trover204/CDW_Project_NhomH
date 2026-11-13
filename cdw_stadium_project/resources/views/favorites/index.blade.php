@extends('web.layouts.app')

@section('title', __('Sân yêu thích') . ' - ' . config('app.name', 'Sân Nhóm H'))

@section('content')

    <div style="padding: 40px 0; background: #FAF7F9; min-height: 70vh;">
        <div class="wrap">
            <div style="font-family:Arial,Helvetica,sans-serif;border:0.5px solid #F1E4EC;border-radius:12px;overflow:hidden;background:#FFFFFF;max-width:900px;margin:0 auto;">
                <div style="display:flex;align-items:center;gap:6px;padding:8px 12px;border-bottom:0.5px solid #F1E4EC;background:#FDF2F8;">
                    <div style="width:8px;height:8px;border-radius:50%;background:#FBCFE8;"></div>
                    <div style="width:8px;height:8px;border-radius:50%;background:#FBCFE8;"></div>
                    <div style="width:8px;height:8px;border-radius:50%;background:#FBCFE8;"></div>
                    <div style="flex:1;margin-left:8px;height:18px;border-radius:6px;background:#FFFFFF;border:0.5px solid #F1E4EC;font-size:11px;color:#525266;display:flex;align-items:center;padding:0 8px;">sannhomh.vn/san-yeu-thich</div>
                </div>
                <div style="padding:18px 20px;">
                    <div style="font-size:15px;font-weight:700;color:#181824;margin-bottom:4px;">{{ __('Sân yêu thích') }}</div>
                    <div style="font-size:11.5px;color:#525266;margin-bottom:16px;">{{ __('Bạn đã lưu :count sân — đặt lại nhanh chỉ với một chạm', ['count' => $courts->total()]) }}</div>

                    @if($courts->count() > 0)
                        <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:12px;">
                            @foreach($courts as $court)
                                @php
                                    $sportType = strtolower($court->sportType->name ?? '');
                                    $bgClass = 'FDF2F8';
                                    $iconColor = '#EC4899';
                                    $iconName = 'ti-ball-football';

                                    if(str_contains($sportType, 'cầu lông')) {
                                        $bgClass = 'E6F0FA';
                                        $iconColor = '#2563EB';
                                        $iconName = 'ti-ball-badminton';
                                    } elseif(str_contains($sportType, 'pickleball')) {
                                        $bgClass = 'FCE7F3';
                                        $iconColor = '#DB2777';
                                        $iconName = 'ti-ball-tennis';
                                    } elseif(str_contains($sportType, 'bóng rổ')) {
                                        $bgClass = 'FCE7F3';
                                        $iconColor = '#EC4899';
                                        $iconName = 'ti-ball-basketball';
                                    }
                                @endphp
                                <div style="border:0.5px solid #F1E4EC;border-radius:10px;overflow:hidden;position:relative;">
                                    <div style="height:66px;background:#{{ $bgClass }};position:relative;display:flex;align-items:center;justify-content:center;">
                                        <i class="ti {{ $iconName }}" style="font-size:24px;color:{{ $iconColor }};" aria-hidden="true"></i>
                                        <form action="{{ route('favorites.toggle', $court->id) }}" method="POST" style="position:absolute;top:6px;right:6px;margin:0;">
                                            @csrf
                                            <button type="submit" style="background:none;border:none;cursor:pointer;padding:0;">
                                                <i class="ti ti-heart-filled" style="font-size:15px;color:#FF2E93;" aria-hidden="true"></i>
                                            </button>
                                        </form>
                                    </div>
                                    <div style="padding:8px;">
                                        <div style="font-size:12px;font-weight:600;color:#181824;">{{ $court->name }}</div>
                                        <div style="font-size:10.5px;color:#525266;margin:3px 0 8px;">{{ $court->facility->name ?? __('Không rõ') }} · {{ number_format($court->price ?? 150000, 0, ',', '.') }}đ/{{ __('giờ') }}</div>
                                        <a href="{{ route('courts.show', $court->id) }}" style="text-decoration:none;display:block;">
                                            <div style="height:28px;border-radius:6px;background:linear-gradient(135deg, #FF2E93 0%, #DB2777 100%);color:#fff;font-size:11px;font-weight:600;display:flex;align-items:center;justify-content:center;">{{ __('Đặt lại') }}</div>
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div style="margin-top: 20px;">
                            {{ $courts->links() }}
                        </div>
                    @else
                        <div style="padding: 30px; text-align: center; color: #6B7A70; background: #F7FAF8; border-radius: 8px;">
                            {{ __('Bạn chưa có sân yêu thích nào.') }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
