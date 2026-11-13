@extends('web.layouts.app')

@section('title', __('Thanh toán Đăng ký Cộng tác viên') . ' - ' . config('app.name', 'Sân Nhóm H'))

@section('content')

<div style="overflow:hidden; background:#FFFFFF; max-width: 600px; margin: 40px auto; padding: 20px; border: 1px solid #F1E4EC; border-radius: 24px; box-shadow: 0 4px 12px rgba(0,0,0,0.05);">

    <div style="padding:22px 20px;">
        <div style="text-align:center;margin-bottom:30px;">
            <div style="font-size:24px;font-weight:700;color:#181824;margin-bottom:6px;">{{ __('Đăng ký Cộng tác viên') }}</div>
        </div>

        <div style="background:#FAF7F9; padding:20px; border-radius:12px; margin-bottom: 24px; border: 1px solid #F1E4EC;">
            <div style="font-size:18px;font-weight:700;color:#181824;margin-bottom:10px;">{{ __('Chi tiết đăng ký') }}</div>
            <div style="display:flex; justify-content:space-between; margin-bottom: 8px;">
                <span style="color:#4B5563;">{{ __('Gói:') }}</span>
                <span style="font-weight:600; color:#181824;">{{ __('Cộng tác viên') }}</span>
            </div>
            <div style="display:flex; justify-content:space-between; margin-bottom: 8px;">
                <span style="color:#4B5563;">{{ __('Thời hạn:') }}</span>
                <span style="font-weight:600; color:#181824;">{{ __('1 Tháng') }}</span>
            </div>
            <div style="display:flex; justify-content:space-between; margin-bottom: 8px;">
                <span style="color:#4B5563;">{{ __('Phúc lợi:') }}</span>
                <span style="font-weight:600; color:#DB2777;">{{ __('Giảm 50% tiền sân') }}</span>
            </div>
            <div style="display:flex; justify-content:space-between; border-top: 1px solid #F1E4EC; padding-top: 10px; margin-top: 10px;">
                <span style="font-weight:600; color:#181824;">{{ __('Tổng tiền cần thanh toán:') }}</span>
                <span style="font-weight:700; font-size:18px; color:#EC4899;">3.000.000đ</span>
            </div>
        </div>

        <form action="{{ route('partnership.process') }}" method="POST" data-confirm="{{ __('Bạn có chắc chắn muốn thanh toán đăng ký làm Cộng tác viên?') }}">
            @csrf

            <div style="margin-bottom: 24px;">
                <div style="font-size:16px;font-weight:600;color:#181824;margin-bottom:12px;">{{ __('Phương thức thanh toán') }}</div>

                <label style="display:flex; align-items:center; padding: 12px; border: 1px solid #F1E4EC; border-radius: 8px; margin-bottom: 10px; cursor: pointer; transition: all 0.2s;">
                    <input type="radio" name="payment_method" value="momo" required style="margin-right: 12px; width: 18px; height: 18px; accent-color: #EC4899;">
                    <span style="font-weight: 500; color: #374151;">{{ __('Ví MoMo') }}</span>
                </label>

                <label style="display:flex; align-items:center; padding: 12px; border: 1px solid #F1E4EC; border-radius: 8px; margin-bottom: 10px; cursor: pointer; transition: all 0.2s;">
                    <input type="radio" name="payment_method" value="visa" required style="margin-right: 12px; width: 18px; height: 18px; accent-color: #EC4899;">
                    <span style="font-weight: 500; color: #374151;">{{ __('Thẻ Visa/Mastercard') }}</span>
                </label>

                <label style="display:flex; align-items:center; padding: 12px; border: 1px solid #F1E4EC; border-radius: 8px; cursor: pointer; transition: all 0.2s;">
                    <input type="radio" name="payment_method" value="paypal" required style="margin-right: 12px; width: 18px; height: 18px; accent-color: #EC4899;">
                    <span style="font-weight: 500; color: #374151;">PayPal</span>
                </label>

                @error('payment_method')
                    <div style="color: red; font-size: 14px; margin-top: 8px;">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" style="width:100%;height:48px;border-radius:8px;background:linear-gradient(135deg, #FF2E93 0%, #DB2777 100%);color:#fff;border:none;display:flex;align-items:center;justify-content:center;font-size:16px;font-weight:600;cursor:pointer;">
                {{ __('Thanh toán ngay') }}
            </button>

            <div style="text-align: center; margin-top: 16px;">
                <a href="{{ route('partnership.index') }}" style="color: #6B7280; text-decoration: none; font-size: 14px;">{{ __('Quay lại') }}</a>
            </div>
        </form>
    </div>
</div>

@endsection
