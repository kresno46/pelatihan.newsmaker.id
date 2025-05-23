@extends('layouts.guest')

@section('namePage', 'Masuk')

@section('content')
<!-- Status Sesi -->
<x-auth-session-status class="mb-4" :status="session('status')" />

<form method="POST" action="{{ route('login') }}" class="w-full space-y-5">
    @csrf

    <!-- Alamat Email -->
    <div>
        <x-input-label for="email" :value="__('Email')" />
        <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required
            autofocus autocomplete="username" />
        <x-input-error :messages="$errors->get('email')" class="mt-2" />
    </div>

    <!-- Kata Sandi -->
    <div>
        <x-input-label for="password" :value="__('Kata Sandi')" />

        <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required
            autocomplete="current-password" />

        <x-input-error :messages="$errors->get('password')" class="mt-2" />
    </div>

    <!-- Ingat Saya -->
    <div class="flex justify-end items-center">
        @if (Route::has('password.request'))
        <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800"
            href="{{ route('password.request') }}">
            Lupa kata sandi?
        </a>
        @endif
    </div>

    <!-- Tombol Masuk -->
    <div class="flex items-center justify-end">
        <x-primary-button class="w-full">
            Masuk
        </x-primary-button>
    </div>

    <!-- Tautan Pendaftaran -->
    <div>
        @if (Route::has('register'))
        <p class="text-sm text-center text-gray-600 dark:text-gray-400">
            Belum punya akun?
            <a class="underline text-sm text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-100"
                href="{{ route('register') }}">
                Daftar di sini
            </a>.
        </p>
        @endif
    </div>
</form>
@endsection