<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <title>@yield('namePage') - Newsmaker23 Edukasi</title>

    {{-- Icon --}}
    <link rel="icon" type="image/png" href="{{asset('Icon/favicon-96x96.png')}}" sizes="96x96" />
    <link rel="icon" type="image/svg+xml" href="{{asset('Icon/favicon.svg')}}" />
    <link rel="shortcut icon" href="{{asset('Icon/favicon.ico')}}" />
    <link rel="apple-touch-icon" sizes="180x180" href="{{asset('Icon/apple-touch-icon.png')}}" />
    <meta name="apple-mobile-web-app-title" content="NM23" />
    <link rel="manifest" href="{{asset('Icon/site.webmanifest')}}" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net" />
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased text-gray-900 dark:text-gray-100"
    style="background-image: url('{{ asset('assets/3439372_61769.jpg') }}'); background-size: cover; background-position: center;">

    <div class="hidden dark:block fixed inset-0 bg-black bg-opacity-70 pointer-events-none"></div>

    <div class="relative min-h-screen flex flex-col justify-center items-center px-5">
        <div
            class="w-full sm:max-w-md px-6 py-4 bg-gray-200/70 dark:bg-gray-900/70 border border-gray-300 dark:border-gray-700 backdrop-blur-sm shadow-lg rounded-lg transition-colors duration-300">
            <div class="flex flex-col justify-center items-center space-y-5">
                <x-application-logo />
                @yield('content')
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const html = document.documentElement;

            // Fungsi untuk apply theme
            function applyTheme(theme) {
                if (theme === 'dark') {
                    html.classList.add('dark');
                    html.style.colorScheme = 'dark';
                } else if (theme === 'light') {
                    html.classList.remove('dark');
                    html.style.colorScheme = 'light';
                } else if (theme === 'auto') {
                    if (window.matchMedia('(prefers-color-scheme: dark)').matches) {
                        html.classList.add('dark');
                        html.style.colorScheme = 'dark';
                    } else {
                        html.classList.remove('dark');
                        html.style.colorScheme = 'light';
                    }
                }
                localStorage.setItem('theme', theme);
            }

            // Load theme dari localStorage atau default ke 'auto'
            const savedTheme = localStorage.getItem('theme') || 'auto';
            applyTheme(savedTheme);

            // Optional: jika ingin tombol toggle sederhana
            const toggle = document.getElementById('darkModeToggle');
            if (toggle) {
                toggle.addEventListener('click', () => {
                    const currentTheme = localStorage.getItem('theme') || 'auto';
                    const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
                    applyTheme(newTheme);
                });
            }
        });
    </script>
</body>

</html>