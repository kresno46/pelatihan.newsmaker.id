@extends('layouts.app')

@section('namePage', 'Tambah Pertanyaan')

@section('content')
    <div class="bg-white dark:bg-gray-800 p-6 rounded shadow text-gray-800 dark:text-gray-100">
        <h2 class="text-xl font-semibold mb-4">Tambah Pertanyaan</h2>

        @if ($errors->any())
            <div class="mb-4 p-4 bg-red-100 dark:bg-red-800 text-red-700 dark:text-red-200 rounded">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form id="questionForm" method="POST"
            action="{{ route('quiz.add-question-store', ['slug' => $slug, 'sessionId' => $sessionId->id]) }}">
            @csrf

            <div class="mb-4">
                <label for="question" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Pertanyaan</label>
                <textarea id="question" name="question"
                    class="w-full mt-1 border border-gray-300 dark:border-gray-700 rounded bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100">
                    {{ old('question') }}
                </textarea>
            </div>

            @foreach (['A', 'B', 'C', 'D'] as $opt)
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Pilihan
                        {{ $opt }}</label>
                    <input type="text" name="option_{{ strtolower($opt) }}"
                        class="w-full mt-1 border border-gray-300 dark:border-gray-700 rounded bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100"
                        value="{{ old('option_' . strtolower($opt)) }}">
                </div>
            @endforeach

            <div class="mb-4">
                <label for="correct_option" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Jawaban
                    Benar</label>
                <select name="correct_option" id="correct_option"
                    class="w-full mt-1 border border-gray-300 dark:border-gray-700 rounded bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100">
                    <option value="">-- Pilih --</option>
                    @foreach (['A', 'B', 'C', 'D'] as $opt)
                        <option value="{{ $opt }}" {{ old('correct_option') == $opt ? 'selected' : '' }}>
                            {{ $opt }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex justify-between items-center mt-6">
                <a href="{{ route('quiz.index', $slug) }}"
                    class="text-sm text-gray-500 dark:text-gray-400 hover:underline">← Kembali</a>

                <button type="button" onclick="document.getElementById('confirmModal').classList.remove('hidden')"
                    class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Simpan</button>
            </div>
        </form>
    </div>

    {{-- Modal Konfirmasi --}}
    <div id="confirmModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden">
        <div class="bg-white dark:bg-gray-900 p-6 rounded shadow-md w-full max-w-md">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Konfirmasi Simpan</h3>
            <p class="text-sm text-gray-600 dark:text-gray-300 mb-6">Apakah Anda yakin ingin menyimpan pertanyaan ini?</p>
            <div class="flex justify-end gap-3">
                <button type="button"
                    class="bg-gray-300 dark:bg-gray-700 text-gray-800 dark:text-gray-100 px-4 py-2 rounded hover:bg-gray-400 dark:hover:bg-gray-600"
                    onclick="document.getElementById('confirmModal').classList.add('hidden')">
                    Batal
                </button>
                <button type="submit" form="questionForm"
                    class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                    Ya, Simpan
                </button>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.tiny.cloud/1/rijrac2uxn06a1q296snq7j1fi420fd29r3lc1o12yzq6fwv/tinymce/7/tinymce.min.js"
        referrerpolicy="origin"></script>
    <script>
        tinymce.init({
            selector: '#question',
            plugins: 'lists link image table code fullscreen',
            toolbar: 'undo redo | formatselect | bold italic underline | bullist numlist | link image | fullscreen code',
            menubar: false,
            height: 300,
            skin: document.documentElement.classList.contains('dark') ? 'oxide-dark' : 'oxide',
            content_css: document.documentElement.classList.contains('dark') ? 'dark' : 'default'
        });
    </script>
@endsection
