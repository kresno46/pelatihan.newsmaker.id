@extends('layouts.guest')

@section('namePage', 'Email Verifikasi')

@section('content')
    <div class="flex flex-col items-center justify-between gap-6">
        <div class="text-center">
            Terima kasih telah mendaftar! Silakan verifikasi email Anda melalui tautan yang kami kirim. Belum menerima
            email? Kami siap mengirimkannya kembali.
        </div>

        @if (session('status') === 'verification-link-sent')
            <div class="font-medium text-sm text-green-600">
                Link verifikasi baru telah dikirim ke alamat email Anda.
            </div>
        @endif

        <div class="flex flex-col items-center justify-between gap-3">
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <div>
                    <x-primary-button>
                        Kirim Ulang Email Verifikasi
                    </x-primary-button>
                </div>
            </form>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="underline text-sm">
                    Keluar
                </button>
            </form>
        </div>
    </div>
@endsection
