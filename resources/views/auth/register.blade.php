@extends('layouts.guest')

@section('namePage', 'Daftar')

@section('content')
    <form method="POST" action="{{ route('register') }}" class="w-full space-y-5 text-gray-900 dark:text-gray-100">
        @csrf

        <!-- Nama Lengkap -->
        <div>
            <x-input-label for="name" :value="__('Nama Lengkap')" class="dark:text-gray-200" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required
                placeholder="John Doe" autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2 dark:text-red-400" />
        </div>

        <!-- Email -->
        <div>
            <x-input-label for="email" :value="__('Email')" class="dark:text-gray-200" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required
                placeholder="johndoe@example.com" autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2 dark:text-red-400" />
        </div>

        <!-- Kata Sandi -->
        <div>
            <x-input-label for="password" :value="__('Kata Sandi')" class="dark:text-gray-200" />
            <div class="relative">
                <x-text-input id="password" class="block mt-1 w-full pr-10" type="password" name="password" required
                    autocomplete="new-password" />
                <button type="button" onclick="togglePassword('password', 'eyeIcon1')"
                    class="absolute inset-y-0 right-0 px-3 flex items-center text-gray-500 dark:text-gray-400">
                    <i class="fa-solid fa-eye" id="eyeIcon1"></i>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2 dark:text-red-400" />
        </div>

        <!-- Konfirmasi Kata Sandi -->
        <div>
            <x-input-label for="password_confirmation" :value="__('Konfirmasi Kata Sandi')" class="dark:text-gray-200" />
            <div class="relative">
                <x-text-input id="password_confirmation" class="block mt-1 w-full pr-10" type="password"
                    name="password_confirmation" required autocomplete="new-password" />
                <button type="button" onclick="togglePassword('password_confirmation', 'eyeIcon2')"
                    class="absolute inset-y-0 right-0 px-3 flex items-center text-gray-500 dark:text-gray-400">
                    <i class="fa-solid fa-eye" id="eyeIcon2"></i>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 dark:text-red-400" />
        </div>

        <!-- Tombol Daftar -->
        <div class="flex items-center justify-end">
            <x-primary-button class="w-full">
                Daftar
            </x-primary-button>
        </div>

        <!-- Tautan Login -->
        <div>
            @if (Route::has('login'))
                <p class="text-sm text-center text-gray-600 dark:text-gray-400">
                    Sudah punya akun?
                    <a href="{{ route('login') }}"
                        class="underline text-sm text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-100">
                        Masuk
                    </a>
                </p>
            @endif
        </div>
    </form>
@endsection

@section('scripts')
    <style>
        .fa-eye,
        .fa-eye-slash {
            transition: transform 0.3s ease, opacity 0.3s ease;
        }

        .rotate-180 {
            transform: rotate(180deg);
        }
    </style>

    <script>
        function togglePassword(inputId, iconId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById(iconId);
            const isPassword = input.type === 'password';

            // Toggle input type
            input.type = isPassword ? 'text' : 'password';

            // Toggle eye icon class
            icon.classList.toggle('fa-eye', !isPassword);
            icon.classList.toggle('fa-eye-slash', isPassword);

            // Add rotation animation
            icon.classList.add('rotate-180');

            // Remove rotation after animation
            setTimeout(() => {
                icon.classList.remove('rotate-180');
            }, 300);
        }
    </script>
@endsection
