@extends('layouts.app')

@section('namePage', 'Kuis')

@section('content')
    <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl">
        <form id="quizForm"
            action="{{ isset($quiz) ? route('quiz.update', [$folderSlug, $ebookSlug, $quiz->id]) : route('quiz.store', [$folderSlug, $ebookSlug]) }}"
            method="POST" class="space-y-6">
            @csrf
            @if (isset($quiz))
                @method('PUT')
            @endif

            <div class="flex justify-between items-center">
                <button type="button" onclick="openBackModal()"
                    class="flex items-center gap-2 px-4 py-2 bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-800 dark:text-gray-100 rounded transition">
                    <i class="fa-solid fa-chevron-left"></i>
                    <span class="hidden md:block">Kembali</span>
                </button>

                <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100">
                    {{ isset($quiz) ? 'Ubah Kuis' : 'Tambah Kuis' }}
                </h2>

                <button type="button" onclick="openSubmitModal()"
                    class="flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded transition">
                    <i class="fa-solid {{ isset($quiz) ? 'fa-floppy-disk' : 'fa-plus' }}"></i>
                    <span class="hidden md:block">{{ isset($quiz) ? 'Perbarui' : 'Simpan' }}</span>
                </button>
            </div>

            <div class="space-y-4">
                <div>
                    <label for="title" class="block font-medium text-gray-700 dark:text-gray-300">Judul</label>
                    <input type="text" id="title" name="title" value="{{ old('title', $quiz->title ?? '') }}"
                        class="w-full rounded p-2 bg-gray-100 dark:bg-gray-900 text-gray-900 dark:text-white border border-gray-300 dark:border-gray-700 focus:ring-2 focus:ring-blue-500"
                        required>
                    @error('title')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="duration" class="block font-medium text-gray-700 dark:text-gray-300">Durasi (menit)</label>
                    <input type="number" id="duration" name="duration" min="1"
                        value="{{ old('duration', $quiz->duration ?? '') }}"
                        class="w-full rounded p-2 bg-gray-100 dark:bg-gray-900 text-gray-900 dark:text-white border border-gray-300 dark:border-gray-700 focus:ring-2 focus:ring-blue-500"
                        required>
                    @error('duration')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </form>
    </div>

    @if (isset($quiz))
        <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl mt-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Daftar Pertanyaan</h3>
                <a href="{{ route('quiz.add-question-index', [$folderSlug, $ebookSlug, $quiz->id]) }}"
                    class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded text-sm">Tambah Soal</a>
            </div>

            @forelse ($questions as $item)
                <div class="border border-gray-300 dark:border-gray-700 p-4 rounded mb-4 space-y-4">
                    <div>{!! $item['question'] !!}</div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        @foreach (['A', 'B', 'C', 'D'] as $opt)
                            <div>
                                <label class="block text-sm font-semibold">Jawaban {{ $opt }}</label>
                                <input type="text" disabled value="{{ $item['option_' . strtolower($opt)] }}"
                                    class="w-full p-2 rounded bg-gray-100 dark:bg-gray-900 text-gray-700 dark:text-white {{ $item['correct_option'] === $opt ? 'border-2 border-green-500' : 'border border-gray-300 dark:border-gray-700' }}">
                            </div>
                        @endforeach
                    </div>
                    <div class="flex justify-end gap-2">
                        <a href="{{ route('quiz.edit-question', [$folderSlug, $ebookSlug, $quiz->id, $item['id']]) }}"
                            class="px-3 py-1 bg-yellow-500 hover:bg-yellow-600 text-white rounded text-sm">
                            <i class="fa-solid fa-pen"></i> Edit
                        </a>
                        <button type="button" onclick="openDeleteModal({{ $item['id'] }})"
                            class="px-3 py-1 bg-red-500 hover:bg-red-600 text-white rounded text-sm">
                            <i class="fa-solid fa-trash"></i> Hapus
                        </button>
                    </div>
                </div>
            @empty
                <div class="text-gray-700 dark:text-gray-300">Belum ada pertanyaan.</div>
            @endforelse
        </div>
    @endif

    {{-- Modal Kembali --}}
    <div id="backModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50 hidden">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-2xl max-w-md w-full p-6">
            <h3 class="text-xl font-bold text-red-500 mb-4"><i class="fa-solid fa-triangle-exclamation mr-2"></i>Konfirmasi
                Kembali</h3>
            <p class="text-gray-700 dark:text-gray-300 mb-6">Apakah Anda yakin ingin kembali? Data yang belum disimpan akan
                hilang.</p>
            <div class="flex justify-end gap-3">
                <button onclick="closeBackModal()"
                    class="px-4 py-2 bg-gray-300 hover:bg-gray-400 rounded dark:bg-gray-600 dark:text-white">Batal</button>
                <a href="{{ route('ebook.show', [$folderSlug, $ebookSlug]) }}"
                    class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded">Ya, Kembali</a>
            </div>
        </div>
    </div>

    {{-- Modal Simpan --}}
    <div id="submitModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50 hidden">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-2xl max-w-md w-full p-6">
            <h3 class="text-xl font-bold text-blue-600 mb-4"><i class="fa-solid fa-circle-check mr-2"></i>Konfirmasi Simpan
            </h3>
            <p class="text-gray-700 dark:text-gray-300 mb-6">Apakah Anda yakin ingin menyimpan kuis ini?</p>
            <div class="flex justify-end gap-3">
                <button onclick="closeSubmitModal()"
                    class="px-4 py-2 bg-gray-300 hover:bg-gray-400 rounded dark:bg-gray-600 dark:text-white">Batal</button>
                <button onclick="submitForm()" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded">Ya,
                    Simpan</button>
            </div>
        </div>
    </div>

    {{-- Modal Hapus Soal --}}
    <div id="deleteModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50 hidden">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-2xl max-w-md w-full p-6">
            <h3 class="text-xl font-bold text-red-600 mb-4"><i class="fa-solid fa-trash mr-2"></i>Konfirmasi Hapus</h3>
            <p class="text-gray-700 dark:text-gray-300 mb-6">Apakah Anda yakin ingin menghapus pertanyaan ini?</p>
            <form id="deleteForm" method="POST" class="flex justify-end gap-3">
                @csrf
                @method('DELETE')
                <button type="button" onclick="closeDeleteModal()"
                    class="px-4 py-2 bg-gray-300 hover:bg-gray-400 rounded dark:bg-gray-600 dark:text-white">Batal</button>
                <button type="submit" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded">Ya, Hapus</button>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        function openBackModal() {
            document.getElementById('backModal').classList.remove('hidden');
        }

        function closeBackModal() {
            document.getElementById('backModal').classList.add('hidden');
        }

        function openSubmitModal() {
            document.getElementById('submitModal').classList.remove('hidden');
        }

        function closeSubmitModal() {
            document.getElementById('submitModal').classList.add('hidden');
        }

        function submitForm() {
            document.getElementById('quizForm').submit();
        }

        function openDeleteModal(id) {
            const url = `{{ route('quiz.delete-question', [$folderSlug, $ebookSlug, $quiz->id ?? 0, '__ID__']) }}`.replace(
                '__ID__', id);
            document.getElementById('deleteForm').setAttribute('action', url);
            document.getElementById('deleteModal').classList.remove('hidden');
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.add('hidden');
        }
    </script>
@endsection
