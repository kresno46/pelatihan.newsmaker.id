@extends('layouts.app')

@section('namePage', 'Edit Kuis')

@section('content')
    <header class="w-full bg-white dark:bg-gray-800 shadow rounded-lg mb-5 p-4 sm:p-6">
        <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
            <div>
                <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ $session->title }}</h2>
                <div class="flex items-center mt-1 gap-2">
                    <p class="text-sm text-gray-600 dark:text-gray-400"><Strong>Tipe Soal:</Strong> {{ $session->tipe }}
                    </p> - <p class="text-sm text-gray-600 dark:text-gray-400">{{ $session->duration }} Menit</p>
                </div>
            </div>

            <div class="flex items-center" x-data="{ open: {{ $errors->any() ? 'true' : 'false' }} }" x-cloak>
                @if (session('success'))
                    <div x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 5000)"
                        class="text-xs bg-green-100 dark:bg-green-200 text-green-800 py-1 px-3 rounded-lg mr-3">
                        {{ session('success') }}
                    </div>
                @endif

                <button type="button" @click="open = true"
                    class="bg-blue-500 px-4 py-2 text-sm w-full hover:bg-blue-600 text-white rounded transition-all text-center">
                    Edit Sesi
                </button>

                {{-- Modal Edit Sesi --}}
                <div x-show="open" x-transition @keydown.escape.window="open=false"
                    class="fixed inset-0 z-50 flex items-center justify-center p-4">
                    <div class="absolute inset-0 bg-black/50" @click="open=false"></div>
                    <div class="relative w-full max-w-lg bg-white dark:bg-gray-800 rounded-xl shadow-xl">
                        <div class="flex items-center justify-between px-5 py-4 border-b dark:border-gray-700">
                            <h3 class="text-base font-semibold text-gray-900 dark:text-gray-100">Edit Sesi Post Test</h3>
                            <button type="button" @click="open=false"
                                class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">✕</button>
                        </div>

                        <form action="{{ route('posttest.update', $session) }}" method="POST" class="p-5">
                            @csrf @method('PUT')
                            <div class="space-y-4">
                                <div>
                                    <label for="title" class="block font-medium text-gray-900 dark:text-gray-100">Judul
                                        Post Test</label>
                                    <input type="text" name="title" id="title"
                                        value="{{ old('title', $session->title) }}"
                                        class="w-full border rounded p-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('title') border-red-500 dark:border-red-400 @enderror">
                                    @error('title')
                                        <p class="text-red-600 dark:text-red-400 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="duration" class="block font-medium text-gray-900 dark:text-gray-100">Durasi
                                        (menit)</label>
                                    <input type="number" min="1" name="duration" id="duration"
                                        value="{{ old('duration', $session->duration) }}"
                                        class="w-full border rounded p-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('duration') border-red-500 dark:border-red-400 @enderror">
                                    @error('duration')
                                        <p class="text-red-600 dark:text-red-400 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="w-full flex flex-col gap-2">
                                    <label for="status"
                                        class="block font-medium text-gray-900 dark:text-gray-100">Status</label>
                                    <select name="status" id="status"
                                        class="w-full border rounded p-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 border-gray-400 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('status') border-red-500 dark:border-red-400 @enderror">
                                        <option value="" disabled {{ old('status') ? '' : 'selected' }}>Pilih status
                                        </option>
                                        <option value="1" {{ old('status') == '1' ? 'selected' : '' }}>Aktif</option>
                                        <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>Tidak Aktif
                                        </option>
                                    </select>
                                    @error('status')
                                        <p class="text-red-600 dark:text-red-400 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="w-full flex flex-col gap-2">
                                    <label for="tipe" class="block font-medium text-gray-900 dark:text-gray-100">Tipe
                                        Soal</label>
                                    <select name="tipe" id="tipe"
                                        class="w-full border rounded p-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('tipe') border-red-500 dark:border-red-400 @enderror">
                                        <option value="" disabled {{ old('tipe') ? '' : 'selected' }}>Pilih tipe
                                        </option>
                                        <option value="PATD"
                                            {{ old('tipe', $session->tipe) == 'PATD' ? 'selected' : '' }}>PATD</option>
                                        <option value="PATL"
                                            {{ old('tipe', $session->tipe) == 'PATL' ? 'selected' : '' }}>PATL
                                        </option>
                                    </select>
                                    @error('tipe')
                                        <p class="text-red-600 dark:text-red-400 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="mt-5 flex items-center justify-end gap-2">
                                <button type="button" @click="open=false"
                                    class="px-4 py-2 rounded border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700">Batal</button>
                                <button type="submit"
                                    class="px-4 py-2 rounded bg-blue-600 hover:bg-blue-700 text-white">Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>
                {{-- /Modal Edit Sesi --}}
            </div>
        </div>
    </header>

    {{-- ===== Daftar Soal ===== --}}
    <div class="mt-6 p-6 bg-white dark:bg-gray-800 rounded-xl shadow-md" x-data="{
        openQuestion: {{ $errors->hasBag('createQuestion') ? 'true' : 'false' }},
        openEdit: {{ $errors->hasBag('updateQuestion') ? 'true' : 'false' }},
        edit: {
            id: {{ old('question_id') ? (int) old('question_id') : 'null' }},
            question_text: @json(old('question_text')),
            option_a: @json(old('option_a')),
            option_b: @json(old('option_b')),
            option_c: @json(old('option_c')),
            option_d: @json(old('option_d')),
            correct_option: @json(old('correct_option')),
            action: null
        }
    }" x-cloak>
        <div class="flex items-center justify-between mb-3">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Daftar Soal</h3>

            @isset($session)
                <button type="button" @click="openQuestion = true"
                    class="bg-green-600 text-white px-3 py-2 rounded text-sm hover:bg-green-700">Tambah Soal</button>
            @endisset
        </div>

        @if ($session->questions->isEmpty())
            <p class="text-gray-500">Belum ada soal.</p>
        @else
            <div class="space-y-2">
                @foreach ($session->questions as $q)
                    <div
                        class="border border-gray-200 dark:border-gray-600 rounded-lg overflow-hidden bg-white dark:bg-gray-800">
                        <div class="flex items-stretch">
                            {{-- Nomor kiri --}}
                            <div
                                class="w-14 border-r bg-gray-200 border-gray-200 dark:border-gray-600 dark:bg-gray-700 text-center flex items-center justify-center font-semibold text-gray-800 dark:text-gray-100 select-none">
                                #{{ $loop->iteration }}
                            </div>

                            {{-- Konten kanan --}}
                            <div class="flex-1 p-4 space-y-4">
                                @isset($q->question)
                                    <div class="prose prose-sm dark:prose-invert max-w-none">{!! $q->question !!}</div>
                                @endisset

                                {{-- Opsi --}}
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-sm">
                                    {{-- A --}}
                                    <div
                                        class="px-3 py-2 rounded-md flex gap-2 {{ $q->correct_option === 'A' ? 'bg-green-100 text-green-800 border border-green-300 dark:bg-green-900/30 dark:text-green-300 dark:border-green-700' : 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-100' }}">
                                        <span class="font-semibold">A.</span>
                                        <span class="break-words">{{ $q->option_a }}</span>
                                        @if ($q->correct_option === 'A')
                                            <span class="sr-only">(Kunci)</span>
                                        @endif
                                    </div>

                                    {{-- B --}}
                                    <div
                                        class="px-3 py-2 rounded-md flex gap-2 {{ $q->correct_option === 'B' ? 'bg-green-100 text-green-800 border border-green-300 dark:bg-green-900/30 dark:text-green-300 dark:border-green-700' : 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-100' }}">
                                        <span class="font-semibold">B.</span>
                                        <span class="break-words">{{ $q->option_b }}</span>
                                        @if ($q->correct_option === 'B')
                                            <span class="sr-only">(Kunci)</span>
                                        @endif
                                    </div>

                                    {{-- C --}}
                                    <div
                                        class="px-3 py-2 rounded-md flex gap-2 {{ $q->correct_option === 'C' ? 'bg-green-100 text-green-800 border border-green-300 dark:bg-green-900/30 dark:text-green-300 dark:border-green-700' : 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-100' }}">
                                        <span class="font-semibold">C.</span>
                                        <span class="break-words">{{ $q->option_c }}</span>
                                        @if ($q->correct_option === 'C')
                                            <span class="sr-only">(Kunci)</span>
                                        @endif
                                    </div>

                                    {{-- D --}}
                                    <div
                                        class="px-3 py-2 rounded-md flex gap-2 {{ $q->correct_option === 'D' ? 'bg-green-100 text-green-800 border border-green-300 dark:bg-green-900/30 dark:text-green-300 dark:border-green-700' : 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-100' }}">
                                        <span class="font-semibold">D.</span>
                                        <span class="break-words">{{ $q->option_d }}</span>
                                        @if ($q->correct_option === 'D')
                                            <span class="sr-only">(Kunci)</span>
                                        @endif
                                    </div>
                                </div>

                                {{-- Aksi --}}
                                <div class="flex gap-3 pt-1">
                                    <button type="button" class="text-blue-600 text-sm hover:underline"
                                        @click="
                    openEdit = true;
                    edit = {
                      id: {{ $q->id }},
                      question_text: @js($q->question),
                      option_a: @js($q->option_a),
                      option_b: @js($q->option_b),
                      option_c: @js($q->option_c),
                      option_d: @js($q->option_d),
                      correct_option: @js($q->correct_option),
                      action: '{{ route('question.update', [$session, $q]) }}'
                    };
                    setTimeout(() => { window.fillEditEditor(edit.question_text); }, 0);
                  ">
                                        Edit
                                    </button>

                                    <form action="{{ route('question.destroy', [$session, $q]) }}" method="POST"
                                        onsubmit="return confirm('Hapus soal ini?')">
                                        @csrf @method('DELETE')
                                        <button class="text-red-600 text-sm hover:underline">Hapus</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        {{-- Modal Tambah Soal --}}
        <div x-show="openQuestion" x-transition @keydown.escape.window="openQuestion=false"
            class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="absolute inset-0 bg-black/50" @click="openQuestion=false"></div>
            <div class="relative w-full max-w-2xl bg-white dark:bg-gray-800 rounded-xl shadow-xl">
                <div class="flex items-center justify-between px-5 py-4 border-b dark:border-gray-700">
                    <h3 class="text-base font-semibold text-gray-900 dark:text-gray-100">Tambah Soal</h3>
                    <button type="button" @click="openQuestion=false"
                        class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">✕</button>
                </div>

                <form action="{{ route('question.store', $session) }}" method="POST" class="p-5">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label for="create-question-editor"
                                class="block font-medium text-gray-900 dark:text-gray-100">Pertanyaan</label>
                            <textarea name="question_text" id="create-question-editor" rows="3"
                                class="w-full border rounded p-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-green-600 @error('question_text', 'createQuestion') border-red-500 dark:border-red-400 @enderror">{{ old('question_text') }}</textarea>
                            @error('question_text', 'createQuestion')
                                <p class="text-red-600 dark:text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach (['a', 'b', 'c', 'd'] as $opt)
                                <div>
                                    <label for="option_{{ $opt }}"
                                        class="block font-medium text-gray-900 dark:text-gray-100">Opsi
                                        {{ strtoupper($opt) }}</label>
                                    <input type="text" name="option_{{ $opt }}"
                                        id="option_{{ $opt }}" value="{{ old('option_' . $opt) }}"
                                        class="w-full border rounded p-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-green-600 @error('option_' . $opt, 'createQuestion') border-red-500 dark:border-red-400 @enderror">
                                    @error('option_' . $opt, 'createQuestion')
                                        <p class="text-red-600 dark:text-red-400 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            @endforeach
                        </div>

                        <div>
                            <label for="correct_option" class="block font-medium text-gray-900 dark:text-gray-100">Kunci
                                Jawaban</label>
                            <select name="correct_option" id="correct_option"
                                class="w-full border rounded p-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-green-600 @error('correct_option', 'createQuestion') border-red-500 dark:border-red-400 @enderror">
                                <option value="">-- pilih --</option>
                                @foreach (['A', 'B', 'C', 'D'] as $k)
                                    <option value="{{ $k }}"
                                        {{ old('correct_option') === $k ? 'selected' : '' }}>{{ $k }}</option>
                                @endforeach
                            </select>
                            @error('correct_option', 'createQuestion')
                                <p class="text-red-600 dark:text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="mt-5 flex items-center justify-end gap-2">
                        <button type="button" @click="openQuestion=false"
                            class="px-4 py-2 rounded border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700">Batal</button>
                        <button type="submit"
                            class="px-4 py-2 rounded bg-green-600 hover:bg-green-700 text-white">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
        {{-- /Modal Tambah Soal --}}

        {{-- Modal Edit Soal --}}
        <div x-show="openEdit" x-transition @keydown.escape.window="openEdit=false"
            class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="absolute inset-0 bg-black/50" @click="openEdit=false"></div>
            <div class="relative w-full max-w-2xl bg-white dark:bg-gray-800 rounded-xl shadow-xl">
                <div class="flex items-center justify-between px-5 py-4 border-b dark:border-gray-700">
                    <h3 class="text-base font-semibold text-gray-900 dark:text-gray-100">Edit Soal</h3>
                    <button type="button" @click="openEdit=false"
                        class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">✕</button>
                </div>

                <form x-bind:action="edit.action" method="POST" class="p-5">
                    @csrf @method('PUT')
                    <input type="hidden" name="question_id" x-model="edit.id">

                    <div class="space-y-4">
                        <div>
                            <label for="edit-question-editor"
                                class="block font-medium text-gray-900 dark:text-gray-100">Pertanyaan</label>
                            <textarea name="question_text" id="edit-question-editor" rows="3"
                                class="w-full border rounded p-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-600 @error('question_text', 'updateQuestion') border-red-500 dark:border-red-400 @enderror"></textarea>
                            @error('question_text', 'updateQuestion')
                                <p class="text-red-600 dark:text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block font-medium text-gray-900 dark:text-gray-100">Opsi A</label>
                                <input type="text" name="option_a" x-model="edit.option_a"
                                    class="w-full border rounded p-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-600 @error('option_a', 'updateQuestion') border-red-500 dark:border-red-400 @enderror">
                                @error('option_a', 'updateQuestion')
                                    <p class="text-red-600 dark:text-red-400 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block font-medium text-gray-900 dark:text-gray-100">Opsi B</label>
                                <input type="text" name="option_b" x-model="edit.option_b"
                                    class="w-full border rounded p-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-600 @error('option_b', 'updateQuestion') border-red-500 dark:border-red-400 @enderror">
                                @error('option_b', 'updateQuestion')
                                    <p class="text-red-600 dark:text-red-400 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block font-medium text-gray-900 dark:text-gray-100">Opsi C</label>
                                <input type="text" name="option_c" x-model="edit.option_c"
                                    class="w-full border rounded p-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-600 @error('option_c', 'updateQuestion') border-red-500 dark:border-red-400 @enderror">
                                @error('option_c', 'updateQuestion')
                                    <p class="text-red-600 dark:text-red-400 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block font-medium text-gray-900 dark:text-gray-100">Opsi D</label>
                                <input type="text" name="option_d" x-model="edit.option_d"
                                    class="w-full border rounded p-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-600 @error('option_d', 'updateQuestion') border-red-500 dark:border-red-400 @enderror">
                                @error('option_d', 'updateQuestion')
                                    <p class="text-red-600 dark:text-red-400 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <label class="block font-medium text-gray-900 dark:text-gray-100">Kunci Jawaban</label>
                            <select name="correct_option" x-model="edit.correct_option"
                                class="w-full border rounded p-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-600 @error('correct_option', 'updateQuestion') border-red-500 dark:border-red-400 @enderror">
                                @foreach (['A', 'B', 'C', 'D'] as $k)
                                    <option value="{{ $k }}">{{ $k }}</option>
                                @endforeach
                            </select>
                            @error('correct_option', 'updateQuestion')
                                <p class="text-red-600 dark:text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="mt-5 flex items-center justify-end gap-2">
                        <button type="button" @click="openEdit=false"
                            class="px-4 py-2 rounded border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700">Batal</button>
                        <button type="submit"
                            class="px-4 py-2 rounded bg-blue-600 hover:bg-blue-700 text-white">Perbarui</button>
                    </div>
                </form>
            </div>
        </div>
        {{-- /Modal Edit Soal --}}
    </div>
