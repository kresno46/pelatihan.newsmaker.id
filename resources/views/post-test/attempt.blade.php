@extends('layouts.app')

@section('namePage', 'Kuis Attempt: ' . $session->title)

@section('content')
    <div class="space-y-5">
        <div class="p-6 bg-white dark:bg-gray-800 rounded-lg shadow">
            <h2 class="text-xl font-bold mb-4 text-gray-900 dark:text-white">{{ $session->title }}</h2>
            <div class="flex justify-between items-center">
                <div id="timer" class="text-red-600 dark:text-red-400 font-semibold">
                    Sisa waktu: <span id="countdown"></span>
                </div>
                <div class="text-sm text-gray-600 dark:text-gray-400">
                    Tipe: <span class="font-semibold {{ $session->tipe === 'PATL' ? 'text-red-600' : 'text-green-600' }}">{{ $session->tipe }}</span>
                </div>
            </div>
        </div>

        <form id="quizForm" action="{{ route('post-test.submit', ['slug' => $session->slug]) }}" method="POST"
            class="space-y-5">
            @csrf
            @foreach ($questions as $index => $question)
                <div id="question-{{ $index }}" class="question space-y-4" style="display: none;">
                    <div
                        class="p-6 bg-white dark:bg-gray-800 rounded-lg shadow border-2 border-blue-200 dark:border-blue-700 no-copy">
                        <div class="border-l-4 border-blue-500 pl-4">
                            <div class="font-medium text-gray-800 dark:text-gray-100 mb-4" style="user-select: none;">
                                {!! $question->question !!}</div>
                            <div class="space-y-2">
                                @foreach (['A', 'B', 'C', 'D'] as $opt)
                                    @php $opt_text = $question->{'option_' . strtolower($opt)}; @endphp
                                    @if ($opt_text)
                                        <div class="p-3 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded hover:bg-gray-100 dark:hover:bg-gray-600 no-copy"
                                            style="user-select: none;">
                                            <label class="block text-gray-700 dark:text-gray-300 cursor-pointer">
                                                <input type="radio" name="answer[{{ $question->id }}]"
                                                    value="{{ $opt }}" class="mr-2"
                                                    data-question="{{ $question->id }}">
                                                {{ $opt }}. {{ $opt_text }}
                                            </label>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="p-4 bg-blue-50 dark:bg-blue-900 border border-blue-200 dark:border-blue-700 rounded-lg">
                        <div class="flex items-start text-sm text-blue-800 dark:text-blue-200">
                            <svg class="w-5 h-5 mr-3 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            <div>
                                <strong class="block mb-1">Navigasi Terbatas:</strong>
                                <p class="text-xs leading-relaxed">Sistem ini hanya memungkinkan navigasi maju. Setelah
                                    melanjutkan ke soal berikutnya, Anda tidak dapat kembali ke soal sebelumnya. Pastikan
                                    jawaban Anda telah dipilih dengan benar sebelum mengklik tombol "Next".</p>
                            </div>
                        </div>
                    </div>
                    <div class="text-right">
                        @if ($index < count($questions) - 1)
                            <button type="button" onclick="nextQuestion({{ $index + 1 }})"
                                class="bg-blue-600 hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600 text-white px-6 py-2 rounded">
                                Next
                            </button>
                        @else
                            <button type="button" onclick="showModal()"
                                class="bg-green-600 hover:bg-green-700 dark:bg-green-500 dark:hover:bg-green-600 text-white px-6 py-2 rounded">
                                Submit
                            </button>
                        @endif
                    </div>
                </div>
            @endforeach
        </form>

        <!-- Navigation Buttons -->
        <div class="flex justify-between">
            @if ($number > 1)
                <button type="button" onclick="navigateTo({{ $number - 1 }})"
                    class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg flex items-center">
                    <i class="fas fa-arrow-left mr-2"></i> Sebelumnya
                </button>
            @else
                <div></div>
            @endif

            @if ($number < $totalQuestions)
                <button type="button" onclick="navigateTo({{ $number + 1 }})"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg flex items-center ml-auto">
                    Selanjutnya <i class="fas fa-arrow-right ml-2"></i>
                </button>
            @else
                <button type="button" onclick="showModal()"
                    class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg flex items-center ml-auto">
                    <i class="fas fa-check mr-2"></i> Selesai
                </button>
            @endif
        </div>

        <!-- Question Navigation -->
        <div class="p-4 bg-white dark:bg-gray-800 rounded-lg shadow">
            <div class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Navigasi Pertanyaan:</div>
            <div class="flex flex-wrap gap-2">
                @for ($i = 1; $i <= $totalQuestions; $i++)
                    <button type="button" onclick="navigateTo({{ $i }})"
                        class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-medium transition-colors
                            {{ $i == $number ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600' }}">
                        {{ $i }}
                    </button>
                @endfor
            </div>
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

                <form id="submitForm" action="{{ route('post-test.submit', ['slug' => $session->slug]) }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" onclick="clearData()"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                        Ya, Selesai
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        const countdownEl = document.getElementById('countdown');
        const sessionKey = 'quiz_timer_{{ $session->id }}';
        const answerKey = 'quiz_answers_{{ $session->id }}';
        const currentQuestionKey = 'quiz_current_question_{{ $session->id }}';
        const duration = {{ $session->duration }} * 60;
        const savedStartTime = localStorage.getItem(sessionKey);
        const startTime = savedStartTime ? parseInt(savedStartTime) : Date.now();
        let currentQuestion = parseInt(localStorage.getItem(currentQuestionKey) || '0');

        if (!savedStartTime) localStorage.setItem(sessionKey, startTime);

        function updateTimer() {
            const elapsed = Math.floor((Date.now() - startTime) / 1000);
            const remaining = duration - elapsed;

            if (remaining <= 0) {
                countdownEl.textContent = '0m 0s';
                alert('Waktu habis! Jawaban Anda akan dikirim otomatis.');
                clearData();
                document.getElementById('submitForm').submit();
                return;
            }

            const minutes = Math.floor(remaining / 60);
            const seconds = remaining % 60;
            countdownEl.textContent = `${minutes}m ${seconds}s`;
        }

        setInterval(updateTimer, 1000);
        updateTimer();

        // Handle radio button changes - save to localStorage temporarily
        const radios = document.querySelectorAll('input[type=radio]');
        radios.forEach(radio => {
            radio.addEventListener('change', () => {
                const qid = radio.dataset.question;
                const value = radio.value;
                let answers = JSON.parse(localStorage.getItem(answerKey) || '{}');
                answers[qid] = value;
                localStorage.setItem(answerKey, JSON.stringify(answers));
            });
        });

        function showQuestion(index) {
            const questions = document.querySelectorAll('.question');
            questions.forEach((q, i) => {
                q.style.display = i === index ? 'block' : 'none';
            });
        }

        function nextQuestion(nextIndex) {
            currentQuestion = nextIndex;
            localStorage.setItem(currentQuestionKey, currentQuestion);
            showQuestion(currentQuestion);
        }

        function clearData() {
            localStorage.removeItem(sessionKey);
            localStorage.removeItem(answerKey);
            localStorage.removeItem(currentQuestionKey);
        }

        function showModal() {
            const selectedRadio = document.querySelector('input[type=radio]:checked');
            if (selectedRadio) {
                // Save current answer first via AJAX
                const formData = new FormData();
                formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
                formData.append('question_id', '{{ $currentQuestion->id }}');
                formData.append('answer', selectedRadio.value);

                fetch(window.location.href, {
                    method: 'POST',
                    body: formData
                }).then(response => {
                    // Show modal after saving
                    document.getElementById('confirmModal').classList.remove('hidden');
                }).catch(error => {
                    console.error('Error saving answer:', error);
                    // Still show modal even if error
                    document.getElementById('confirmModal').classList.remove('hidden');
                });
            } else {
                document.getElementById('confirmModal').classList.remove('hidden');
            }
        }

        function hideModal() {
            document.getElementById('confirmModal').classList.add('hidden');
        }

        // Prevent form submission on Enter key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
            }
        });

        // Prevent copy and paste
        document.addEventListener('contextmenu', function(e) {
            if (e.target.closest('.no-copy')) {
                e.preventDefault();
            }
        });

        document.addEventListener('copy', function(e) {
            if (e.target.closest('.no-copy')) {
                e.preventDefault();
            }
        });

        document.addEventListener('paste', function(e) {
            if (e.target.closest('.no-copy')) {
                e.preventDefault();
            }
        });

        document.addEventListener('cut', function(e) {
            if (e.target.closest('.no-copy')) {
                e.preventDefault();
            }
        });

        // Show the current question on load
        showQuestion(currentQuestion);
    </script>
@endsection
