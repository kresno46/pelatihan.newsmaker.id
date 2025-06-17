@extends('layouts.guest')

@section('content')
    <div class="mb-4 text-center     text-gray-600">
        Terima kasih telah mendaftar! Sebelum memulai, mohon verifikasi alamat email Anda
        dengan mengklik tautan yang baru saja kami kirimkan ke email Anda. Jika Anda tidak menerima email tersebut,
        kami akan dengan senang hati mengirimkannya lagi.
    </div>

    @if (session('status') === 'verification-link-sent')
        <div class="mb-4 font-medium text-sm text-green-600">
            Link verifikasi baru telah dikirim ke alamat email Anda.
        </div>
    @endif

    <div class="mt-4 flex flex-col items-center justify-between">
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
            <button type="submit" class="underline text-sm text-gray-600 hover:text-gray-900">
                Keluar
            </button>
        </form>
    </div>
@endsection
