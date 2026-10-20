@extends('web.layouts.app')

@section('title', 'Điều khoản bảo mật - Sân Nhóm H')

@section('content')


    <section style="padding: 60px 0; background-color: var(--bg-page); min-height: calc(100vh - 200px);">
        <div class="wrap">
            <div style="max-width: 800px; margin: 0 auto; background: var(--surface); padding: 40px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.05);">
                <h1 style="font-size: 36px; color: var(--turf-dark); margin-bottom: 24px; font-family: 'Oswald', sans-serif; text-align: center;">Điều khoản & Bảo mật</h1>

                <div style="font-size: 16px; line-height: 1.6; color: var(--ink);">
                    <p style="margin-bottom: 16px;">Sân Nhóm H cam kết bảo vệ thông tin cá nhân của bạn. Chính sách bảo mật này giải thích cách chúng tôi thu thập, sử dụng và bảo vệ dữ liệu của bạn khi sử dụng nền tảng của chúng tôi.</p>

                    <h2 style="font-size: 20px; color: var(--turf-dark); margin-top: 24px; margin-bottom: 12px;">1. Thu thập thông tin</h2>
                    <p style="margin-bottom: 16px;">Chúng tôi có thể thu thập các thông tin cá nhân như tên, địa chỉ email, số điện thoại khi bạn đăng ký tài khoản hoặc đặt sân. Ngoài ra, chúng tôi cũng có thể thu thập thông tin không định danh như loại trình duyệt, địa chỉ IP để cải thiện trải nghiệm người dùng.</p>

                    <h2 style="font-size: 20px; color: var(--turf-dark); margin-top: 24px; margin-bottom: 12px;">2. Sử dụng thông tin</h2>
                    <ul style="list-style-type: disc; margin-left: 20px; margin-bottom: 16px;">
                        <li style="margin-bottom: 8px;">Để xác nhận lịch đặt sân và thanh toán.</li>
                        <li style="margin-bottom: 8px;">Để gửi thông báo quan trọng về dịch vụ.</li>
                        <li style="margin-bottom: 8px;">Để cải thiện và cá nhân hóa trải nghiệm của bạn trên trang web.</li>
                    </ul>

                    <h2 style="font-size: 20px; color: var(--turf-dark); margin-top: 24px; margin-bottom: 12px;">3. Bảo vệ thông tin</h2>
                    <p style="margin-bottom: 16px;">Chúng tôi áp dụng các biện pháp bảo mật tiêu chuẩn để bảo vệ thông tin cá nhân của bạn khỏi truy cập trái phép, sửa đổi, tiết lộ hoặc phá hủy. Tuy nhiên, không có phương thức truyền tải qua internet nào là an toàn 100%.</p>

                    <h2 style="font-size: 20px; color: var(--turf-dark); margin-top: 24px; margin-bottom: 12px;">4. Chia sẻ thông tin</h2>
                    <p style="margin-bottom: 16px;">Chúng tôi không bán, trao đổi hoặc chuyển nhượng thông tin cá nhân của bạn cho bên thứ ba, ngoại trừ các đối tác đáng tin cậy hỗ trợ chúng tôi trong việc vận hành trang web hoặc cung cấp dịch vụ cho bạn, miễn là các bên này đồng ý giữ bí mật thông tin.</p>

                    <h2 style="font-size: 20px; color: var(--turf-dark); margin-top: 24px; margin-bottom: 12px;">5. Thay đổi chính sách</h2>
                    <p style="margin-bottom: 16px;">Chúng tôi có thể cập nhật Chính sách bảo mật này theo thời gian. Mọi thay đổi sẽ được thông báo trên trang này. Việc bạn tiếp tục sử dụng dịch vụ sau khi có thay đổi đồng nghĩa với việc bạn chấp nhận các điều khoản mới.</p>
                </div>
            </div>
        </div>
    </section>

    @include('layouts.footer')
@endsection
