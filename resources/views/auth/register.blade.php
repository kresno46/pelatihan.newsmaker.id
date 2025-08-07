@extends('layouts.guest')

@section('namePage', 'Daftar')

@section('content')
    <form method="POST" action="{{ route('register') }}" class="w-full space-y-6 text-gray-900 dark:text-gray-100">
        @csrf

        {{-- Nama Lengkap --}}
        <div>
            <x-input-label for="name" :value="__('Nama Lengkap')" class="dark:text-gray-200" />
            <x-text-input id="name" name="name" type="text" class="block mt-1 w-full" placeholder="John Doe"
                :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2 text-sm dark:text-red-400" />
        </div>

        {{-- Email --}}
        <div>
            <x-input-label for="email" :value="__('Email')" class="dark:text-gray-200" />
            <x-text-input id="email" name="email" type="email" class="block mt-1 w-full"
                placeholder="email@domain.com" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-sm dark:text-red-400" />
        </div>

        {{-- Role / Perusahaan --}}
        <div>
            <x-input-label for="role" :value="__('Perusahaan')" class="dark:text-gray-200" />
            <select id="role" name="role"
                class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-indigo-200 dark:bg-gray-800 dark:border-gray-600 dark:text-white"
                required>
                <option value="" disabled selected>-- Pilih Perusahaan --</option>
                <option value="Trainer (RFB)" {{ old('role') == 'Trainer (RFB)' ? 'selected' : '' }}>PT Rifan Financindo
                    Berjangka</option>
                <option value="Trainer (SGB)" {{ old('role') == 'Trainer (SGB)' ? 'selected' : '' }}>PT Solid Gold Berjangka
                </option>
                <option value="Trainer (KPF)" {{ old('role') == 'Trainer (KPF)' ? 'selected' : '' }}>PT Kontak Perkasa
                    Futures</option>
                <option value="Trainer (BPF)" {{ old('role') == 'Trainer (BPF)' ? 'selected' : '' }}>PT BestProfit Futures
                </option>
                <option value="Trainer (EWF)" {{ old('role') == 'Trainer (EWF)' ? 'selected' : '' }}>PT Equity World Futures
                </option>
            </select>
            <x-input-error :messages="$errors->get('role')" class="mt-2 text-sm dark:text-red-400" />
        </div>

        {{-- Password --}}
        <div>
            <x-input-label for="password" :value="__('Kata Sandi')" class="dark:text-gray-200" />
            <div class="relative">
                <x-text-input id="password" name="password" type="password" class="block mt-1 w-full pr-10" required
                    autocomplete="new-password" />
                <button type="button" onclick="togglePassword('password', 'eyeIcon1')"
                    class="absolute inset-y-0 right-0 px-3 flex items-center text-gray-500 dark:text-gray-400">
                    <i class="fa-solid fa-eye" id="eyeIcon1"></i>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2 text-sm dark:text-red-400" />
        </div>

        {{-- Konfirmasi Password --}}
        <div>
            <x-input-label for="password_confirmation" :value="__('Konfirmasi Kata Sandi')" class="dark:text-gray-200" />
            <div class="relative">
                <x-text-input id="password_confirmation" name="password_confirmation" type="password"
                    class="block mt-1 w-full pr-10" required autocomplete="new-password" />
                <button type="button" onclick="togglePassword('password_confirmation', 'eyeIcon2')"
                    class="absolute inset-y-0 right-0 px-3 flex items-center text-gray-500 dark:text-gray-400">
                    <i class="fa-solid fa-eye" id="eyeIcon2"></i>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-sm dark:text-red-400" />
        </div>

        {{-- Tombol Daftar --}}
        <div>
            <x-primary-button class="w-full">
                Daftar
            </x-primary-button>
        </div>

        {{-- Tautan Login --}}
        @if (Route::has('login'))
            <div class="text-center text-sm text-gray-600 dark:text-gray-400">
                Sudah punya akun?
                <a href="{{ route('login') }}"
                    class="underline text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-200">
                    Masuk
                </a>
            </div>
        @endif
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

            input.type = isPassword ? 'text' : 'password';
            icon.classList.toggle('fa-eye', !isPassword);
            icon.classList.toggle('fa-eye-slash', isPassword);
            icon.classList.add('rotate-180');

            setTimeout(() => {
                icon.classList.remove('rotate-180');
            }, 300);
        }
    </script>
@endsection
