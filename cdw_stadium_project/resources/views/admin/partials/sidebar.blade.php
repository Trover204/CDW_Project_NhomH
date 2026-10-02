@php
    $menu = [
        ['Dashboard',   'ti-chart-bar',      'admin.dashboard',      'admin.dashboard'],
        ['Bình luận',   'ti-message-circle', 'admin.comments.index', 'admin.comments.*'],
        // ... các mục còn lại giữ nguyên, thêm phần tử thứ 4 là pattern
    ];
@endphp

<nav>
    @foreach ($menu as [$label, $icon, $route, $pattern])
        <a href="{{ route($route) }}" class="{{ request()->routeIs($pattern) ? 'active' : '' }}">
            <i class="ti {{ $icon }}"></i>{{ $label }}
        </a>
    @endforeach
</nav>