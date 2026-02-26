@extends('layouts.app')

@section('namePage', 'Ganti Password')

@section('content')
    <div class="space-y-8">
        <div
            class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-800 sm:p-7">
            <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">Ganti Password</h1>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-300">
                        Perbarui kata sandi untuk menjaga keamanan akun Anda.
                    </p>
                </div>
                <a href="{{ route('profile.index') }}"
                    class="inline-flex items-center gap-2 rounded-lg border border-gray-200 px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-200 dark:hover:bg-gray-900/40">
                    <i class="fa-solid fa-arrow-left"></i>
                    Kembali
                </a>
            </div>
        </div>

        @include('profile.partials.update-password-form')
    </div>
@endsection
