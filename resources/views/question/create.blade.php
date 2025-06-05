@extends('layouts.app')

@section('namePage', 'Tambah Pertanyaan')

@section('content')
<div class="card p-6 bg-white dark:bg-gray-800 rounded shadow">
    <form id="pivotForm" action="{{route('question.store', $quiz->slug)}}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="flex justify-between items-center mb-5">
            <button type="button" onclick="openBackModal()"
                class="inline-flex items-center gap-2 bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 py-2 px-6 rounded-lg text-gray-700 dark:text-gray-200 hover:text-gray-900 dark:hover:text-white transition">
                <i class="fa-solid fa-chevron-left"></i>
                <span class="hidden md:block">Kembali</span>
            </button>

            <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100">Tambah Pertanyaan untuk {{
                $quiz->title }}</h2>

            <button type="button" onclick="openSubmitModal()"
                class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white py-2 px-6 rounded-lg font-semibold transition">
                <i class="fa-solid fa-plus"></i>
                <span class="hidden md:block">Tambah</span>
            </button>
        </div>

        <div class="space-y-5">
            {{-- Pertanyaan --}}
            <div class="w-full">
                <label for="question" class="block font-medium text-gray-900 dark:text-gray-100">Pertanyaan</label>
                <textarea name="question" id="question" rows="5"
                    class="w-full border rounded p-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('question') border-red-500 dark:border-red-400 @enderror">{{ old('question') }}</textarea>
                @error('question')
                <p class="text-red-600 dark:text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                {{-- Opsi A --}}
                <div class="w-full">
                    <label for="option_a" class="block font-medium text-gray-900 dark:text-gray-100">Jawaban A</label>
                    <input type="text" name="option_a" id="option_a" value="{{ old('option_a') }}"
                        class="w-full border rounded p-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('option_a') border-red-500 dark:border-red-400 @enderror">
                    @error('option_a')
                    <p class="text-red-600 dark:text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Opsi B --}}
                <div class="w-full">
                    <label for="option_b" class="block font-medium text-gray-900 dark:text-gray-100">Jawaban B</label>
                    <input type="text" name="option_b" id="option_b" value="{{ old('option_b') }}"
                        class="w-full border rounded p-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('option_b') border-red-500 dark:border-red-400 @enderror">
                    @error('option_b')
                    <p class="text-red-600 dark:text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Opsi C --}}
                <div class="w-full">
                    <label for="option_c" class="block font-medium text-gray-900 dark:text-gray-100">Jawaban C</label>
                    <input type="text" name="option_c" id="option_c" value="{{ old('option_c') }}"
                        class="w-full border rounded p-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('option_c') border-red-500 dark:border-red-400 @enderror">
                    @error('option_c')
                    <p class="text-red-600 dark:text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Opsi D --}}
                <div class="w-full">
                    <label for="option_d" class="block font-medium text-gray-900 dark:text-gray-100">Jawaban D</label>
                    <input type="text" name="option_d" id="option_d" value="{{ old('option_d') }}"
                        class="w-full border rounded p-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('option_d') border-red-500 dark:border-red-400 @enderror">
                    @error('option_d')
                    <p class="text-red-600 dark:text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Jawaban Benar --}}
            <div class="w-full">
                <label for="correct_option" class="block font-medium text-gray-900 dark:text-gray-100">Jawaban
                    Benar</label>
                <select name="correct_option" id="correct_option"
                    class="w-full border rounded p-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('correct_option') border-red-500 dark:border-red-400 @enderror">
                    <option value="">-- Pilih Jawaban Benar --</option>
                    @foreach(['A', 'B', 'C', 'D'] as $opt)
                    <option value="{{ $opt }}" {{ old('correct_option')===$opt ? 'selected' : '' }}>Jawaban {{ $opt }}
                    </option>
                    @endforeach
                </select>
                @error('correct_option')
                <p class="text-red-600 dark:text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </form>
</div>

{{-- Modal Konfirmasi Simpan --}}
<div id="submitModal" class="fixed inset-0 bg-black/50 backdrop-blur flex items-center justify-center hidden px-5">
    <div class="bg-white dark:bg-gray-800 rounded p-6 w-full max-w-md text-gray-900 dark:text-gray-100">
        <h3 class="text-lg font-semibold mb-4">Konfirmasi Simpan</h3>
        <p class="mb-6">Apakah Anda yakin ingin menyimpan pertanyaan ini?</p>
        <div class="flex justify-end gap-4">
            <button onclick="closeSubmitModal()"
                class="px-4 py-2 bg-gray-300 dark:bg-gray-700 rounded hover:bg-gray-400 dark:hover:bg-gray-600 transition">Batal</button>
            <button onclick="submitForm()"
                class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded transition">Ya, Simpan</button>
        </div>
    </div>
</div>

{{-- Modal Konfirmasi Kembali --}}
<div id="backModal" class="fixed inset-0 bg-black/50 backdrop-blur flex items-center justify-center hidden px-5">
    <div class="bg-white dark:bg-gray-800 rounded p-6 w-full max-w-md text-gray-900 dark:text-gray-100">
        <h3 class="text-lg font-semibold mb-4">Konfirmasi Kembali</h3>
        <p class="mb-6">Apakah Anda yakin ingin kembali? Data yang belum disimpan akan hilang.</p>
        <div class="flex justify-end gap-4">
            <button onclick="closeBackModal()"
                class="px-4 py-2 bg-gray-300 dark:bg-gray-700 rounded hover:bg-gray-400 dark:hover:bg-gray-600 transition">Batal</button>
            <a href="{{ route('quiz.show', $quiz->slug) }}"
                class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded transition">Ya, Kembali</a>
        </div>
    </div>
</div>

<script>
    function openSubmitModal() {
        document.getElementById('submitModal').classList.remove('hidden');
    }

    function closeSubmitModal() {
        document.getElementById('submitModal').classList.add('hidden');
    }

    function submitForm() {
        document.getElementById('pivotForm').submit();
    }

    function openBackModal() {
        document.getElementById('backModal').classList.remove('hidden');
    }

    function closeBackModal() {
        document.getElementById('backModal').classList.add('hidden');
    }
</script>
@endsection