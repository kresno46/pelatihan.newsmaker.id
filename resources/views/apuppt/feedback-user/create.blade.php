@extends('layouts.app')

@section('namePage', 'Kuesioner Feedback APUPPT')

@section('content')
    <div class="max-w-3xl mx-auto">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl p-4 sm:p-6 mb-6 border border-gray-200 dark:border-gray-700">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ $form->title }}</h2>
            @if ($form->description)
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">{{ $form->description }}</p>
            @endif

            @if (session('error'))
                <div class="mt-3 text-sm bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-300 px-3 py-2 rounded-lg">
                    {{ session('error') }}
                </div>
            @endif
        </div>

        <form method="POST" action="{{ route('apuppt.feedbackUser.store', $form) }}" class="space-y-4">
            @csrf

            @foreach ($form->questions as $question)
                <div class="bg-white dark:bg-gray-800 rounded-xl p-5 border border-gray-200 dark:border-gray-700 shadow-sm">
                    <label class="block font-medium text-gray-900 dark:text-gray-100 mb-3">
                        {{ $loop->iteration }}. {{ $question->question_text }}
                        @if ($question->is_required)
                            <span class="text-red-500">*</span>
                        @endif
                    </label>

                    @if ($question->type === 'rating')
                        <div class="flex items-center gap-3" x-data="{ value: {{ old('answers.' . $question->id) ?: 'null' }} }">
                            @foreach ([1, 2, 3, 4, 5] as $star)
                                <label class="cursor-pointer flex flex-col items-center gap-1">
                                    <input type="radio" name="answers[{{ $question->id }}]" value="{{ $star }}"
                                        x-model="value" class="sr-only" {{ old('answers.' . $question->id) == $star ? 'checked' : '' }}>
                                    <span class="text-2xl" :class="value >= {{ $star }} ? 'text-yellow-400' : 'text-gray-300 dark:text-gray-600'">★</span>
                                    <span class="text-xs text-gray-500 dark:text-gray-400">{{ $star }}</span>
                                </label>
                            @endforeach
                        </div>
                    @else
                        <textarea name="answers[{{ $question->id }}]" rows="3"
                            class="w-full border rounded p-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('answers.' . $question->id) border-red-500 @enderror"
                            placeholder="Tulis jawaban Anda...">{{ old('answers.' . $question->id) }}</textarea>
                    @endif

                    @error('answers.' . $question->id)
                        <p class="text-red-600 dark:text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            @endforeach

            <div class="flex justify-end">
                <button type="submit"
                    class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg shadow-md transition">
                    Kirim Kuesioner
                </button>
            </div>
        </form>
    </div>
@endsection
