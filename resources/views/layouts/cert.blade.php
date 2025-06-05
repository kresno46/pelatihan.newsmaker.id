<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('namePage') - Newsmaker23 Edukasi</title>

    {{-- Icon --}}
    <link rel="icon" type="image/png" href="{{asset('Icon/favicon-96x96.png')}}" sizes="96x96" />
    <link rel="icon" type="image/svg+xml" href="{{asset('Icon/favicon.svg')}}" />
    <link rel="shortcut icon" href="{{asset('Icon/favicon.ico')}}" />
    <link rel="apple-touch-icon" sizes="180x180" href="{{asset('Icon/apple-touch-icon.png')}}" />
    <meta name="apple-mobile-web-app-title" content="NM23" />
    <link rel="manifest" href="{{asset('Icon/site.webmanifest')}}" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-blue-500 flex items-center justify-center h-full w-full p-2">
    @yield('content')
</body>

</html>