@extends('layouts.guest')

@section('namePage', 'Email Verifikasi')

@section('content')
    <div class="flex flex-col items-center justify-between gap-6">
        <div class="text-center">
            Terima kasih telah mendaftar! Silakan verifikasi email Anda melalui tautan yang kami kirimkan ke alamat email
            yang terdaftar.
        </div>

        <div class="text-center text-sm text-gray-600">
            Jika email verifikasi belum diterima dalam beberapa menit, silakan periksa folder
            <strong>Spam</strong> atau <strong>Promosi</strong>.
            Tambahkan alamat email kami ke daftar kontak Anda agar email berikutnya dapat masuk ke inbox.
        </div>

        <div class="text-center text-sm text-gray-600">
            Apabila Anda masih belum menerima email verifikasi, silakan hubungi kami melalui
            <a href="mailto:support@newsmaker.id" class="text-primary font-medium">
                support@newsmaker.id
            </a>
            atau klik ikon <strong>Customer Service</strong> untuk mendapatkan bantuan lebih lanjut.
        </div>

        @if (session('status') === 'verification-link-sent')
            <div class="font-medium text-sm text-green-600">
                Link verifikasi baru telah berhasil dikirim ke alamat email Anda.
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
