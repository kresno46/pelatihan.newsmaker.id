<?php

namespace App\Http\Controllers;

use App\Models\Ebook;
use App\Models\PostTestResult;
use App\Models\PostTestSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostTestController extends Controller
{
    public function showQuiz($folderSlug, $ebookSlug, PostTestSession $session)
    {
        $ebook = Ebook::where('slug', $ebookSlug)->firstOrFail();

        if ($session->ebook_id !== $ebook->id) {
            abort(404, 'Sesi tidak sesuai dengan ebook.');
        }

        $userId = auth()->id();

        // Cek hasil sebelumnya
        $existingResult = PostTestResult::where('user_id', $userId)
            ->where('session_id', $session->id)
            ->latest()
            ->first();

        if ($existingResult && $existingResult->score >= 75) {
            return redirect()->route('ebook.show', [$folderSlug, $ebookSlug])
                ->with('info', 'Anda sudah mengerjakan post test ini dan mendapatkan nilai yang cukup.');
        }

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

        $startKey = "quiz_{$session->id}_start_time_user_{$userId}";
        if (!session()->has($startKey)) {
            session([$startKey => now()]);
        }

        return view('post-test.index', compact('ebook', 'session', 'questions', 'folderSlug', 'ebookSlug'));
    }

    public function submitQuiz(Request $request, $folderSlug, $ebookSlug, $sessionId)
    {
        if (!auth()->check()) {
            abort(403, 'Unauthorized.');
        }

        $ebook = Ebook::where('slug', $ebookSlug)->firstOrFail();
        $session = PostTestSession::with('questions')->findOrFail($sessionId);

        if ($session->ebook_id !== $ebook->id) {
            abort(404, 'Sesi tidak sesuai dengan ebook.');
        }

        $userId = auth()->id();

        // Cek hasil sebelumnya
        $latestResult = PostTestResult::where('user_id', $userId)
            ->where('session_id', $session->id)
            ->latest()
            ->first();

        if ($latestResult && $latestResult->score >= 75) {
            return redirect()->route('ebook.show', [$folderSlug, $ebookSlug])
                ->with('info', 'Anda sudah mengerjakan post test ini dengan nilai yang cukup.');
        }

        // Hapus nilai lama jika ada (dan skor < 75)
        if ($latestResult && $latestResult->score < 75) {
            $latestResult->delete();
        }

        // Hitung skor
        $answers = $request->input('answer', []);
        $correct = 0;
        $total = count($session->questions);

        foreach ($session->questions as $question) {
            $userAnswer = $answers[$question->id] ?? null;
            if ($userAnswer && strtoupper($userAnswer) === $question->correct_option) {
                $correct++;
            }
        }

        $score = $total > 0 ? round(($correct / $total) * 100, 2) : 0;

        // Simpan hasil baru
        $result = PostTestResult::create([
            'user_id'    => $userId,
            'session_id' => $session->id,
            'ebook_id'   => $ebook->id,
            'score'      => $score,
        ]);

        return redirect()->route('posttest.result', [
            'folderSlug' => $folderSlug,
            'ebookSlug'  => $ebookSlug,
            'result'     => $result->id,
        ])->with('success', 'Post test berhasil dikumpulkan.');
    }


    public function showResult(PostTestResult $result)
    {
        $user = $result->user;
        $ebook = $result->ebook;
        $folderSlug = $ebook->folderEbook->slug ?? null;
        $ebookSlug = $ebook->slug;
        $session = PostTestSession::with('questions', 'ebook')->findOrFail($result->session_id);

        return view('post-test.result', compact('session', 'result', 'folderSlug', 'ebookSlug', 'user'));
    }
}
