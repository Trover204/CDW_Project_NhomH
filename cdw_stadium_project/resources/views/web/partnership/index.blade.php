@extends('web.layouts.app')

@section('title', 'Đăng ký Cộng tác viên')

@section('content')

<div style="max-width: 800px; margin: 60px auto; padding: 0 20px;">
    <div style="text-align: center; margin-bottom: 40px;">
        <h1 style="font-size: 32px; font-weight: 700; color: #16241C; margin-bottom: 10px;">Chương trình Cộng tác viên</h1>
        <p style="font-size: 16px; color: #4B5563;">Trở thành đối tác của chúng tôi và nhận nhiều ưu đãi hấp dẫn.</p>
    </div>

    <div style="background: #FFFFFF; border: 1px solid #E1E8E3; border-radius: 24px; padding: 40px; box-shadow: 0 4px 12px rgba(0,0,0,0.05);">

        <h2 style="font-size: 24px; font-weight: 700; color: #16241C; margin-bottom: 20px;">Phúc lợi Cộng tác viên</h2>

        <ul style="list-style-type: none; padding: 0; margin: 0 0 30px 0;">
            <li style="display: flex; align-items: flex-start; margin-bottom: 12px;">
                <i class="ti ti-check" style="color: #178A4C; font-size: 20px; margin-right: 12px; margin-top: 2px;"></i>
                <span style="font-size: 16px; color: #374151;">Giảm <strong>50%</strong> tiền sân cho mỗi lần đặt sân.</span>
            </li>
            <li style="display: flex; align-items: flex-start; margin-bottom: 12px;">
                <i class="ti ti-check" style="color: #178A4C; font-size: 20px; margin-right: 12px; margin-top: 2px;"></i>
                <span style="font-size: 16px; color: #374151;">Ưu tiên đặt sân vào các khung giờ vàng.</span>
            </li>
            <li style="display: flex; align-items: flex-start; margin-bottom: 12px;">
                <i class="ti ti-check" style="color: #178A4C; font-size: 20px; margin-right: 12px; margin-top: 2px;"></i>
                <span style="font-size: 16px; color: #374151;">Hỗ trợ tư vấn và giải đáp ưu tiên.</span>
            </li>
        </ul>

        <div style="background: #F9FAFB; padding: 24px; border-radius: 12px; border: 1px solid #E5E7EB; margin-bottom: 30px; text-align: center;">
            <div style="font-size: 16px; color: #4B5563; margin-bottom: 8px;">Phí duy trì</div>
            <div style="font-size: 32px; font-weight: 700; color: #F97316;">3.000.000đ<span style="font-size: 16px; color: #6B7280; font-weight: 400;">/tháng</span></div>
        </div>

        <div style="text-align: center;">
            @auth
                @if(auth()->user()->role === 'ctv')
                    <div style="padding: 16px; background: #DCFCE7; color: #166534; border-radius: 8px; font-weight: 600;">Bạn đang là Cộng tác viên.</div>
                @elseif(auth()->user()->role === 'pending_ctv')
                    <div style="padding: 16px; background: #FEF3C7; color: #92400E; border-radius: 8px; font-weight: 600;">Đơn đăng ký của bạn đang chờ Admin phê duyệt.</div>
                @else
                    <a href="{{ route('partnership.checkout') }}" style="display: inline-flex; align-items: center; justify-content: center; width: 100%; max-width: 300px; height: 52px; background: #178A4C; color: #fff; text-decoration: none; font-size: 16px; font-weight: 600; border-radius: 8px; transition: all 0.2s;">
                        Đăng ký ngay
                    </a>
                @endif
            @else
                <a href="{{ route('login') }}" style="display: inline-flex; align-items: center; justify-content: center; width: 100%; max-width: 300px; height: 52px; background: #178A4C; color: #fff; text-decoration: none; font-size: 16px; font-weight: 600; border-radius: 8px; transition: all 0.2s;">
                    Đăng nhập để đăng ký
                </a>
            @endauth
        </div>

    </div>
</div>

@endsection
