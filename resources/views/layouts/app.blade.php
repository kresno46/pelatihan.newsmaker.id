<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script>
        document.addEventListener('DOMContentLoaded', function () {
        const toggle = document.getElementById('darkModeToggle');
        if (toggle) {
            toggle.addEventListener('click', function () {
                const html = document.querySelector('html');
                const isDark = html.classList.contains('dark');

                if (isDark) {
                    html.classList.remove('dark');
                    html.style.colorScheme = 'light';
                    localStorage.setItem('dark-mode', 'false');
                } else {
                    html.classList.add('dark');
                    html.style.colorScheme = 'dark';
                    localStorage.setItem('dark-mode', 'true');
                }
            });
        }

        // Saat halaman dimuat, set dark mode berdasarkan localStorage
        if (localStorage.getItem('dark-mode') === 'true') {
            document.querySelector('html').classList.add('dark');
            document.querySelector('html').style.colorScheme = 'dark';
        } else {
            document.querySelector('html').classList.remove('dark');
            document.querySelector('html').style.colorScheme = 'light';
        }
    });
    </script>
</head>

<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
        <!-- Tombol Dark Mode -->
        <button id="darkModeToggle"
            class="m-4 px-4 py-2 bg-gray-300 dark:bg-gray-700 text-black dark:text-white rounded">
            Toggle Dark Mode
        </button>

        <!-- Page Content -->
        <main>
            @yield('content')
        </main>
    </div>
</body>

</html>