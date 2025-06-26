@extends('layouts.guest')

@section('namePage', 'Reset Password')

@section('content')
    <div class="text-center">
        <h1 class="text-xl font-bold mb-4">Reset Password</h1>
        <p class="text-gray-600">Silakan buat kata sandi baru untuk akun Anda. Pastikan kata sandi cukup kuat dan mudah
            diingat agar akun tetap aman.</p>
    </div>

    <form method="POST" action="{{ route('password.store') }}" class="w-full flex flex-col items-center">
        @csrf

        <input type="hidden" name="token" value="{{ $token }}">
        <input type="hidden" name="email" value="{{ is_array($email) ? $email[0] ?? '' : $email }}">

        <div class="mb-4 w-full">
            <label for="password" class="block text-sm font-medium text-gray-700">Password Baru</label>
            <input id="password" type="password" name="password" required
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500" />
            @error('password')
                <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4 w-full">
            <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Konfirmasi Password</label>
            <input id="password_confirmation" type="password" name="password_confirmation" required
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500" />
            @error('password_confirmation')
                <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <x-primary-button>
                Reset Password
            </x-primary-button>
        </div>
    </form>
@endsection
