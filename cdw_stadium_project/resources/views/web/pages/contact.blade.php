@extends('web.layouts.app')

@section('title', 'Liên hệ - Sân Nhóm H')

@section('content')


    <section style="padding: 60px 0; background-color: var(--bg-page); min-height: calc(100vh - 200px);">
        <div class="wrap">
            <div style="max-width: 800px; margin: 0 auto; background: var(--surface); padding: 40px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.05);">
                <h1 style="font-size: 36px; color: var(--turf-dark); margin-bottom: 24px; font-family: 'Oswald', sans-serif; text-align: center;">Liên hệ</h1>

                <div style="font-size: 16px; line-height: 1.6; color: var(--ink);">
                    <p style="margin-bottom: 32px; text-align: center;">Chúng tôi luôn sẵn sàng lắng nghe và giải đáp mọi thắc mắc của bạn.</p>

                    <div style="display: flex; flex-direction: column; gap: 20px; margin-bottom: 40px;">
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <i class="ti ti-map-pin" style="font-size: 24px; color: var(--turf-teal);"></i>
                            <span><strong>Địa chỉ:</strong> Khu dân cư Nhóm H, Thành phố Hồ Chí Minh</span>
                        </div>
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <i class="ti ti-phone" style="font-size: 24px; color: var(--turf-teal);"></i>
                            <span><strong>Điện thoại:</strong> 0123 456 789</span>
                        </div>
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <i class="ti ti-mail" style="font-size: 24px; color: var(--turf-teal);"></i>
                            <span><strong>Email:</strong> hotro@sannhomh.vn</span>
                        </div>
                    </div>

                    <h2 style="font-size: 24px; color: var(--turf-dark); margin-bottom: 16px;">Gửi tin nhắn cho chúng tôi</h2>
                    <form action="#" method="POST" style="display: flex; flex-direction: column; gap: 16px;">
                        <div>
                            <label style="display: block; margin-bottom: 8px; font-weight: 500;">Họ và tên</label>
                            <input type="text" style="width: 100%; padding: 10px 14px; border: 1px solid var(--border-color); border-radius: 8px; background: var(--bg-page); color: var(--ink);" placeholder="Nhập họ và tên...">
                        </div>
                        <div>
                            <label style="display: block; margin-bottom: 8px; font-weight: 500;">Email</label>
                            <input type="email" style="width: 100%; padding: 10px 14px; border: 1px solid var(--border-color); border-radius: 8px; background: var(--bg-page); color: var(--ink);" placeholder="Nhập địa chỉ email...">
                        </div>
                        <div>
                            <label style="display: block; margin-bottom: 8px; font-weight: 500;">Tin nhắn</label>
                            <textarea rows="5" style="width: 100%; padding: 10px 14px; border: 1px solid var(--border-color); border-radius: 8px; background: var(--bg-page); color: var(--ink); resize: vertical;" placeholder="Nhập nội dung tin nhắn..."></textarea>
                        </div>
                        <button type="button" class="btn btn-solid" style="align-self: flex-start; padding: 12px 24px;">Gửi tin nhắn</button>
                    </form>
                </div>
            </div>
        </div>
    </section>


@endsection
