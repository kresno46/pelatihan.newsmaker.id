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
                    Pertanyaan {{ $number }} dari {{ $totalQuestions }}
                </div>
            </div>
        </div>

        <!-- Progress Bar -->
        <div class="w-full bg-gray-200 rounded-full h-2.5 dark:bg-gray-700">
            <div class="bg-blue-600 h-2.5 rounded-full" style="width: {{ ($number / $totalQuestions) * 100 }}%"></div>
        </div>

        <div class="p-6 bg-white dark:bg-gray-800 rounded-lg shadow">
            <div class="mb-4">
                <div class="font-medium text-lg text-gray-800 dark:text-gray-100 mb-4">{!! $currentQuestion->question !!}</div>
                <div class="space-y-3">
                    @foreach (['A', 'B', 'C', 'D'] as $opt)
                        @php $opt_text = $currentQuestion->{'option_' . strtolower($opt)}; @endphp
                        @if ($opt_text)
                            <label class="block p-3 border rounded-lg cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors {{ $currentAnswer == $opt ? 'bg-blue-50 border-blue-300 dark:bg-blue-900 dark:border-blue-600' : 'border-gray-200 dark:border-gray-600' }}">
                                <input type="radio" name="answer" value="{{ $opt }}"
                                    {{ $currentAnswer == $opt ? 'checked' : '' }}
                                    class="mr-3" data-question="{{ $currentQuestion->id }}">
                                <span class="font-medium">{{ $opt }}.</span> {{ $opt_text }}
                            </label>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Hidden form for navigation -->
        <form id="navigationForm" method="POST" style="display: none;">
            @csrf
            <input type="hidden" name="question_id" id="navQuestionId">
            <input type="hidden" name="answer" id="navAnswer">
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
        const duration = {{ $session->duration }} * 60;
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

        function saveAnswer() {
            // Save current answer to localStorage before navigating
            const selectedRadio = document.querySelector('input[type=radio]:checked');
            if (selectedRadio) {
                const qid = selectedRadio.dataset.question;
                const value = selectedRadio.value;
                let answers = JSON.parse(localStorage.getItem(answerKey) || '{}');
                answers[qid] = value;
                localStorage.setItem(answerKey, JSON.stringify(answers));
            }
        }

        function navigateTo(questionNumber) {
            // Save current answer
            saveAnswer();

            // Get current answer
            const selectedRadio = document.querySelector('input[type=radio]:checked');
            const currentAnswer = selectedRadio ? selectedRadio.value : '';

            // Set form values
            document.getElementById('navQuestionId').value = '{{ $currentQuestion->id }}';
            document.getElementById('navAnswer').value = currentAnswer;

            // Set form action to next question
            const form = document.getElementById('navigationForm');
            form.action = '{{ route("post-test.question", ["slug" => $session->slug, "number" => ":number"]) }}'.replace(':number', questionNumber);

            // Submit form
            form.submit();
        }

        function clearData() {
            localStorage.removeItem(sessionKey);
            localStorage.removeItem(answerKey);
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
    </script>
@endsection
