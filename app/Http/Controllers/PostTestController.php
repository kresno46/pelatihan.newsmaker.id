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
use Illuminate\Support\Facades\DB;

class PostTestController extends Controller
{
    // ====== Tambah soal (modal + Summernote) ======
    public function questionStore(Request $request, PostTestSession $id)
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
        $id->questions()->create([
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
    public function questionUpdate(Request $request, PostTestSession $id, PostTest $question)
    {
        if ($question->session_id !== $id->id) abort(404);

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
    public function questionDestroy(PostTestSession $id, PostTest $question)
    {
        if ($question->session_id !== $id->id) abort(404);
        $question->delete();
        return back()->with('success', 'Soal berhasil dihapus.');
    }

    // Mengerjakan Kuis
    public function showQuiz(PostTestSession $id)
    {
        $userId = auth()->id();

        // Cek hasil sebelumnya
        $existingResult = PostTestResult::where('user_id', $userId)
            ->where('session_id', $id->id)
            ->latest()
            ->first();

        if ($existingResult && $existingResult->score >= 60) {
            return redirect()->route('dashboard')
                ->with('info', 'Anda sudah mengerjakan post test ini dan mendapatkan nilai yang cukup.');
        }

        $key = "quiz_{$id->id}_questions_user_{$userId}";

        if (!session()->has($key)) {
            $questions = $id->questions()->inRandomOrder()->get();
            session([$key => $questions->pluck('id')->toArray()]);
        } else {
            $questionIds = session($key);
            $questions = $id->questions()
                ->whereIn('id', $questionIds)
                ->get()
                ->sortBy(function ($q) use ($questionIds) {
                    return array_search($q->id, $questionIds);
                })
                ->values();
        }

        $startKey = "quiz_{$id->id}_start_time_user_{$userId}";
        if (!session()->has($startKey)) {
            session([$startKey => now()]);
        }

        return view('post-test.index', compact('id', 'questions'));
    }

    // Submit Kuis
    public function submitQuiz(Request $request, PostTestSession $id)
    {
        $userId = Auth::id();
        $answers = $request->input('answer', []);

        // Ambil semua soal dalam sesi, bukan hanya soal yang dijawab
        $questions = PostTest::where('session_id', $id->id)->get();

        if ($questions->isEmpty()) {
            return redirect()->route('post-test.show', $id->id)
                ->with('error', 'Tidak ada soal dalam sesi ini.');
        }

        $totalQuestions = $questions->count();
        $correct = 0;
        $wrong = 0;

        foreach ($questions as $question) {
            $userAnswer = $answers[$question->id] ?? null;

            if ($userAnswer) {
                if (strtoupper($userAnswer) === strtoupper($question->correct_option)) {
                    $correct++;
                } else {
                    $wrong++;
                }
            } else {
                // Kalau soal tidak dijawab, dianggap salah
                $wrong++;
            }
        }

        // Hitung skor dari jawaban benar dibanding total soal
        $score = round(($correct / $totalQuestions) * 100);

        DB::transaction(function () use ($userId, $id, $correct, $wrong, $score, $answers) {
            PostTestResult::updateOrCreate(
                [
                    'user_id' => $userId,
                    'session_id' => $id->id,
                ],
                [
                    'correct' => $correct,
                    'wrong' => $wrong,
                    'score' => $score,
                    'answers' => json_encode($answers),
                    'submitted_at' => now(),
                ]
            );
        });

        return redirect()->route('post-test.result', ['session' => $id->id])
            ->with('success', 'Jawaban berhasil dikirim!');
    }

    // Lihat Hasil
    public function showResult(PostTestResult $result)
    {
        $user = $result->user;
        $session = PostTestSession::with('questions')->findOrFail($result->session_id);

        return view('post-test.result', compact('session', 'result', 'user'));
    }

    public function toggleStatus(PostTestSession $id)
    {
        // Toggle the 'status' (Aktif -> Tidak Aktif or vice versa)
        $id->status = !$id->status;
        $id->save();

        // Redirect back with a success message
        return redirect()->route('posttest.index')->with('alert', 'Status updated successfully!');
    }
}
