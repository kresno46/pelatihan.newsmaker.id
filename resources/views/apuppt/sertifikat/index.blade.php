@extends('layouts.app')

@section('namePage', 'Sertifikat APUPPT')

@section('content')
    <div class="p-6 bg-white dark:bg-gray-800 space-y-6 rounded-lg shadow-lg border border-gray-100 dark:border-gray-700">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-bold text-gray-900 dark:text-white">Sertifikat APUPPT</h2>
                <p class="text-gray-600 dark:text-gray-300">
                    Sertifikat APUPPT tersedia setelah menyelesaikan post-test dengan nilai minimal 60.
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

        <div class="flex flex-col gap-3">
            @forelse ($userResults as $postTest)
                <div
                    class="border {{ $postTest->score >= 60 ? 'border-green-500' : 'border-red-500' }} px-5 py-3 rounded-lg {{ $postTest->score >= 60 ? 'bg-green-50 dark:bg-green-900/20' : 'bg-red-50 dark:bg-red-900/20' }} mb-3">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="font-semibold text-gray-900 dark:text-white">
                                {{ 'Post-Test APUPPT: ' . ($postTest->session->title ?? '-') }}
                            </p>
                        </div>
                        <div class="flex items-center gap-4">
                            <div class="text-sm rounded-full">
                                {{ $postTest->score }}/100
                            </div>
                            @if ($postTest->score >= 60)
                                <a href="{{ route('apuppt.sertifikatUser.download', $postTest->id) }}"
                                    class="text-green-600 hover:text-green-700 font-semibold">Download Sertifikat</a>
                            @else
                                <span class="italic text-red-500">Nilai kurang dari 60</span>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div>
                    <p class="text-gray-500 dark:text-gray-300">Belum ada data post-test APUPPT Anda.</p>
                </div>
            @endforelse
        </div>
    </div>
@endsection
