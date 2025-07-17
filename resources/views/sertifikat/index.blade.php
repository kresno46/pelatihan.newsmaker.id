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
            @forelse ($eligibleFolders as $data)
                @if ($data->can_download)
                    <a href="{{ route('sertifikat.download', $data->folder->slug) }}"
                        class="border border-green-500 px-5 py-3 rounded-lg hover:bg-green-100 transition-all duration-100 block mb-3">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-green-600 font-semibold">{{ $data->folder->folder_name }}</p>
                            </div>
                            <div class="flex items-center gap-4">
                                <div class="text-green-500 text-sm rounded-full">
                                    {{ $data->average_score }}/100
                                </div>
                                <i class="fa-solid fa-download text-green-600"></i>
                            </div>
                        </div>
                    </a>
                @elseif ($data->is_completed_but_failed)
                    <div class="border border-red-500 px-5 py-3 rounded-lg bg-red-50 text-red-500 mb-3">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-red-600 font-semibold">{{ $data->folder->folder_name }}</p>
                            </div>
                            <div class="flex items-center gap-4">
                                <div class="text-red-500 text-sm rounded-full">
                                    {{ $data->average_score }}/100
                                </div>
                                <span class="italic text-red-500">Nilai kurang dari 75</span>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="border border-gray-300 px-5 py-3 rounded-lg text-gray-400 mb-3">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500 font-semibold">{{ $data->folder->folder_name }}</p>
                            </div>
                            <div class="flex items-center gap-4">
                                <span class="italic text-gray-400">Belum memenuhi</span>
                            </div>
                        </div>
                    </div>
                @endif
            @empty
                <div>
                    <p>Belum ada data post-test Anda.</p>
                </div>
            @endforelse
        </div>
    </div>
@endsection
