@extends('layouts.app')

@section('namePage', 'Tambah Pertanyaan')

@section('content')
    <div class="bg-white dark:bg-gray-800 p-6 rounded shadow text-gray-900 dark:text-gray-100">
        <h2 class="text-xl font-semibold mb-4">Tambah Pertanyaan</h2>

        <form id="questionForm" method="POST"
            action="{{ route('quiz.add-question-store', ['folderSlug' => $folderSlug, 'ebookSlug' => $ebookSlug, 'sessionId' => $session->id]) }}">
            @csrf

            <div class="mb-4">
                <label for="question"
                    class="text-sm font-medium block mb-1 text-gray-700 dark:text-gray-300">Pertanyaan</label>
                <textarea id="question" name="question"
                    class="summernote w-full rounded border border-gray-300 dark:border-gray-600 dark:bg-gray-100">{{ old('question') }}</textarea>
                @error('question')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            @foreach (['A', 'B', 'C', 'D'] as $opt)
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1 text-gray-700 dark:text-gray-300">Pilihan
                        {{ $opt }}</label>
                    <input type="text" name="option_{{ strtolower($opt) }}"
                        class="w-full rounded border border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        value="{{ old('option_' . strtolower($opt)) }}">
                    @error('option_' . strtolower($opt))
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            @endforeach

            <div class="mb-4">
                <label for="correct_option" class="block text-sm font-medium mb-1 text-gray-700 dark:text-gray-300">Jawaban
                    Benar</label>
                <select name="correct_option" id="correct_option"
                    class="w-full rounded border border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">-- Pilih --</option>
                    @foreach (['A', 'B', 'C', 'D'] as $opt)
                        <option value="{{ $opt }}" {{ old('correct_option') == $opt ? 'selected' : '' }}>
                            {{ $opt }}
                        </option>
                    @endforeach
                </select>
                @error('correct_option')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-between items-center mt-6">
                <a href="{{ route('quiz.index', [
                    'folderSlug' => $folderSlug,
                    'ebookSlug' => $ebookSlug,
                ]) }}"
                    class="text-sm text-gray-500 dark:text-gray-400 hover:underline">← Kembali</a>

                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                    Simpan
                </button>
            </div>
        </form>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $('.summernote').summernote({
                height: 300,
                callbacks: {
                    onImageUpload: function(files) {
                        let data = new FormData();
                        data.append('image', files[0]);
                        data.append('_token', '{{ csrf_token() }}');

                        $.ajax({
                            url: "{{ route('summernote.upload') }}",
                            type: 'POST',
                            data: data,
                            contentType: false,
                            processData: false,
                            success: function(url) {
                                $('.summernote').summernote('insertImage', url);
                            }
                        });
                    },
                    onMediaDelete: function(target) {
                        $.ajax({
                            url: "{{ route('summernote.delete') }}",
                            type: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}',
                                image: target[0].src
                            },
                            success: function(res) {
                                console.log('Gambar dihapus:', res);
                            }
                        });
                    }
                }
            });
        });
    </script>
@endsection
