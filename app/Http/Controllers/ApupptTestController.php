<?php

namespace App\Http\Controllers;

use App\Models\ApupptCertificateAward;
use App\Models\ApupptPostTestResult;
use App\Models\ApupptPostTestSession;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ApupptTestController extends Controller
{
    public function index()
    {
        $userId = auth()->id();
        $userRole = auth()->user()->role;

        $tests = ApupptPostTestSession::where('tipe', 'APUPPT')
            ->where('apuppt_pt_scope', $userRole)
            ->get()
            ->map(function ($test) use ($userId) {
            $result = ApupptPostTestResult::where('user_id', $userId)
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

        $ebookTests = $tests->whereNotNull('ebook_id')->where('status', true)->values();
        $postTests = $tests->whereNull('ebook_id')->where('status', true)->values();
        $type = request()->get('type', 'posttest');
        if (! in_array($type, ['posttest', 'ebook'], true)) {
            $type = 'posttest';
        }

        return view('apuppt.post-test-user.index', compact('ebookTests', 'postTests', 'type'));
    }

    public function showQuiz($slug)
    {
        $session = ApupptPostTestSession::where('slug', $slug)->firstOrFail();
        if ($session->apuppt_pt_scope !== auth()->user()->role) {
            abort(403);
        }

        if ($session->tipe !== 'APUPPT') {
            return redirect()->route('apuppt.test.index')->with('error', 'Tes ini bukan kategori APUPPT.');
        }

        if (! $session->status) {
            return redirect()->route('apuppt.test.index')->with('error', 'Post test APUPPT ini saat ini tidak tersedia.');
        }

        $userId = auth()->id();
        $existingResult = ApupptPostTestResult::where('user_id', $userId)
            ->where('session_id', $session->id)
            ->latest()
            ->first();

        if ($existingResult && $existingResult->score >= 60) {
            return redirect()->route('dashboard')->with('info', 'Anda sudah mengerjakan post test APUPPT ini dan mendapatkan nilai yang cukup.');
        }

        return redirect()->route('apuppt.test.question', ['slug' => $slug, 'number' => 1]);
    }

    public function showQuestion($slug, $number)
    {
        $session = ApupptPostTestSession::where('slug', $slug)->with('questions')->firstOrFail();
        if ($session->apuppt_pt_scope !== auth()->user()->role) {
            abort(403);
        }

        if ($session->tipe !== 'APUPPT') {
            return redirect()->route('apuppt.test.index')->with('error', 'Tes ini bukan kategori APUPPT.');
        }

        if (! $session->status) {
            return redirect()->route('apuppt.test.index')->with('error', 'Post test APUPPT ini saat ini tidak tersedia.');
        }

        $userId = auth()->id();

        $existingResult = ApupptPostTestResult::where('user_id', $userId)
            ->where('session_id', $session->id)
            ->latest()
            ->first();

        if ($existingResult && $existingResult->score >= 60) {
            return redirect()->route('dashboard')->with('info', 'Anda sudah mengerjakan post test APUPPT ini dengan nilai cukup.');
        }

        $key = "apuppt_quiz_{$session->id}_questions_user_{$userId}";

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

        $startKey = "apuppt_quiz_{$session->id}_start_time_user_{$userId}";
        if (! session()->has($startKey)) {
            session([$startKey => now()]);
        }

        $totalQuestions = $questions->count();
        if ($number < 1 || $number > $totalQuestions) {
            return redirect()->route('apuppt.test.question', ['slug' => $slug, 'number' => 1]);
        }

        $currentQuestion = $questions->get($number - 1);

        $answerKey = "apuppt_quiz_answers_{$session->id}_user_{$userId}";
        $answers = session($answerKey, []);

        if (request()->has('answer') && request()->has('question_id')) {
            $answers[request('question_id')] = request('answer');
            session([$answerKey => $answers]);
        }

        $currentAnswer = $answers[$currentQuestion->id] ?? null;

        return view('apuppt.post-test-user.attempt', compact('session', 'questions', 'currentQuestion', 'number', 'totalQuestions', 'currentAnswer'));
    }

    public function submitQuiz(Request $request, $slug)
    {
        $session = ApupptPostTestSession::where('slug', $slug)->with('questions')->firstOrFail();
        if ($session->apuppt_pt_scope !== auth()->user()->role) {
            abort(403);
        }

        if ($session->tipe !== 'APUPPT') {
            return redirect()->route('apuppt.test.index')->with('error', 'Tes ini bukan kategori APUPPT.');
        }

        if (! $session->status) {
            return redirect()->route('apuppt.test.index')->with('error', 'Post test APUPPT ini saat ini tidak tersedia.');
        }

        $userId = auth()->id();

        $latestResult = ApupptPostTestResult::where('user_id', $userId)
            ->where('session_id', $session->id)
            ->latest()
            ->first();

        if ($latestResult && $latestResult->score >= 60) {
            return redirect()->route('dashboard')->with('info', 'Anda sudah mengerjakan post test APUPPT ini dengan nilai cukup.');
        }

        if ($latestResult && $latestResult->score < 60) {
            $latestResult->delete();
        }

        $answerKey = "apuppt_quiz_answers_{$session->id}_user_{$userId}";
        $answers = $request->input('answer', session($answerKey, []));

        $correct = 0;
        $total = $session->questions->count();

        foreach ($session->questions as $question) {
            $questionId = (string) $question->id;
            $userAnswer = isset($answers[$questionId]) ? trim(strtoupper($answers[$questionId])) : null;
            $correctOption = trim(strtoupper($question->correct_option));
            if ($userAnswer && $userAnswer === $correctOption) {
                $correct++;
            }
        }

        $score = $total > 0 ? round(($correct / $total) * 100, 2) : 0;

        $result = ApupptPostTestResult::create([
            'user_id' => $userId,
            'session_id' => $session->id,
            'score' => $score,
        ]);

        // Simpan data sertifikat otomatis untuk peserta APUPPT yang lulus.
        if ($score >= 60) {
            $existingAward = ApupptCertificateAward::where('user_id', $userId)
                ->where('post_test_id', $result->id)
                ->first();

            if (! $existingAward) {
                ApupptCertificateAward::create([
                    'user_id' => $userId,
                    'post_test_id' => $result->id,
                    'average_score' => $score,
                    'certificate_uuid' => (string) Str::uuid(),
                    'awarded_at' => now(),
                ]);
            }
        }

        session()->forget([
            "apuppt_quiz_{$session->id}_questions_user_{$userId}",
            "apuppt_quiz_{$session->id}_start_time_user_{$userId}",
            $answerKey,
        ]);

        return redirect()->route('apuppt.test.result', $result->id)->with('success', 'Post test APUPPT berhasil dikumpulkan.');
    }

    public function showResult(ApupptPostTestResult $result)
    {
        if ($result->user_id !== auth()->id()) {
            abort(403);
        }

        $user = $result->user;
        $session = ApupptPostTestSession::with('questions')->findOrFail($result->session_id);

        return view('apuppt.post-test-user.result', compact('session', 'result', 'user'));
    }
}