@endsection

@section('styles')
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-lite.min.css" rel="stylesheet">
    <style>
        [x-cloak] {
            display: none !important
        }

        /* Tweak dark mode untuk summernote */
        .dark .note-editor.note-frame {
            background-color: #1f2937;
            color: #f3f4f6;
        }

        .dark .note-toolbar {
            background-color: #374151;
        }

        .dark .note-editable {
            background-color: #111827;
            color: #e5e7eb;
        }
    </style>
@endsection

@section('scripts')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-lite.min.js"></script>
    <script>
        (function() {
            const commonConfig = {
                placeholder: 'Tulis pertanyaan…',
                height: 180,
                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'underline', 'clear']],
                    ['fontname', ['fontname']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['table', ['table']],
                    ['insert', ['link', 'picture', 'video']],
                    ['view', ['fullscreen', 'codeview', 'help']],
                ],
            };

            $(function() {
                const $create = $('#create-question-editor');
                if ($create.length) $create.summernote(commonConfig);

                const $edit = $('#edit-question-editor');
                if ($edit.length) $edit.summernote(commonConfig);

                // Helper dipanggil saat klik tombol Edit
                window.fillEditEditor = function(html) {
                    const $ed = $('#edit-question-editor');
                    if ($ed.data('summernote')) {
                        $ed.summernote('code', html || '');
                    }
                };

                // Jika validasi update gagal, isi editor edit dengan old()
                @if ($errors->hasBag('updateQuestion') && old('question_text'))
                    window.fillEditEditor(@json(old('question_text')));
                @endif

                // Jika validasi create gagal, isi editor create dengan old()
                @if ($errors->hasBag('createQuestion') && old('question_text'))
                    if ($create.data('summernote')) {
                        $create.summernote('code', @json(old('question_text')));
                    }
                @endif
            });
        })();
    </script>
@endsection
