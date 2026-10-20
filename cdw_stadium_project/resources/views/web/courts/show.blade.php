@extends('web.layouts.app')

@section('title', $court->name . ' - Sân Nhóm H')

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

<style>
  .time-slot-label input[type="radio"] {
    display: none;
  }
  .time-slot-label {
    cursor: pointer;
    height: 28px;
    border-radius: 6px;
    border: 0.5px solid #C7D3CA;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 11px;
    color: #3A453E;
    background: #FFFFFF;
  }
  .time-slot-label.disabled {
    border: 0.5px solid #E1E8E3;
    color: #B9C2BC;
    text-decoration: line-through;
    cursor: not-allowed;
    background: #F9FAFB;
  }
  .time-slot-label:has(input[type="radio"]:checked) {
    background: #178A4C;
    color: #FFFFFF;
    font-weight: 600;
    border-color: #178A4C;
  }
</style>

<div class="wrap" style="padding: 40px 0; min-height: 80vh;">
    <div style="font-family:Arial,Helvetica,sans-serif;border:0.5px solid #E1E8E3;border-radius:12px;overflow:hidden;background:#FFFFFF;">
      <div style="padding:18px 20px;display:grid;grid-template-columns:1.6fr 1fr;gap:20px;">
        <div>
          <div style="font-size:10.5px;color:#6B7A70;margin-bottom:10px;">Trang chủ &gt; {{ $court->sportType->name ?? 'Sân' }} &gt; {{ $court->name }}</div>
          <div style="height:150px;border-radius:10px;background:#E6F0FA;display:flex;align-items:center;justify-content:center;margin-bottom:8px;"><i class="ti ti-ball-badminton" style="font-size:36px;color:#2563EB;" aria-hidden="true"></i></div>
          <div style="display:flex;gap:8px;margin-bottom:16px;">
            <div style="width:52px;height:38px;border-radius:6px;background:#E6F0FA;border:0.5px solid #C7D3CA;"></div>
            <div style="width:52px;height:38px;border-radius:6px;background:#E6F5EB;border:0.5px solid #C7D3CA;"></div>
            <div style="width:52px;height:38px;border-radius:6px;background:#FBEFE3;border:0.5px solid #C7D3CA;"></div>
            <div style="width:52px;height:38px;border-radius:6px;background:#F3EAF8;border:0.5px solid #C7D3CA;"></div>
          </div>

          <div style="font-size:16px;font-weight:700;color:#16241C;margin-bottom:4px;">{{ $court->name }}</div>
          <div style="display:flex;align-items:center;gap:10px;margin-bottom:12px;">
            <span style="font-size:11.5px;color:#D97706;"><i class="ti ti-star" aria-hidden="true"></i> 5.0 (Mới)</span>
            <span style="font-size:11.5px;color:#6B7A70;"><i class="ti ti-map-pin" aria-hidden="true"></i> {{ $court->facility->address ?? 'Không rõ' }}</span>
          </div>
          <div style="font-size:12px;color:#3A453E;line-height:1.6;margin-bottom:18px;">{{ $court->description ?? 'Không có mô tả chi tiết cho sân này.' }}</div>

          <div style="font-size:13px;font-weight:700;color:#16241C;margin-bottom:10px;">Đánh giá gần đây</div>
          @forelse ($court->reviews as $review)
          <div style="display:flex;gap:10px;align-items:flex-start;margin-bottom:12px;">
            <div style="width:28px;height:28px;border-radius:50%;background:#E6F5EB;color:#178A4C;font-size:11px;font-weight:700;display:flex;align-items:center;justify-content:center;flex-shrink:0;">{{ strtoupper(substr($review->user->name ?? 'U', 0, 2)) }}</div>
            <div>
              <div style="font-size:12px;font-weight:600;color:#16241C;">{{ $review->user->name ?? 'Người dùng' }} <span style="color:#D97706;font-weight:400;">{{ str_repeat('★', $review->rating ?? 5) }}{{ str_repeat('☆', 5 - ($review->rating ?? 5)) }}</span></div>
              <div style="font-size:11px;color:#6B7A70;">{{ $review->content }}</div>
            </div>
          </div>
          @empty
          <div style="font-size:11px;color:#6B7A70;">Chưa có đánh giá nào.</div>
          @endforelse
        </div>

        <div style="background:#F7FAF8;border-radius:12px;padding:16px;align-self:start;border:0.5px solid #E1E8E3;">
          <form action="{{ route('bookings.create', $court->id) }}" method="GET">
            <div style="font-size:13px;font-weight:700;color:#16241C;margin-bottom:12px;">Đặt sân</div>
            <div style="height:34px;border-radius:8px;background:#FFFFFF;border:0.5px solid #C7D3CA;margin-bottom:12px;display:flex;align-items:center;padding:0 10px;font-size:11.5px;color:#3A453E;">
              <i class="ti ti-calendar" style="font-size:14px;margin-right:6px;color:#178A4C;" aria-hidden="true"></i>
              <input type="date" name="date" value="{{ request('date', \Carbon\Carbon::now()->format('Y-m-d')) }}" required style="border:none;outline:none;background:transparent;width:100%;font-size:11.5px;color:#3A453E;cursor:pointer;">
            </div>

            <div style="font-size:11px;color:#6B7A70;margin-bottom:6px;">Khung giờ trống</div>
            <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:6px;margin-bottom:16px;">
              <label class="time-slot-label">
                <input type="radio" name="time" value="17:00" required>
                17:00
              </label>
              <label class="time-slot-label">
                <input type="radio" name="time" value="18:00" required>
                18:00
              </label>
              <label class="time-slot-label">
                <input type="radio" name="time" value="19:00" required>
                19:00
              </label>
              <label class="time-slot-label disabled">
                <input type="radio" name="time" value="20:00" disabled>
                20:00
              </label>
              <label class="time-slot-label">
                <input type="radio" name="time" value="21:00" required>
                21:00
              </label>
              <label class="time-slot-label">
                <input type="radio" name="time" value="22:00" required>
                22:00
              </label>
            </div>

            <div style="border-top:0.5px solid #E1E8E3;padding-top:10px;margin-bottom:14px;">
              <div style="display:flex;justify-content:space-between;font-size:11.5px;color:#6B7A70;margin-bottom:6px;"><span>1 giờ x 120.000đ</span><span>120.000đ</span></div>
              <div style="display:flex;justify-content:space-between;font-size:13px;font-weight:700;color:#16241C;"><span>Tổng cộng</span><span style="color:#C2570A;">120.000đ</span></div>
            </div>

            <button type="submit" style="width:100%;border:none;cursor:pointer;height:36px;border-radius:8px;background:#F97316;display:flex;align-items:center;justify-content:center;font-size:12.5px;color:#FFFFFF;font-weight:600;">
              Đặt sân ngay
            </button>
          </form>
        </div>
      </div>
    </div>
</div>

    <footer>
        <div class="wrap foot-row">
            <div class="foot-content">
                <span>
                    © {{ date('Y') }} Sân Nhóm H. All rights reserved.
                </span>
                <div class="social-links">
                    <a href="#" aria-label="Facebook"><i class="ti ti-brand-facebook"></i></a>
                    <a href="#" aria-label="Instagram"><i class="ti ti-brand-instagram"></i></a>
                    <a href="#" aria-label="Twitter"><i class="ti ti-brand-twitter"></i></a>
                </div>
            </div>

                        <div class="foot-links">
                <a href="{{ route('pages.about') }}">Về chúng tôi</a>
                <a href="{{ route('pages.contact') }}">Liên hệ</a>
                <a href="{{ route('pages.privacy') }}">Điều khoản</a>
            </div>
        </div>
    </footer>
@endsection
