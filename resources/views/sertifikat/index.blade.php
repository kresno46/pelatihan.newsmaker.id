@extends('layouts.app')

@section('namePage', 'Sertifikat')

@section('content')
    <div class="p-6 bg-white dark:bg-gray-800 space-y-6 rounded-lg shadow-lg">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-bold text-gray-900 dark:text-white">Sertifikat</h2>
                <p class="text-gray-600 dark:text-gray-300">
                    Dapatkan sertifikat setelah menyelesaikan post-test dengan nilai minimal 75.
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
                    class="border {{ $postTest->score >= 75 ? 'border-green-500' : 'border-red-500' }} px-5 py-3 rounded-lg {{ $postTest->score >= 75 ? 'bg-green-50' : 'bg-red-50' }} mb-3">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="font-semibold text-gray-900 dark:text-white">
                                {{ 'Post-Test: ' . $postTest->session->title }} <!-- Mengambil title dari session -->
                            </p>
                        </div>
                        <div class="flex items-center gap-4">
                            <div class="text-sm rounded-full">
                                {{ $postTest->score }}/100
                            </div>
                            @if ($postTest->score >= 75)
                                <a href="{{ route('sertifikat.download', $postTest->id) }}"
                                    class="text-green-600 hover:text-green-700 font-semibold">Download Sertifikat</a>
                            @else
                                <span class="italic text-red-500">Nilai kurang dari 75</span>
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
