@extends('layouts.guest')

@section('namePage', 'Lupa Password')

@section('content')
    <div class="text-center">
        <h1 class="text-xl font-bold mb-4">Lupa Password</h1>
        <p class="text-gray-600">Masukkan alamat email yang terdaftar pada akun Anda, lalu kami akan mengirimkan tautan untuk
            mengatur ulang kata sandi Anda. Pastikan email yang Anda masukkan aktif dan dapat diakses.</p>
    </div>

    @if (session('status'))
        <div class="mb-4 text-green-600">{{ session('status') }}</div>
    @endif

    <form method="POST" action="{{ route('password.email') }}" class="w-full flex flex-col items-center">
        @csrf

        <div class="w-full mb-4">
            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
            <input id="email" type="email" name="email"
                value="{{ is_array(old('email')) ? old('email')[0] ?? '' : old('email') }}" required autofocus
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500" />
            @error('email')
                <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
            @enderror
        </div>

        <div class="w-full flex flex-col items-center gap-4">
            <x-primary-button>
                Kirim Link Reset Password
            </x-primary-button>

            <a href="{{ route('login') }}" class="text-blue-800 hover:text-blue-900">
                <i class="fa-solid fa-arrow-left"></i> Kembali
            </a>
        </div>


    </form>
@endsection
