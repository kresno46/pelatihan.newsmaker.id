<?php

namespace App\Http\Controllers;

use App\Models\Ebook;
use App\Models\PostTest;
use App\Models\PostTestResult;
use App\Models\PostTestSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostTestController extends Controller
{
    public function showQuiz($slug, PostTestSession $session)
    {
        $ebook = Ebook::where('slug', $slug)->firstOrFail();

        if ($session->ebook_id !== $ebook->id) {
            abort(404, 'Sesi tidak sesuai dengan ebook.');
        }

        $userId = auth()->id();

        // Cek jika sudah pernah mengerjakan
        $existingResult = PostTestResult::where('user_id', $userId)
            ->where('session_id', $session->id)
            ->exists();

        if ($existingResult) {
            return redirect()->route('ebook.show', $slug)
                ->with('info', 'Anda sudah mengerjakan post test ini sebelumnya.');
        }

        // Ambil soal dari session jika sudah ada, kalau tidak acak dan simpan
        $key = "quiz_{$session->id}_questions_user_{$userId}";
        if (!session()->has($key)) {
            $questions = $session->questions()->inRandomOrder()->get();
            session([$key => $questions->pluck('id')->toArray()]);
        } else {
            $questionIds = session($key);
            $questions = $session->questions()->whereIn('id', $questionIds)->get()->sortBy(function ($q) use ($questionIds) {
                return array_search($q->id, $questionIds);
            })->values();
        }

        // Simpan waktu mulai
        $startKey = "quiz_{$session->id}_start_time_user_{$userId}";
        if (!session()->has($startKey)) {
            session([$startKey => now()]);
        }

        return view('post-test.index', compact('ebook', 'session', 'questions'));
    }


    public function submitQuiz(Request $request, $slug, $sessionId)
    {
        if (!auth()->check()) {
            abort(403, 'Unauthorized. Anda tidak login.');
        }

        $session = PostTestSession::with('questions')->findOrFail($sessionId);
        $ebook = Ebook::where('slug', $slug)->firstOrFail();

        // Validasi session cocok dengan ebook
        if ($session->ebook_id !== $ebook->id) {
            abort(404, 'Sesi tidak sesuai dengan ebook.');
        }

        // Cegah user submit ulang
        $existingResult = PostTestResult::where('user_id', auth()->id())
            ->where('session_id', $session->id)
            ->exists();

        if ($existingResult) {
            return redirect()->route('ebook.show', $slug)
                ->with('info', 'Anda sudah mengerjakan post test ini sebelumnya.');
        }

        $answers = $request->input('answer', []);
        $correct = 0;
        $total = count($session->questions);

        foreach ($session->questions as $question) {
            $userAnswer = $answers[$question->id] ?? null;
            if ($userAnswer && strtoupper($userAnswer) === $question->correct_option) {
                $correct++;
            }
        }

        // Hitung score dalam bentuk persentase (maksimal 100)
        $score = $total > 0 ? round(($correct / $total) * 100, 2) : 0;

        // Simpan hasil post test
        $result = PostTestResult::create([
            'user_id'    => auth()->id(),
            'session_id' => $session->id,
            'ebook_id'   => $ebook->id,
            'score'      => $score, // sekarang score adalah nilai 0–100
        ]);

        return redirect()->route('posttest.result', $result->id)
            ->with('success', 'Post test berhasil dikumpulkan.');
    }


    public function showResult($resultId)
    {
        $result = PostTestResult::with('ebook') // tambahkan eager load relasi
            ->where('user_id', Auth::id())
            ->where('id', $resultId) // ✅ yang benar: cari berdasarkan result.id
            ->firstOrFail();

        $session = PostTestSession::with('questions', 'ebook')->findOrFail($result->session_id);

        return view('post-test.result', compact('session', 'result'));
    }
}
