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
                ['admin.dashboard', 'ti-chart-bar', 'Dashboard'],
                ['admin.sport-types.index', 'ti-ball-football', 'Loại môn thể thao'],
            ];
        @endphp
        @foreach ($items as [$route, $icon, $label])
            <a href="{{ route($route) }}"
               style="display:flex;align-items:center;gap:8px;padding:8px 10px;border-radius:6px;font-size:12px;text-decoration:none;
                      color:#fff;background:{{ request()->routeIs($route) || (str_contains($route,'sport-types') && request()->routeIs('admin.sport-types.*')) ? 'rgba(255,255,255,.14)' : 'transparent' }};">
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
        @yield('content')
    </main>
</div>
</body>
</html>