<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Admin') — Sân Nhóm H</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css">
</head>
<body style="margin:0;font-family:Arial,Helvetica,sans-serif;background:#F7FAF8;">
<div style="display:flex;min-height:100vh;">
    <aside style="width:180px;background:#0F5C33;padding:16px 12px;flex-shrink:0;">
        <div style="font-size:14px;font-weight:700;color:#fff;margin-bottom:20px;">Sân Nhóm H<br>
            <span style="font-size:10px;font-weight:400;color:#9FCBAE;">Admin</span></div>
        @php
            $items = [
                ['admin.dashboard', 'ti-chart-bar', 'Dashboard', 'admin.dashboard'],
                ['admin.facilities.index', 'ti-building', 'Cơ sở', 'admin.facilities.*'],
                ['admin.sport-types.index', 'ti-ball-football', 'Loại môn thể thao', 'admin.sport-types.*'],
                ['admin.courts.index', 'ti-layout-grid', 'Sân thể thao', 'admin.courts.*'],
                ['admin.comments.index', 'ti-message-circle', 'Bình luận', 'admin.comments.*'],
            ];
        @endphp
        @foreach ($items as [$route, $icon, $label, $pattern])
            <a href="{{ route($route) }}"
               style="display:flex;align-items:center;gap:8px;padding:8px 10px;border-radius:6px;font-size:12px;text-decoration:none;
                      color:#fff;background:{{ request()->routeIs($pattern) ? 'rgba(255,255,255,.14)' : 'transparent' }};">
                <i class="ti {{ $icon }}" style="font-size:15px;"></i>{{ $label }}
            </a>
        @endforeach
    </aside>

    <main style="flex:1;padding:20px;min-width:0;">
        @if (session('success'))
            <div style="background:#E6F5EB;color:#0F5C33;padding:10px 14px;border-radius:8px;margin-bottom:14px;font-size:13px;">
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div style="background:#FCEBEB;color:#A32D2D;padding:10px 14px;border-radius:8px;margin-bottom:14px;font-size:13px;">
                {{ session('error') }}
            </div>
        @endif
        @yield('content')
    </main>
</div>
</body>
</html>