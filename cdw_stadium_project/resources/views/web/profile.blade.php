@extends('web.layouts.app')

@push('styles')
<style>
        .profile-container {
            max-width: 1180px;
            margin: 36px auto 60px;
            padding: 0 20px;
        }

        .breadcrumb {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
            color: var(--ink-soft, #5E726A);
            margin-bottom: 24px;
        }

        .breadcrumb a {
            color: var(--ink-soft, #5E726A);
            transition: color 0.2s;
        }

        .breadcrumb a:hover {
            color: #178A4C;
        }

        .profile-grid {
            display: grid;
            grid-template-columns: 320px 1fr;
            gap: 28px;
            align-items: start;
        }

        @media (max-width: 900px) {
            .profile-grid {
                grid-template-columns: 1fr;
            }
        }

        /* Sidebar Card */
        .profile-card {
            background: #FFFFFF;
            border: 1px solid #E1E8E3;
            border-radius: 16px;
            box-shadow: 0 4px 18px rgba(13, 56, 46, 0.04);
            overflow: hidden;
        }

        .profile-header-card {
            padding: 28px 20px 22px;
            text-align: center;
            background: linear-gradient(180deg, #F0FDF4 0%, #FFFFFF 100%);
            border-bottom: 1px solid #EBF1ED;
        }

        .avatar-wrap {
            position: relative;
            width: 86px;
            height: 86px;
            margin: 0 auto 14px;
        }

        .avatar-img {
            width: 86px;
            height: 86px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #FFFFFF;
            box-shadow: 0 4px 12px rgba(15, 92, 51, 0.15);
            background: #E6F5EB;
        }

        .avatar-fallback {
            width: 86px;
            height: 86px;
            border-radius: 50%;
            background: #0F5C33;
            color: #FFFFFF;
            font-size: 28px;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 3px solid #FFFFFF;
            box-shadow: 0 4px 12px rgba(15, 92, 51, 0.15);
        }

        .avatar-badge-btn {
            position: absolute;
            bottom: 0;
            right: 0;
            width: 28px;
            height: 28px;
            border-radius: 50%;
            background: #178A4C;
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid #FFFFFF;
            cursor: pointer;
            box-shadow: 0 2px 6px rgba(0,0,0,0.15);
        }

        .profile-name {
            font-size: 17px;
            font-weight: 700;
            color: #11201B;
            margin-bottom: 4px;
        }

        .profile-email {
            font-size: 13px;
            color: #5E726A;
            margin-bottom: 12px;
        }

        .membership-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .badge-vip {
            background: #FEF3C7;
            color: #92400E;
            border: 1px solid #FDE68A;
        }

        .badge-standard {
            background: #E6F5EB;
            color: #0F5C33;
            border: 1px solid #C7D3CA;
        }

        .profile-stats-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            border-top: 1px solid #EBF1ED;
            border-bottom: 1px solid #EBF1ED;
            background: #FAFDFB;
        }

        .profile-stat-box {
            padding: 14px 10px;
            text-align: center;
        }

        .profile-stat-box:first-child {
            border-right: 1px solid #EBF1ED;
        }

        .profile-stat-num {
            font-size: 18px;
            font-weight: 700;
            color: #0F5C33;
        }

        .profile-stat-label {
            font-size: 11.5px;
            color: #6B7A70;
            margin-top: 2px;
        }

        .profile-menu {
            padding: 12px;
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .menu-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 11px 16px;
            border-radius: 10px;
            font-size: 13.5px;
            color: #3A453E;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s ease;
            text-decoration: none;
            border: none;
            background: transparent;
            width: 100%;
            text-align: left;
        }

        .menu-item i {
            font-size: 18px;
            color: #6B7A70;
            transition: color 0.2s;
        }

        .menu-item:hover {
            background: #F2F7F4;
            color: #0F5C33;
        }

        .menu-item:hover i {
            color: #0F5C33;
        }

        .menu-item.active {
            background: #E6F5EB;
            color: #0F5C33;
            font-weight: 600;
        }

        .menu-item.active i {
            color: #178A4C;
        }

        .menu-divider {
            height: 1px;
            background: #EBF1ED;
            margin: 6px 12px;
        }

        /* Right Content Panel */
        .content-card {
            background: #FFFFFF;
            border: 1px solid #E1E8E3;
            border-radius: 16px;
            box-shadow: 0 4px 18px rgba(13, 56, 46, 0.04);
            padding: 32px 36px;
        }

        @media (max-width: 600px) {
            .content-card {
                padding: 20px;
            }
        }

        .section-header {
            margin-bottom: 24px;
            padding-bottom: 14px;
            border-bottom: 1px solid #EBF1ED;
        }

        .section-title {
            font-size: 20px;
            font-weight: 700;
            color: #11201B;
            margin: 0 0 4px;
        }

        .section-desc {
            font-size: 13px;
            color: #5E726A;
            margin: 0;
        }

        /* Form Styles */
        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 22px;
        }

        @media (max-width: 650px) {
            .form-grid {
                grid-template-columns: 1fr;
            }
        }

        .form-group-full {
            grid-column: 1 / -1;
        }

        .field-group {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .field-label {
            font-size: 13px;
            font-weight: 600;
            color: #213229;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .input-wrap {
            position: relative;
            display: flex;
            align-items: center;
        }

        .input-icon {
            position: absolute;
            left: 14px;
            font-size: 18px;
            color: #879C94;
            pointer-events: none;
        }

        .form-input {
            width: 100%;
            height: 44px;
            padding: 0 14px 0 42px;
            border: 1px solid #C7D3CA;
            border-radius: 10px;
            font-size: 14px;
            color: #11201B;
            background: #FFFFFF;
            outline: none;
            transition: all 0.2s ease;
            font-family: inherit;
        }

        .form-input:focus {
            border-color: #178A4C;
            box-shadow: 0 0 0 3px rgba(23, 138, 76, 0.12);
        }

        .form-input.is-invalid {
            border-color: #DC2626;
            background: #FEF2F2;
        }

        .input-error {
            font-size: 12px;
            color: #DC2626;
            margin-top: 2px;
        }

        .form-hint {
            font-size: 11.5px;
            color: #6B7A70;
        }

        /* Avatar Upload Area */
        .avatar-upload-box {
            display: flex;
            align-items: center;
            gap: 20px;
            padding: 16px;
            border-radius: 12px;
            background: #F7FAF8;
            border: 1px dashed #C7D3CA;
            margin-bottom: 24px;
        }

        .avatar-preview-small {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #FFFFFF;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        .btn-upload-file {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 16px;
            border-radius: 8px;
            border: 1px solid #C7D3CA;
            background: #FFFFFF;
            font-size: 12.5px;
            font-weight: 600;
            color: #3A453E;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-upload-file:hover {
            background: #F0FDF4;
            border-color: #178A4C;
            color: #0F5C33;
        }

        /* Buttons */
        .form-actions {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-top: 10px;
            padding-top: 20px;
            border-top: 1px solid #EBF1ED;
        }

        .btn-save {
            height: 42px;
            padding: 0 24px;
            border-radius: 10px;
            background: #F97316;
            color: #FFFFFF;
            font-size: 13.5px;
            font-weight: 600;
            border: none;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: background 0.2s;
        }

        .btn-save:hover {
            background: #EA580C;
        }

        .btn-cancel {
            height: 42px;
            padding: 0 20px;
            border-radius: 10px;
            border: 1px solid #C7D3CA;
            background: #FFFFFF;
            color: #3A453E;
            font-size: 13.5px;
            font-weight: 500;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            transition: all 0.2s;
        }

        .btn-cancel:hover {
            background: #F5F7F5;
        }

        /* Alert Notifications */
        .alert-box {
            padding: 12px 16px;
            border-radius: 10px;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 13.5px;
        }

        .alert-success {
            background: #E6F5EB;
            color: #0F5C33;
            border: 1px solid #B8E4C7;
        }

        .alert-danger {
            background: #FEF2F2;
            color: #991B1B;
            border: 1px solid #FECACA;
        }

        /* Membership Card Inside Tab */
        .plan-card-detail {
            border: 1.5px solid #178A4C;
            border-radius: 14px;
            padding: 24px;
            background: linear-gradient(135deg, #F0FDF4 0%, #FFFFFF 100%);
            margin-bottom: 20px;
        }

        .plan-detail-top {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 16px;
        }

        .plan-benefit-item {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 10px;
            font-size: 13px;
            color: #213229;
        }

        .plan-benefit-item i {
            color: #178A4C;
            font-size: 16px;
        }
    </style>
@endpush

@section('content')
{{-- =====================================================

    ====================================================== --}}


    {{-- =====================================================
         MAIN PROFILE CONTENT
    ====================================================== --}}
    <div class="profile-container">

        {{-- BREADCRUMB --}}
        <div class="breadcrumb">
            <a href="{{ route('home') }}"><i class="ti ti-home" style="font-size: 14px;"></i> Trang chủ</a>
            <i class="ti ti-chevron-right" style="font-size: 12px;"></i>
            <span>Tài khoản cá nhân</span>
            <i class="ti ti-chevron-right" style="font-size: 12px;"></i>
            <span style="color: #11201B; font-weight: 600;">Hồ sơ</span>
        </div>

        {{-- FLASH ALERTS --}}
        @if(session('success'))
            <div class="alert-box alert-success">
                <i class="ti ti-circle-check" style="font-size: 18px;"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if($errors->any())
            <div class="alert-box alert-danger">
                <i class="ti ti-alert-triangle" style="font-size: 18px;"></i>
                <div>
                    <strong>Đã có lỗi xảy ra:</strong>
                    <ul style="margin: 4px 0 0; padding-left: 18px;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <div class="profile-grid">

            {{-- =====================================================
                 LEFT COLUMN: USER SUMMARY CARD & NAVIGATION
            ====================================================== --}}
            <aside class="profile-card">

                <div class="profile-header-card">
                    <div class="avatar-wrap">
                        @if($user->avatar)
                            <img src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->name }}" class="avatar-img" id="sidebarAvatar">
                        @else
                            <div class="avatar-fallback" id="sidebarAvatarFallback">
                                {{ strtoupper(mb_substr($user->name, 0, 1, 'UTF-8')) }}
                            </div>
                        @endif
                        <label for="avatarInput" class="avatar-badge-btn" title="Thay đổi ảnh đại diện">
                            <i class="ti ti-camera" style="font-size: 14px;"></i>
                        </label>
                    </div>

                    <div class="profile-name">{{ $user->name }}</div>
                    <div class="profile-email">{{ $user->email }}</div>

                    @if($activeSubscription && $activeSubscription->plan)
                        <span class="membership-badge badge-vip">
                            <i class="ti ti-crown"></i> Gói {{ $activeSubscription->plan->name }}
                        </span>
                    @else
                        <span class="membership-badge badge-standard">
                            <i class="ti ti-user-check"></i> Thành viên tiêu chuẩn
                        </span>
                    @endif
                </div>

                {{-- QUICK STATS ROW --}}
                <div class="profile-stats-row">
                    <div class="profile-stat-box">
                        <div class="profile-stat-num">{{ $bookingsCount ?? 0 }}</div>
                        <div class="profile-stat-label">Lượt đặt sân</div>
                    </div>
                    <div class="profile-stat-box">
                        <div class="profile-stat-num">{{ $favoritesCount ?? 0 }}</div>
                        <div class="profile-stat-label">Sân yêu thích</div>
                    </div>
                </div>

                {{-- NAVIGATION TABS --}}
                <div class="profile-menu">
                    <button type="button" class="menu-item {{ session('active_tab', 'profile') == 'profile' ? 'active' : '' }}" onclick="switchTab('profile', this)">
                        <i class="ti ti-user"></i>
                        <span>Thông tin cá nhân</span>
                    </button>

                    <button type="button" class="menu-item {{ session('active_tab') == 'password' ? 'active' : '' }}" onclick="switchTab('password', this)">
                        <i class="ti ti-shield-lock"></i>
                        <span>Đổi mật khẩu</span>
                    </button>

                    <a href="{{ route('plans.index') }}" class="menu-item">
                        <i class="ti ti-award"></i>
                        <span>Gói hội viên</span>
                    </a>

                    <div class="menu-divider"></div>

                    @if(in_array($user->role, ['admin', 'staff']))
                        <a href="{{ route('admin.dashboard') }}" class="menu-item" style="color: #0F5C33; font-weight: 600;">
                            <i class="ti ti-layout-dashboard" style="color: #178A4C;"></i>
                            <span>Dashboard</span>
                        </a>
                    @endif

                    <a href="{{ route('favorites.index') }}" class="menu-item">
                        <i class="ti ti-heart"></i>
                        <span>Sân yêu thích</span>
                    </a>

                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="menu-item" style="color: #DC2626;">
                            <i class="ti ti-logout" style="color: #DC2626;"></i>
                            <span>Đăng xuất</span>
                        </button>
                    </form>
                </div>

            </aside>

            {{-- =====================================================
                 RIGHT COLUMN: TAB DETAILS PANELS
            ====================================================== --}}
            <section class="content-card">

                {{-- TAB 1: THÔNG TIN CÁ NHÂN --}}
                <div id="tab-profile" class="tab-pane {{ session('active_tab', 'profile') == 'profile' ? '' : 'style-none' }}" style="{{ session('active_tab', 'profile') == 'profile' ? '' : 'display:none;' }}">
                    <div class="section-header">
                        <h2 class="section-title">Thông tin cá nhân</h2>
                        <p class="section-desc">Cập nhật họ tên, số điện thoại, ngày sinh và ảnh đại diện của bạn.</p>
                    </div>

                    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        {{-- AVATAR UPLOAD ROW --}}
                        <div class="avatar-upload-box">
                            @if($user->avatar)
                                <img src="{{ asset('storage/' . $user->avatar) }}" id="previewAvatarImg" class="avatar-preview-small" alt="Avatar">
                            @else
                                <div id="previewAvatarFallback" class="avatar-fallback" style="width: 60px; height: 60px; font-size: 20px;">
                                    {{ strtoupper(mb_substr($user->name, 0, 1, 'UTF-8')) }}
                                </div>
                            @endif

                            <div>
                                <label for="avatarInput" class="btn-upload-file">
                                    <i class="ti ti-upload"></i> Chọn ảnh đại diện mới
                                </label>
                                <input type="file" id="avatarInput" name="avatar" accept="image/png, image/jpeg, image/jpg" style="display: none;" onchange="previewSelectedAvatar(this)">
                                <div class="form-hint" style="margin-top: 6px;">Hỗ trợ ảnh JPG, PNG tối đa 2MB.</div>
                            </div>
                        </div>

                        <div class="form-grid">

                            {{-- HỌ VÀ TÊN --}}
                            <div class="field-group">
                                <label class="field-label" for="name">
                                    Họ và tên <span style="color: #DC2626;">*</span>
                                </label>
                                <div class="input-wrap">
                                    <i class="ti ti-user input-icon"></i>
                                    <input
                                        type="text"
                                        id="name"
                                        name="name"
                                        class="form-input @error('name') is-invalid @enderror"
                                        value="{{ old('name', $user->name) }}"
                                        placeholder="Ví dụ: Nguyễn Văn An"
                                        required
                                    >
                                </div>
                                @error('name')
                                    <span class="input-error">{{ $message }}</span>
                                @enderror
                            </div>

                            {{-- SỐ ĐIỆN THOẠI --}}
                            <div class="field-group">
                                <label class="field-label" for="phone">
                                    Số điện thoại
                                </label>
                                <div class="input-wrap">
                                    <i class="ti ti-phone input-icon"></i>
                                    <input
                                        type="tel"
                                        id="phone"
                                        name="phone"
                                        class="form-input @error('phone') is-invalid @enderror"
                                        value="{{ old('phone', $user->phone) }}"
                                        placeholder="Ví dụ: 0912345678"
                                    >
                                </div>
                                @error('phone')
                                    <span class="input-error">{{ $message }}</span>
                                @enderror
                            </div>

                            {{-- EMAIL --}}
                            <div class="field-group">
                                <label class="field-label" for="email">
                                    Địa chỉ Email <span style="color: #DC2626;">*</span>
                                </label>
                                <div class="input-wrap">
                                    <i class="ti ti-mail input-icon"></i>
                                    <input
                                        type="email"
                                        id="email"
                                        name="email"
                                        class="form-input @error('email') is-invalid @enderror"
                                        value="{{ old('email', $user->email) }}"
                                        placeholder="name@example.com"
                                        required
                                    >
                                </div>
                                @error('email')
                                    <span class="input-error">{{ $message }}</span>
                                @enderror
                            </div>

                            {{-- NGÀY SINH --}}
                            <div class="field-group">
                                <label class="field-label" for="birthday">
                                    <span>Ngày sinh</span>
                                    <span class="form-hint">(Tối thiểu 16 tuổi)</span>
                                </label>
                                <div class="input-wrap">
                                    <i class="ti ti-calendar input-icon"></i>
                                    <input
                                        type="date"
                                        id="birthday"
                                        name="birthday"
                                        max="{{ now()->subYears(16)->format('Y-m-d') }}"
                                        class="form-input @error('birthday') is-invalid @enderror"
                                        value="{{ old('birthday', $user->birthday ? \Carbon\Carbon::parse($user->birthday)->format('Y-m-d') : '') }}"
                                    >
                                </div>
                                @error('birthday')
                                    <span class="input-error">{{ $message }}</span>
                                @enderror
                            </div>

                            {{-- VAI TRÒ / QUYỀN HẠN --}}
                            <div class="field-group form-group-full">
                                <label class="field-label">Loại tài khoản</label>
                                <div style="display: flex; gap: 12px; align-items: center; justify-content: space-between; padding: 10px 14px; background: #F7FAF8; border-radius: 8px; border: 1px solid #E1E8E3;">
                                    <div style="display: flex; gap: 12px; align-items: center;">
                                        <i class="ti ti-id-badge" style="font-size: 20px; color: #178A4C;"></i>
                                        <div>
                                            <strong style="color: #16241C; font-size: 13.5px; text-transform: uppercase;">{{ $user->role }}</strong>
                                            <span style="color: #6B7A70; font-size: 12px; margin-left: 8px;">(Trạng thái: {{ $user->status ? 'Đang hoạt động' : 'Tạm khóa' }})</span>
                                        </div>
                                    </div>
                                    @if(in_array($user->role, ['admin', 'staff']))
                                        <a href="{{ route('admin.dashboard') }}" class="btn-save" style="height: 36px; padding: 0 16px; font-size: 13px; text-decoration: none; background: #0F5C33;">
                                            <i class="ti ti-layout-dashboard"></i> Dashboard
                                        </a>
                                    @endif
                                </div>
                            </div>

                        </div>

                        {{-- ACTIONS --}}
                        <div class="form-actions">
                            <button type="submit" class="btn-save">
                                <i class="ti ti-device-floppy"></i> Lưu thay đổi
                            </button>
                            <a href="{{ route('profile.edit') }}" class="btn-cancel">
                                Hủy bỏ
                            </a>
                        </div>
                    </form>
                </div>

                {{-- TAB 2: ĐỔI MẬT KHẨU --}}
                <div id="tab-password" class="tab-pane {{ session('active_tab') == 'password' ? '' : 'style-none' }}" style="{{ session('active_tab') == 'password' ? '' : 'display:none;' }}">
                    <div class="section-header">
                        <h2 class="section-title">Bảo mật & Đổi mật khẩu</h2>
                        <p class="section-desc">Để bảo mật tài khoản, vui lòng không chia sẻ mật khẩu của bạn với người khác.</p>
                    </div>

                    <form action="{{ route('profile.password.update') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div style="display: flex; flex-direction: column; gap: 18px; max-width: 520px;">

                            {{-- MẬT KHẨU HIỆN TẠI --}}
                            <div class="field-group">
                                <label class="field-label" for="current_password">
                                    Mật khẩu hiện tại <span style="color: #DC2626;">*</span>
                                </label>
                                <div class="input-wrap">
                                    <i class="ti ti-lock input-icon"></i>
                                    <input
                                        type="password"
                                        id="current_password"
                                        name="current_password"
                                        class="form-input @error('current_password') is-invalid @enderror"
                                        placeholder="••••••••"
                                        required
                                    >
                                </div>
                                @error('current_password')
                                    <span class="input-error">{{ $message }}</span>
                                @enderror
                            </div>

                            {{-- MẬT KHẨU MỚI --}}
                            <div class="field-group">
                                <label class="field-label" for="password">
                                    Mật khẩu mới <span style="color: #DC2626;">*</span>
                                </label>
                                <div class="input-wrap">
                                    <i class="ti ti-key input-icon"></i>
                                    <input
                                        type="password"
                                        id="password"
                                        name="password"
                                        class="form-input @error('password') is-invalid @enderror"
                                        placeholder="Tối thiểu 8 ký tự"
                                        required
                                    >
                                </div>
                                <div class="form-hint">Mật khẩu cần tối thiểu 8 ký tự và khác với mật khẩu cũ.</div>
                                @error('password')
                                    <span class="input-error">{{ $message }}</span>
                                @enderror
                            </div>

                            {{-- XÁC NHẬN MẬT KHẨU MỚI --}}
                            <div class="field-group">
                                <label class="field-label" for="password_confirmation">
                                    Xác nhận mật khẩu mới <span style="color: #DC2626;">*</span>
                                </label>
                                <div class="input-wrap">
                                    <i class="ti ti-check-check input-icon"></i>
                                    <input
                                        type="password"
                                        id="password_confirmation"
                                        name="password_confirmation"
                                        class="form-input"
                                        placeholder="Nhập lại mật khẩu mới"
                                        required
                                    >
                                </div>
                            </div>

                        </div>

                        {{-- ACTIONS --}}
                        <div class="form-actions" style="max-width: 520px;">
                            <button type="submit" class="btn-save">
                                <i class="ti ti-shield-check"></i> Cập nhật mật khẩu
                            </button>
                            <a href="{{ route('profile.edit') }}" class="btn-cancel">
                                Hủy bỏ
                            </a>
                        </div>
                    </form>
                </div>

                {{-- TAB 3: GÓI HỘI VIÊN --}}
                <div id="tab-subscription" class="tab-pane {{ session('active_tab') == 'subscription' ? '' : 'style-none' }}" style="{{ session('active_tab') == 'subscription' ? '' : 'display:none;' }}">
                    <div class="section-header">
                        <h2 class="section-title">Gói hội viên & Quyền lợi</h2>
                        <p class="section-desc">Xem trạng thái gói hội viên bạn đang tham gia cùng các ưu đãi đặt sân đặc quyền.</p>
                    </div>

                    @if($activeSubscription && $activeSubscription->plan)
                        <div class="plan-card-detail">
                            <div class="plan-detail-top">
                                <div>
                                    <span class="membership-badge badge-vip" style="margin-bottom: 8px;">
                                        <i class="ti ti-crown"></i> Gói đang sử dụng
                                    </span>
                                    <h3 style="font-size: 22px; font-weight: 700; color: #0D382E; margin: 4px 0;">{{ $activeSubscription->plan->name }}</h3>
                                    <div style="font-size: 13px; color: #5E726A;">
                                        Hiệu lực từ <strong>{{ $activeSubscription->starts_at ? \Carbon\Carbon::parse($activeSubscription->starts_at)->format('d/m/Y') : 'Ngay sau đăng ký' }}</strong>
                                        đến <strong>{{ \Carbon\Carbon::parse($activeSubscription->expires_at)->format('d/m/Y') }}</strong>
                                    </div>
                                </div>
                                <div style="text-align: right;">
                                    <span style="font-size: 11.5px; color: #6B7A70;">Giảm giá đặt sân</span>
                                    <div style="font-size: 24px; font-weight: 800; color: #178A4C;">-{{ $activeSubscription->plan->discount_percent }}%</div>
                                </div>
                            </div>

                            <div style="border-top: 1px dashed #B8E4C7; padding-top: 14px; margin-top: 10px;">
                                <div class="plan-benefit-item">
                                    <i class="ti ti-check"></i>
                                    <span>Tự động giảm {{ $activeSubscription->plan->discount_percent }}% trực tiếp trên mỗi khung giờ đặt sân.</span>
                                </div>
                                <div class="plan-benefit-item">
                                    <i class="ti ti-check"></i>
                                    <span>Được ưu tiên giữ chỗ và hủy miễn phí trước khung giờ theo chính sách hội viên.</span>
                                </div>
                                <div class="plan-benefit-item">
                                    <i class="ti ti-check"></i>
                                    <span>Hỗ trợ xác nhận đặt sân nhanh qua hệ thống chăm sóc khách hàng ưu tiên.</span>
                                </div>
                            </div>
                        </div>
                    @else
                        <div style="padding: 28px 24px; border: 1px dashed #C7D3CA; border-radius: 14px; text-align: center; background: #FAFDFB;">
                            <div style="width: 56px; height: 56px; border-radius: 50%; background: #E6F5EB; color: #178A4C; display: flex; align-items: center; justify-content: center; margin: 0 auto 12px; font-size: 26px;">
                                <i class="ti ti-sparkles"></i>
                            </div>
                            <h3 style="font-size: 17px; font-weight: 700; color: #16241C; margin-bottom: 6px;">Bạn đang là Thành viên tiêu chuẩn</h3>
                            <p style="font-size: 13px; color: #5E726A; max-width: 480px; margin: 0 auto 18px;">
                                Nâng cấp lên các gói thành viên VIP, Tiêu chuẩn để nhận ưu đãi giảm đến 25% mỗi lượt đặt sân và giữ khung giờ vàng mỗi tuần!
                            </p>
                            <a href="{{ route('home') }}#plans" class="btn-save" style="display: inline-flex; text-decoration: none;">
                                <i class="ti ti-award"></i> Khám phá các gói hội viên
                            </a>
                        </div>
                    @endif
                </div>

            </section>

        </div>
    </div>

    {{-- =====================================================
         JAVASCRIPT XỬ LÝ CHUYỂN TAB & PREVIEW AVATAR
    ====================================================== --}}
@endsection

@push('scripts')
<script>
        function switchTab(tabName, btnElement) {
            // Ẩn tất cả tab pane
            const panes = document.querySelectorAll('.tab-pane');
            panes.forEach(pane => {
                pane.style.display = 'none';
            });

            // Hiện tab pane được chọn
            const targetPane = document.getElementById('tab-' + tabName);
            if (targetPane) {
                targetPane.style.display = 'block';
            }

            // Cập nhật active class ở menu items
            const menuItems = document.querySelectorAll('.profile-menu .menu-item');
            menuItems.forEach(item => {
                item.classList.remove('active');
            });
            if (btnElement) {
                btnElement.classList.add('active');
            }
        }

        function previewSelectedAvatar(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    // Update preview in form
                    let formPreviewImg = document.getElementById('previewAvatarImg');
                    let formFallback = document.getElementById('previewAvatarFallback');
                    if (formFallback) {
                        formFallback.style.display = 'none';
                    }
                    if (!formPreviewImg) {
                        formPreviewImg = document.createElement('img');
                        formPreviewImg.id = 'previewAvatarImg';
                        formPreviewImg.className = 'avatar-preview-small';
                        formFallback.parentNode.insertBefore(formPreviewImg, formFallback);
                    }
                    formPreviewImg.src = e.target.result;
                    formPreviewImg.style.display = 'block';

                    // Update preview in sidebar
                    let sidebarImg = document.getElementById('sidebarAvatar');
                    let sidebarFallback = document.getElementById('sidebarAvatarFallback');
                    if (sidebarFallback) {
                        sidebarFallback.style.display = 'none';
                    }
                    if (!sidebarImg) {
                        sidebarImg = document.createElement('img');
                        sidebarImg.id = 'sidebarAvatar';
                        sidebarImg.className = 'avatar-img';
                        sidebarFallback.parentNode.insertBefore(sidebarImg, sidebarFallback);
                    }
                    sidebarImg.src = e.target.result;
                    sidebarImg.style.display = 'block';
                };
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
@endpush
