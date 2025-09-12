<?php

namespace App\Http\Controllers;

use App\Models\Ebook;
use App\Models\PostTest;
use App\Models\PostTestResult;
use App\Models\PostTestSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Mews\Purifier\Facades\Purifier;
use Illuminate\Support\Facades\Auth;

class PostTestController extends Controller
{
    // ====== Tambah soal (modal + Summernote) ======
    public function questionStore(Request $request, PostTestSession $session)
    {
        $v = Validator::make($request->all(), [
            'question_text'  => ['required', 'string', 'max:10000'],
            'option_a'       => ['required', 'string', 'max:1000'],
            'option_b'       => ['required', 'string', 'max:1000'],
            'option_c'       => ['required', 'string', 'max:1000'],
            'option_d'       => ['required', 'string', 'max:1000'],
            'correct_option' => ['required', 'in:A,B,C,D'],
        ]);

        $data = $v->validateWithBag('createQuestion');

        // Sanitasi HTML pertanyaan
        $clean = Purifier::clean($data['question_text'], 'default');
        $session->questions()->create([
            'question'       => $clean,            // <— kolom DB: question (HTML)
            'option_a'       => $data['option_a'],
            'option_b'       => $data['option_b'],
            'option_c'       => $data['option_c'],
            'option_d'       => $data['option_d'],
            'correct_option' => $data['correct_option'],
        ]);

        return back()->with('success', 'Soal berhasil ditambahkan.');
    }

    // ====== Update soal (modal + Summernote) ======
    public function questionUpdate(Request $request, PostTestSession $session, PostTest $question)
    {
        if ($question->session_id !== $session->id) abort(404);

        $v = Validator::make($request->all(), [
            'question_text'  => ['required', 'string', 'max:10000'],
            'option_a'       => ['required', 'string', 'max:1000'],
            'option_b'       => ['required', 'string', 'max:1000'],
            'option_c'       => ['required', 'string', 'max:1000'],
            'option_d'       => ['required', 'string', 'max:1000'],
            'correct_option' => ['required', 'in:A,B,C,D'],
            'question_id'    => ['nullable', 'integer'],
        ]);

        $data = $v->validateWithBag('updateQuestion');

        $clean = Purifier::clean($data['question_text'], 'default');
        $question->update([
            'question'       => $clean,            // <— simpan HTML tersanitasi
            'option_a'       => $data['option_a'],
            'option_b'       => $data['option_b'],
            'option_c'       => $data['option_c'],
            'option_d'       => $data['option_d'],
            'correct_option' => $data['correct_option'],
        ]);

        return back()->with('success', 'Soal berhasil diperbarui.');
    }

    // ====== Hapus soal ======
    public function questionDestroy(PostTestSession $session, PostTest $question)
    {
        if ($question->session_id !== $session->id) abort(404);
        $question->delete();
        return back()->with('success', 'Soal berhasil dihapus.');
    }

    // Mengerjakan Kuis
    public function showQuiz(PostTestSession $session)
    {
        $userId = auth()->id();

        // Cek hasil sebelumnya
        $existingResult = PostTestResult::where('user_id', $userId)
            ->where('session_id', $session->id)
            ->latest()
            ->first();

        if ($existingResult && $existingResult->score >= 75) {
            return redirect()->route('dashboard')
                ->with('info', 'Anda sudah mengerjakan post test ini dan mendapatkan nilai yang cukup.');
        }

        $key = "quiz_{$session->id}_questions_user_{$userId}";

        if (!session()->has($key)) {
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
        if (!session()->has($startKey)) {
            session([$startKey => now()]);
        }

        return view('post-test.index', compact('session', 'questions'));
    }

    // Submit Kuis
    public function submitQuiz(Request $request, $sessionId)
    {
        if (!auth()->check()) {
            abort(403, 'Unauthorized.');
        }

        $session = PostTestSession::with('questions')->findOrFail($sessionId);
        $userId = auth()->id();

        // Cek hasil sebelumnya
        $latestResult = PostTestResult::where('user_id', $userId)
            ->where('session_id', $session->id)
            ->latest()
            ->first();

        if ($latestResult && $latestResult->score >= 75) {
            return redirect()->route('dashboard')
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
            'score'      => $score,
        ]);

        return redirect()->route('posttest.result', $result->id)
            ->with('success', 'Post test berhasil dikumpulkan.');
    }

    // Lihat Hasil
    public function showResult(PostTestResult $result)
    {
        $user = $result->user;
        $session = PostTestSession::with('questions')->findOrFail($result->session_id);

        return view('post-test.result', compact('session', 'result', 'user'));
    }

    public function toggleStatus($slug)
    {
        // Find the post test by its slug
        $postTest = PostTestSession::where('slug', $slug)->firstOrFail();

        // Toggle the 'status' (Aktif -> Tidak Aktif or vice versa)
        $postTest->status = !$postTest->status;
        $postTest->save();

        // Redirect back with a success message
        return redirect()->route('posttest.index')->with('alert', 'Status updated successfully!');
    }
}
