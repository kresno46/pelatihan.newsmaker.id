@extends('layouts.app')

@section('namePage', 'Kuis')

@section('content')
    <header class="w-full bg-white dark:bg-gray-800 shadow rounded-lg mb-5 p-4 sm:p-6">
        <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
            <div>
                <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                    {{ __('Kuis / Post Test') }}
                </h2>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    {{ __('Kelola daftar post test: tambah, ubah, lihat laporan, atau hapus.') }}
                </p>
            </div>

            <div class="flex items-center">
                @if (session('success'))
                    <div x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 5000)"
                        class="text-xs bg-green-100 dark:bg-green-200 text-green-800 py-1 px-3 rounded-lg mr-3"
                        aria-live="polite">
                        {{ session('success') }}
                    </div>
                @elseif (session('status'))
                    <div x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 5000)"
                        class="text-xs bg-green-100 dark:bg-green-200 text-green-800 py-1 px-3 rounded-lg mr-3"
                        aria-live="polite">
                        {{ session('status') }}
                    </div>
                @endif

                <a href="{{ route('posttest.create') }}"
                    class="bg-blue-500 px-4 py-2 text-sm hover:bg-blue-600 text-white rounded transition-all text-center">
                    {{ __('Tambah') }}
                </a>
            </div>
        </div>
    </header>

    <div class="p-6 bg-white dark:bg-gray-800 rounded-xl shadow-md">
        @if ($sessions->isEmpty())
            <div class="text-center py-12 text-gray-600 dark:text-gray-300">
                <p class="font-medium">{{ __('Belum ada post test.') }}</p>
                <p class="text-sm mt-1">{{ __('Klik "Tambah" untuk membuat post test baru.') }}</p>
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3">
                @foreach ($sessions as $item)
                    <div
                        class="bg-gray-50 dark:bg-gray-900/40 p-5 rounded-lg flex flex-col gap-4 border border-gray-200 dark:border-gray-700">
                        <div class="w-full">
                            <h3 class="font-semibold text-gray-900 dark:text-gray-100">
                                {{ $item->title }}
                            </h3>
                            <p class="text-sm text-gray-700 dark:text-gray-300">
                                <strong>{{ __('Durasi:') }}</strong> {{ $item->duration }} {{ __('menit') }}
                            </p>
                        </div>

                        <div class="w-full grid grid-cols-3 gap-2">
                            {{-- Laporan / Show --}}
                            <a href="{{ route('posttest.report', $item->slug) }}"
                                class="bg-green-500 px-3 py-2 text-xs sm:text-sm hover:bg-green-600 text-white rounded transition-all text-center">
                                {{ __('Laporan') }}
                            </a>

                            {{-- Edit --}}
                            <a href="{{ route('posttest.edit', $item->slug) }}"
                                class="bg-yellow-500 px-3 py-2 text-xs sm:text-sm hover:bg-yellow-600 text-white rounded transition-all text-center">
                                {{ __('Edit') }}
                            </a>

                            {{-- Hapus (DELETE) --}}
                            <form action="{{ route('posttest.destroy', $item->slug) }}" method="POST" x-data
                                @submit.prevent="if (confirm('{{ __('Yakin ingin menghapus post test ini? Tindakan tidak dapat dibatalkan.') }}')) $el.submit()">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="w-full bg-red-500 px-3 py-2 text-xs sm:text-sm hover:bg-red-600 text-white rounded transition-all text-center">
                                    {{ __('Hapus') }}
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Optional Pagination --}}
            @if (method_exists($sessions, 'links'))
                <div class="mt-4">
                    {{ $sessions->links() }}
                </div>
            @endif
        @endif
    </div>
@endsection
