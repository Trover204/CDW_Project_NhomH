<header>
    <div class="wrap">
        <div class="nav">

            {{-- LOGO --}}
            <a href="{{ route('home') }}" class="brand">
                Sân Nhóm <span>H</span>
            </a>

            {{-- MENU --}}
            <nav class="nav-links">
                <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">{{ __('Trang chủ') }}</a>
                <a href="{{ route('courts.index') }}" class="{{ request()->routeIs('courts.*') ? 'active' : '' }}">{{ __('Sân') }}</a>
                <a href="{{ route('news.index') }}" class="{{ request()->routeIs('news.*') ? 'active' : '' }}">{{ __('Tin tức') }}</a>
                <a href="{{ route('plans.index') }}" class="{{ request()->routeIs('plans.*') ? 'active' : '' }}">{{ __('Gói thành viên') }}</a>
                <a href="{{ route('products.index') }}" class="{{ request()->routeIs('products.*') ? 'active' : '' }}">{{ __('Merch') }}</a>
            </nav>

            {{-- LOGIN / PROFILE & SETTINGS GEAR DROPDOWN --}}
            <div class="nav-actions">
                @guest
                    <a href="{{ route('login') }}" class="btn btn-ghost btn-nav-login">{{ __('Đăng nhập') }}</a>
                @else
                    <a href="{{ route('profile.edit') }}" class="nav-user-link">
                        <i class="ti ti-user-circle"></i>
                        <span>{{ Auth::user()->name }}</span>
                    </a>
                @endguest

                <div class="settings-dropdown">
                    <button type="button" class="settings-toggle-btn" id="settingsDropdownBtn" aria-label="{{ __('Cài đặt') }}" aria-expanded="false">
                        <i class="ti ti-settings"></i>
                    </button>

                    <div class="settings-dropdown-menu" id="settingsDropdownMenu">
                        {{-- Điều chỉnh Theme Sáng / Tối --}}
                        <button type="button" class="settings-dropdown-item theme-toggle-btn" aria-label="Toggle Theme">
                            <span class="settings-item-label">
                                <i class="ti ti-moon"></i>
                                <span>{{ __('Giao diện') }}</span>
                            </span>
                            <span class="settings-badge theme-status-badge" data-light-text="{{ __('Sáng') }}" data-dark-text="{{ __('Tối') }}">
                                {{ __('Sáng') }}
                            </span>
                        </button>

                        {{-- Chuyển đổi Ngôn ngữ Tiếng Việt - Anh --}}
                        <a href="{{ route('lang.switch', app()->getLocale() === 'en' ? 'vi' : 'en') }}" class="settings-dropdown-item lang-switch-btn" data-lang-toggle>
                            <span class="settings-item-label">
                                <i class="ti ti-language"></i>
                                <span>{{ __('Ngôn ngữ') }}</span>
                            </span>
                            <span class="settings-lang-group">
                                <span class="lang-option {{ app()->getLocale() !== 'en' ? 'active' : '' }}" data-lang-code="vi">VI</span>
                                <span class="lang-option {{ app()->getLocale() === 'en' ? 'active' : '' }}" data-lang-code="en">EN</span>
                            </span>
                        </a>

                        {{-- Đăng xuất (hiển thị khi đã đăng nhập) --}}
                        @auth
                            <div class="settings-dropdown-divider"></div>
                            <a href="#" data-submit-form="logout-form" class="settings-dropdown-item settings-logout-item">
                                <span class="settings-item-label">
                                    <i class="ti ti-logout"></i>
                                    <span>{{ __('Đăng xuất') }}</span>
                                </span>
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        @endauth
                    </div>
                </div>
            </div>

        </div>
    </div>
</header>
