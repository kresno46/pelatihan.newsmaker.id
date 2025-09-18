@extends('layouts.app')

@section('namePage', 'Post Test')

@section('content')
    <header class="w-full bg-white dark:bg-gray-800 shadow rounded-lg mb-5 p-4 sm:p-6">
        <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
            <div>
                <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                    {{ __('Kuis / Post Test') }}
                </h2>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    {{ __('Daftar post test yang bisa Anda kerjakan.') }}
                </p>
            </div>

            <div class="flex items-center">
                @if (session('success'))
                    <div x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 5000)"
                        class="text-xs bg-green-100 dark:bg-green-200 text-green-800 py-1 px-3 rounded-lg mr-3"
                        aria-live="polite">
                        {{ session('success') }}
                    </div>
                @elseif (session('error'))
                    <div x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 5000)"
                        class="text-xs bg-red-100 dark:bg-red-200 text-red-800 py-1 px-3 rounded-lg mr-3"
                        aria-live="polite">
                        {{ session('error') }}
                    </div>
                @endif
            </div>
        </div>
    </header>

    <div>
        {{-- Filter Button --}}
        @php
            $activeTipe = request('tipe', 'PATD'); // default ke PATD
        @endphp

        <div class="w-full flex">
            <a href="{{ route('dashboard') }}"
                class="bg-gray-500 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-gray-600 transition cursor-pointer sm:w-auto">
                <i class="fa-solid fa-arrow-left text-sm"></i>
            </a>
            <a href="{{ route('post-test.index', ['tipe' => 'PATD']) }}"
                class="py-2 shadow text-center rounded-t-3xl w-full 
                    {{ $activeTipe === 'PATD' ? 'bg-white font-semibold' : 'bg-gray-300 hover:bg-gray-200 transition-all' }}">
                PATD
            </a>
            {{-- <a href="{{ route('post-test.index', ['tipe' => 'PATL']) }}"
                class="py-2 shadow text-center rounded-t-3xl w-full 
                    {{ $activeTipe === 'PATL' ? 'bg-white font-semibold' : 'bg-gray-300 hover:bg-gray-200 transition-all' }}">
                PATL
            </a> --}}
        </div>

        {{-- Tabel Data --}}
        <div class="bg-white shadow dark:bg-gray-800 rounded-b-lg p-4 sm:p-6">
            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-100 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-4 py-3">#</th>
                        <th scope="col" class="px-4 py-3">Judul</th>
                        <th scope="col" class="px-4 py-3">Durasi</th>
                        <th scope="col" class="px-4 py-3">Progress</th>
                        <th scope="col" class="px-4 py-3">Status</th>
                        <th scope="col" class="px-4 py-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($tests as $index => $posttest)
                        <tr class="border-b dark:border-gray-700">
                            <td class="px-4 py-3">{{ $index + 1 }}</td>
                            <td class="px-4 py-3 font-medium text-gray-900 dark:text-white">
                                {{ $posttest->title }}
                            </td>
                            <td class="px-4 py-3">{{ $posttest->duration }} menit</td>
                            <td class="px-4 py-3">
                                @if ($posttest->progres === 'Belum Dikerjakan')
                                    <span class="text-yellow-600 font-semibold">Belum Dikerjakan</span>
                                @elseif ($posttest->progres === 'Nilai di Bawah 60')
                                    <span class="text-red-600 font-semibold">Nilai di Bawah 60
                                        ({{ $posttest->score }})
                                    </span>
                                @else
                                    <span class="text-green-600 font-semibold">Selesai ({{ $posttest->score }})</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                @if ($posttest->status)
                                    <!-- Status is now boolean -->
                                    <div class="bg-green-500 text-center rounded-full py-1 text-sm">
                                        <span class="text-green-100 font-semibold">Aktif</span>
                                    </div>
                                @else
                                    <div class="bg-red-500 text-center rounded-full py-1 text-sm">
                                        <span class="text-red-100 font-semibold">Tidak Aktif</span>
                                    </div>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-center">
                                @if (!$posttest->status)
                                    <!-- Status is now boolean -->
                                    <div class="flex gap-2">
                                        <span
                                            class="inline-block w-full bg-gray-400 text-white text-xs px-3 py-1 rounded cursor-not-allowed">
                                            Tidak Tersedia
                                        </span>
                                        @if ($posttest->progres === 'Selesai')
                                            <a href="{{ route('post-test.result', $posttest->result_id) }}"
                                                class="inline-block w-full bg-gray-600 text-white text-xs px-3 py-1 rounded hover:bg-gray-700">
                                                Lihat Hasil
                                            </a>
                                        @endif
                                    </div>
                                @else
                                    @if ($posttest->progres === 'Belum Dikerjakan')
                                        <a href="{{ route('post-test.show', $posttest->slug) }}"
                                            class="inline-block w-full bg-blue-600 text-white text-xs px-3 py-1 rounded hover:bg-blue-700">
                                            Mulai
                                        </a>
                                    @elseif ($posttest->progres === 'Nilai di Bawah 60')
                                        {{-- <a href="{{ route('post-test.show', $posttest->slug) }}"
                                            class="inline-block w-full bg-orange-600 text-white text-xs px-3 py-1 rounded hover:bg-orange-700">
                                            Ulangi
                                        </a> --}}
                                    @else
                                        <a href="{{ route('post-test.result', $posttest->result_id) }}"
                                            class="inline-block w-full bg-gray-600 text-white text-xs px-3 py-1 rounded hover:bg-gray-700">
                                            Lihat Hasil
                                        </a>
                                    @endif
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-3 text-center text-gray-500">
                                Tidak ada post test tersedia.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
