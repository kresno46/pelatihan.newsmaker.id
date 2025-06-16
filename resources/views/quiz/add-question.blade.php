@extends('layouts.app')

@section('namePage', 'Tambah Pertanyaan')

@section('content')
    <div class="bg-white dark:bg-gray-800 p-6 rounded shadow text-gray-800 dark:text-gray-100">
        <h2 class="text-xl font-semibold mb-4">Tambah Pertanyaan</h2>

        <form id="questionForm" method="POST"
            action="{{ route('quiz.add-question-store', ['slug' => $slug, 'sessionId' => $sessionId->id]) }}">
            @csrf

            <div class="mb-4">
                <label for="question" class="text-sm font-medium">Pertanyaan</label>
                <textarea id="question" name="question" class="summernote w-full">{{ old('question') }}</textarea>
            </div>

            @foreach (['A', 'B', 'C', 'D'] as $opt)
                <div class="mb-4">
                    <label class="block text-sm font-medium">Pilihan {{ $opt }}</label>
                    <input type="text" name="option_{{ strtolower($opt) }}" class="w-full rounded"
                        value="{{ old('option_' . strtolower($opt)) }}">
                </div>
            @endforeach

            <div class="mb-4">
                <label for="correct_option" class="block text-sm font-medium">Jawaban Benar</label>
                <select name="correct_option" id="correct_option" class="w-full rounded">
                    <option value="">-- Pilih --</option>
                    @foreach (['A', 'B', 'C', 'D'] as $opt)
                        <option value="{{ $opt }}" {{ old('correct_option') == $opt ? 'selected' : '' }}>
                            {{ $opt }}</option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Simpan</button>
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
