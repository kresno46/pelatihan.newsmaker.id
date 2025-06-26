@extends('layouts.app')

@section('namePage', 'Sertifikat')

@section('content')
    <div class="p-6 bg-white dark:bg-gray-800 space-y-6 rounded-lg shadow-lg">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-bold text-gray-900 dark:text-white">Sertifikat</h2>
                <p class="text-gray-600 dark:text-gray-300">
                    Kami mengirimkan sertifikat Anda ke alamat email yang terdaftar.
                </p>
            </div>

            @if (session('Alert'))
                <div class="text-center text-green-600 dark:text-green-400">
                    {{ session('Alert') }}
                </div>
            @endif
        </div>

        <hr class="border-gray-300 dark:border-gray-600">

        {{-- Container Sertifikat --}}
        <div class="flex flex-col gap-3">
            @php
                $titles = ['Fundamental', 'Beginner', 'Intermediate', 'Advanced', 'Expert'];
            @endphp

            @foreach ($titles as $index => $title)
                @php
                    $batchNumber = $index + 1;
                    $canDownload = $availableBatches >= $batchNumber;
                @endphp

                @if ($canDownload)
                    <a href="{{ route('sertifikat.download', ['batch' => $batchNumber]) }}"
                        class="hover:bg-green-50 dark:hover:bg-green-900 transition">
                        <div
                            class="flex justify-between items-center gap-4 border border-green-500 dark:border-green-400 p-4 rounded-lg">
                            <div class="flex items-center gap-2 text-green-700 dark:text-green-300">
                                <i class="fa-solid fa-certificate"></i>
                                <h1 class="font-bold">Sertifikat {{ $title }}</h1>
                            </div>
                            <i class="fa-solid fa-download text-green-600 dark:text-green-300"></i>
                        </div>
                    </a>
                @else
                    <div
                        class="flex justify-between items-center gap-4 border border-gray-300 dark:border-gray-600 p-4 rounded-lg opacity-60 cursor-not-allowed">
                        <div class="flex items-center gap-2 text-gray-600 dark:text-gray-400">
                            <i class="fa-solid fa-certificate"></i>
                            <h1 class="font-bold">Sertifikat {{ $title }}</h1>
                        </div>
                        <i class="fa-solid fa-lock text-gray-400"></i>
                    </div>
                @endif
            @endforeach
        </div>
    </div>
@endsection
