@extends('layouts.app')

@section('namePage', $quiz->title)

@section('content')
<div class="bg-white dark:bg-black rounded-xl p-5 space-y-6">

    <!-- Header -->
    <div class="text-center space-y-2">
        <div class="flex justify-between items-center mb-5">
            {{-- Button Kembali --}}
            <a href="{{ route('quiz.index') }}"
                class="text-sm font-medium bg-gray-200 rounded-lg py-2 px-3 flex items-center gap-2 ">
                <i class="fa-solid fa-arrow-left"></i>
                <span class="hidden md:block">Kembali</span>
            </a>

            {{-- Title --}}
            <h1 class="text-3xl font-bold text-gray-800 dark:text-white">{{ $quiz->title }}</h1>

            <!-- Button Tambah Soal -->
            <a href="{{ route('question.create', $quiz->slug) }}"
                class="inline-block px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-lg text-sm">
                Tambah Soal
            </a>
        </div>

        <p class="text-gray-600 dark:text-gray-300">
            {{ $quiz->description ?? 'Tidak ada deskripsi.' }}
        </p>

        <div class="flex items-center justify-center gap-4 text-gray-700 dark:text-gray-300 text-sm">
            <span class="inline-block px-2 py-1 text-sm font-semibold rounded-full
                {{ $quiz->status === 'public' ? 'bg-green-200 text-green-800' : 'bg-red-200 text-red-800' }}">
                {{ ucfirst($quiz->status) }}
            </span>
            -
            <span>{{ $quiz->questions->count() }} soal</span>
        </div>
    </div>

    <!-- Soal -->
    <div class="space-y-4">
        @forelse ($questions as $item)
        <div class="border border-gray-300 dark:border-gray-700 rounded-lg p-4 bg-gray-50 dark:bg-gray-900">
            <div class="flex justify-between items-start mb-2">
                <h3 class="font-semibold text-gray-800 dark:text-gray-100">Pertanyaan {{ $loop->iteration }}:</h3>

                <div class="flex gap-2">
                    <a href="{{ route('question.edit', [$quiz->slug, $item->id]) }}"
                        class="text-sm bg-yellow-400 hover:bg-yellow-500 text-black px-3 py-1 rounded transition">Edit</a>
                    <button onclick="openDeleteModal({{ $item->id }})"
                        class="text-sm bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded transition">
                        Hapus
                    </button>
                </div>
            </div>

            <p class="mb-3 text-gray-700 dark:text-gray-300">{{ $item->question }}</p>

            <ul class="text-sm text-gray-800 dark:text-gray-200 space-y-1">
                <li><strong>A.</strong> {{ $item->option_a }}</li>
                <li><strong>B.</strong> {{ $item->option_b }}</li>
                <li><strong>C.</strong> {{ $item->option_c }}</li>
                <li><strong>D.</strong> {{ $item->option_d }}</li>
            </ul>

            <p class="mt-2 text-sm text-green-600 dark:text-green-400 font-semibold">Jawaban Benar: {{
                $item->correct_option }}</p>
        </div>
        @empty
        <p class="text-center text-gray-500 dark:text-gray-400">Belum ada pertanyaan ditambahkan.</p>
        @endforelse
    </div>
</div>

<!-- Modal Hapus -->
<div id="deleteModal"
    class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur flex items-center justify-center hidden px-5">
    <div class="bg-white dark:bg-gray-800 rounded p-6 w-full max-w-md text-gray-900 dark:text-gray-100">
        <h3 class="text-lg font-semibold mb-4">Konfirmasi Hapus</h3>
        <p class="mb-6">Apakah Anda yakin ingin menghapus pertanyaan ini? Tindakan ini tidak dapat dibatalkan.</p>
        <div class="flex justify-end gap-4">
            <button onclick="closeDeleteModal()"
                class="px-4 py-2 bg-gray-300 dark:bg-gray-700 rounded hover:bg-gray-400 dark:hover:bg-gray-600 transition">Batal</button>
            <form id="deleteForm" method="POST" class="inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded transition">Ya,
                    Hapus</button>
            </form>
        </div>
    </div>
</div>

<script>
    function openDeleteModal(questionId) {
        const modal = document.getElementById('deleteModal');
        const form = document.getElementById('deleteForm');
        // Set action URL dengan quiz slug dan question id
        form.action = `{{ url('quiz/' . $quiz->slug . '/soal') }}/${questionId}`;
        modal.classList.remove('hidden');
    }

    function closeDeleteModal() {
        const modal = document.getElementById('deleteModal');
        modal.classList.add('hidden');
    }
</script>
@endsection