@php
    $menu = [
        ['Dashboard',    'ti-chart-bar',      'admin.dashboard'],
        ['Sân & cơ sở',  'ti-building',       'admin.dashboard'],   // đổi route sau khi có
        ['Đơn đặt sân',  'ti-calendar-event', 'admin.dashboard'],
        ['Người dùng',   'ti-users',          'admin.dashboard'],
        ['Nội dung',     'ti-news',           'admin.dashboard'],
        ['Cài đặt',      'ti-settings',       'admin.dashboard'],
    ];
@endphp

<aside class="sidebar">
    <div class="brand">Sân Nhóm H<br><small>Admin</small></div>
    <nav>
        @foreach ($menu as [$label, $icon, $route])
            <a href="{{ route($route) }}"
               class="{{ $loop->first && request()->routeIs($route) ? 'active' : '' }}">
                <i class="ti {{ $icon }}"></i>{{ $label }}
            </a>
        @endforeach
    </nav>
</aside>