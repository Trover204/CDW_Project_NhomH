<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <script nonce="{{ $cspNonce ?? '' }}">if(localStorage.getItem("theme")==="dark") document.documentElement.setAttribute("data-theme", "dark");</script>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('Khôi phục mật khẩu') }} - {{ config('app.name', 'Sân Nhóm H') }}</title>
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
            <div style="font-size:22px;font-weight:700;line-height:1.4;margin-bottom:12px;">{{ __('Khôi phục mật khẩu') }}</div>
            <div style="font-size:14px;color:#FBCFE8;">{{ __('Nhập email của bạn và chúng tôi sẽ gửi liên kết khôi phục mật khẩu.') }}</div>
        </div>
        <div style="display:flex;gap:8px;">
            <div style="width:10px;height:10px;border-radius:50%;background:rgba(255,255,255,.3);"></div>
            <div style="width:10px;height:10px;border-radius:50%;background:#EC4899;"></div>
            <div style="width:10px;height:10px;border-radius:50%;background:rgba(255,255,255,.3);"></div>
        </div>
    </div>

    <div class="right-panel">
        <div class="tabs">
            <a href="{{ route('login') }}" class="tab active" style="color:#6E687A; border:none; text-decoration:none;">{{ __('Đăng nhập') }}</a>
            <a href="{{ route('login') }}?form=register" class="tab" style="color:#6E687A; border:none; text-decoration:none;">{{ __('Đăng ký') }}</a>
            <div class="tab active" style="color:#EC4899; border-bottom:2px solid #EC4899; cursor:default;">{{ __('Quên mật khẩu') }}</div>
        </div>

        <form id="forgotPasswordForm" action="#" method="POST">
            @csrf

            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" value="{{ old('email') }}" placeholder="ban@email.com" required>
                @error('email') <span class="text-error">{{ $message }}</span> @enderror
            </div>

            <button type="submit" class="btn-submit">{{ __('Gửi liên kết khôi phục') }}</button>
        </form>

    </div>
</div>

</body>
</html>
