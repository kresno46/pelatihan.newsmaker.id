@extends('layouts.app')

@section('namePage', 'Kuis')

@section('content')
    <div class="bg-white dark:bg-gray-800 px-6 py-3 rounded-2xl">
        <form id="quizForm"
            action="{{ isset($quiz) ? route('quiz.update', ['slug' => $slug, 'sessionId' => $quiz->id]) : route('quiz.store', ['slug' => $slug]) }}"
            method="POST" class="space-y-5 mb-10">
            @csrf
            @if (isset($quiz))
                @method('PUT')
            @endif

            {{-- Header --}}
            <div class="flex justify-between items-center my-0">
                <button type="button" onclick="openBackModal()"
                    class="inline-flex items-center gap-2 bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 py-2 px-6 rounded-lg text-gray-700 dark:text-gray-200 hover:text-gray-900 dark:hover:text-white transition">
                    <i class="fa-solid fa-chevron-left"></i><span class="hidden md:block">Kembali</span>
                </button>

                <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100">
                    {{ isset($quiz) ? 'Ubah Quiz' : 'Tambah Quiz' }}
                </h2>

                <button type="button" onclick="openSubmitModal()"
                    class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white py-2 px-6 rounded-lg font-semibold transition">
                    {!! isset($quiz)
                        ? '<i class="fa-solid fa-floppy-disk"></i> Perbarui'
                        : '<i class="fa-solid fa-plus"></i> Simpan' !!}
                </button>
            </div>

            {{-- Form Fields --}}
            <div class="space-y-3">
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Judul</label>
                    <input type="text" id="title" name="title"
                        class="mt-1 block w-full bg-gray-300 dark:bg-gray-900 rounded-lg p-2"
                        value="{{ old('title', $quiz->title ?? '') }}" required />
                    <div class="text-red-500 text-xs mt-2">{{ $errors->first('title') }}</div>
                </div>

                <div>
                    <label for="duration" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Durasi
                        (menit)</label>
                    <input type="number" id="duration" name="duration"
                        class="mt-1 block w-full bg-gray-300 dark:bg-gray-900 rounded-lg p-2"
                        value="{{ old('duration', $quiz->duration ?? '') }}" min="1" required />
                    <div class="text-red-500 text-xs mt-2">{{ $errors->first('duration') }}</div>
                </div>
            </div>
        </form>
    </div>

    <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl mt-5">
        {{-- Daftar Soal --}}
        @if (isset($quiz))
            <div class="mb-3 space-y-5">
                <div class="flex justify-between items-center">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Daftar Soal</h3>
                    <div class="flex items-center gap-2">
                        @if (session('Alert'))
                            <div x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 5000)"
                                class="text-xs bg-green-100 dark:bg-green-200 text-green-800 py-1 px-3 rounded-lg">
                                {{ session('Alert') }}
                            </div>
                        @endif
                        <a href="{{ route('quiz.add-question-index', ['slug' => $slug, 'sessionId' => $quiz->id]) }}"
                            class="px-3 py-2 bg-green-600 text-white rounded hover:bg-green-700 transition text-sm">
                            Tambah Soal
                        </a>
                    </div>
                </div>

                <div class="space-y-5 rounded-lg border-gray-100/10">
                    @forelse ($questions as $item)
                        <div class="space-y-3 rounded-lg">
                            <div>
                                {{-- <label>Pertanyaan</label> --}}
                                <div>
                                    {!! $item['question'] !!}
                                </div>
                            </div>

                            {{-- Opsi Jawaban --}}
                            @php $correct = $item['correct_option']; @endphp
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                @foreach (['A', 'B', 'C', 'D'] as $opt)
                                    <div>
                                        <label>Jawaban {{ $opt }}</label>
                                        <input type="text"
                                            class="w-full bg-gray-300 dark:bg-gray-900 rounded-lg p-2 pr-24 {{ $correct === $opt ? 'border-2 border-green-500' : '' }}"
                                            value="{{ $item['option_' . strtolower($opt)] }}" disabled>
                                    </div>
                                @endforeach
                            </div>

                            <div class="flex flex-col md:flex-row md:justify-between gap-2 mt-2">
                                <div class="flex items-center gap-2">
                                    <span class="inline-block w-4 h-4 rounded bg-green-500"></span>
                                    <span class="text-sm">Jawaban yang benar</span>
                                </div>
                                <div class="flex gap-2">
                                    {{-- Tombol Edit --}}
                                    <a href="{{ route('quiz.edit-question', ['slug' => $slug, 'sessionId' => $quiz['id'], 'questionId' => $item['id']]) }}"
                                        class="inline-flex items-center gap-1 px-3 py-1 bg-yellow-500 text-white text-sm rounded hover:bg-yellow-600 transition">
                                        <i class="fa-solid fa-pen"></i> Edit
                                    </a>

                                    {{-- Tombol Hapus --}}
                                    <button type="button" onclick="openDeleteModal({{ $item['id'] }})"
                                        class="inline-flex items-center gap-1 px-3 py-1 bg-red-500 text-white text-sm rounded hover:bg-red-600 transition">
                                        <i class="fa-solid fa-trash"></i> Hapus
                                    </button>
                                </div>
                            </div>
                        </div>

                        <hr class="border-gray-600 my-5">
                    @empty
                        <div class="bg-slate-800 p-4 border-2 border-gray-700 rounded-lg">Belum ada pertanyaan</div>
                    @endforelse
                </div>
            </div>
        @endif
    </div>

    {{-- Modal Back --}}
    <div id="backModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg max-w-md w-full">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Konfirmasi Kembali</h2>
            <p class="mb-6 text-gray-700 dark:text-gray-300">Apakah Anda yakin ingin kembali? Data yang belum disimpan akan
                hilang.</p>
            <div class="flex justify-end gap-4">
                <button onclick="closeBackModal()"
                    class="px-4 py-2 bg-gray-300 dark:bg-gray-700 rounded hover:bg-gray-400 dark:hover:bg-gray-600 transition">Batal</button>
                <a href="{{ route('ebook.show', ['slug' => $slug]) }}"
                    class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700 transition">Ya, Kembali</a>
            </div>
        </div>
    </div>

    {{-- Modal Submit --}}
    <div id="submitModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg max-w-md w-full">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Konfirmasi Simpan</h2>
            <p class="mb-6 text-gray-700 dark:text-gray-300">Apakah Anda yakin ingin menyimpan kuis ini?</p>
            <div class="flex justify-end gap-4">
                <button onclick="closeSubmitModal()"
                    class="px-4 py-2 bg-gray-300 dark:bg-gray-700 rounded hover:bg-gray-400 dark:hover:bg-gray-600 transition">Batal</button>
                <button onclick="confirmSubmit()"
                    class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">Ya, Simpan</button>
            </div>
        </div>
    </div>

    {{-- Modal Hapus Soal --}}
    <div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg max-w-md w-full">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Konfirmasi Hapus</h2>
            <p class="mb-6 text-gray-700 dark:text-gray-300">Apakah Anda yakin ingin menghapus pertanyaan ini?</p>
            <form id="deleteForm" method="POST">
                @csrf @method('DELETE')
                <div class="flex justify-end gap-4">
                    <button type="button" onclick="closeDeleteModal()"
                        class="px-4 py-2 bg-gray-300 dark:bg-gray-700 rounded hover:bg-gray-400 dark:hover:bg-gray-600 transition">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700 transition">Ya,
                        Hapus</button>
                </div>
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

        function confirmSubmit() {
            document.getElementById('quizForm').submit();
        }

        function openDeleteModal(id) {
            const url =
                `{{ route('quiz.delete-question', ['slug' => $slug, 'sessionId' => $quiz['id'], 'questionId' => 'QUESTION_ID']) }}`
                .replace('QUESTION_ID', id);
            document.getElementById('deleteForm').setAttribute('action', url);
            document.getElementById('deleteModal').classList.remove('hidden');
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.add('hidden');
        }
    </script>
@endsection
