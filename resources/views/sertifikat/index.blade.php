@extends('layouts.app')

@section('namePage', 'Sertifikat')

@section('content')
    <div class="p-6 bg-white dark:bg-gray-800 space-y-6 rounded-lg shadow-lg transition-colors">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
            <div>
                <h2 class="text-xl font-bold text-gray-900 dark:text-white">Sertifikat</h2>
                <p class="text-gray-600 dark:text-gray-300">
                    Dapatkan sertifikat setelah menyelesaikan post-test dengan nilai minimal 60.
                </p>
            </div>

            @if (session('Alert'))
                <div class="text-center text-green-600 dark:text-green-400 font-semibold">
                    {{ session('Alert') }}
                </div>
            @endif

            @if (session('error'))
                <div class="text-center text-red-600 dark:text-red-400 font-semibold">
                    {{ session('error') }}
                </div>
            @endif
        </div>

        <hr class="border-gray-300 dark:border-gray-600">

        {{-- List Post-Test --}}
        <div class="flex flex-col gap-3">
            @forelse ($userResults as $postTest)
                <div
                    class="border rounded-lg px-5 py-3 mb-3 transition-colors 
                    {{ $postTest->score >= 60
                        ? 'border-green-500 bg-green-50 dark:bg-green-900/20 dark:border-green-400'
                        : 'border-red-500 bg-red-50 dark:bg-red-900/20 dark:border-red-400' }}">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                        <p class="font-semibold text-gray-900 dark:text-white">
                            {{ 'Post-Test: ' . $postTest->session->title }}
                        </p>
                        <div class="flex items-center gap-4">
                            <div class="text-sm font-medium text-gray-800 dark:text-gray-200 rounded-full">
                                {{ $postTest->score }}/100
                            </div>
                            @if ($postTest->score >= 60)
                                <a href="{{ route('sertifikat.download', $postTest->id) }}"
                                    class="text-green-600 dark:text-green-400 hover:text-green-700 dark:hover:text-green-300 font-semibold transition-colors">
                                    Download Sertifikat
                                </a>
                            @else
                                <span class="italic text-red-600 dark:text-red-400">Nilai kurang dari 60</span>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div>
                    <p class="text-gray-500 dark:text-gray-300">Belum ada data post-test Anda.</p>
                </div>
            @endforelse
        </div>
    </div>
@endsection
