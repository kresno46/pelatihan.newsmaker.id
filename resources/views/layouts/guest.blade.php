<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <title>Newsmaker Trainer - @yield('namePage')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net" />
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const toggle = document.getElementById('darkModeToggle');
            if (toggle) {
                toggle.addEventListener('click', function () {
                    const html = document.documentElement; // langsung html element
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

            // Set dark mode saat halaman dimuat berdasarkan localStorage
            const html = document.documentElement;
            if (localStorage.getItem('dark-mode') === 'true') {
                html.classList.add('dark');
                html.style.colorScheme = 'dark';
            } else {
                html.classList.remove('dark');
                html.style.colorScheme = 'light';
            }
        });
    </script>
</head>

<body class="font-sans antialiased text-gray-900 dark:text-gray-100"
    style="background-image: url('{{ asset('assets/3439372_61769.jpg') }}'); background-size: cover; background-position: center;">

    <!-- Overlay gelap saat dark mode untuk kontras -->
    <div class="fixed inset-0 bg-black bg-opacity-40 dark:bg-opacity-70 pointer-events-none"></div>

    <div class="relative min-h-screen flex flex-col justify-center items-center px-5">
        <div
            class="w-full sm:max-w-md px-6 py-4 bg-gray-200/70 dark:bg-gray-900/70 border border-gray-300 dark:border-gray-700 backdrop-blur-sm shadow-lg rounded-lg transition-colors duration-300">
            <div class="flex flex-col justify-center items-center space-y-5">
                <x-application-logo />
                @yield('content')
            </div>
        </div>
    </div>

</body>

</html>