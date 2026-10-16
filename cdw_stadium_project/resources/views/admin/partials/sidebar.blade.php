@php
    $menu = [
        ['Dashboard',          'ti-chart-bar',      'admin.dashboard',         'admin.dashboard'],
        ['Cơ sở',              'ti-building',       'admin.facilities.index',  'admin.facilities.*'],
        ['Loại môn thể thao', 'ti-ball-football',  'admin.sport-types.index', 'admin.sport-types.*'],
        ['Sân thể thao',       'ti-layout-grid',    'admin.courts.index',      'admin.courts.*'],
        ['Bình luận',          'ti-message-circle', 'admin.comments.index',    'admin.comments.*'],
        ['Đơn đặt sân',        'ti-calendar-event', 'admin.bookings.index',    'admin.bookings.*'],
        ['Người dùng',         'ti-users',          'admin.users.index',       'admin.users.*'],
        ['Nội dung',           'ti-news',           'admin.news.index',        'admin.news.*'],
        ['Báo cáo',            'ti-report',         'admin.reports.revenue',   'admin.reports.*'],
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
    <div style="margin-top: auto; padding-top: 12px; border-top: 1px solid rgba(255, 255, 255, 0.1);">
        <a href="{{ route('home') }}">
            <i class="ti ti-home"></i>Quay về trang chủ
        </a>
    </div>
</aside>