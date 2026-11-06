<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <script nonce="{{ $cspNonce ?? '' }}">if(localStorage.getItem("theme")==="dark") document.documentElement.setAttribute("data-theme", "dark");</script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', __('Sân Nhóm H'))</title>

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Oswald:wght@500;600;700&family=Be+Vietnam+Pro:wght@400;500;600;700;800&display=swap"
        rel="stylesheet"
    >

    {{-- Tabler Icons --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css">
    <link rel="stylesheet" href="{{ asset('css/home.css') }}?v={{ filemtime(public_path('css/home.css')) }}">
    @stack('styles')
</head>

<body>
    @include('web.layouts.header')

    <main>
        @yield('content')
    </main>

    @include('web.layouts.footer')

    {{-- Order Success Modal (Merch) --}}
    @if(session('order_success'))
        @php $order = session('order_success'); @endphp
        <div id="orderSuccessModal" style="position: fixed; inset: 0; background: rgba(0,0,0,0.65); backdrop-filter: blur(5px); display: flex; align-items: center; justify-content: center; z-index: 999999; padding: 20px;">
            <div style="background: var(--bg-white, #FFFFFF); border-radius: 24px; max-width: 520px; width: 100%; padding: 32px; box-shadow: 0 25px 60px rgba(0,0,0,0.3); border: 1px solid var(--line, #E3ECE6); position: relative; animation: popIn 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275); text-align: center;">

                <button type="button" id="btn-close-order-modal" style="position: absolute; right: 18px; top: 18px; width: 34px; height: 34px; border-radius: 50%; border: none; background: var(--bg-page, #F3F4F6); color: var(--ink-soft, #6B7280); font-size: 18px; cursor: pointer; display: flex; align-items: center; justify-content: center;">
                    <i class="ti ti-x"></i>
                </button>

                {{-- Success Icon & Title --}}
                <div style="width: 76px; height: 76px; border-radius: 50%; background: #DCFCE7; color: #16A34A; display: inline-flex; align-items: center; justify-content: center; font-size: 42px; margin-bottom: 16px; box-shadow: 0 8px 20px rgba(22,163,74,0.25);">
                    <i class="ti ti-circle-check"></i>
                </div>

                <h2 style="font-size: 24px; font-weight: 800; color: var(--ink, #11201B); margin: 0 0 6px; font-family: 'Oswald', sans-serif; letter-spacing: 0.5px;">
                    ĐẶT ĐƠN HÀNG THÀNH CÔNG!
                </h2>
                <p style="font-size: 14px; color: var(--ink-soft, #5E726A); margin: 0 0 20px; line-height: 1.5;">
                    Đơn hàng của bạn đã được ghi nhận vào hệ thống. Chúng tôi sẽ nhanh chóng chuẩn bị và liên hệ giao hàng sớm nhất!
                </p>

                {{-- Order Summary Details --}}
                <div style="background: var(--bg-page, #F9FAFB); border-radius: 16px; border: 1px solid var(--line, #E5E7EB); padding: 18px; text-align: left; margin-bottom: 24px;">
                    <div style="display: flex; justify-content: space-between; border-bottom: 1px dashed var(--line, #E5E7EB); padding-bottom: 10px; margin-bottom: 10px; font-size: 13.5px;">
                        <span style="color: var(--ink-soft, #6B7280);">Mã đơn hàng:</span>
                        <strong style="color: #178A4C;">#{{ $order['reference'] ?? ('SL-' . $order['order_id']) }}</strong>
                    </div>
                    <div style="display: flex; justify-content: space-between; border-bottom: 1px dashed var(--line, #E5E7EB); padding-bottom: 10px; margin-bottom: 10px; font-size: 13.5px;">
                        <span style="color: var(--ink-soft, #6B7280);">Sản phẩm:</span>
                        <strong style="color: var(--ink, #111827); max-width: 65%; text-align: right;">{{ $order['quantity'] }}x {{ $order['product_name'] }}</strong>
                    </div>
                    <div style="display: flex; justify-content: space-between; border-bottom: 1px dashed var(--line, #E5E7EB); padding-bottom: 10px; margin-bottom: 10px; font-size: 13.5px;">
                        <span style="color: var(--ink-soft, #6B7280);">Người nhận:</span>
                        <span style="font-weight: 600; color: var(--ink, #111827);">{{ $order['customer_name'] }} ({{ $order['customer_phone'] }})</span>
                    </div>
                    @if(!empty($order['shipping_address']))
                        <div style="display: flex; justify-content: space-between; border-bottom: 1px dashed var(--line, #E5E7EB); padding-bottom: 10px; margin-bottom: 10px; font-size: 13.5px;">
                            <span style="color: var(--ink-soft, #6B7280);">Địa chỉ nhận:</span>
                            <span style="color: var(--ink, #111827); text-align: right; max-width: 65%;">{{ $order['shipping_address'] }}</span>
                        </div>
                    @endif
                    <div style="display: flex; justify-content: space-between; align-items: baseline; padding-top: 8px; font-size: 14.5px;">
                        <span style="font-weight: 700; color: var(--ink, #111827);">Tổng thanh toán:</span>
                        <span style="font-size: 20px; font-weight: 800; color: #EA580C; font-family: 'Oswald', sans-serif;">
                            {{ number_format($order['total_price'], 0, ',', '.') }}₫
                        </span>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div style="display: flex; gap: 12px;">
                    <a href="{{ route('products.index') }}" style="flex: 1; height: 46px; border-radius: 12px; background: #178A4C; color: #FFFFFF; font-size: 14.5px; font-weight: 700; display: inline-flex; align-items: center; justify-content: center; gap: 6px; text-decoration: none; box-shadow: 0 4px 12px rgba(23,138,76,0.3);">
                        <i class="ti ti-shopping-bag"></i> Tiếp tục mua hàng
                    </a>
                    <a href="{{ route('profile.edit') }}" style="flex: 1; height: 46px; border-radius: 12px; background: var(--bg-page, #F3F4F6); color: var(--ink, #11201B); border: 1.5px solid var(--line, #D1D5DB); font-size: 14px; font-weight: 600; display: inline-flex; align-items: center; justify-content: center; gap: 6px; text-decoration: none;">
                        <i class="ti ti-history"></i> Xem đơn hàng
                    </a>
                </div>
            </div>
        </div>
        <script nonce="{{ $cspNonce ?? '' }}">
            document.addEventListener('DOMContentLoaded', function() {
                const btnClose = document.getElementById('btn-close-order-modal');
                const modal = document.getElementById('orderSuccessModal');
                if (btnClose && modal) {
                    btnClose.addEventListener('click', function() {
                        modal.style.display = 'none';
                    });
                }
            });
        </script>
    @endif

    {{-- Plan Subscription Success Modal --}}
    @if(session('subscription_success'))
        @php $sub = session('subscription_success'); @endphp
        <div id="subscriptionSuccessModal" style="position: fixed; inset: 0; background: rgba(0,0,0,0.65); backdrop-filter: blur(5px); display: flex; align-items: center; justify-content: center; z-index: 999999; padding: 20px;">
            <div style="background: var(--bg-white, #FFFFFF); border-radius: 24px; max-width: 520px; width: 100%; padding: 32px; box-shadow: 0 25px 60px rgba(0,0,0,0.3); border: 1px solid var(--line, #E3ECE6); position: relative; animation: popIn 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275); text-align: center;">

                <button type="button" id="btn-close-sub-modal" style="position: absolute; right: 18px; top: 18px; width: 34px; height: 34px; border-radius: 50%; border: none; background: var(--bg-page, #F3F4F6); color: var(--ink-soft, #6B7280); font-size: 18px; cursor: pointer; display: flex; align-items: center; justify-content: center;">
                    <i class="ti ti-x"></i>
                </button>

                {{-- Success Icon & Title --}}
                <div style="width: 76px; height: 76px; border-radius: 50%; background: #DCFCE7; color: #16A34A; display: inline-flex; align-items: center; justify-content: center; font-size: 42px; margin-bottom: 16px; box-shadow: 0 8px 20px rgba(22,163,74,0.25);">
                    <i class="ti ti-crown"></i>
                </div>

                <h2 style="font-size: 24px; font-weight: 800; color: var(--ink, #11201B); margin: 0 0 6px; font-family: 'Oswald', sans-serif; letter-spacing: 0.5px;">
                    ĐĂNG KÝ GÓI THÀNH CÔNG!
                </h2>
                <p style="font-size: 14px; color: var(--ink-soft, #5E726A); margin: 0 0 20px; line-height: 1.5;">
                    Chúc mừng bạn đã kích hoạt gói thành viên VIP! Mọi ưu đãi giảm giá và đặc quyền giờ vàng đã sẵn sàng cho các lần đặt sân tiếp theo.
                </p>

                {{-- Subscription Details --}}
                <div style="background: var(--bg-page, #F9FAFB); border-radius: 16px; border: 1px solid var(--line, #E5E7EB); padding: 18px; text-align: left; margin-bottom: 24px;">
                    <div style="display: flex; justify-content: space-between; border-bottom: 1px dashed var(--line, #E5E7EB); padding-bottom: 10px; margin-bottom: 10px; font-size: 13.5px;">
                        <span style="color: var(--ink-soft, #6B7280);">Mã giao dịch:</span>
                        <strong style="color: #178A4C;">#{{ $sub['reference'] ?? 'SUB-VIP' }}</strong>
                    </div>
                    <div style="display: flex; justify-content: space-between; border-bottom: 1px dashed var(--line, #E5E7EB); padding-bottom: 10px; margin-bottom: 10px; font-size: 13.5px;">
                        <span style="color: var(--ink-soft, #6B7280);">Gói thành viên:</span>
                        <strong style="color: var(--ink, #111827);">{{ $sub['plan_name'] }} ({{ $sub['duration_days'] }} ngày)</strong>
                    </div>
                    <div style="display: flex; justify-content: space-between; border-bottom: 1px dashed var(--line, #E5E7EB); padding-bottom: 10px; margin-bottom: 10px; font-size: 13.5px;">
                        <span style="color: var(--ink-soft, #6B7280);">Đặc quyền giảm giá:</span>
                        <span style="color: #16A34A; font-weight: 700;">Giảm {{ $sub['discount_percent'] }}% mỗi lượt đặt sân</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; border-bottom: 1px dashed var(--line, #E5E7EB); padding-bottom: 10px; margin-bottom: 10px; font-size: 13.5px;">
                        <span style="color: var(--ink-soft, #6B7280);">Thời hạn sử dụng:</span>
                        <span style="font-weight: 600; color: var(--ink, #111827);">{{ $sub['starts_at'] }} &rarr; {{ $sub['expires_at'] }}</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; border-bottom: 1px dashed var(--line, #E5E7EB); padding-bottom: 10px; margin-bottom: 10px; font-size: 13.5px;">
                        <span style="color: var(--ink-soft, #6B7280);">Tài khoản:</span>
                        <span style="color: var(--ink, #111827);">{{ $sub['customer_name'] }} ({{ $sub['customer_email'] }})</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; align-items: baseline; padding-top: 8px; font-size: 14.5px;">
                        <span style="font-weight: 700; color: var(--ink, #111827);">Tổng thanh toán:</span>
                        <span style="font-size: 20px; font-weight: 800; color: #EA580C; font-family: 'Oswald', sans-serif;">
                            {{ number_format($sub['total_price'], 0, ',', '.') }}₫
                        </span>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div style="display: flex; gap: 12px;">
                    <a href="{{ route('courts.index') }}" style="flex: 1; height: 46px; border-radius: 12px; background: #178A4C; color: #FFFFFF; font-size: 14.5px; font-weight: 700; display: inline-flex; align-items: center; justify-content: center; gap: 6px; text-decoration: none; box-shadow: 0 4px 12px rgba(23,138,76,0.3);">
                        <i class="ti ti-calendar-event"></i> Đặt sân hưởng ưu đãi ngay
                    </a>
                    <a href="{{ route('profile.edit') }}" style="flex: 1; height: 46px; border-radius: 12px; background: var(--bg-page, #F3F4F6); color: var(--ink, #11201B); border: 1.5px solid var(--line, #D1D5DB); font-size: 14px; font-weight: 600; display: inline-flex; align-items: center; justify-content: center; gap: 6px; text-decoration: none;">
                        <i class="ti ti-user-check"></i> Xem hồ sơ
                    </a>
                </div>
            </div>
        </div>
        <script nonce="{{ $cspNonce ?? '' }}">
            document.addEventListener('DOMContentLoaded', function() {
                const btnCloseSub = document.getElementById('btn-close-sub-modal');
                const subModal = document.getElementById('subscriptionSuccessModal');
                if (btnCloseSub && subModal) {
                    btnCloseSub.addEventListener('click', function() {
                        subModal.style.display = 'none';
                    });
                }
            });
        </script>
    @endif

    {{-- Booking Success Modal --}}
    @if(session('booking_success'))
        @php $bk = session('booking_success'); @endphp
        <div id="bookingSuccessModal" style="position: fixed; inset: 0; background: rgba(0,0,0,0.65); backdrop-filter: blur(5px); display: flex; align-items: center; justify-content: center; z-index: 999999; padding: 20px;">
            <div style="background: var(--bg-white, #FFFFFF); border-radius: 24px; max-width: 520px; width: 100%; padding: 32px; box-shadow: 0 25px 60px rgba(0,0,0,0.3); border: 1px solid var(--line, #E3ECE6); position: relative; animation: popIn 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275); text-align: center;">

                <button type="button" id="btn-close-booking-modal" style="position: absolute; right: 18px; top: 18px; width: 34px; height: 34px; border-radius: 50%; border: none; background: var(--bg-page, #F3F4F6); color: var(--ink-soft, #6B7280); font-size: 18px; cursor: pointer; display: flex; align-items: center; justify-content: center;">
                    <i class="ti ti-x"></i>
                </button>

                {{-- Success Icon & Title --}}
                <div style="width: 76px; height: 76px; border-radius: 50%; background: #DCFCE7; color: #16A34A; display: inline-flex; align-items: center; justify-content: center; font-size: 42px; margin-bottom: 16px; box-shadow: 0 8px 20px rgba(22,163,74,0.25);">
                    <i class="ti ti-ticket"></i>
                </div>

                <h2 style="font-size: 24px; font-weight: 800; color: var(--ink, #11201B); margin: 0 0 6px; font-family: 'Oswald', sans-serif; letter-spacing: 0.5px;">
                    ĐẶT SÂN THÀNH CÔNG!
                </h2>
                <p style="font-size: 14px; color: var(--ink-soft, #5E726A); margin: 0 0 20px; line-height: 1.5;">
                    Lịch đặt sân của bạn đã được hệ thống xác nhận và giữ chỗ tự động. Vui lòng đến trước 10 phút để nhận sân thuận lợi nhất!
                </p>

                {{-- Booking Details --}}
                <div style="background: var(--bg-page, #F9FAFB); border-radius: 16px; border: 1px solid var(--line, #E5E7EB); padding: 18px; text-align: left; margin-bottom: 24px;">
                    <div style="display: flex; justify-content: space-between; border-bottom: 1px dashed var(--line, #E5E7EB); padding-bottom: 10px; margin-bottom: 10px; font-size: 13.5px;">
                        <span style="color: var(--ink-soft, #6B7280);">Mã giữ chỗ:</span>
                        <strong style="color: #178A4C;">#{{ $bk['reference'] ?? 'BK-SUCCESS' }}</strong>
                    </div>
                    <div style="display: flex; justify-content: space-between; border-bottom: 1px dashed var(--line, #E5E7EB); padding-bottom: 10px; margin-bottom: 10px; font-size: 13.5px;">
                        <span style="color: var(--ink-soft, #6B7280);">Sân & Môn:</span>
                        <strong style="color: var(--ink, #111827);">{{ $bk['court_name'] }} ({{ $bk['sport_type'] ?? 'Thể thao' }})</strong>
                    </div>
                    <div style="display: flex; justify-content: space-between; border-bottom: 1px dashed var(--line, #E5E7EB); padding-bottom: 10px; margin-bottom: 10px; font-size: 13.5px;">
                        <span style="color: var(--ink-soft, #6B7280);">Thời gian thi đấu:</span>
                        <span style="font-weight: 700; color: #178A4C;">{{ $bk['start_time'] }} - {{ $bk['end_time'] }} | {{ $bk['booking_date'] }}</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; border-bottom: 1px dashed var(--line, #E5E7EB); padding-bottom: 10px; margin-bottom: 10px; font-size: 13.5px;">
                        <span style="color: var(--ink-soft, #6B7280);">Người đặt sân:</span>
                        <span style="color: var(--ink, #111827);">{{ $bk['customer_name'] }} ({{ $bk['customer_phone'] }})</span>
                    </div>
                    @if(!empty($bk['discount_amount']) && $bk['discount_amount'] > 0)
                        <div style="display: flex; justify-content: space-between; border-bottom: 1px dashed var(--line, #E5E7EB); padding-bottom: 10px; margin-bottom: 10px; font-size: 13.5px;">
                            <span style="color: var(--ink-soft, #6B7280);">Ưu đãi VIP:</span>
                            <span style="color: #16A34A; font-weight: 700;">-{{ number_format($bk['discount_amount'], 0, ',', '.') }}₫</span>
                        </div>
                    @endif
                    <div style="display: flex; justify-content: space-between; align-items: baseline; padding-top: 8px; font-size: 14.5px;">
                        <span style="font-weight: 700; color: var(--ink, #111827);">Tổng tiền:</span>
                        <span style="font-size: 20px; font-weight: 800; color: #EA580C; font-family: 'Oswald', sans-serif;">
                            {{ number_format($bk['total_price'], 0, ',', '.') }}₫
                        </span>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div style="display: flex; gap: 12px;">
                    <a href="{{ route('courts.index') }}" style="flex: 1; height: 46px; border-radius: 12px; background: #178A4C; color: #FFFFFF; font-size: 14.5px; font-weight: 700; display: inline-flex; align-items: center; justify-content: center; gap: 6px; text-decoration: none; box-shadow: 0 4px 12px rgba(23,138,76,0.3);">
                        <i class="ti ti-plus"></i> Đặt thêm sân
                    </a>
                    <a href="{{ route('profile.edit') }}" style="flex: 1; height: 46px; border-radius: 12px; background: var(--bg-page, #F3F4F6); color: var(--ink, #11201B); border: 1.5px solid var(--line, #D1D5DB); font-size: 14px; font-weight: 600; display: inline-flex; align-items: center; justify-content: center; gap: 6px; text-decoration: none;">
                        <i class="ti ti-ticket"></i> Xem lịch đã đặt
                    </a>
                </div>
            </div>
        </div>
        <script nonce="{{ $cspNonce ?? '' }}">
            document.addEventListener('DOMContentLoaded', function() {
                const btnCloseBk = document.getElementById('btn-close-booking-modal');
                const bkModal = document.getElementById('bookingSuccessModal');
                if (btnCloseBk && bkModal) {
                    btnCloseBk.addEventListener('click', function() {
                        bkModal.style.display = 'none';
                    });
                }
            });
        </script>
    @endif

    <style>
        @keyframes popIn {
            0% { transform: scale(0.85); opacity: 0; }
            100% { transform: scale(1); opacity: 1; }
        }
    </style>

    @stack('scripts')
    <script src="{{ asset('js/theme-toggle.js') }}?v={{ filemtime(public_path('js/theme-toggle.js')) }}"></script>
</body>

</html>
