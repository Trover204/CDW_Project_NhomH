<!DOCTYPE html>
<html lang="vi">
<head>
    <script nonce="{{ $cspNonce ?? '' }}">if(localStorage.getItem("theme")==="dark") document.documentElement.setAttribute("data-theme", "dark");</script>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('Đăng nhập / Đăng ký') }} - {{ config('app.name', 'Sân Nhóm H') }}</title>
    
    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@500;600;700&family=Be+Vietnam+Pro:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css">
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}?v={{ filemtime(public_path('css/auth.css')) }}">
</head>
<body>

<div class="auth-layout-container">
    {{-- Left Showcase Panel --}}
    <div class="auth-left-showcase">
        <div class="showcase-header">
            <a href="{{ route('home') }}" class="auth-brand-logo">
                <i class="ti ti-trophy"></i>
                <span>Sân Nhóm <strong>H</strong></span>
            </a>
        </div>

        <div class="showcase-middle">
            <span class="showcase-badge"><i class="ti ti-bolt"></i> Nền tảng đặt sân thể thao số 1</span>
            <h2>Khởi động đam mê, ra sân thi đấu chỉ trong 30 giây</h2>
            <p>Hơn 500 sân bóng đá cỏ nhân tạo, cầu lông thảm chuẩn quốc tế, pickleball và tennis trên khắp thành phố đang chờ bạn.</p>

            <div class="showcase-highlights">
                <div class="highlight-item">
                    <div class="hl-icon"><i class="ti ti-calendar-check"></i></div>
                    <div class="hl-info">
                        <strong>Giữ chỗ tức thì 100%</strong>
                        <span>Không lo trùng lịch hay chờ xác nhận</span>
                    </div>
                </div>
                <div class="highlight-item">
                    <div class="hl-icon"><i class="ti ti-discount-check"></i></div>
                    <div class="hl-info">
                        <strong>Tích điểm & giảm giá độc quyền</strong>
                        <span>Ưu đãi tới 20% cho thành viên</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="showcase-footer">
            <div class="showcase-dots">
                <span class="s-dot active"></span>
                <span class="s-dot"></span>
                <span class="s-dot"></span>
            </div>
            <span class="showcase-copyright">© {{ date('Y') }} Sân Nhóm H. All rights reserved.</span>
        </div>
    </div>

    {{-- Right Form Panel --}}
    <div class="auth-right-form-panel">
        <div class="auth-box-inner">
            <div class="auth-header-mobile">
                <a href="{{ route('home') }}" class="mobile-back-home"><i class="ti ti-arrow-left"></i> Về trang chủ</a>
            </div>

            <div class="auth-welcome-text">
                <h3 id="authFormTitle">{{ old('form_type') == 'register' ? 'Tạo tài khoản mới' : 'Chào mừng trở lại!' }}</h3>
                <p id="authFormSub">{{ old('form_type') == 'register' ? 'Điền thông tin để tham gia cùng hàng ngàn người chơi thể thao' : 'Đăng nhập vào tài khoản Sân Nhóm H của bạn' }}</p>
            </div>

            <div class="tabs-switcher">
                <button type="button" class="tab-btn {{ old('form_type', 'login') == 'login' ? 'active' : '' }}" data-auth-tab="login">
                    <i class="ti ti-login"></i>
                    <span>Đăng nhập</span>
                </button>
                <button type="button" class="tab-btn {{ old('form_type') == 'register' ? 'active' : '' }}" data-auth-tab="register">
                    <i class="ti ti-user-plus"></i>
                    <span>Đăng ký</span>
                </button>
            </div>

            <!-- FORM ĐĂNG NHẬP -->
            <form id="loginForm" class="{{ old('form_type', 'login') == 'login' ? '' : 'hidden' }}" action="{{ route('login.post') }}" method="POST">
                @csrf
                <input type="hidden" name="form_type" value="login">

                <div class="form-group-modern">
                    <label>Địa chỉ Email</label>
                    <div class="input-modern-wrap">
                        <i class="ti ti-mail input-icon"></i>
                        <input type="email" name="email" value="{{ old('form_type', 'login') == 'login' ? old('email') : '' }}" placeholder="name@example.com" required autocomplete="email">
                    </div>
                    @if(old('form_type', 'login') == 'login')
                        @error('email') <span class="text-error-modern"><i class="ti ti-alert-circle"></i> {{ $message }}</span> @enderror
                    @endif
                </div>

                <div class="form-group-modern">
                    <label>Mật khẩu</label>
                    <div class="input-modern-wrap">
                        <i class="ti ti-lock input-icon"></i>
                        <input type="password" name="password" id="loginPassword" placeholder="••••••••" required autocomplete="current-password">
                        <button type="button" class="toggle-password-btn" data-toggle-target="loginPassword" aria-label="Toggle password visibility">
                            <i class="ti ti-eye"></i>
                        </button>
                    </div>
                    @if(old('form_type', 'login') == 'login')
                        @error('password') <span class="text-error-modern"><i class="ti ti-alert-circle"></i> {{ $message }}</span> @enderror
                    @endif
                </div>

                <div class="form-helpers-row">
                    <label class="remember-label">
                        <input type="checkbox" name="remember" checked>
                        <span>Ghi nhớ đăng nhập</span>
                    </label>
                    <a href="{{ route('password.request') }}" class="forgot-link">Quên mật khẩu?</a>
                </div>

                <button type="submit" class="btn-submit-modern">
                    <span>Đăng nhập tài khoản</span>
                    <i class="ti ti-arrow-right"></i>
                </button>
            </form>

            <!-- FORM ĐĂNG KÝ -->
            <form id="registerForm" class="{{ old('form_type') == 'register' ? '' : 'hidden' }}" action="{{ route('register.post') }}" method="POST">
                @csrf
                <input type="hidden" name="form_type" value="register">

                <div class="form-group-modern">
                    <label>Họ và tên</label>
                    <div class="input-modern-wrap">
                        <i class="ti ti-user input-icon"></i>
                        <input type="text" name="name" value="{{ old('form_type') == 'register' ? old('name') : '' }}" placeholder="Ví dụ: Nguyễn Văn An" required>
                    </div>
                    @if(old('form_type') == 'register')
                        @error('name') <span class="text-error-modern"><i class="ti ti-alert-circle"></i> {{ $message }}</span> @enderror
                    @endif
                </div>

                <div class="form-group-modern">
                    <label>Địa chỉ Email</label>
                    <div class="input-modern-wrap">
                        <i class="ti ti-mail input-icon"></i>
                        <input type="email" name="email" value="{{ old('form_type') == 'register' ? old('email') : '' }}" placeholder="name@example.com" required>
                    </div>
                    @if(old('form_type') == 'register')
                        @error('email') <span class="text-error-modern"><i class="ti ti-alert-circle"></i> {{ $message }}</span> @enderror
                    @endif
                </div>

                <div class="form-group-modern">
                    <label>Mật khẩu</label>
                    <div class="input-modern-wrap">
                        <i class="ti ti-lock input-icon"></i>
                        <input type="password" name="password" id="regPassword" placeholder="Tối thiểu 6 ký tự" required autocomplete="new-password">
                        <button type="button" class="toggle-password-btn" data-toggle-target="regPassword" aria-label="Toggle password visibility">
                            <i class="ti ti-eye"></i>
                        </button>
                    </div>
                    @if(old('form_type') == 'register')
                        @error('password') <span class="text-error-modern"><i class="ti ti-alert-circle"></i> {{ $message }}</span> @enderror
                    @endif
                </div>

                <div class="form-group-modern">
                    <label>Xác nhận mật khẩu</label>
                    <div class="input-modern-wrap">
                        <i class="ti ti-shield-lock input-icon"></i>
                        <input type="password" name="password_confirmation" id="regPasswordConf" placeholder="Nhập lại mật khẩu" required autocomplete="new-password">
                    </div>
                </div>

                <div class="terms-note-row">
                    <span>Bằng cách đăng ký, bạn đồng ý với <a href="{{ route('pages.privacy') }}" target="_blank">Điều khoản sử dụng</a> & <a href="{{ route('pages.privacy') }}" target="_blank">Chính sách bảo mật</a> của chúng tôi.</span>
                </div>

                <button type="submit" class="btn-submit-modern btn-reg-submit">
                    <span>Hoàn tất đăng ký</span>
                    <i class="ti ti-check"></i>
                </button>
            </form>

            <div class="auth-bottom-switch">
                <span id="bottomSwitchText">{{ old('form_type') == 'register' ? 'Đã có tài khoản?' : 'Chưa có tài khoản?' }}</span>
                <button type="button" id="btnToggleAuthBottom" class="btn-link-switch">
                    {{ old('form_type') == 'register' ? 'Đăng nhập ngay' : 'Tạo tài khoản mới' }}
                </button>
            </div>
        </div>
    </div>
