@extends('layouts.app')

@section('namePage', 'Edit Pertanyaan')

@section('content')
    <div class="bg-white dark:bg-gray-800 p-6 rounded shadow text-gray-800 dark:text-gray-100">
        <h2 class="text-xl font-semibold mb-4">Edit Pertanyaan</h2>

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
            action="{{ route('quiz.update-question', ['slug' => $slug, 'sessionId' => $session->id, 'questionId' => $question->id]) }}">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label for="question" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Pertanyaan</label>
                <textarea id="question" name="question"
                    class="summernote w-full mt-1 border border-gray-300 dark:border-gray-700 rounded bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100">{{ old('question', $question->question) }}</textarea>
            </div>

            @foreach (['A', 'B', 'C', 'D'] as $opt)
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Pilihan
                        {{ $opt }}</label>
                    <input type="text" name="option_{{ strtolower($opt) }}"
                        class="w-full mt-1 border border-gray-300 dark:border-gray-700 rounded bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100"
                        value="{{ old('option_' . strtolower($opt), $question->{'option_' . strtolower($opt)}) }}">
                </div>
            @endforeach

            <div class="mb-4">
                <label for="correct_option" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Jawaban
                    Benar</label>
                <select name="correct_option" id="correct_option"
                    class="w-full mt-1 border border-gray-300 dark:border-gray-700 rounded bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100">
                    <option value="">-- Pilih --</option>
                    @foreach (['A', 'B', 'C', 'D'] as $opt)
                        <option value="{{ $opt }}"
                            {{ old('correct_option', $question->correct_option) == $opt ? 'selected' : '' }}>
                            {{ $opt }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="flex justify-between items-center mt-6">
                <a href="{{ route('quiz.index', $slug) }}"
                    class="text-sm text-gray-500 dark:text-gray-400 hover:underline">← Kembali</a>
                <button type="button" onclick="document.getElementById('confirmModal').classList.remove('hidden')"
                    class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Perbarui</button>
            </div>
        </form>
    </div>

    {{-- Modal Konfirmasi --}}
    <div id="confirmModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden">
        <div class="bg-white dark:bg-gray-900 p-6 rounded shadow-md w-full max-w-md">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Konfirmasi Perbarui</h3>
            <p class="text-sm text-gray-600 dark:text-gray-300 mb-6">Apakah Anda yakin ingin memperbarui pertanyaan ini?</p>
            <div class="flex justify-end gap-3">
                <button type="button"
                    class="bg-gray-300 dark:bg-gray-700 text-gray-800 dark:text-gray-100 px-4 py-2 rounded hover:bg-gray-400 dark:hover:bg-gray-600"
                    onclick="document.getElementById('confirmModal').classList.add('hidden')">Batal</button>
                <button type="submit" form="questionForm"
                    class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Ya, Perbarui</button>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $('.summernote').summernote({
                placeholder: 'Tulis pertanyaan di sini...',
                height: 300,
                tabsize: 2,
                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'italic', 'underline', 'clear']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['insert', ['link', 'picture', 'table']],
                    ['view', ['fullscreen', 'codeview']]
                ],
                callbacks: {
                    onImageUpload: function(files) {
                        let data = new FormData();
                        data.append('image', files[0]);
                        data.append('_token', '{{ csrf_token() }}');

                        $.ajax({
                            url: '{{ route('summernote.upload') }}',
                            method: 'POST',
                            data: data,
                            contentType: false,
                            processData: false,
                            success: function(url) {
                                $('.summernote').summernote('insertImage', url);
                            },
                            error: function(xhr) {
                                alert('Upload gagal');
                            }
                        });
                    },
                    onMediaDelete: function(target) {
                        $.ajax({
                            url: '{{ route('summernote.delete') }}',
                            method: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}',
                                image: target[0].src
                            },
                            success: function(resp) {
                                console.log('Gambar dihapus:', resp);
                            },
                            error: function(err) {
                                console.error('Gagal menghapus gambar', err);
                            }
                        });
                    }
                }
            });

            if (document.documentElement.classList.contains('dark')) {
                document.querySelectorAll('.note-editor').forEach(editor => {
                    editor.classList.add('bg-gray-800', 'text-white');
                });
            }
        });
    </script>
@endsection
