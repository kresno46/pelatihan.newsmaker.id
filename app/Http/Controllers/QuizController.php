<?php

namespace App\Http\Controllers;

use App\Models\Ebook;
use Illuminate\Http\Request;
use App\Models\PostTestSession;
use App\Models\PostTest;
use Illuminate\Support\Facades\DB;


class QuizController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($slug)
    {
        // Cari ebook berdasarkan slug
        $ebook = Ebook::where('slug', $slug)->firstOrFail();

        // Cari kuis (session) yang terkait dengan ebook ini, misalnya yang terakhir dibuat
        $quiz = PostTestSession::where('ebook_id', $ebook->id)->latest()->first();

        // Jika ada quiz, ambil soal-soalnya, kalau tidak, null
        if ($quiz) {
            $questions = PostTest::where('session_id', $quiz->id)->get()->map(function ($item) {
                return [
                    'question' => $item->question,
                    'option_a' => $item->option_a,
                    'option_b' => $item->option_b,
                    'option_c' => $item->option_c,
                    'option_d' => $item->option_d,
                    'correct_option' => strtoupper($item->correct_option),
                ];
            })->toArray();
        } else {
            $questions = null;
        }

        return view('quiz.index', compact('slug', 'quiz', 'questions'));
    }


    public function saveQuiz(Request $request, $slug, $sessionId = null)
    {
        // Cari ebook sesuai slug
        $ebook = Ebook::where('slug', $slug)->firstOrFail();

        $request->validate([
            'title' => 'required|string',
            'duration' => 'required|integer|min:1',
            'questions' => 'required|array|min:1',
            'questions.*.question' => 'required|string',
            'questions.*.option_A' => 'required|string',
            'questions.*.option_B' => 'required|string',
            'questions.*.option_C' => 'nullable|string',
            'questions.*.option_D' => 'nullable|string',
            'questions.*.correct_option' => 'required|in:A,B,C,D',
        ]);

        if ($sessionId) {
            // Update mode
            $session = PostTestSession::where('id', $sessionId)
                ->where('ebook_id', $ebook->id)
                ->firstOrFail();

            // Update session title dan duration
            $session->update([
                'title' => $request->title,
                'duration' => $request->duration,
            ]);

            // Tambahkan soal baru tanpa hapus soal lama
            foreach ($request->questions as $q) {
                PostTest::create([
                    'session_id' => $session->id,
                    'question' => $q['question'],
                    'option_a' => $q['option_A'],
                    'option_b' => $q['option_B'],
                    'option_c' => $q['option_C'],
                    'option_d' => $q['option_D'],
                    'correct_option' => $q['correct_option'],
                ]);
            }
        } else {
            // Store mode (buat session baru)
            $session = PostTestSession::create([
                'ebook_id' => $ebook->id,
                'title' => $request->title,
                'duration' => $request->duration,
            ]);

            // Simpan semua soal baru
            foreach ($request->questions as $q) {
                PostTest::create([
                    'session_id' => $session->id,
                    'question' => $q['question'],
                    'option_a' => $q['option_A'],
                    'option_b' => $q['option_B'],
                    'option_c' => $q['option_C'],
                    'option_d' => $q['option_D'],
                    'correct_option' => $q['correct_option'],
                ]);
            }
        }

        return redirect()->route('quiz.index', ['slug' => $slug])
            ->with('success', $sessionId ? 'Post test berhasil diperbarui dan soal baru ditambahkan!' : 'Post test berhasil dibuat!');
    }
}
