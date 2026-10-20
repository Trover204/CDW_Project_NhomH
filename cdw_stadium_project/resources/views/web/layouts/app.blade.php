<!DOCTYPE html>
<html lang="vi">
<head>
    <script>if(localStorage.getItem("theme")==="dark") document.documentElement.setAttribute("data-theme", "dark");</script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Sân Nhóm H')</title>

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Oswald:wght@500;600;700&family=Be+Vietnam+Pro:wght@400;500;600;700;800&display=swap"
        rel="stylesheet"
    >

    {{-- Tabler Icons --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css">
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
    @stack('styles')
</head>

<body>
    @include('web.layouts.header')

    <main>
        @yield('content')
    </main>

    @include('web.layouts.footer')

    @stack('scripts')
    <script src="{{ asset('js/theme-toggle.js') }}"></script>
</body>

</html>
