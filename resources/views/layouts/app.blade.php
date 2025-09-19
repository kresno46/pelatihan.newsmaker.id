<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('namePage') - Newsmaker23 Internal</title>

    {{-- Icon --}}
    <link rel="icon" type="image/png" href="{{ asset('Icon/favicon-96x96.png') }}" sizes="96x96" />
    <link rel="icon" type="image/svg+xml" href="{{ asset('Icon/favicon.svg') }}" />
    <link rel="shortcut icon" href="{{ asset('Icon/favicon.ico') }}" />
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('Icon/apple-touch-icon.png') }}" />
    <meta name="apple-mobile-web-app-title" content="NM23" />
    <link rel="manifest" href="{{ asset('Icon/site.webmanifest') }}" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- ✅ Dark mode prevention script -->
    <script>
        (function() {
            const theme = localStorage.getItem('theme') || 'auto';
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            if (theme === 'dark' || (theme === 'auto' && prefersDark)) {
                document.documentElement.classList.add('dark');
                document.documentElement.style.colorScheme = 'dark';
            }
        })();
    </script>

    @yield('styles')

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Summernote Lite CSS & JS -->
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-lite.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-lite.min.js"></script>

</head>

<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100 dark:bg-gray-900 flex">
        <!-- Sidebar : Dekstop -->
        <aside
            class="bg-white dark:bg-gray-800 w-64 hidden md:block sticky top-0 flex-shrink-0 shadow-lg border-r border-gray-200 dark:border-gray-700 h-screen overflow-auto z-50">
            <div class="h-full p-4 space-y-6">
                <!-- Logo -->
                <div class="w-full flex justify-center">
                    <a href="{{ route('dashboard') }}">
                        {{-- <a href="https://www.newsmaker.id"> --}}
                        <img src="{{ asset('assets/NewsMaker-23-logo.png') }}" alt="NewsMaker 23"
                            class="block dark:hidden h-20" />
                        <img src="{{ asset('assets/NewsMaker-23-logo-white.png') }}" alt="NewsMaker 23"
                            class="hidden dark:block h-20" />
                    </a>
                </div>

                <hr class="border-gray-300 dark:border-gray-700">

                <!-- Navigation - Main Menu -->
                <nav>
                    <ul class="flex flex-col space-y-2">
                        <li class="text-sm text-gray-500 uppercase tracking-wide">Menu</li>
                        <li>
                            <a href="{{ route('dashboard') }}"
                                class="flex items-center space-x-4 px-3 py-2 rounded transition duration-200
                            {{ request()->routeIs('dashboard')
                                ? 'bg-gray-200 dark:bg-gray-700 text-gray-900 dark:text-white font-semibold'
                                : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                                <i class="fas fa-home"></i>
                                <span>{{ __('Beranda') }}</span>
                            </a>
                        </li>
                    </ul>
                </nav>

                @if (Auth::user()->role !== 'Admin')
                    <hr class="border-gray-300 dark:border-gray-700">

                    <!-- Navigation - Edukasi -->
                    <nav>
                        <ul class="flex flex-col space-y-2">
                            <li class="text-sm text-gray-500 uppercase tracking-wide">Edukasi</li>
                            <li>
                                <a href="{{ route('folder.index') }}"
                                    class="flex items-center space-x-4 px-3 py-2 rounded transition duration-200
                            {{ request()->routeIs('ebook.*') || request()->routeIs('quiz.*') || request()->routeIs('folder.*')
                                ? 'bg-gray-200 dark:bg-gray-700 text-gray-900 dark:text-white font-semibold'
                                : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                                    <i class="fa-solid fa-book"></i>
                                    <span>{{ __('eBook') }}</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('AbsensiUser.index') }}"
                                    class="flex items-center space-x-4 px-3 py-2 rounded transition duration-200
                            {{ request()->routeIs('AbsensiUser.*')
                                ? 'bg-gray-200 dark:bg-gray-700 text-gray-900 dark:text-white font-semibold'
                                : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                                    <i class="fa-solid fa-users-viewfinder"></i>
                                    <span>{{ __('Absensi') }}</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('post-test.index') }}"
                                    class="flex items-center space-x-4 px-3 py-2 rounded transition duration-200
                            {{ request()->routeIs('posttest.*')
                                ? 'bg-gray-200 dark:bg-gray-700 text-gray-900 dark:text-white font-semibold'
                                : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                                    <i class="fa-solid fa-clipboard-question"></i>
                                    <span>{{ __('Post Test') }}</span>
                                </a>
                            </li>
                        </ul>
                    </nav>
                @endif

                <hr class="border-gray-300 dark:border-gray-700">

                <!-- Navigation - Laporan -->
                @if (Auth::user()->role === 'Admin')
                    <nav>
                        <ul class="flex flex-col space-y-2">
                            <li class="text-sm text-gray-500 uppercase tracking-wide">Menu</li>
                            <li>
                                <a href="{{ route('folder.index') }}"
                                    class="flex items-center space-x-4 px-3 py-2 rounded transition duration-200
                            {{ request()->routeIs('ebook.*') || request()->routeIs('quiz.*') || request()->routeIs('folder.*')
                                ? 'bg-gray-200 dark:bg-gray-700 text-gray-900 dark:text-white font-semibold'
                                : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                                    <i class="fa-solid fa-book"></i>
                                    <span>{{ __('eBook') }}</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('posttest.index') }}"
                                    class="flex items-center space-x-4 px-3 py-2 rounded transition duration-200
                            {{ request()->routeIs('posttest.*')
                                ? 'bg-gray-200 dark:bg-gray-700 text-gray-900 dark:text-white font-semibold'
                                : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                                    <i class="fa-solid fa-clipboard-question"></i>
                                    <span>{{ __('Post Test') }}</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('absensi.index') }}"
                                    class="flex items-center space-x-4 px-3 py-2 rounded transition duration-200
                            {{ request()->routeIs('absensi.*') || request()->routeIs('absensiAdmin.*')
                                ? 'bg-gray-200 dark:bg-gray-700 text-gray-900 dark:text-white font-semibold'
                                : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                                    <i class="fa-solid fa-face-smile"></i>
                                    <span>{{ __('Absensi') }}</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('LaporanSertifikat.index') }}"
                                    class="flex items-center space-x-4 px-3 py-2 rounded transition duration-200
                            {{ request()->routeIs('LaporanSertifikat.*')
                                ? 'bg-gray-200 dark:bg-gray-700 text-gray-900 dark:text-white font-semibold'
                                : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                                    <i class="fa-solid fa-certificate"></i>
                                    <span>{{ __('Sertifikat') }}</span>
                                </a>
                            </li>
                        </ul>
                    </nav>

                    <hr class="border-gray-300 dark:border-gray-700">
                @endif

                <!-- Navigation - Manajemen -->
                @if (Auth::user()->role === 'Admin')
                    <nav>
                        <ul class="flex flex-col space-y-2">
                            <li class="text-sm text-gray-500 uppercase tracking-wide">Manajemen</li>
                            <li>
                                <a href="{{ route('admin.index') }}"
                                    class="flex items-center space-x-4 px-3 py-2 rounded transition duration-200
                            {{ request()->routeIs('admin.*')
                                ? 'bg-gray-200 dark:bg-gray-700 text-gray-900 dark:text-white font-semibold'
                                : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                                    <i class="fa-solid fa-user"></i>
                                    <span>Admin</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('trainer.index') }}"
                                    class="flex items-center space-x-4 px-3 py-2 rounded transition duration-200
                            {{ request()->routeIs('trainer.*')
                                ? 'bg-gray-200 dark:bg-gray-700 text-gray-900 dark:text-white font-semibold'
                                : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                                    <i class="fa-solid fa-user"></i>
                                    <span>User</span>
                                </a>
                            </li>
                        </ul>
                    </nav>

                    <hr class="border-gray-300 dark:border-gray-700">
                @endif

                <nav>
                    <ul class="flex flex-col space-y-2">
                        <li>
                            <a href="{{ route('profile.edit') }}"
                                class="flex items-center space-x-4 px-3 py-2 rounded transition duration-200
                            {{ request()->routeIs('profile.*')
                                ? 'bg-gray-200 dark:bg-gray-700 text-gray-900 dark:text-white font-semibold'
                                : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                                <i class="fa-solid fa-user"></i>
                                <span>Profile</span>
                            </a>
                        </li>
                        @if (Auth::user()->role !== 'Admin')
                            <li>
                                <a href="{{ route('sertifikat.index') }}"
                                    class="flex items-center space-x-4 px-3 py-2 rounded transition duration-200
                            {{ request()->routeIs('sertifikat.*')
                                ? 'bg-gray-200 dark:bg-gray-700 text-gray-900 dark:text-white font-semibold'
                                : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                                    <i class="fa-solid fa-certificate"></i>
                                    <span>Sertifikat</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('riwayat.index') }}"
                                    class="flex items-center space-x-4 px-3 py-2 rounded transition duration-200
                            {{ request()->routeIs('riwayat.*')
                                ? 'bg-gray-200 dark:bg-gray-700 text-gray-900 dark:text-white font-semibold'
                                : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                                    <i class="fa-solid fa-clock-rotate-left"></i>
                                    <span>Riwayat Saya</span>
                                </a>
                            </li>
                        @endif
                    </ul>
                </nav>
            </div>
        </aside>

        <!-- Content Area -->
        <div class="flex-1 flex flex-col">
            <!-- Navbar -->
            <header
                class="sticky top-0 bg-white dark:bg-gray-800 shadow px-4 py-4 flex items-center justify-between z-20">
                <div class="flex items-center justify-center gap-2">
                    <!-- Mobile sidebar toggle -->
                    <button id="mobileMenuButton" class="md:hidden text-gray-700 dark:text-white focus:outline-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>

                    <h1 class="hidden md:block text-lg font-semibold text-gray-800 dark:text-white">NewsMaker23</h1>
                </div>

                <div class="flex items-center space-x-4 relative">
                    <!-- Profile Dropdown -->
                    <div class="relative">
                        <button id="profileButton" class="flex items-center space-x-2 focus:outline-none">
                            <div
                                class="w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center font-semibold">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </div>
                            <span class="text-gray-800 dark:text-white font-medium hidden md:inline">
                                {{ Auth::user()->name }}
                            </span>
                            <svg class="w-4 h-4 text-gray-600 dark:text-gray-300" fill="none"
                                stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <!-- Dropdown Menu -->
                        <div id="profileDropdown"
                            class="hidden absolute right-0 mt-2 w-48 bg-white dark:bg-gray-700 shadow-md rounded-md overflow-hidden z-40">

                            <!-- Theme Dropdown -->
                            <div class="px-4 py-2 text-gray-800 dark:text-white">
                                <label for="themeSelect" class="block mb-1 font-semibold">Pilih Tema</label>
                                <select id="themeSelect"
                                    class="w-full rounded bg-gray-200 dark:bg-gray-600 text-gray-900 dark:text-white p-1">
                                    <option value="light">Terang</option>
                                    <option value="dark">Gelap</option>
                                    <option value="auto">Otomatis</option>
                                </select>
                            </div>

                            <hr class="border-gray-200 dark:border-gray-600" />

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                    class="w-full text-left px-4 py-2 text-white bg-red-600 hover:bg-red-700">
                                    Keluar
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Sidebar : Mobile -->
            <div id="mobileSidebar"
                class="fixed inset-y-0 left-0 z-40 w-64 transform -translate-x-full bg-white dark:bg-gray-800 transition-transform duration-300 shadow-xl md:hidden">
                <div class="p-4">
                    <!-- Close Button -->
                    <div class="flex items-center mb-4 gap-3">
                        <button id="closeMobileSidebar"
                            class="bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 hover:bg-gray-300  text-gray-700 dark:text-white py-2 px-3 rounded-lg text-sm border-2 border-gray-300 dark:border-gray-700 transition-all">
                            ✕
                        </button>
                        {{-- <h2 class="text-lg font-semibold text-gray-800 dark:text-white">NewsMaker23</h2> --}}
                        <a href="{{ route('dashboard') }}">
                            <img src="{{ asset('assets/NewsMaker-23-logo.png') }}" alt="NewsMaker 23"
                            class="block dark:hidden h-20" />
                            <img src="{{ asset('assets/NewsMaker-23-logo-white.png') }}" alt="NewsMaker 23"
                            class="hidden dark:block h-20" />
                        </a>
                    </div>

                    <hr class="border-gray-300 dark:border-gray-700">

                    <!-- Navigation - Main Menu -->
                    <nav class="my-4">
                        <ul class="flex flex-col space-y-2">
                            <li class="text-sm text-gray-500 uppercase tracking-wide">Menu</li>
                            <li>
                                <a href="{{ route('dashboard') }}"
                                    class="flex items-center space-x-4 px-3 py-2 rounded transition duration-200
                            {{ request()->routeIs('dashboard')
                                ? 'bg-gray-200 dark:bg-gray-700 text-gray-900 dark:text-white font-semibold'
                                : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                                    <i class="fas fa-home"></i>
                                    <span>{{ __('Beranda') }}</span>
                                </a>
                            </li>
                        </ul>
                    </nav>

                    @if (Auth::user()->role !== 'Admin')
                        <hr class="border-gray-300 dark:border-gray-700">

                        <!-- Navigation - Edukasi -->
                        <nav class="my-4">
                            <ul class="flex flex-col space-y-2">
                                <li class="text-sm text-gray-500 uppercase tracking-wide">Edukasi</li>
                                <li>
                                    <a href="{{ route('folder.index') }}"
                                        class="flex items-center space-x-4 px-3 py-2 rounded transition duration-200
                            {{ request()->routeIs('ebook.*') || request()->routeIs('quiz.*') || request()->routeIs('folder.*')
                                ? 'bg-gray-200 dark:bg-gray-700 text-gray-900 dark:text-white font-semibold'
                                : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                                        <i class="fa-solid fa-book"></i>
                                        <span>{{ __('eBook') }}</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('AbsensiUser.index') }}"
                                        class="flex items-center space-x-4 px-3 py-2 rounded transition duration-200
                            {{ request()->routeIs('AbsensiUser.*')
                                ? 'bg-gray-200 dark:bg-gray-700 text-gray-900 dark:text-white font-semibold'
                                : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                                        <i class="fa-solid fa-users-viewfinder"></i>
                                        <span>{{ __('Absensi') }}</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('post-test.index') }}"
                                        class="flex items-center space-x-4 px-3 py-2 rounded transition duration-200
                            {{ request()->routeIs('posttest.*')
                                ? 'bg-gray-200 dark:bg-gray-700 text-gray-900 dark:text-white font-semibold'
                                : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                                        <i class="fa-solid fa-clipboard-question"></i>
                                        <span>{{ __('Post Test') }}</span>
                                    </a>
                                </li>
                            </ul>
                        </nav>
                    @endif

                    <hr class="border-gray-300 dark:border-gray-700">

                    <!-- Navigation - Laporan -->
                    @if (Auth::user()->role === 'Admin')
                        <nav class="my-4">
                            <ul class="flex flex-col space-y-2">
                                <li class="text-sm text-gray-500 uppercase tracking-wide">Menu</li>
                                <li>
                                    <a href="{{ route('folder.index') }}"
                                        class="flex items-center space-x-4 px-3 py-2 rounded transition duration-200
                            {{ request()->routeIs('ebook.*') || request()->routeIs('quiz.*') || request()->routeIs('folder.*')
                                ? 'bg-gray-200 dark:bg-gray-700 text-gray-900 dark:text-white font-semibold'
                                : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                                        <i class="fa-solid fa-book"></i>
                                        <span>{{ __('eBook') }}</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('posttest.index') }}"
                                        class="flex items-center space-x-4 px-3 py-2 rounded transition duration-200
                            {{ request()->routeIs('posttest.*')
                                ? 'bg-gray-200 dark:bg-gray-700 text-gray-900 dark:text-white font-semibold'
                                : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                                        <i class="fa-solid fa-clipboard-question"></i>
                                        <span>{{ __('Post Test') }}</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('absensi.index') }}"
                                        class="flex items-center space-x-4 px-3 py-2 rounded transition duration-200
                            {{ request()->routeIs('absensi.*') || request()->routeIs('absensiAdmin.*')
                                ? 'bg-gray-200 dark:bg-gray-700 text-gray-900 dark:text-white font-semibold'
                                : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                                        <i class="fa-solid fa-face-smile"></i>
                                        <span>{{ __('Absensi') }}</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('LaporanSertifikat.index') }}"
                                        class="flex items-center space-x-4 px-3 py-2 rounded transition duration-200
                            {{ request()->routeIs('LaporanSertifikat.*')
                                ? 'bg-gray-200 dark:bg-gray-700 text-gray-900 dark:text-white font-semibold'
                                : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                                        <i class="fa-solid fa-certificate"></i>
                                        <span>{{ __('Sertifikat') }}</span>
                                    </a>
                                </li>
                            </ul>
                        </nav>

                        <hr class="border-gray-300 dark:border-gray-700">
                    @endif

                    <!-- Navigation - Manajemen -->
                    @if (Auth::user()->role === 'Admin')
                        <nav class="my-4">
                            <ul class="flex flex-col space-y-2">
                                <li class="text-sm text-gray-500 uppercase tracking-wide">Manajemen</li>
                                <li>
                                    <a href="{{ route('admin.index') }}"
                                        class="flex items-center space-x-4 px-3 py-2 rounded transition duration-200
                            {{ request()->routeIs('admin.*')
                                ? 'bg-gray-200 dark:bg-gray-700 text-gray-900 dark:text-white font-semibold'
                                : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                                        <i class="fa-solid fa-user"></i>
                                        <span>Admin</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('trainer.index') }}"
                                        class="flex items-center space-x-4 px-3 py-2 rounded transition duration-200
                            {{ request()->routeIs('trainer.*')
                                ? 'bg-gray-200 dark:bg-gray-700 text-gray-900 dark:text-white font-semibold'
                                : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                                        <i class="fa-solid fa-user"></i>
                                        <span>User</span>
                                    </a>
                                </li>
                            </ul>
                        </nav>

                        <hr class="border-gray-300 dark:border-gray-700">
                    @endif

                    <nav class="my-4">
                        <ul class="flex flex-col space-y-2">
                            <li>
                                <a href="{{ route('profile.edit') }}"
                                    class="flex items-center space-x-4 px-3 py-2 rounded transition duration-200
                            {{ request()->routeIs('profile.*')
                                ? 'bg-gray-200 dark:bg-gray-700 text-gray-900 dark:text-white font-semibold'
                                : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                                    <i class="fa-solid fa-user"></i>
                                    <span>Profile</span>
                                </a>
                            </li>
                            @if (Auth::user()->role !== 'Admin')
                                <li>
                                    <a href="{{ route('sertifikat.index') }}"
                                        class="flex items-center space-x-4 px-3 py-2 rounded transition duration-200
                            {{ request()->routeIs('sertifikat.*')
                                ? 'bg-gray-200 dark:bg-gray-700 text-gray-900 dark:text-white font-semibold'
                                : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                                        <i class="fa-solid fa-certificate"></i>
                                        <span>Sertifikat</span>
                                    </a>
                                </li>
                                <li>
                                    {{-- <a href="{{ route('riwayat.index') }}"
                                        class="flex items-center space-x-4 px-3 py-2 rounded transition duration-200
                            {{ request()->routeIs('riwayat.*')
                                ? 'bg-gray-200 dark:bg-gray-700 text-gray-900 dark:text-white font-semibold'
                                : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                                        <i class="fa-solid fa-clock-rotate-left"></i>
                                        <span>Riwayat Saya</span>
                                    </a> --}}
                                </li>
                            @endif
                        </ul>
                    </nav>
                </div>
            </div>

            <!-- Page Content -->
            <main class="flex-1 p-6">
                @if (session('success'))
                    <div class="mb-6 rounded-lg border-l-2 border-green-600 bg-green-100 dark:bg-green-900/50 p-4 text-green-800 dark:text-green-200"
                        x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 5000)">
                        <div class="flex items-center space-x-2 font-semibold">
                            <i class="fa-solid fa-circle-check"></i>
                            <span>{{ session('success') }}</span>
                        </div>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>


    @yield('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const html = document.documentElement;
            const profileButton = document.getElementById('profileButton');
            const profileDropdown = document.getElementById('profileDropdown');
            const themeSelect = document.getElementById('themeSelect');

            // Fungsi untuk apply theme
            function applyTheme(theme) {
                if (theme === 'dark') {
                    html.classList.add('dark');
                    html.style.colorScheme = 'dark';
                } else if (theme === 'light') {
                    html.classList.remove('dark');
                    html.style.colorScheme = 'light';
                } else if (theme === 'auto') {
                    // Sesuaikan dengan preferensi sistem
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

            // Load theme dari localStorage atau default auto
            const savedTheme = localStorage.getItem('theme') || 'auto';
            applyTheme(savedTheme);

            // Set dropdown ke theme yang sedang aktif
            if (themeSelect) {
                themeSelect.value = savedTheme;

                themeSelect.addEventListener('change', (e) => {
                    applyTheme(e.target.value);
                });
            }

            // Dropdown profil toggle
            if (profileButton && profileDropdown) {
                profileButton.addEventListener('click', (e) => {
                    e.stopPropagation();
                    profileDropdown.classList.toggle('hidden');
                });

                document.addEventListener('click', (e) => {
                    if (!profileDropdown.contains(e.target)) {
                        profileDropdown.classList.add('hidden');
                    }
                });
            }

            // Mobile sidebar toggle (optional)
            const mobileMenuButton = document.getElementById('mobileMenuButton');
            const mobileSidebar = document.getElementById('mobileSidebar');
            const closeMobileSidebar = document.getElementById('closeMobileSidebar');

            mobileMenuButton?.addEventListener('click', () => {
                mobileSidebar.classList.remove('-translate-x-full');
            });

            closeMobileSidebar?.addEventListener('click', () => {
                mobileSidebar.classList.add('-translate-x-full');
            });

            // Optional: klik di luar sidebar untuk menutup
            document.addEventListener('click', function(event) {
                if (!mobileSidebar.contains(event.target) && !mobileMenuButton.contains(event.target)) {
                    mobileSidebar.classList.add('-translate-x-full');
                }
            });
        });
    </script>
</body>

</html>
