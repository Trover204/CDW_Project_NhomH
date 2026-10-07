@php
    $menu = [
        ['Dashboard',          'ti-chart-bar',      'admin.dashboard',         'admin.dashboard'],
        ['Cơ sở',              'ti-building',       'admin.facilities.index',  'admin.facilities.*'],
        ['Loại môn thể thao', 'ti-ball-football',  'admin.sport-types.index', 'admin.sport-types.*'],
        ['Sân thể thao',       'ti-layout-grid',    'admin.courts.index',      'admin.courts.*'],
        ['Bình luận',          'ti-message-circle', 'admin.comments.index',    'admin.comments.*'],
        ['Đơn đặt sân',        'ti-calendar-event', 'admin.dashboard',         'admin.bookings.*'],
        ['Người dùng',         'ti-users',          'admin.dashboard',         'admin.users.*'],
        ['Nội dung',           'ti-news',           'admin.dashboard',         'admin.contents.*'],
        ['Cài đặt',            'ti-settings',       'admin.dashboard',         'admin.settings.*'],
    ];
@endphp

<aside class="sidebar">
    <div class="brand">Sân Nhóm H<br><small>Admin</small></div>
    <nav>
        @foreach ($menu as [$label, $icon, $route, $pattern])
            <a href="{{ route($route) }}" class="{{ request()->routeIs($pattern) ? 'active' : '' }}">
                <i class="ti {{ $icon }}"></i>{{ $label }}
            </a>
        @endforeach
    </nav>
</aside>