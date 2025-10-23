@extends('layouts.app')

@section('namePage', 'Kuis Attempt: ' . $session->title)

@section('content')
    <style>
        .no-select {
            user-select: none;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            -webkit-touch-callout: none;
            -webkit-tap-highlight-color: transparent;
        }

        .no-select * {
            user-select: none !important;
            -webkit-user-select: none !important;
            -moz-user-select: none !important;
            -ms-user-select: none !important;
        }
    </style>
    <div class="space-y-5">
        <!-- Header & Timer -->
        <div class="p-6 bg-white dark:bg-gray-800 rounded-lg shadow">
            <h2 class="text-xl font-bold mb-4 text-gray-900 dark:text-white">{{ $session->title }}</h2>
            <div id="timer" class="text-red-600 dark:text-red-400 font-semibold">
                Sisa waktu: <span id="countdown"></span>
            </div>
        </div>

        <!-- Form Kuis -->
        <form id="quizForm" action="{{ route('post-test.submit', ['slug' => $session->slug]) }}" method="POST"
            class="space-y-5">
            @csrf

            @foreach ($questions as $index => $question)
                <div class="question-card p-6 bg-white dark:bg-gray-800 rounded-lg shadow hidden"
                    data-index="{{ $index }}">
                    <div>
                        <div class="font-medium text-gray-800 dark:text-gray-100 mb-3 no-select">
                            {!! $question->question !!}
                        </div>
                        <div class="space-y-3">
                            @foreach (['A', 'B', 'C', 'D'] as $opt)
                                @php $opt_text = $question->{'option_' . strtolower($opt)}; @endphp
                                @if ($opt_text)
                                    <label
                                        class="flex items-start space-x-2 p-3 border rounded-lg cursor-pointer transition-all duration-200 hover:bg-gray-50 dark:hover:bg-gray-800 border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300">
                                        <input type="radio" name="temp_answer_{{ $question->id }}"
                                            value="{{ $opt }}" required
                                            class="mt-1 accent-blue-600 dark:accent-blue-500"
                                            data-question="{{ $question->id }}">
                                        <span class="leading-snug no-select">
                                            <strong>{{ $opt }}.</strong> {{ $opt_text }}
                                        </span>
                                    </label>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
            @endforeach

            <!-- Navigasi Soal -->
            <div class="flex justify-between mt-4">
                <button type="button" id="nextBtn" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                    Berikutnya
                </button>

                <button type="button" id="submitBtn" onclick="showModal()"
                    class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded hidden">
                    Selesai
                </button>
            </div>
        </form>

        <!-- Reminder -->
        <div
            class="p-4 bg-yellow-100 border-l-4 border-yellow-500 text-yellow-800 dark:bg-yellow-900 dark:border-yellow-600 dark:text-yellow-300 rounded-lg">
            <div class="font-bold flex items-center mb-2">
                ⚠️ Reminder!!
            </div>
            <ul class="list-disc pl-5 space-y-1">
                <li>Setelah menekan <strong>“Berikutnya”</strong>, Anda <strong>tidak dapat kembali</strong> ke soal
                    sebelumnya.</li>
                <li>Pastikan jawaban sudah benar sebelum melanjutkan ke soal berikutnya.</li>
                <li>Baca pertanyaan dengan teliti sebelum memilih jawaban.</li>
                <li>Gunakan waktu Anda dengan sebaik-baiknya, perhatikan sisa waktu ujian.</li>
            </ul>
        </div>
    </div>

    <!-- Modal Konfirmasi -->
    <div id="confirmModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg w-full max-w-md">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">Konfirmasi</h3>
            <p class="text-gray-600 dark:text-gray-300 mb-6">Apakah Anda yakin ingin menyelesaikan kuis ini?</p>
            <div class="flex justify-end space-x-3">
                <button type="button" onclick="hideModal()"
                    class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">Batal</button>
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
        const currentIndexKey = 'quiz_current_index_{{ $session->id }}';
        const duration = {{ $session->duration }} * 60;

        // ============= TIMER =============
        const savedStartTime = localStorage.getItem(sessionKey);
        const startTime = savedStartTime ? parseInt(savedStartTime) : Date.now();
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

        // ============= NAVIGASI SOAL =============
        const questions = document.querySelectorAll('.question-card');
        const nextBtn = document.getElementById('nextBtn');
        const submitBtn = document.getElementById('submitBtn');

        let currentQuestion = parseInt(localStorage.getItem(currentIndexKey)) || 0;

        function showQuestion(index) {
            questions.forEach((q, i) => {
                q.classList.toggle('hidden', i !== index);
            });
            nextBtn.classList.toggle('hidden', index === questions.length - 1);
            submitBtn.classList.toggle('hidden', index !== questions.length - 1);
        }
        showQuestion(currentQuestion);

        nextBtn.addEventListener('click', () => {
            const currentInputs = questions[currentQuestion].querySelectorAll('input[type="radio"]');
            const answered = Array.from(currentInputs).some(input => input.checked);
            if (!answered) {
                alert('Silakan pilih jawaban terlebih dahulu sebelum melanjutkan.');
                return;
            }

            if (currentQuestion < questions.length - 1) {
                currentQuestion++;
                localStorage.setItem(currentIndexKey, currentQuestion);
                showQuestion(currentQuestion);
            }
        });

        // ============= SIMPAN JAWABAN & BUAT HIDDEN INPUT =============
        const radios = quizForm.querySelectorAll('input[type=radio]');
        let answers = JSON.parse(localStorage.getItem(answerKey) || '{}');

        radios.forEach(radio => {
            const qid = radio.dataset.question;
            if (answers[qid] === radio.value) {
                radio.checked = true;
                updateHiddenInput(qid, radio.value);
            }
            radio.addEventListener('change', () => {
                answers[qid] = radio.value;
                localStorage.setItem(answerKey, JSON.stringify(answers));
                updateHiddenInput(qid, radio.value);
            });
        });

        function updateHiddenInput(qid, value) {
            let hidden = quizForm.querySelector(`input[type="hidden"][name="answer[${qid}]"]`);
            if (!hidden) {
                hidden = document.createElement('input');
                hidden.type = 'hidden';
                hidden.name = `answer[${qid}]`;
                quizForm.appendChild(hidden);
            }
            hidden.value = value;
        }

        function clearData() {
            localStorage.removeItem(sessionKey);
            localStorage.removeItem(answerKey);
            localStorage.removeItem(currentIndexKey);
        }

        function showModal() {
            document.getElementById('confirmModal').classList.remove('hidden');
        }

        function hideModal() {
            document.getElementById('confirmModal').classList.add('hidden');
        }

        // Blok tombol back browser
        history.pushState(null, null, location.href);
        window.onpopstate = function() {
            history.go(1);
        };

        // Cegah submit dengan enter
        quizForm.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') e.preventDefault();
        });

        // Cegah copy paste dan right-click
        document.addEventListener('contextmenu', function(e) {
            e.preventDefault();
        });

        document.addEventListener('keydown', function(e) {
            if (e.ctrlKey && (e.key === 'c' || e.key === 'v' || e.key === 'x' || e.key === 'a' || e.key === 'u' || e
                    .key === 's' || e.key === 'p')) {
                e.preventDefault();
            }
            if (e.key === 'F12' || (e.ctrlKey && e.shiftKey && e.key === 'I') || (e.ctrlKey && e.shiftKey && e
                    .key === 'J') || (e.ctrlKey && e.shiftKey && e.key === 'C')) {
                e.preventDefault();
            }
        });

        document.addEventListener('selectstart', function(e) {
            e.preventDefault();
        });

        // Cegah drag and drop
        document.addEventListener('dragstart', function(e) {
            e.preventDefault();
        });

        // Cegah paste via mouse
        document.addEventListener('paste', function(e) {
            e.preventDefault();
        });

        // Override console to prevent debugging
        (function() {
            const noop = () => {};
            const methods = ['log', 'warn', 'error', 'info', 'debug', 'trace'];
            methods.forEach(method => {
                console[method] = noop;
            });
        })();
    </script>
@endsection
