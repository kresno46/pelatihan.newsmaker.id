@extends('layouts.app')

@section('namePage', 'Kuis')

@section('content')
    <div class="bg-white dark:bg-gray-800 p-3 rounded-2xl">
        <form id="quizForm"
            action="{{ isset($quiz) ? route('quiz.update', ['slug' => $slug, 'sessionId' => $quiz->id]) : route('quiz.store', ['slug' => $slug]) }}"
            method="POST">
            @csrf

            @if (isset($quiz))
                @method('PUT')
            @endif

            <div class="flex items-center justify-between">
                <button type="button" onclick="openBackModal()"
                    class="inline-flex items-center gap-2 bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 py-2 px-6 rounded-lg text-gray-700 dark:text-gray-200 hover:text-gray-900 dark:hover:text-white transition">
                    <i class="fa-solid fa-chevron-left"></i>
                    <span class="hidden md:block">Kembali</span>
                </button>

                <button type="button" onclick="openSubmitModal()"
                    class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white py-2 px-6 rounded-lg font-semibold transition">
                    <i class="fa-solid fa-plus"></i>
                    <span class="hidden md:block">Simpan</span>
                </button>
            </div>

            {{-- Judul dan Durasi --}}
            <div class="space-y-4 mt-4">
                {{-- Judul Kuis --}}
                <div>
                    <label for="title" class="block text-gray-700 dark:text-gray-200">Judul Kuis</label>
                    <input type="text" id="title" name="title"
                        class="w-full px-3 py-2 rounded-lg border-2 border-gray-300 dark:border-gray-700 focus:border-blue-500 dark:focus:border-blue-400 dark:bg-gray-800 dark:text-gray-100"
                        value="{{ old('title', $quiz->title ?? '') }}" required>
                    @error('title')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Durasi Kuis --}}
                <div>
                    <label for="duration" class="block text-gray-700 dark:text-gray-200">Durasi Kuis (Menit)</label>
                    <input type="number" id="duration" name="duration"
                        class="w-full px-3 py-2 rounded-lg border-2 border-gray-300 dark:border-gray-700 focus:border-blue-500 dark:focus:border-blue-400 dark:bg-gray-800 dark:text-gray-100"
                        value="{{ old('duration', $quiz->duration ?? '') }}" required>
                    @error('duration')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Soal --}}
            <div class="mt-10 rounded-lg space-y-4">
                <div class="text-center">
                    <h1 class="text-xl font-bold text-gray-900 dark:text-gray-100">Daftar Soal</h1>
                </div>

                <div id="question-container" class="space-y-4">
                    @if ($questions && count($questions) > 0)
                        @foreach ($questions as $index => $q)
                            <div
                                class="bg-gray-200 dark:bg-gray-700 rounded-lg p-2 space-y-4 relative border-2 border-gray-400 dark:border-gray-500">
                                <button type="button" onclick="removeQuestion(this)"
                                    class="absolute top-2 right-2 bg-red-500 text-white rounded-full w-8 h-8 flex items-center justify-center hover:bg-red-600 transition">
                                    <i class="fa-solid fa-trash"></i>
                                </button>

                                <div>
                                    <label class="block text-gray-700 dark:text-gray-200">Soal</label>
                                    <input type="text" name="questions[{{ $index }}][question]"
                                        class="w-full px-3 py-2 rounded-lg border-2 border-gray-300 dark:border-gray-700 focus:border-blue-500 dark:focus:border-blue-400 dark:bg-gray-800 dark:text-gray-100"
                                        value="{{ old('questions.' . $index . '.question', $q['question']) }}" required>
                                    @error('questions.' . $index . '.question')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    @foreach (['a', 'b', 'c', 'd'] as $option)
                                        <div>
                                            <label class="block text-gray-700 dark:text-gray-200">Jawaban
                                                {{ strtoupper($option) }}</label>
                                            <input type="text"
                                                name="questions[{{ $index }}][option_{{ $option }}]"
                                                class="w-full px-3 py-2 rounded-lg border-2 border-gray-300 dark:border-gray-700 focus:border-blue-500 dark:focus:border-blue-400 dark:bg-gray-800 dark:text-gray-100"
                                                value="{{ old('questions.' . $index . '.option_' . $option, $q['option_' . $option]) }}"
                                                required>
                                            @error('questions.' . $index . '.option_' . $option)
                                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    @endforeach
                                </div>

                                <div class="mt-2">
                                    <label class="block text-gray-700 dark:text-gray-200 font-semibold">Jawaban
                                        Benar</label>
                                    <div class="flex gap-4 mt-1">
                                        @foreach (['A', 'B', 'C', 'D'] as $option)
                                            <label class="inline-flex items-center gap-1">
                                                <input type="radio" name="questions[{{ $index }}][correct_option]"
                                                    value="{{ $option }}"
                                                    {{ old('questions.' . $index . '.correct_option', $q['correct_option']) == $option ? 'checked' : '' }}
                                                    required>
                                                <span>{{ $option }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                    @error('questions.' . $index . '.correct_option')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        @endforeach
                    @else
                        {{-- Kalau belum ada soal, tampilkan satu soal kosong default --}}
                        <div
                            class="bg-gray-200 dark:bg-gray-700 rounded-lg p-2 space-y-4 relative border-2 border-gray-400 dark:border-gray-500">
                            <button type="button" onclick="removeQuestion(this)"
                                class="absolute top-2 right-2 bg-red-500 text-white rounded-full w-8 h-8 flex items-center justify-center hover:bg-red-600 transition hidden">
                                <i class="fa-solid fa-trash"></i>
                            </button>

                            <div>
                                <label class="block text-gray-700 dark:text-gray-200">Soal</label>
                                <input type="text" name="questions[0][question]"
                                    class="w-full px-3 py-2 rounded-lg border-2 border-gray-300 dark:border-gray-700 focus:border-blue-500 dark:focus:border-blue-400 dark:bg-gray-800 dark:text-gray-100"
                                    value="{{ old('questions.0.question') }}" required>
                                @error('questions.0.question')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @foreach (['a', 'b', 'c', 'd'] as $option)
                                    <div>
                                        <label class="block text-gray-700 dark:text-gray-200">Jawaban
                                            {{ strtoupper($option) }}</label>
                                        <input type="text" name="questions[0][option_{{ $option }}]"
                                            class="w-full px-3 py-2 rounded-lg border-2 border-gray-300 dark:border-gray-700 focus:border-blue-500 dark:focus:border-blue-400 dark:bg-gray-800 dark:text-gray-100"
                                            value="{{ old('questions.0.option_' . $option) }}" required>
                                        @error('questions.0.option_' . $option)
                                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                @endforeach
                            </div>

                            <div class="mt-2">
                                <label class="block text-gray-700 dark:text-gray-200 font-semibold">Jawaban Benar</label>
                                <div class="flex gap-4 mt-1">
                                    @foreach (['A', 'B', 'C', 'D'] as $option)
                                        <label class="inline-flex items-center gap-1">
                                            <input type="radio" name="questions[0][correct_option]"
                                                value="{{ $option }}"
                                                {{ old('questions.0.correct_option') == $option ? 'checked' : '' }}
                                                required>
                                            <span>{{ $option }}</span>
                                        </label>
                                    @endforeach
                                </div>
                                @error('questions.0.correct_option')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    @endif
                </div>

                {{-- Tombol Tambah Soal --}}
                <button type="button" onclick="addQuestion()"
                    class="w-full p-7 border-2 border-gray-200 dark:border-gray-600 rounded-lg flex flex-col justify-center items-center gap-4 hover:bg-gray-300 dark:hover:bg-gray-600 transition">
                    <div class="bg-gray-200 dark:bg-gray-700 w-fit px-3 py-2 rounded-full">
                        <i class="fa-solid fa-plus fa-2x text-gray-900 dark:text-gray-100"></i>
                    </div>
                    <h1 class="text-gray-900 dark:text-gray-100">Tambah Pertanyaan</h1>
                </button>
            </div>
        </form>
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
                    class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700 transition">Ya,
                    Kembali</a>
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
        let questionIndex = 1; // Mulai dari 1 karena 0 sudah ada di HTML

        function addQuestion() {
            const container = document.getElementById('question-container');

            const div = document.createElement('div');
            div.classList.add(
                'bg-gray-200', 'dark:bg-gray-700', 'rounded-lg', 'p-2', 'space-y-4', 'relative', 'border-2',
                'border-gray-400', 'dark:border-gray-500'
            );

            div.innerHTML = `
        <button type="button" onclick="removeQuestion(this)" class="absolute top-2 right-2 bg-red-500 text-white rounded-full w-8 h-8 flex items-center justify-center hover:bg-red-600 transition">
            <i class="fa-solid fa-trash"></i>
        </button>

        <div>
            <label class="block text-gray-700 dark:text-gray-200">Soal</label>
            <input type="text" name="questions[${questionIndex}][question]" class="w-full px-3 py-2 rounded-lg border-2 border-gray-300 dark:border-gray-700 focus:border-blue-500 dark:focus:border-blue-400 dark:bg-gray-800 dark:text-gray-100" required>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-gray-700 dark:text-gray-200">Jawaban A</label>
                <input type="text" name="questions[${questionIndex}][option_a]" class="w-full px-3 py-2 rounded-lg border-2 border-gray-300 dark:border-gray-700 focus:border-blue-500 dark:focus:border-blue-400 dark:bg-gray-800 dark:text-gray-100" required>
            </div>
            <div>
                <label class="block text-gray-700 dark:text-gray-200">Jawaban B</label>
                <input type="text" name="questions[${questionIndex}][option_b]" class="w-full px-3 py-2 rounded-lg border-2 border-gray-300 dark:border-gray-700 focus:border-blue-500 dark:focus:border-blue-400 dark:bg-gray-800 dark:text-gray-100" required>
            </div>
            <div>
                <label class="block text-gray-700 dark:text-gray-200">Jawaban C</label>
                <input type="text" name="questions[${questionIndex}][option_c]" class="w-full px-3 py-2 rounded-lg border-2 border-gray-300 dark:border-gray-700 focus:border-blue-500 dark:focus:border-blue-400 dark:bg-gray-800 dark:text-gray-100" required>
            </div>
            <div>
                <label class="block text-gray-700 dark:text-gray-200">Jawaban D</label>
                <input type="text" name="questions[${questionIndex}][option_d]" class="w-full px-3 py-2 rounded-lg border-2 border-gray-300 dark:border-gray-700 focus:border-blue-500 dark:focus:border-blue-400 dark:bg-gray-800 dark:text-gray-100" required>
            </div>
        </div>

        <div class="mt-2">
            <label class="block text-gray-700 dark:text-gray-200 font-semibold">Jawaban Benar</label>
            <div class="flex gap-4 mt-1">
                <label class="inline-flex items-center gap-1">
                    <input type="radio" name="questions[${questionIndex}][correct_option]" value="A" required>
                    <span>A</span>
                </label>
                <label class="inline-flex items-center gap-1">
                    <input type="radio" name="questions[${questionIndex}][correct_option]" value="B" required>
                    <span>B</span>
                </label>
                <label class="inline-flex items-center gap-1">
                    <input type="radio" name="questions[${questionIndex}][correct_option]" value="C" required>
                    <span>C</span>
                </label>
                <label class="inline-flex items-center gap-1">
                    <input type="radio" name="questions[${questionIndex}][correct_option]" value="D" required>
                    <span>D</span>
                </label>
            </div>
        </div>
    `;

            container.appendChild(div);
            questionIndex++;
        }

        function removeQuestion(button) {
            const questionDiv = button.closest('div.relative');
            if (questionDiv) {
                questionDiv.remove();
            }
        }

        // Modal Back
        function openBackModal() {
            document.getElementById('backModal').classList.remove('hidden');
        }

        function closeBackModal() {
            document.getElementById('backModal').classList.add('hidden');
        }

        function confirmBack() {
            closeBackModal();
            window.history.back(); // atau ganti sesuai kebutuhan: window.location.href = 'url_tujuan';
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
