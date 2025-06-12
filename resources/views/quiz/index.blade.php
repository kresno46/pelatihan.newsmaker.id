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

            <div class="flex justify-between items-center my-0">
                <button type="button" onclick="openBackModal()"
                    class="inline-flex items-center gap-2 bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 py-2 px-6 rounded-lg text-gray-700 dark:text-gray-200 hover:text-gray-900 dark:hover:text-white transition">
                    <i class="fa-solid fa-chevron-left"></i>
                    <span class="hidden md:block">Kembali</span>
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

        <hr class="border-gray-600">

        @if (isset($quiz))
            <div class="mt-10 mb-3 space-y-5">
                <div class="flex justify-between items-center">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Daftar Soal</h3>
                    <div>
                        @if (isset($Alert))
                            <div class="text-green-500 text-xs">{{ $Alert }}</div>
                        @endif
                        <a href="{{ route('quiz.add-question-index', ['slug' => $slug, 'sessionId' => $quiz->id]) }}"
                            class="px-3 py-2 bg-green-600 text-white rounded hover:bg-green-700 transition text-sm">
                            Tambah Soal
                        </a>
                    </div>
                </div>

                <div class="">
                    @forelse ($questions as $item)
                        <div class="space-y-3 bg-slate-800 border-2 border-gray-700 p-4 rounded-lg">
                            <div>
                                <label for="question">Pertanyaan</label>
                                <input type="text" class="w-full bg-gray-300 dark:bg-gray-900 rounded-lg p-2"
                                    name="question" disabled></input>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                <div>
                                    <label for="option_a">Jawaban A</label>
                                    <input type="text" class="w-full bg-gray-300 dark:bg-gray-900 rounded-lg p-2"
                                        name="option_a" disabled></input>
                                </div>
                                <div>
                                    <label for="option_b">Jawaban B</label>
                                    <input type="text" class="w-full bg-gray-300 dark:bg-gray-900 rounded-lg p-2"
                                        name="option_a" disabled></input>
                                </div>
                                <div>
                                    <label for="option_c">Jawaban C</label>
                                    <input type="text" class="w-full bg-gray-300 dark:bg-gray-900 rounded-lg p-2"
                                        name="option_c" disabled></input>
                                </div>
                                <div>
                                    <label for="option_d">Jawaban D</label>
                                    <input type="text" class="w-full bg-gray-300 dark:bg-gray-900 rounded-lg p-2"
                                        name="option_d" disabled></input>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="space-y-3 bg-slate-800 border-2 border-gray-700 p-4 rounded-lg">
                            Belum ada pertanyaan
                        </div>
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
@endsection

@section('scripts')
    <script>
        // Modal Back
        function openBackModal() {
            document.getElementById('backModal').classList.remove('hidden');
        }

        function closeBackModal() {
            document.getElementById('backModal').classList.add('hidden');
        }

        function confirmBack() {
            closeBackModal();
            window.history.back();
        }

        // Modal Submit
        function openSubmitModal() {
            document.getElementById('submitModal').classList.remove('hidden');
        }

        function closeSubmitModal() {
            document.getElementById('submitModal').classList.add('hidden');
        }

        function confirmSubmit() {
            closeSubmitModal();
            document.getElementById('quizForm').submit();
        }
    </script>
@endsection
