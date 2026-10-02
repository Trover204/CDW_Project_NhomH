@php
    // [nhãn, icon, tên route, pattern để đánh dấu active]
    $menu = [
        ['Dashboard',          'ti-chart-bar',      'admin.dashboard',         'admin.dashboard'],
        ['Loại môn thể thao',  'ti-ball-football',  'admin.sport-types.index', 'admin.sport-types.*'],
        ['Sân & cơ sở',       'ti-building',       'admin.dashboard',         null],   // đổi route sau khi có
        ['Đơn đặt sân',       'ti-calendar-event', 'admin.dashboard',         null],
        ['Người dùng',        'ti-users',          'admin.dashboard',         null],
        ['Nội dung',          'ti-news',           'admin.dashboard',         null],
        ['Cài đặt',           'ti-settings',       'admin.dashboard',         null],
    ];
@endphp

<aside class="sidebar">
    <div class="brand">Sân Nhóm H<br><small>Admin</small></div>
    <nav>
        @foreach ($menu as [$label, $icon, $route, $pattern])
            <a href="{{ route($route) }}"
               class="{{ $pattern && request()->routeIs($pattern) ? 'active' : '' }}">
                <i class="ti {{ $icon }}"></i>{{ $label }}
            </a>
        @endforeach
    </nav>
</aside>