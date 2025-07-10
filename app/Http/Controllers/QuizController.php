<?php

namespace App\Http\Controllers;

use App\Models\Ebook;
use App\Models\PostTestSession;
use App\Models\PostTest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class QuizController extends Controller
{
    /**
     * Tampilkan halaman kuis berdasarkan folder dan ebook slug.
     *
     * @param  string  $folderSlug
     * @param  string  $ebookSlug
     * @return \Illuminate\View\View
     */
    public function index($folderSlug, $ebookSlug)
    {
        $ebook = Ebook::where('slug', $ebookSlug)->firstOrFail();
        $quiz = PostTestSession::where('ebook_id', $ebook->id)->latest()->first();

        $questions = $quiz ? PostTest::where('session_id', $quiz->id)->get()->map(function ($item) {
            return [
                'id'             => $item->id,
                'question'       => $item->question,
                'option_a'       => $item->option_a,
                'option_b'       => $item->option_b,
                'option_c'       => $item->option_c,
                'option_d'       => $item->option_d,
                'correct_option' => strtoupper($item->correct_option),
            ];
        })->toArray() : null;

        return view('quiz.index', compact('folderSlug', 'ebookSlug', 'quiz', 'questions'));
    }

    /**
     * Simpan sesi post test baru berdasarkan ebook.
     */
    public function store(Request $request, $folderSlug, $ebookSlug)
    {
        $ebook = Ebook::where('slug', $ebookSlug)->firstOrFail();

        $validator = Validator::make($request->all(), [
            'title'    => 'required|string|max:100',
            'duration' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput();
        }

        PostTestSession::create([
            'title'    => $request->title,
            'duration' => $request->duration,
            'ebook_id' => $ebook->id,
        ]);

        return redirect()->route('quiz.index', [$folderSlug, $ebookSlug])->with('success', 'Sesi post test berhasil dibuat.');
    }

    /**
     * Update sesi post test.
     */
    public function update(Request $request, $folderSlug, $ebookSlug, $sessionId)
    {
        $session = PostTestSession::findOrFail($sessionId);

        $validator = Validator::make($request->all(), [
            'title'    => 'required|string|max:100',
            'duration' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput();
        }

        $session->update([
            'title'    => $request->title,
            'duration' => $request->duration,
        ]);

        return redirect()->route('quiz.index', [$folderSlug, $ebookSlug])->with('success', 'Sesi post test berhasil diperbarui.');
    }

    /**
     * Tampilkan form tambah pertanyaan.
     */
    public function addQuestionShow($folderSlug, $ebookSlug, $sessionId)
    {
        $session = PostTestSession::findOrFail($sessionId);

        return view('quiz.add-question', [
            'folderSlug' => $folderSlug,
            'ebookSlug'  => $ebookSlug,
            'session'    => $session,
        ]);
    }

    /**
     * Simpan pertanyaan baru.
     */
    public function addQuestionStore(Request $request, $folderSlug, $ebookSlug, $sessionId)
    {
        $session = PostTestSession::findOrFail($sessionId);

        $validator = Validator::make($request->all(), [
            'question'       => 'required|string',
            'option_a'       => 'required|string|max:255',
            'option_b'       => 'required|string|max:255',
            'option_c'       => 'nullable|string|max:255',
            'option_d'       => 'nullable|string|max:255',
            'correct_option' => 'required|in:A,B,C,D',
        ]);

        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput();
        }

        PostTest::create([
            'question'       => $request->question,
            'option_a'       => $request->option_a,
            'option_b'       => $request->option_b,
            'option_c'       => $request->option_c,
            'option_d'       => $request->option_d,
            'correct_option' => $request->correct_option,
            'session_id'     => $session->id,
        ]);

        return redirect()->route('quiz.index', [$folderSlug, $ebookSlug])->with('Alert', 'Pertanyaan berhasil ditambahkan.');
    }

    /**
     * Hapus pertanyaan.
     */
    public function deleteQuestion($folderSlug, $ebookSlug, $sessionId, $questionId)
    {
        $question = PostTest::where('session_id', $sessionId)->where('id', $questionId)->firstOrFail();
        $question->delete();

        return redirect()->route('quiz.index', [$folderSlug, $ebookSlug])->with('Alert', 'Pertanyaan berhasil dihapus.');
    }

    /**
     * Tampilkan form edit pertanyaan.
     */
    public function editQuestion($folderSlug, $ebookSlug, $sessionId, $questionId)
    {
        $session = PostTestSession::findOrFail($sessionId);
        $question = PostTest::where('session_id', $sessionId)->where('id', $questionId)->firstOrFail();

        return view('quiz.edit-question', compact('folderSlug', 'ebookSlug', 'session', 'question'));
    }

    /**
     * Update pertanyaan.
     */
    public function updateQuestion(Request $request, $folderSlug, $ebookSlug, $sessionId, $questionId)
    {
        $request->validate([
            'question'       => 'required|string',
            'option_a'       => 'required|string|max:255',
            'option_b'       => 'required|string|max:255',
            'option_c'       => 'required|string|max:255',
            'option_d'       => 'required|string|max:255',
            'correct_option' => 'required|in:A,B,C,D',
        ]);

        $question = PostTest::where('session_id', $sessionId)->where('id', $questionId)->firstOrFail();

        $question->update([
            'question'       => $request->question,
            'option_a'       => $request->option_a,
            'option_b'       => $request->option_b,
            'option_c'       => $request->option_c,
            'option_d'       => $request->option_d,
            'correct_option' => $request->correct_option,
        ]);

        return redirect()->route('quiz.index', [$folderSlug, $ebookSlug])->with('Alert', 'Pertanyaan berhasil diperbarui.');
    }
}
