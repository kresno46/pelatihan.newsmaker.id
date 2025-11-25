<?php

namespace App\Http\Controllers;

use App\Models\PostTestResult;
use App\Models\PostTestSession;
use Illuminate\Http\Request;

class TestController extends Controller
{
    public function index(Request $request)
    {
        $tipe = $request->get('tipe', 'PATD'); // default PATD
        $userId = auth()->id();

        $tests = PostTestSession::where('tipe', $tipe)->get()->map(function ($test) use ($userId) {
            $result = PostTestResult::where('user_id', $userId)
                ->where('session_id', $test->id)
                ->latest()
                ->first();

            if (! $result) {
                $test->progres = 'Belum Dikerjakan';
            } elseif ($result->score < 60) {
                $test->progres = 'Nilai di Bawah 60';
            } else {
                $test->progres = 'Selesai';
            }

            $test->result_id = $result?->id;
            $test->score = $result?->score;

            return $test;
        });

        return view('post-test.index', compact('tests'));
    }

    // Mengerjakan Kuis berdasarkan slug - redirect ke pertanyaan pertama
    public function showQuiz($slug)
    {
        $session = PostTestSession::where('slug', $slug)->firstOrFail();

        // Proteksi: cek status
        if (!$session->status) { // Status now boolean
            return redirect()->route('post-test.index')
                ->with('error', 'Post test ini saat ini tidak tersedia.');
        }

        $userId = auth()->id();

        // Cek hasil sebelumnya
        $existingResult = PostTestResult::where('user_id', $userId)
            ->where('session_id', $session->id)
            ->latest()
            ->first();

        if ($existingResult && $existingResult->score >= 60) {
            return redirect()->route('dashboard')
                ->with('info', 'Anda sudah mengerjakan post test ini dan mendapatkan nilai yang cukup.');
        }

        // Redirect ke pertanyaan pertama
        return redirect()->route('post-test.question', ['slug' => $slug, 'number' => 1]);
    }

    // Tampilkan pertanyaan tertentu
    public function showQuestion($slug, $number)
    {
        $session = PostTestSession::where('slug', $slug)->with('questions')->firstOrFail();

        // Proteksi: cek status
        if (! $session->status) { // Status now boolean
            return redirect()->route('post-test.index')
                ->with('error', 'Post test ini saat ini tidak tersedia.');
        }

        $userId = auth()->id();

        // Cek hasil sebelumnya
        $existingResult = PostTestResult::where('user_id', $userId)
            ->where('session_id', $session->id)
            ->latest()
            ->first();

        if ($existingResult && $existingResult->score >= 60) {
            return redirect()->route('dashboard')
                ->with('info', 'Anda sudah mengerjakan post test ini dan mendapatkan nilai yang cukup.');
        }

        $key = "quiz_{$session->id}_questions_user_{$userId}";

        if (! session()->has($key)) {
            $questions = $session->questions()->inRandomOrder()->get();
            session([$key => $questions->pluck('id')->toArray()]);
        } else {
            $questionIds = session($key);
            $questions = $session->questions()
                ->whereIn('id', $questionIds)
                ->get()
                ->sortBy(function ($q) use ($questionIds) {
                    return array_search($q->id, $questionIds);
                })
                ->values();
        }

        $startKey = "quiz_{$session->id}_start_time_user_{$userId}";
        if (! session()->has($startKey)) {
            session([$startKey => now()]);
        }

        // Validasi nomor pertanyaan
        $totalQuestions = $questions->count();
        if ($number < 1 || $number > $totalQuestions) {
            return redirect()->route('post-test.question', ['slug' => $slug, 'number' => 1]);
        }

        // Ambil pertanyaan saat ini
        $currentQuestion = $questions->get($number - 1); // array index dimulai dari 0

        // Sinkronisasi jawaban dari localStorage ke session
        $answerKey = "quiz_answers_{$session->id}_user_{$userId}";
        $answers = session($answerKey, []);

        // Jika ada data di request (dari form submission), update session
        if (request()->has('answer') && request()->has('question_id')) {
            $answers[request('question_id')] = request('answer');
            session([$answerKey => $answers]);
        }

        $currentAnswer = $answers[$currentQuestion->id] ?? null;

        return view('post-test.attempt', compact('session', 'questions', 'currentQuestion', 'number', 'totalQuestions', 'currentAnswer'));
    }

    // Submit Kuis
    public function submitQuiz(Request $request, $slug)
    {
        if (! auth()->check()) {
            abort(403, 'Unauthorized.');
        }

        $session = PostTestSession::where('slug', $slug)->with('questions')->firstOrFail();

        // Proteksi: cek status
        if (! $session->status) { // Status now boolean
            return redirect()->route('post-test.index')
                ->with('error', 'Post test ini saat ini tidak tersedia.');
        }

        $userId = auth()->id();

        // Cek hasil sebelumnya
        $latestResult = PostTestResult::where('user_id', $userId)
            ->where('session_id', $session->id)
            ->latest()
            ->first();

        if ($latestResult && $latestResult->score >= 60) {
            return redirect()->route('dashboard')
                ->with('info', 'Anda sudah mengerjakan post test ini dengan nilai yang cukup.');
        }

        // Hapus nilai lama jika ada (dan skor < 75)
        if ($latestResult && $latestResult->score < 60) {
            $latestResult->delete();
        }

        // Ambil jawaban dari session
        $answerKey = "quiz_answers_{$session->id}_user_{$userId}";
        $answers = session($answerKey, []);

        // Ambil jawaban dari request input jika ada, kalau tidak gunakan session
        $answers = $request->input('answer', session($answerKey, []));

        // Hitung skor
        $correct = 0;
        $total = $session->questions->count();

        foreach ($session->questions as $question) {
            // Pastikan tipe id soal dan kunci jawaban konsisten dan hilangkan spasi
            $questionId = (string) $question->id;
            $userAnswer = isset($answers[$questionId]) ? trim(strtoupper($answers[$questionId])) : null;
            $correctOption = trim(strtoupper($question->correct_option));
            if ($userAnswer && $userAnswer === $correctOption) {
                $correct++;
            }
        }

        $score = $total > 0 ? round(($correct / $total) * 100, 2) : 0;

        // Simpan hasil baru
        $result = PostTestResult::create([
            'user_id' => $userId,
            'session_id' => $session->id,
            'score' => $score,
        ]);

        // Hapus data session setelah submit
        session()->forget([
            "quiz_{$session->id}_questions_user_{$userId}",
            "quiz_{$session->id}_start_time_user_{$userId}",
            $answerKey
        ]);

        return redirect()->route('post-test.result', $result->id)
            ->with('success', 'Post test berhasil dikumpulkan.');
    }

    // Lihat Hasil
    public function showResult(PostTestResult $result)
    {
        $user = $result->user;
        $session = PostTestSession::with('questions')->findOrFail($result->session_id);

        return view('post-test.result', compact('session', 'result', 'user'));
    }
}