</div>

<script nonce="{{ $cspNonce ?? '' }}">
    function showForm(type) {
        const titleEl = document.getElementById('authFormTitle');
        const subEl = document.getElementById('authFormSub');
        const switchTextEl = document.getElementById('bottomSwitchText');
        const switchBtnEl = document.getElementById('btnToggleAuthBottom');

        document.querySelectorAll('.tab-btn').forEach(tab => tab.classList.remove('active'));
        document.getElementById('loginForm').classList.add('hidden');
        document.getElementById('registerForm').classList.add('hidden');

        if(type === 'login') {
            document.querySelector('[data-auth-tab="login"]').classList.add('active');
            document.getElementById('loginForm').classList.remove('hidden');
            if (titleEl) titleEl.textContent = 'Chào mừng trở lại!';
            if (subEl) subEl.textContent = 'Đăng nhập vào tài khoản Sân Nhóm H của bạn';
            if (switchTextEl) switchTextEl.textContent = 'Chưa có tài khoản?';
            if (switchBtnEl) switchBtnEl.textContent = 'Tạo tài khoản mới';
        } else {
            document.querySelector('[data-auth-tab="register"]').classList.add('active');
            document.getElementById('registerForm').classList.remove('hidden');
            if (titleEl) titleEl.textContent = 'Tạo tài khoản mới';
            if (subEl) subEl.textContent = 'Điền thông tin để tham gia cùng hàng ngàn người chơi thể thao';
            if (switchTextEl) switchTextEl.textContent = 'Đã có tài khoản?';
            if (switchBtnEl) switchBtnEl.textContent = 'Đăng nhập ngay';
        }
    }

    document.querySelectorAll('[data-auth-tab]').forEach(tab => {
        tab.addEventListener('click', () => showForm(tab.dataset.authTab));
    });

    const bottomSwitch = document.getElementById('btnToggleAuthBottom');
    if (bottomSwitch) {
        bottomSwitch.addEventListener('click', () => {
            const isLogin = !document.getElementById('loginForm').classList.contains('hidden');
            showForm(isLogin ? 'register' : 'login');
        });
    }

    // Toggle password visibility
    document.querySelectorAll('[data-toggle-target]').forEach(btn => {
        btn.addEventListener('click', () => {
            const targetId = btn.getAttribute('data-toggle-target');
            const targetInput = document.getElementById(targetId);
            const icon = btn.querySelector('i');
            if (targetInput) {
                if (targetInput.type === 'password') {
                    targetInput.type = 'text';
                    icon.className = 'ti ti-eye-off';
                } else {
                    targetInput.type = 'password';
                    icon.className = 'ti ti-eye';
                }
            }
        });
    });
</script>

</body>
</html>
