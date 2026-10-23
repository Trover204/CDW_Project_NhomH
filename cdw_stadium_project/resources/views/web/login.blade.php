<!DOCTYPE html>
<html lang="vi">
<head>
    <script>if(localStorage.getItem("theme")==="dark") document.documentElement.setAttribute("data-theme", "dark");</script>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Đăng nhập / Đăng ký - Sân Nhóm H</title>
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
            <div style="font-size:22px;font-weight:700;line-height:1.4;margin-bottom:12px;">Đặt sân thể thao yêu thích chỉ trong vài giây</div>
            <div style="font-size:14px;color:#BFE0CC;">Hơn 500 sân bóng đá, cầu lông, pickleball trên toàn thành phố</div>
        </div>
        <div style="display:flex;gap:8px;">
            <div style="width:10px;height:10px;border-radius:50%;background:rgba(255,255,255,.3);"></div>
            <div style="width:10px;height:10px;border-radius:50%;background:#fff;"></div>
            <div style="width:10px;height:10px;border-radius:50%;background:rgba(255,255,255,.3);"></div>
        </div>
    </div>

    <div class="right-panel">
        <div class="tabs">
            <div class="tab {{ old('form_type', 'login') == 'login' ? 'active' : '' }}" onclick="showForm('login')">Đăng nhập</div>
            <div class="tab {{ old('form_type') == 'register' ? 'active' : '' }}" onclick="showForm('register')">Đăng ký</div>
        </div>

        <!-- FORM ĐĂNG NHẬP -->
        <form id="loginForm" class="{{ old('form_type', 'login') == 'login' ? '' : 'hidden' }}" action="{{ route('login.post') }}" method="POST">
            @csrf
            <input type="hidden" name="form_type" value="login">

            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" value="{{ old('form_type', 'login') == 'login' ? old('email') : '' }}" placeholder="ban@email.com" required>
                @if(old('form_type', 'login') == 'login')
                    @error('email') <span class="text-error">{{ $message }}</span> @enderror
                @endif
            </div>

            <div class="form-group">
                <label>Mật khẩu</label>
                <input type="password" name="password" placeholder="••••••••" required>
                @if(old('form_type', 'login') == 'login')
                    @error('password') <span class="text-error">{{ $message }}</span> @enderror
                @endif
            </div>

            <div style="display:flex;justify-content:flex-end;margin-bottom:16px;">
                <a href="{{ route('password.request') }}" style="font-size:12px;color:#178A4C;text-decoration:none;">Quên mật khẩu?</a>
            </div>

            <button type="submit" class="btn-submit">Đăng nhập</button>
        </form>

        <!-- FORM ĐĂNG KÝ -->
        <form id="registerForm" class="{{ old('form_type') == 'register' ? '' : 'hidden' }}" action="{{ route('register.post') }}" method="POST">
            @csrf
            <input type="hidden" name="form_type" value="register">

            <div class="form-group">
                <label>Họ tên</label>
                <input type="text" name="name" value="{{ old('form_type') == 'register' ? old('name') : '' }}" placeholder="Nguyễn Văn A" required>
                @if(old('form_type') == 'register')
                    @error('name') <span class="text-error">{{ $message }}</span> @enderror
                @endif
            </div>

            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" value="{{ old('form_type') == 'register' ? old('email') : '' }}" placeholder="ban@email.com" required>
                @if(old('form_type') == 'register')
                    @error('email') <span class="text-error">{{ $message }}</span> @enderror
                @endif
            </div>

            <div class="form-group">
                <label>Mật khẩu</label>
                <input type="password" name="password" placeholder="••••••••" required>
                @if(old('form_type') == 'register')
                    @error('password') <span class="text-error">{{ $message }}</span> @enderror
                @endif
            </div>

            <div class="form-group">
                <label>Xác nhận mật khẩu</label>
                <input type="password" name="password_confirmation" placeholder="••••••••" required>
            </div>

            <button type="submit" class="btn-submit">Đăng ký</button>
        </form>

    </div>
</div>

<script>
    function showForm(type) {
        document.querySelectorAll('.tab').forEach(tab => tab.classList.remove('active'));
        document.getElementById('loginForm').classList.add('hidden');
        document.getElementById('registerForm').classList.add('hidden');

        if(type === 'login') {
            document.querySelectorAll('.tab')[0].classList.add('active');
            document.getElementById('loginForm').classList.remove('hidden');
        } else {
            document.querySelectorAll('.tab')[1].classList.add('active');
            document.getElementById('registerForm').classList.remove('hidden');
        }
    }
</script>

</body>
</html>
