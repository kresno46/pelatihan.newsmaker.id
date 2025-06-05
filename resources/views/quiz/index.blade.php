@extends('layouts.app')

@section('namePage', 'Kuis')

@section('content')
<div class="bg-white dark:bg-gray-800 p-3 rounded-2xl">
    <!-- Search Bar -->
    <div class="w-full flex items-center gap-2 mb-5">
        <form action="{{ route('quiz.index') }}" method="GET" class="flex items-center gap-2 flex-grow">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari judul kuis..."
                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm text-gray-900 dark:text-gray-100 bg-white dark:bg-gray-800" />
        </form>

        <a href="{{ route('quiz.index') }}"
            class="bg-red-500 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-red-600 transition cursor-pointer">
            Reset
        </a>

        @if (Auth::user()->role === 'Admin')
        <a href="{{ route('quiz.create') }}"
            class="bg-blue-500 text-white px-4 py-2 rounded-lg text-sm font-bold hover:bg-blue-600 transition cursor-pointer">
            <span class="block sm:hidden">+</span>
            <span class="hidden sm:block">Tambah Kuis</span>
        </a>
        @endif
    </div>

    <!-- Grid Card -->
    <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6">
        @forelse ($quizzes as $quiz)
        <!-- Kuis Card -->
        <div
            class="bg-gray-50 dark:bg-gray-900 rounded-2xl shadow-lg p-6 hover:shadow-lg transition-shadow duration-300 flex flex-col justify-between">
            <!-- Header: Judul & Status -->
            <div>
                <h2 class="text-xl font-bold text-gray-800 dark:text-white mb-2 line-clamp-1">
                    {{ $quiz->title }}
                </h2>

                <!-- Status + Info -->
                <div class="flex items-center gap-3 mb-2">
                    <div>
                        @if (Auth::user()->role === 'Admin')
                        <span
                            class="inline-block text-xs font-semibold px-3 py-1 rounded-full 
                                {{ $quiz->status === 'public' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                            {{ ucfirst($quiz->status) }}
                        </span>
                        @else
                        <span
                            class="inline-block text-xs font-semibold px-3 py-1 rounded-full bg-blue-100 text-blue-700">
                            Available
                        </span>
                        @endif
                    </div>

                    <div class="text-sm text-gray-600 dark:text-gray-300">
                        {{ $quiz->questions->count() }} Soal
                    </div>
                </div>

                <!-- Deskripsi -->
                <p class="text-sm text-gray-700 dark:text-gray-400 line-clamp-2">
                    {{ $quiz->description ?? 'Tidak ada deskripsi.' }}
                </p>
            </div>

            <!-- Tombol Aksi -->
            <div class="mt-4 flex gap-2">
                @if (Auth::user()->role === 'Admin')
                <a href="{{ route('quiz.show', $quiz->slug) }}"
                    class="w-full bg-green-600 max-w-[150px] min-w-[100px] hover:bg-green-700 text-white text-sm px-4 py-2 rounded-lg text-center">
                    Lihat
                </a>

                <a href="{{ route('quiz.edit', $quiz->id) }}"
                    class="w-full bg-yellow-500 max-w-[150px] min-w-[100px] hover:bg-yellow-600 text-white text-sm px-4 py-2 rounded-lg text-center">
                    Edit
                </a>

                <button onclick="openDeleteModal({{ $quiz->id }})"
                    class="w-full bg-red-600 hover:bg-red-700 text-white text-sm px-4 py-2 rounded-lg text-center max-w-[150px] min-w-[100px]">
                    Hapus
                </button>

                <!-- Modal Delete -->
                <div id="deleteModal{{ $quiz->id }}"
                    class="fixed inset-0 bg-black/50 backdrop-blur flex items-center justify-center hidden px-5 z-50">
                    <div class="bg-white dark:bg-gray-800 rounded p-6 w-full max-w-md text-gray-900 dark:text-gray-100">
                        <h3 class="text-lg font-semibold mb-4">Konfirmasi Hapus</h3>
                        <p class="mb-6">Apakah Anda yakin ingin menghapus kuis <strong>{{ $quiz->title }}</strong>?</p>
                        <div class="flex justify-end gap-4">
                            <button onclick="closeDeleteModal({{ $quiz->id }})"
                                class="px-4 py-2 bg-gray-300 dark:bg-gray-700 rounded hover:bg-gray-400 dark:hover:bg-gray-600 transition">
                                Batal
                            </button>
                            <form id="deleteForm{{ $quiz->id }}" action="{{ route('quiz.destroy', $quiz->id) }}"
                                method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded transition">Ya,
                                    Hapus</button>
                            </form>
                        </div>
                    </div>
                </div>
                @else
                <a href="#"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white text-sm px-4 py-2 rounded-lg text-center">
                    Kerjakan
                </a>
                @endif
            </div>
        </div>
        @empty
        <div class="col-span-full text-center py-10 text-gray-500 dark:text-gray-400">
            Tidak ada kuis ditemukan.
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mt-6 gap-2">
        @if ($quizzes->total() > 8)
        <div class="w-full">
            {{ $quizzes->appends(['search' => request('search')])->links() }}
        </div>
        @else
        <div class="text-sm text-gray-600 dark:text-gray-300 p-2">
            Menampilkan
            @if ($quizzes->total() > 0)
            {{ $quizzes->firstItem() }} sampai {{ $quizzes->lastItem() }} dari total {{ $quizzes->total() }} hasil
            @else
            0 sampai 0 dari total 0 hasil
            @endif
        </div>
        @endif
    </div>
</div>

<script>
    function openDeleteModal(id) {
        document.getElementById('deleteModal' + id).classList.remove('hidden');
    }

    function closeDeleteModal(id) {
        document.getElementById('deleteModal' + id).classList.add('hidden');
    }
</script>
@endsection