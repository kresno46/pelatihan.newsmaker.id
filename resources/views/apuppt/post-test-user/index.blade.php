@extends('layouts.app')

@section('namePage', 'Post Test APUPPT')

@section('content')
    <div class="mx-auto">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl p-4 sm:p-6 mb-6 border border-gray-200 dark:border-gray-700">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Soal APUPPT</h2>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Pilih jenis soal yang ingin dikerjakan.</p>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 p-2 mb-6">
            <div class="grid grid-cols-2 gap-2">
                <a href="{{ route('apuppt.test.index', ['type' => 'posttest']) }}"
                    class="text-center py-2 px-4 rounded-lg text-sm font-semibold {{ $type === 'posttest' ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-200' }}">
                    Soal Post Test
                </a>
                <a href="{{ route('apuppt.test.index', ['type' => 'ebook']) }}"
                    class="text-center py-2 px-4 rounded-lg text-sm font-semibold {{ $type === 'ebook' ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-200' }}">
                    Soal Ebook
                </a>
            </div>
        </div>

        @php
            $activeTests = $type === 'ebook' ? $ebookTests : $postTests;
        @endphp

        @if ($activeTests->isNotEmpty())
            <div class="space-y-4">
                @foreach ($activeTests as $index => $test)
                    <div class="bg-white dark:bg-gray-800 rounded-xl p-5 border border-gray-200 dark:border-gray-700 shadow-sm">
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                            <div>
                                <h3 class="font-bold text-gray-900 dark:text-white">{{ $test->title }}</h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Durasi {{ $test->duration }} menit</p>
                            </div>

                            <div class="flex items-center gap-2">
                                @if ($test->progres === 'Belum Dikerjakan')
                                    <span class="px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">Belum Dikerjakan</span>
                                    <a href="{{ route('apuppt.test.show', $test->slug) }}" class="bg-blue-600 hover:bg-blue-700 text-white text-sm px-4 py-2 rounded-lg">
                                        Mulai
                                    </a>
                                @elseif ($test->progres === 'Nilai di Bawah 60')
                                    <span class="px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">Belum Lulus ({{ $test->score }})</span>
                                    <a href="{{ route('apuppt.test.result', $test->result_id) }}" class="bg-gray-600 hover:bg-gray-700 text-white text-sm px-4 py-2 rounded-lg">
                                        Lihat Hasil
                                    </a>
                                @else
                                    <span class="px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">Lulus ({{ $test->score }})</span>
                                    <a href="{{ route('apuppt.test.result', $test->result_id) }}" class="bg-gray-600 hover:bg-gray-700 text-white text-sm px-4 py-2 rounded-lg">
                                        Lihat Hasil
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-12 bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Belum ada soal tersedia</h3>
                <p class="text-gray-600 dark:text-gray-400">
                    {{ $type === 'ebook' ? 'Soal ebook belum tersedia saat ini.' : 'Soal post test belum tersedia saat ini.' }}
                </p>
            </div>
        @endif
    </div>
@endsection
