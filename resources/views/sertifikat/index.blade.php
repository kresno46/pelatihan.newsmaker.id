@extends('layouts.app')

@section('namePage', 'Sertifikat')

@section('content')
    <div class="p-6 bg-white dark:bg-gray-800 space-y-6 rounded-lg shadow-lg">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-bold text-gray-900 dark:text-white">Sertifikat</h2>
                <p class="text-gray-600 dark:text-gray-300">
                    Dapatkan sertifikat setelah menyelesaikan seluruh eBook dalam satu materi dengan nilai minimal 75.
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

        {{-- List Semua Folder --}}
        <div class="flex flex-col gap-3">
            @foreach ($allFolders as $folder)
                @php
                    $folderId = $folder->id;
                    $isEligible = $eligibility[$folderId] ?? false;
                    $hasAward = $awards->firstWhere('batch_number', $folderId);
                @endphp

                <div
                    class="flex justify-between items-center gap-4 border {{ $isEligible ? 'border-green-500 dark:border-green-400' : 'border-gray-300 dark:border-gray-600' }} p-4 rounded-lg {{ $isEligible ? '' : 'opacity-60 cursor-not-allowed' }}">
                    <div
                        class="flex items-center gap-2 {{ $isEligible ? 'text-green-700 dark:text-green-300' : 'text-gray-600 dark:text-gray-400' }}">
                        <i class="fa-solid fa-certificate"></i>
                        <h1 class="font-bold">{{ $folder->folder_name }}</h1>
                    </div>

                    @if ($isEligible)
                        <a href="{{ route('sertifikat.generate', ['folderSlug' => $folder->slug]) }}"
                            class="text-green-600 dark:text-green-300 hover:text-green-800">
                            <i class="fa-solid fa-download"></i>
                        </a>
                    @else
                        <i class="fa-solid fa-lock text-gray-400"></i>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
@endsection
