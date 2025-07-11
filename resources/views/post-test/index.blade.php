@extends('layouts.app')

@section('namePage', 'Kuis Attempt: ' . $session->title)

@section('content')
    <div class="space-y-5">
        <div class="p-6 bg-white dark:bg-gray-800 rounded-lg shadow">
            <h2 class="text-xl font-bold mb-4 text-gray-900 dark:text-white">{{ $session->title }}</h2>
            <div id="timer" class="text-red-600 dark:text-red-400 font-semibold">
                Sisa waktu: <span id="countdown"></span>
            </div>
        </div>

        <form id="quizForm"
            action="{{ route('posttest.submit', [
                'folderSlug' => $folderSlug,
                'ebookSlug' => $ebook->slug,
                'session' => $session->id,
            ]) }}"
            method="POST" class="space-y-5">
            @csrf
            @foreach ($questions as $index => $question)
                <div class="p-6 bg-white dark:bg-gray-800 rounded-lg shadow">
                    <div>
                        <div class="font-medium text-gray-800 dark:text-gray-100">{!! $question->question !!}</div>
                        @foreach (['A', 'B', 'C', 'D'] as $opt)
                            @php $opt_text = $question->{'option_' . strtolower($opt)}; @endphp
                            @if ($opt_text)
                                <label class="block text-gray-700 dark:text-gray-300">
                                    <input type="radio" name="answer[{{ $question->id }}]" value="{{ $opt }}"
                                        required class="mr-2" data-question="{{ $question->id }}">
                                    {{ $opt }}. {{ $opt_text }}
                                </label>
                            @endif
                        @endforeach
                    </div>
                </div>
            @endforeach

            <!-- Tombol untuk buka modal -->
            <button type="button" onclick="showModal()"
                class="bg-blue-600 hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600 text-white px-4 py-2 rounded w-full">
                Submit
            </button>
        </form>
    </div>

    <!-- Modal Konfirmasi -->
    <div id="confirmModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg w-full max-w-md">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">Konfirmasi</h3>
            <p class="text-gray-600 dark:text-gray-300 mb-6">Apakah Anda yakin ingin menyelesaikan kuis ini?</p>
            <div class="flex justify-end space-x-3">
                <button type="button" onclick="hideModal()"
                    class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">Batal</button>

                <!-- Tombol submit langsung -->
                <button type="submit" form="quizForm" onclick="clearData()"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                    Ya, Selesai
                </button>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        const countdownEl = document.getElementById('countdown');
        const quizForm = document.getElementById('quizForm');
        const sessionKey = 'quiz_timer_{{ $session->id }}';
        const answerKey = 'quiz_answers_{{ $session->id }}';
        const duration = {{ $session->duration }} * 60;
        const savedStartTime = localStorage.getItem(sessionKey);
        const startTime = savedStartTime ? parseInt(savedStartTime) : Date.now();

        // Save start time if not already saved
        if (!savedStartTime) localStorage.setItem(sessionKey, startTime);

        function updateTimer() {
            const elapsed = Math.floor((Date.now() - startTime) / 1000);
            const remaining = duration - elapsed;

            if (remaining <= 0) {
                countdownEl.textContent = '0m 0s';
                alert('Waktu habis! Jawaban Anda akan dikirim otomatis.');
                clearData();
                quizForm.submit();
                return;
            }

            const minutes = Math.floor(remaining / 60);
            const seconds = remaining % 60;
            countdownEl.textContent = `${minutes}m ${seconds}s`;
        }

        setInterval(updateTimer, 1000);
        updateTimer();

        // Load & Simpan Jawaban
        const radios = quizForm.querySelectorAll('input[type=radio]');
        let answers = JSON.parse(localStorage.getItem(answerKey) || '{}');

        radios.forEach(radio => {
            const qid = radio.dataset.question;
            if (answers[qid] === radio.value) {
                radio.checked = true;
            }
            radio.addEventListener('change', () => {
                answers[qid] = radio.value;
                localStorage.setItem(answerKey, JSON.stringify(answers));
            });
        });

        function clearData() {
            localStorage.removeItem(sessionKey);
            localStorage.removeItem(answerKey);
        }

        // Modal Logic
        function showModal() {
            document.getElementById('confirmModal').classList.remove('hidden');
        }

        function hideModal() {
            document.getElementById('confirmModal').classList.add('hidden');
        }

        // Cegah submit via Enter
        quizForm.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
            }
        });
    </script>
@endsection
