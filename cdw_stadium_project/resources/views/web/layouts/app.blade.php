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

    {{-- Order Success Modal & Floating Payment Notification (Merch) --}}
    @if(session('order_success'))
        @php
            $order = session('order_success');
            $isOrderPaidOnline = ($order['payment_status'] ?? 'paid') === 'paid' && !in_array($order['payment_method'] ?? 'qr', ['cod', 'cash'], true);
        @endphp

        {{-- Top-right Toast Banner for Merch Order --}}
        <div id="orderPaymentToast" style="position: fixed; top: 24px; right: 24px; z-index: 1000000; background: #181824; color: #FFFFFF; border-radius: 16px; padding: 16px 20px; display: flex; align-items: center; gap: 14px; box-shadow: 0 18px 40px rgba(24, 24, 36, 0.35); border: 1.5px solid #EC4899; max-width: 420px; animation: popIn 0.35s ease;">
            <div style="width: 42px; height: 42px; border-radius: 12px; background: linear-gradient(135deg, #FF2E93 0%, #DB2777 100%); color: #FFFFFF; display: flex; align-items: center; justify-content: center; font-size: 24px; flex-shrink: 0;">
                <i class="ti ti-bell-check"></i>
            </div>
            <div style="flex: 1; min-width: 0;">
                <div style="font-size: 13.5px; font-weight: 800; color: #F472B6; text-transform: uppercase; letter-spacing: 0.4px;">
                    {{ $isOrderPaidOnline ? 'Thông báo: Đã nhận thanh toán!' : 'Thông báo: Đã ghi nhận đơn COD!' }}
                </div>
                <div style="font-size: 13px; color: #E2E8F0; line-height: 1.4; margin-top: 2px;">
                    Đơn hàng <strong>#{{ $order['reference'] ?? ('SL-' . $order['order_id']) }}</strong> ({{ $order['quantity'] }}x {{ $order['product_name'] }}) đã được khởi tạo thành công.
                </div>
            </div>
        </div>

        <div id="orderSuccessModal" style="position: fixed; inset: 0; background: rgba(0,0,0,0.65); backdrop-filter: blur(5px); display: flex; align-items: center; justify-content: center; z-index: 999999; padding: 20px;">
            <div style="background: var(--bg-white, #FFFFFF); border-radius: 24px; max-width: 540px; width: 100%; padding: 32px; box-shadow: 0 25px 60px rgba(0,0,0,0.3); border: 1px solid var(--line, #F1E4EC); position: relative; animation: popIn 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275); text-align: center; max-height: 92vh; overflow-y: auto;">

                <button type="button" id="btn-close-order-modal" style="position: absolute; right: 18px; top: 18px; width: 34px; height: 34px; border-radius: 50%; border: none; background: var(--bg-page, #F3F4F6); color: var(--ink-soft, #6B7280); font-size: 18px; cursor: pointer; display: flex; align-items: center; justify-content: center;">
                    <i class="ti ti-x"></i>
                </button>

                {{-- Success Icon & Status Pill --}}
                <div style="width: 76px; height: 76px; border-radius: 50%; background: #FCE7F3; color: #DB2777; display: inline-flex; align-items: center; justify-content: center; font-size: 42px; margin-bottom: 12px; box-shadow: 0 8px 20px rgba(236,72,153,0.25);">
                    <i class="ti ti-circle-check"></i>
                </div>

                <div style="margin-bottom: 10px;">
                    @if($isOrderPaidOnline)
                        <span style="display: inline-flex; align-items: center; gap: 6px; background: #FCE7F3; color: #BE185D; font-size: 12px; font-weight: 800; padding: 5px 14px; border-radius: 999px; border: 1px solid #F9A8D4;">
                            <i class="ti ti-qrcode"></i> ĐÃ XÁC NHẬN THANH TOÁN QR / ONLINE
                        </span>
                    @else
                        <span style="display: inline-flex; align-items: center; gap: 6px; background: #FEF3C7; color: #B45309; font-size: 12px; font-weight: 800; padding: 5px 14px; border-radius: 999px; border: 1px solid #FDE68A;">
                            <i class="ti ti-truck-delivery"></i> ĐÃ LÊN ĐƠN — THANH TOÁN KHI NHẬN HÀNG (COD)
                        </span>
                    @endif
                </div>

                <h2 style="font-size: 24px; font-weight: 800; color: var(--ink, #181824); margin: 0 0 6px; font-family: 'Oswald', sans-serif; letter-spacing: 0.5px;">
                    {{ $isOrderPaidOnline ? 'THANH TOÁN & ĐẶT HÀNG THÀNH CÔNG!' : 'ĐẶT ĐƠN HÀNG THÀNH CÔNG!' }}
                </h2>
                <p style="font-size: 13.5px; color: var(--ink-soft, #5A5A72); margin: 0 0 20px; line-height: 1.5;">
                    Biên lai thanh toán và đơn hàng của bạn đã được ghi nhận vào hệ thống. Chúng tôi sẽ nhanh chóng chuẩn bị và liên hệ giao hàng sớm nhất!
                </p>

                {{-- Order Summary Details --}}
                <div style="background: var(--bg-page, #FAF7F9); border-radius: 16px; border: 1px solid var(--line, #F1E4EC); padding: 18px; text-align: left; margin-bottom: 24px;">
                    <div style="display: flex; justify-content: space-between; border-bottom: 1px dashed var(--line, #F1E4EC); padding-bottom: 10px; margin-bottom: 10px; font-size: 13.5px;">
                        <span style="color: var(--ink-soft, #6B7280);">Mã đơn hàng:</span>
                        <strong style="color: #EC4899; font-size: 14.5px;">#{{ $order['reference'] ?? ('SL-' . $order['order_id']) }}</strong>
                    </div>
                    @if(!empty($order['transaction_code']))
                        <div style="display: flex; justify-content: space-between; border-bottom: 1px dashed var(--line, #F1E4EC); padding-bottom: 10px; margin-bottom: 10px; font-size: 13.5px;">
                            <span style="color: var(--ink-soft, #6B7280);">Mã giao dịch thanh toán:</span>
                            <strong style="color: var(--ink, #111827); font-family: monospace;">{{ $order['transaction_code'] }}</strong>
                        </div>
                    @endif
                    <div style="display: flex; justify-content: space-between; border-bottom: 1px dashed var(--line, #F1E4EC); padding-bottom: 10px; margin-bottom: 10px; font-size: 13.5px;">
                        <span style="color: var(--ink-soft, #6B7280);">Phương thức thanh toán:</span>
                        <strong style="color: #EC4899; text-align: right; max-width: 62%;">{{ $order['payment_method_label'] ?? strtoupper($order['payment_method'] ?? 'QR') }}</strong>
                    </div>
                    <div style="display: flex; justify-content: space-between; border-bottom: 1px dashed var(--line, #F1E4EC); padding-bottom: 10px; margin-bottom: 10px; font-size: 13.5px;">
                        <span style="color: var(--ink-soft, #6B7280);">Sản phẩm:</span>
                        <strong style="color: var(--ink, #111827); max-width: 65%; text-align: right;">{{ $order['quantity'] }}x {{ $order['product_name'] }}</strong>
                    </div>
                    <div style="display: flex; justify-content: space-between; border-bottom: 1px dashed var(--line, #F1E4EC); padding-bottom: 10px; margin-bottom: 10px; font-size: 13.5px;">
                        <span style="color: var(--ink-soft, #6B7280);">Người nhận:</span>
                        <span style="font-weight: 600; color: var(--ink, #111827);">{{ $order['customer_name'] }} ({{ $order['customer_phone'] }})</span>
                    </div>
                    @if(!empty($order['shipping_address']))
                        <div style="display: flex; justify-content: space-between; border-bottom: 1px dashed var(--line, #F1E4EC); padding-bottom: 10px; margin-bottom: 10px; font-size: 13.5px;">
                            <span style="color: var(--ink-soft, #6B7280);">Địa chỉ nhận:</span>
                            <span style="color: var(--ink, #111827); text-align: right; max-width: 65%;">{{ $order['shipping_address'] }}</span>
                        </div>
                    @endif
                    <div style="display: flex; justify-content: space-between; align-items: baseline; padding-top: 8px; font-size: 14.5px;">
                        <span style="font-weight: 700; color: var(--ink, #111827);">{{ $isOrderPaidOnline ? 'Tổng tiền đã thanh toán:' : 'Tổng thanh toán:' }}</span>
                        <span style="font-size: 22px; font-weight: 800; color: #EC4899; font-family: 'Oswald', sans-serif;">
                            {{ number_format($order['total_price'], 0, ',', '.') }}₫
                        </span>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div style="display: flex; gap: 12px;">
                    <a href="{{ route('products.index') }}" style="flex: 1; height: 46px; border-radius: 12px; background: linear-gradient(135deg, #FF2E93 0%, #DB2777 100%); color: #FFFFFF; font-size: 14.5px; font-weight: 700; display: inline-flex; align-items: center; justify-content: center; gap: 6px; text-decoration: none; box-shadow: 0 4px 12px rgba(236,72,153,0.35);">
                        <i class="ti ti-shopping-bag"></i> Tiếp tục mua hàng
                    </a>
                    <a href="{{ route('profile.edit') }}" style="flex: 1; height: 46px; border-radius: 12px; background: var(--bg-page, #F3F4F6); color: var(--ink, #181824); border: 1.5px solid var(--line, #D1D5DB); font-size: 14px; font-weight: 600; display: inline-flex; align-items: center; justify-content: center; gap: 6px; text-decoration: none;">
                        <i class="ti ti-history"></i> Xem đơn hàng
                    </a>
                </div>
            </div>
        </div>
        <script nonce="{{ $cspNonce ?? '' }}">
            document.addEventListener('DOMContentLoaded', function() {
                const btnClose = document.getElementById('btn-close-order-modal');
                const modal = document.getElementById('orderSuccessModal');
                const orderToast = document.getElementById('orderPaymentToast');
                if (btnClose && modal) {
                    btnClose.addEventListener('click', function() {
                        modal.style.display = 'none';
                    });
                }
                if (orderToast) {
                    setTimeout(function() {
                        orderToast.style.display = 'none';
                    }, 7000);
                }
            });
        </script>
    @endif

    {{-- Plan Subscription Success Modal & Floating Payment Notification --}}
    @if(session('subscription_success'))
        @php $sub = session('subscription_success'); @endphp

        {{-- Top-right Toast Banner for Plan Subscription --}}
        <div id="subPaymentToast" style="position: fixed; top: 24px; right: 24px; z-index: 1000000; background: #181824; color: #FFFFFF; border-radius: 16px; padding: 16px 20px; display: flex; align-items: center; gap: 14px; box-shadow: 0 18px 40px rgba(24, 24, 36, 0.35); border: 1.5px solid #EC4899; max-width: 420px; animation: popIn 0.35s ease;">
            <div style="width: 42px; height: 42px; border-radius: 12px; background: linear-gradient(135deg, #FF2E93 0%, #DB2777 100%); color: #FFFFFF; display: flex; align-items: center; justify-content: center; font-size: 24px; flex-shrink: 0;">
                <i class="ti ti-crown"></i>
            </div>
            <div style="flex: 1; min-width: 0;">
                <div style="font-size: 13.5px; font-weight: 800; color: #F472B6; text-transform: uppercase; letter-spacing: 0.4px;">
                    Thông báo: Đã kích hoạt gói VIP!
                </div>
                <div style="font-size: 13px; color: #E2E8F0; line-height: 1.4; margin-top: 2px;">
                    Gói <strong>{{ $sub['plan_name'] }}</strong> (Mã <strong>#{{ $sub['reference'] ?? 'SUB-VIP' }}</strong>) đã được kích hoạt thành công.
                </div>
            </div>
        </div>

        <div id="subscriptionSuccessModal" style="position: fixed; inset: 0; background: rgba(0,0,0,0.65); backdrop-filter: blur(5px); display: flex; align-items: center; justify-content: center; z-index: 999999; padding: 20px;">
            <div style="background: var(--bg-white, #FFFFFF); border-radius: 24px; max-width: 540px; width: 100%; padding: 32px; box-shadow: 0 25px 60px rgba(0,0,0,0.3); border: 1px solid var(--line, #F1E4EC); position: relative; animation: popIn 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275); text-align: center; max-height: 92vh; overflow-y: auto;">

                <button type="button" id="btn-close-sub-modal" style="position: absolute; right: 18px; top: 18px; width: 34px; height: 34px; border-radius: 50%; border: none; background: var(--bg-page, #F3F4F6); color: var(--ink-soft, #6B7280); font-size: 18px; cursor: pointer; display: flex; align-items: center; justify-content: center;">
                    <i class="ti ti-x"></i>
                </button>

                {{-- Success Icon & Status Pill --}}
                <div style="width: 76px; height: 76px; border-radius: 50%; background: #FCE7F3; color: #DB2777; display: inline-flex; align-items: center; justify-content: center; font-size: 42px; margin-bottom: 12px; box-shadow: 0 8px 20px rgba(236,72,153,0.25);">
                    <i class="ti ti-crown"></i>
                </div>

                <div style="margin-bottom: 10px;">
                    <span style="display: inline-flex; align-items: center; gap: 6px; background: #FCE7F3; color: #BE185D; font-size: 12px; font-weight: 800; padding: 5px 14px; border-radius: 999px; border: 1px solid #F9A8D4;">
                        <i class="ti ti-qrcode"></i> ĐÃ XÁC NHẬN THANH TOÁN & KÍCH HOẠT TỰ ĐỘNG
                    </span>
                </div>

                <h2 style="font-size: 24px; font-weight: 800; color: var(--ink, #181824); margin: 0 0 6px; font-family: 'Oswald', sans-serif; letter-spacing: 0.5px;">
                    THANH TOÁN & KÍCH HOẠT GÓI THÀNH CÔNG!
                </h2>
                <p style="font-size: 13.5px; color: var(--ink-soft, #5A5A72); margin: 0 0 20px; line-height: 1.5;">
                    Chúc mừng bạn đã kích hoạt gói thành viên VIP! Mọi ưu đãi giảm giá và đặc quyền giờ vàng đã sẵn sàng cho các lần đặt sân tiếp theo.
                </p>

                {{-- Subscription Details --}}
                <div style="background: var(--bg-page, #FAF7F9); border-radius: 16px; border: 1px solid var(--line, #F1E4EC); padding: 18px; text-align: left; margin-bottom: 24px;">
                    <div style="display: flex; justify-content: space-between; border-bottom: 1px dashed var(--line, #F1E4EC); padding-bottom: 10px; margin-bottom: 10px; font-size: 13.5px;">
                        <span style="color: var(--ink-soft, #6B7280);">Mã đăng ký:</span>
                        <strong style="color: #EC4899; font-size: 14.5px;">#{{ $sub['reference'] ?? 'SUB-VIP' }}</strong>
                    </div>
                    @if(!empty($sub['transaction_code']))
                        <div style="display: flex; justify-content: space-between; border-bottom: 1px dashed var(--line, #F1E4EC); padding-bottom: 10px; margin-bottom: 10px; font-size: 13.5px;">
                            <span style="color: var(--ink-soft, #6B7280);">Mã giao dịch thanh toán:</span>
                            <strong style="color: var(--ink, #111827); font-family: monospace;">{{ $sub['transaction_code'] }}</strong>
                        </div>
                    @endif
                    <div style="display: flex; justify-content: space-between; border-bottom: 1px dashed var(--line, #F1E4EC); padding-bottom: 10px; margin-bottom: 10px; font-size: 13.5px;">
                        <span style="color: var(--ink-soft, #6B7280);">Phương thức thanh toán:</span>
                        <strong style="color: #EC4899; text-align: right; max-width: 62%;">{{ $sub['payment_method_label'] ?? strtoupper($sub['payment_method'] ?? 'QR') }}</strong>
                    </div>
                    <div style="display: flex; justify-content: space-between; border-bottom: 1px dashed var(--line, #F1E4EC); padding-bottom: 10px; margin-bottom: 10px; font-size: 13.5px;">
                        <span style="color: var(--ink-soft, #6B7280);">Gói thành viên:</span>
                        <strong style="color: var(--ink, #111827);">{{ $sub['plan_name'] }} ({{ $sub['duration_days'] }} ngày)</strong>
                    </div>
                    <div style="display: flex; justify-content: space-between; border-bottom: 1px dashed var(--line, #F1E4EC); padding-bottom: 10px; margin-bottom: 10px; font-size: 13.5px;">
                        <span style="color: var(--ink-soft, #6B7280);">Đặc quyền giảm giá:</span>
                        <span style="color: #DB2777; font-weight: 700;">Giảm {{ $sub['discount_percent'] }}% mỗi lượt đặt sân</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; border-bottom: 1px dashed var(--line, #F1E4EC); padding-bottom: 10px; margin-bottom: 10px; font-size: 13.5px;">
                        <span style="color: var(--ink-soft, #6B7280);">Thời hạn sử dụng:</span>
                        <span style="font-weight: 600; color: var(--ink, #111827);">{{ $sub['starts_at'] }} &rarr; {{ $sub['expires_at'] }}</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; border-bottom: 1px dashed var(--line, #F1E4EC); padding-bottom: 10px; margin-bottom: 10px; font-size: 13.5px;">
                        <span style="color: var(--ink-soft, #6B7280);">Tài khoản:</span>
                        <span style="color: var(--ink, #111827); font-weight: 600;">{{ $sub['customer_name'] }} ({{ $sub['customer_email'] }})</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; align-items: baseline; padding-top: 8px; font-size: 14.5px;">
                        <span style="font-weight: 700; color: var(--ink, #111827);">Tổng tiền đã thanh toán:</span>
                        <span style="font-size: 22px; font-weight: 800; color: #EC4899; font-family: 'Oswald', sans-serif;">
                            {{ number_format($sub['total_price'], 0, ',', '.') }}₫
                        </span>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div style="display: flex; gap: 12px;">
                    <a href="{{ route('courts.index') }}" style="flex: 1; height: 46px; border-radius: 12px; background: linear-gradient(135deg, #FF2E93 0%, #DB2777 100%); color: #FFFFFF; font-size: 14.5px; font-weight: 700; display: inline-flex; align-items: center; justify-content: center; gap: 6px; text-decoration: none; box-shadow: 0 4px 12px rgba(236,72,153,0.35);">
                        <i class="ti ti-calendar-event"></i> Đặt sân hưởng ưu đãi ngay
                    </a>
                    <a href="{{ route('profile.edit') }}" style="flex: 1; height: 46px; border-radius: 12px; background: var(--bg-page, #F3F4F6); color: var(--ink, #181824); border: 1.5px solid var(--line, #D1D5DB); font-size: 14px; font-weight: 600; display: inline-flex; align-items: center; justify-content: center; gap: 6px; text-decoration: none;">
                        <i class="ti ti-user-check"></i> Xem hồ sơ
                    </a>
                </div>
            </div>
        </div>
        <script nonce="{{ $cspNonce ?? '' }}">
            document.addEventListener('DOMContentLoaded', function() {
                const btnCloseSub = document.getElementById('btn-close-sub-modal');
                const subModal = document.getElementById('subscriptionSuccessModal');
                const subToast = document.getElementById('subPaymentToast');
                if (btnCloseSub && subModal) {
                    btnCloseSub.addEventListener('click', function() {
                        subModal.style.display = 'none';
                    });
                }
                if (subToast) {
                    setTimeout(function() {
                        subToast.style.display = 'none';
                    }, 7000);
                }
            });
        </script>
    @endif

    {{-- Booking Success Modal & Floating Payment Notification --}}
    @if(session('booking_success'))
        @php
            $bk = session('booking_success');
            $isPaidOnline = ($bk['payment_status'] ?? 'pending') === 'paid';
        @endphp

        {{-- Top-right Toast Banner for Payment & Booking Notification --}}
        <div id="bookingPaymentToast" style="position: fixed; top: 24px; right: 24px; z-index: 1000000; background: #181824; color: #FFFFFF; border-radius: 16px; padding: 16px 20px; display: flex; align-items: center; gap: 14px; box-shadow: 0 18px 40px rgba(24, 24, 36, 0.35); border: 1.5px solid #EC4899; max-width: 420px; animation: popIn 0.35s ease;">
            <div style="width: 42px; height: 42px; border-radius: 12px; background: linear-gradient(135deg, #FF2E93 0%, #DB2777 100%); color: #FFFFFF; display: flex; align-items: center; justify-content: center; font-size: 24px; flex-shrink: 0;">
                <i class="ti ti-bell-check"></i>
            </div>
            <div style="flex: 1; min-width: 0;">
                <div style="font-size: 13.5px; font-weight: 800; color: #F472B6; text-transform: uppercase; letter-spacing: 0.4px;">
                    {{ $isPaidOnline ? 'Thông báo: Đã nhận thanh toán!' : 'Thông báo: Đã giữ chỗ sân!' }}
                </div>
                <div style="font-size: 13px; color: #E2E8F0; line-height: 1.4; margin-top: 2px;">
                    Mã vé <strong>#{{ $bk['reference'] ?? 'BK-SUCCESS' }}</strong> ({{ $bk['court_name'] }}) đã được kích hoạt thành công.
                </div>
            </div>
        </div>

        <div id="bookingSuccessModal" style="position: fixed; inset: 0; background: rgba(0,0,0,0.65); backdrop-filter: blur(5px); display: flex; align-items: center; justify-content: center; z-index: 999999; padding: 20px;">
            <div style="background: var(--bg-white, #FFFFFF); border-radius: 24px; max-width: 540px; width: 100%; padding: 32px; box-shadow: 0 25px 60px rgba(0,0,0,0.3); border: 1px solid var(--line, #F1E4EC); position: relative; animation: popIn 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275); text-align: center; max-height: 92vh; overflow-y: auto;">

                <button type="button" id="btn-close-booking-modal" style="position: absolute; right: 18px; top: 18px; width: 34px; height: 34px; border-radius: 50%; border: none; background: var(--bg-page, #F3F4F6); color: var(--ink-soft, #6B7280); font-size: 18px; cursor: pointer; display: flex; align-items: center; justify-content: center;">
                    <i class="ti ti-x"></i>
                </button>

                {{-- Success Icon & Status Pill --}}
                <div style="width: 76px; height: 76px; border-radius: 50%; background: #FCE7F3; color: #DB2777; display: inline-flex; align-items: center; justify-content: center; font-size: 42px; margin-bottom: 12px; box-shadow: 0 8px 20px rgba(236,72,153,0.25);">
                    <i class="ti ti-circle-check"></i>
                </div>

                <div style="margin-bottom: 10px;">
                    @if($isPaidOnline)
                        <span style="display: inline-flex; align-items: center; gap: 6px; background: #FCE7F3; color: #BE185D; font-size: 12px; font-weight: 800; padding: 5px 14px; border-radius: 999px; border: 1px solid #F9A8D4;">
                            <i class="ti ti-qrcode"></i> ĐÃ XÁC NHẬN THANH TOÁN QR / ONLINE
                        </span>
                    @else
                        <span style="display: inline-flex; align-items: center; gap: 6px; background: #FEF3C7; color: #B45309; font-size: 12px; font-weight: 800; padding: 5px 14px; border-radius: 999px; border: 1px solid #FDE68A;">
                            <i class="ti ti-cash"></i> ĐÃ GIỮ CHỖ — THANH TOÁN TẠI SÂN
                        </span>
                    @endif
                </div>

                <h2 style="font-size: 24px; font-weight: 800; color: var(--ink, #181824); margin: 0 0 6px; font-family: 'Oswald', sans-serif; letter-spacing: 0.5px;">
                    {{ $isPaidOnline ? 'THANH TOÁN & ĐẶT SÂN THÀNH CÔNG!' : 'XÁC NHẬN GIỮ CHỖ SÂN THÀNH CÔNG!' }}
                </h2>
                <p style="font-size: 13.5px; color: var(--ink-soft, #5A5A72); margin: 0 0 20px; line-height: 1.5;">
                    Biên lai thanh toán và mã vé giữ chỗ điện tử của bạn đã được hệ thống kích hoạt tự động. Vui lòng đến trước 10 phút để nhận sân thuận lợi nhất!
                </p>

                {{-- Booking & Payment Receipt Details --}}
                <div style="background: var(--bg-page, #FAF7F9); border-radius: 16px; border: 1px solid var(--line, #F1E4EC); padding: 18px; text-align: left; margin-bottom: 24px;">
                    <div style="display: flex; justify-content: space-between; border-bottom: 1px dashed var(--line, #F1E4EC); padding-bottom: 10px; margin-bottom: 10px; font-size: 13.5px;">
                        <span style="color: var(--ink-soft, #6B7280);">Mã vé giữ chỗ:</span>
                        <strong style="color: #EC4899; font-size: 14.5px;">#{{ $bk['reference'] ?? 'BK-SUCCESS' }}</strong>
                    </div>
                    @if(!empty($bk['transaction_code']))
                        <div style="display: flex; justify-content: space-between; border-bottom: 1px dashed var(--line, #F1E4EC); padding-bottom: 10px; margin-bottom: 10px; font-size: 13.5px;">
                            <span style="color: var(--ink-soft, #6B7280);">Mã giao dịch thanh toán:</span>
                            <strong style="color: var(--ink, #111827); font-family: monospace;">{{ $bk['transaction_code'] }}</strong>
                        </div>
                    @endif
                    <div style="display: flex; justify-content: space-between; border-bottom: 1px dashed var(--line, #F1E4EC); padding-bottom: 10px; margin-bottom: 10px; font-size: 13.5px;">
                        <span style="color: var(--ink-soft, #6B7280);">Phương thức thanh toán:</span>
                        <strong style="color: #EC4899; text-align: right; max-width: 62%;">{{ $bk['payment_method_label'] ?? strtoupper($bk['payment_method'] ?? 'QR') }}</strong>
                    </div>
                    <div style="display: flex; justify-content: space-between; border-bottom: 1px dashed var(--line, #F1E4EC); padding-bottom: 10px; margin-bottom: 10px; font-size: 13.5px;">
                        <span style="color: var(--ink-soft, #6B7280);">Sân & Bộ môn:</span>
                        <strong style="color: var(--ink, #111827); text-align: right;">{{ $bk['court_name'] }} ({{ $bk['sport_type'] ?? 'Thể thao' }})</strong>
                    </div>
                    <div style="display: flex; justify-content: space-between; border-bottom: 1px dashed var(--line, #F1E4EC); padding-bottom: 10px; margin-bottom: 10px; font-size: 13.5px;">
                        <span style="color: var(--ink-soft, #6B7280);">Thời gian thi đấu:</span>
                        <span style="font-weight: 700; color: #EC4899;">{{ $bk['start_time'] }} - {{ $bk['end_time'] }} | {{ $bk['booking_date'] }}</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; border-bottom: 1px dashed var(--line, #F1E4EC); padding-bottom: 10px; margin-bottom: 10px; font-size: 13.5px;">
                        <span style="color: var(--ink-soft, #6B7280);">Người đặt sân:</span>
                        <span style="color: var(--ink, #111827); font-weight: 600;">{{ $bk['customer_name'] }} ({{ $bk['customer_phone'] }})</span>
                    </div>
                    @if(!empty($bk['discount_amount']) && $bk['discount_amount'] > 0)
                        <div style="display: flex; justify-content: space-between; border-bottom: 1px dashed var(--line, #F1E4EC); padding-bottom: 10px; margin-bottom: 10px; font-size: 13.5px;">
                            <span style="color: var(--ink-soft, #6B7280);">Ưu đãi hội viên VIP:</span>
                            <span style="color: #DB2777; font-weight: 700;">-{{ number_format($bk['discount_amount'], 0, ',', '.') }}₫</span>
                        </div>
                    @endif
                    <div style="display: flex; justify-content: space-between; align-items: baseline; padding-top: 8px; font-size: 14.5px;">
                        <span style="font-weight: 700; color: var(--ink, #111827);">{{ $isPaidOnline ? 'Tổng tiền đã thanh toán:' : 'Tổng tiền thanh toán:' }}</span>
                        <span style="font-size: 22px; font-weight: 800; color: #EC4899; font-family: 'Oswald', sans-serif;">
                            {{ number_format($bk['total_price'], 0, ',', '.') }}₫
                        </span>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div style="display: flex; gap: 12px;">
                    <a href="{{ route('courts.index') }}" style="flex: 1; height: 46px; border-radius: 12px; background: linear-gradient(135deg, #FF2E93 0%, #DB2777 100%); color: #FFFFFF; font-size: 14.5px; font-weight: 700; display: inline-flex; align-items: center; justify-content: center; gap: 6px; text-decoration: none; box-shadow: 0 4px 12px rgba(236,72,153,0.35);">
                        <i class="ti ti-plus"></i> Đặt thêm sân
                    </a>
                    <a href="{{ route('profile.edit') }}" style="flex: 1; height: 46px; border-radius: 12px; background: var(--bg-page, #F3F4F6); color: var(--ink, #181824); border: 1.5px solid var(--line, #D1D5DB); font-size: 14px; font-weight: 600; display: inline-flex; align-items: center; justify-content: center; gap: 6px; text-decoration: none;">
                        <i class="ti ti-ticket"></i> Xem lịch đã đặt
                    </a>
                </div>
            </div>
        </div>
        <script nonce="{{ $cspNonce ?? '' }}">
            document.addEventListener('DOMContentLoaded', function() {
                const btnCloseBk = document.getElementById('btn-close-booking-modal');
                const bkModal = document.getElementById('bookingSuccessModal');
                const bkToast = document.getElementById('bookingPaymentToast');
                if (btnCloseBk && bkModal) {
                    btnCloseBk.addEventListener('click', function() {
                        bkModal.style.display = 'none';
                    });
                }
                if (bkToast) {
                    setTimeout(function() {
                        bkToast.style.display = 'none';
                    }, 7000);
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
