@extends('web.layouts.app')

@section('title', 'Gói thành viên')

@section('content')

    <header>
        <div class="wrap">
            <div class="nav">
                <a href="{{ route('home') }}" class="brand">
                    Sân Nhóm <span>H</span>
                </a>
                <nav class="nav-links">
                    <a href="{{ route('home') }}">Trang chủ</a>
                    <a href="{{ route('courts.index') }}">Sân</a>
                    <a href="{{ route('news.index') }}">Tin tức</a>
                    <a href="{{ route('plans.index') }}" class="active">Gói thành viên</a>
                </nav>
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

<div style="font-family:Arial,Helvetica,sans-serif; overflow:hidden; background:#FFFFFF; max-width: 1000px; margin: 40px auto; padding: 20px; border: 1px solid #E1E8E3; border-radius: 24px; box-shadow: 0 4px 12px rgba(0,0,0,0.05);">

    @if(session('success'))
        <div style="background: #178A4C; color: white; padding: 10px 20px; border-radius: 8px; margin-bottom: 20px; text-align: center;">
            {{ session('success') }}
        </div>
    @endif

    <div style="padding:22px 20px;">
        <div style="text-align:center;margin-bottom:40px;">
            <div style="font-size:24px;font-weight:700;color:#16241C;margin-bottom:6px;">Chọn gói phù hợp với bạn</div>
            <div style="font-size:16px;color:#6B7A70;">Ưu đãi giảm giá và ưu tiên giữ khung giờ đẹp mỗi tuần</div>
        </div>
        <div style="display:grid;grid-template-columns:repeat(auto-fit, minmax(280px, 1fr));gap:20px;">

            @foreach($plans as $index => $plan)
                @php
                    $isPopular = ($index == 1); // just arbitrarily making the second plan "popular" for UI purposes
                    $borderColor = $isPopular ? '#178A4C' : '#E1E8E3';
                    $borderWidth = $isPopular ? '2px' : '0.5px';
                @endphp

                <div style="border:{{ $borderWidth }} solid {{ $borderColor }};border-radius:12px;padding:24px;position:relative; display:flex; flex-direction:column;">

                    @if($isPopular)
                        <div style="position:absolute;top:-12px;left:50%;transform:translateX(-50%);background:#178A4C;color:#fff;font-size:12px;padding:4px 16px;border-radius:20px; font-weight:600;">Phổ biến nhất</div>
                    @endif

                    <div style="font-size:18px;font-weight:700;color:#16241C;margin-bottom:8px;{{ $isPopular ? 'margin-top:8px;' : '' }}">{{ $plan->name }}</div>
                    <div style="font-size:28px;font-weight:700;color:#16241C;margin-bottom:4px;">{{ number_format($plan->price, 0, ',', '.') }}đ<span style="font-size:14px;font-weight:400;color:#8A968F;">/{{ $plan->duration_days }} ngày</span></div>

                    <div style="font-size:14px;color:#8A968F;margin-bottom:24px;">Giảm {{ $plan->discount_percent }}% cho mỗi lượt đặt sân</div>

                    <div style="display:flex;flex-direction:column;gap:12px;font-size:14px;color:#3A453E;flex-grow:1;">
                        <div><i class="ti ti-check" style="color:#178A4C;margin-right:8px;font-size:16px;" aria-hidden="true"></i>Giảm {{ $plan->discount_percent }}% mỗi lượt đặt</div>
                        <div><i class="ti ti-check" style="color:#178A4C;margin-right:8px;font-size:16px;" aria-hidden="true"></i>Hủy miễn phí trước {{ $isPopular ? '2' : '6' }} giờ</div>
                        @if($isPopular || $index > 1)
                            <div><i class="ti ti-check" style="color:#178A4C;margin-right:8px;font-size:16px;" aria-hidden="true"></i>Giữ chỗ khung giờ đẹp</div>
                        @endif
                        @if($index > 1)
                            <div><i class="ti ti-check" style="color:#178A4C;margin-right:8px;font-size:16px;" aria-hidden="true"></i>Đặt cố định hàng tuần</div>
                            <div><i class="ti ti-check" style="color:#178A4C;margin-right:8px;font-size:16px;" aria-hidden="true"></i>Hỗ trợ ưu tiên 24/7</div>
                        @endif
                    </div>

                    <form action="{{ route('plans.subscribe', $plan->id) }}" method="POST" style="margin-top:24px;">
                        @csrf
                        @auth
                            <button type="submit" style="width:100%;height:44px;border-radius:8px;{{ $isPopular ? 'background:#F97316;color:#fff;border:none;' : 'background:#fff;border:1px solid #C7D3CA;color:#3A453E;' }} display:flex;align-items:center;justify-content:center;font-size:15px;font-weight:600;cursor:pointer;">
                                Chọn gói
                            </button>
                        @else
                            <a href="{{ route('login') }}" style="width:100%;height:44px;border-radius:8px;{{ $isPopular ? 'background:#F97316;color:#fff;border:none;' : 'background:#fff;border:1px solid #C7D3CA;color:#3A453E;' }} display:flex;align-items:center;justify-content:center;font-size:15px;font-weight:600;cursor:pointer;text-decoration:none;">
                                Đăng nhập để đăng ký
                            </a>
                        @endauth
                    </form>
                </div>
            @endforeach

        </div>
    </div>
</div>

@endsection