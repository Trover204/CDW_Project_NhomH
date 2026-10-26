<!DOCTYPE html>
<html lang="vi">
<head>
    <script>if(localStorage.getItem("theme")==="dark") document.documentElement.setAttribute("data-theme", "dark");</script>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Quên mật khẩu - Sân Nhóm H</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css">
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
</head>
<body>

<div class="container">
    <div class="left-panel">
        <div style="font-size:20px;font-weight:700;">
            <a href="{{ route('home') }}" style="color: white; text-decoration: none;">Sân Nhóm H</a>
        </div>
        <div>
            <div style="font-size:22px;font-weight:700;line-height:1.4;margin-bottom:12px;">Khôi phục mật khẩu</div>
            <div style="font-size:14px;color:#BFE0CC;">Nhập email của bạn và chúng tôi sẽ gửi liên kết khôi phục mật khẩu.</div>
        </div>
        <div style="display:flex;gap:8px;">
            <div style="width:10px;height:10px;border-radius:50%;background:rgba(255,255,255,.3);"></div>
            <div style="width:10px;height:10px;border-radius:50%;background:#fff;"></div>
            <div style="width:10px;height:10px;border-radius:50%;background:rgba(255,255,255,.3);"></div>
        </div>
    </div>

    <div class="right-panel">
        <div class="tabs">
            <div class="tab active" onclick="window.location.href='{{ route('login') }}'" style="color:#6B7A70; border:none;">Đăng nhập</div>
            <div class="tab" onclick="window.location.href='{{ route('login') }}?form=register'" style="color:#6B7A70; border:none;">Đăng ký</div>
            <div class="tab active" style="color:#178A4C; border-bottom:2px solid #178A4C; cursor:default;">Quên mật khẩu</div>
        </div>

        <form id="forgotPasswordForm" action="#" method="POST">
            @csrf

            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" value="{{ old('email') }}" placeholder="ban@email.com" required>
                @error('email') <span class="text-error">{{ $message }}</span> @enderror
            </div>

            <button type="submit" class="btn-submit">Gửi liên kết khôi phục</button>
        </form>

    </div>
</div>

</body>
</html>
