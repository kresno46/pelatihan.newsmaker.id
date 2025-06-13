<?php

namespace App\Http\Controllers;

use App\Models\Ebook;
use Illuminate\Http\Request;
use App\Models\PostTestSession;
use App\Models\PostTest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class QuizController extends Controller
{
    /**
     * Tampilkan halaman kuis berdasarkan slug ebook.
     *
     * @param  string  $slug
     * @return \Illuminate\View\View
     */
    public function index($slug)
    {
        // Ambil ebook berdasarkan slug
        $ebook = Ebook::where('slug', $slug)->firstOrFail();

        // Ambil sesi post test terbaru dari ebook ini
        $quiz = PostTestSession::where('ebook_id', $ebook->id)->latest()->first();

        // Ambil semua pertanyaan jika sesi ditemukan
        $questions = $quiz
            ? PostTest::where('session_id', $quiz->id)->get()->map(function ($item) {
                return [
                    'id'             => $item->id, // ✅ Tambahkan ini
                    'question'       => $item->question,
                    'option_a'       => $item->option_a,
                    'option_b'       => $item->option_b,
                    'option_c'       => $item->option_c,
                    'option_d'       => $item->option_d,
                    'correct_option' => strtoupper($item->correct_option),
                ];
            })->toArray()
            : null;

        return view('quiz.index', compact('slug', 'quiz', 'questions'));
    }

    /**
     * Simpan sesi post test baru berdasarkan ebook.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Ambil ebook berdasarkan slug yang dikirim dari form
        $ebook = Ebook::where('slug', $request->slug)->firstOrFail();

        // Validasi input
        $validator = Validator::make($request->all(), [
            'title'    => ['required', 'string', 'max:100'],
            'duration' => ['required', 'integer', 'min:1'], // durasi minimal 1 menit
        ]);

        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput();
        }

        // Simpan sesi baru
        PostTestSession::create([
            'title'    => $request->title,
            'duration' => $request->duration,
            'ebook_id' => $ebook->id,
        ]);

        return redirect()->back()->with('success', 'Sesi post test berhasil dibuat.');
    }

    /**
     * Update sesi post test baru berdasarkan ebook.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $slug, $sessionId)
    {
        $session = PostTestSession::findOrFail($sessionId);

        $validator = Validator::make($request->all(), [
            'title'    => ['required', 'string', 'max:100'],
            'duration' => ['required', 'integer', 'min:1'],
        ]);

        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput();
        }

        $session->update([
            'title'    => $request->title,
            'duration' => $request->duration,
        ]);

        return redirect()->back()->with('success', 'Sesi post test berhasil diperbarui.');
    }

    /**
     * Tampilkan form tambah pertanyaan dan simpan ke database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $slug
     * @param  int  $sessionId
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function addQuestionShow($slug, $sessionId)
    {
        $session = PostTestSession::findOrFail($sessionId);

        return view('quiz.add-question', [
            'slug' => $slug,
            'sessionId' => $session,
        ]);
    }

    /** 
     * Store pertanyaan baru ke database.
     * 
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $slug
     * @param  int  $sessionId
     */
    public function addQuestionStore(Request $request, $slug, $sessionId)
    {
        $session = PostTestSession::findOrFail($sessionId);

        $validator = Validator::make($request->all(), [
            'question'       => ['required', 'string'],
            'option_a'       => ['required', 'string', 'max:255'],
            'option_b'       => ['required', 'string', 'max:255'],
            'option_c'       => ['nullable', 'string', 'max:255'],
            'option_d'       => ['nullable', 'string', 'max:255'],
            'correct_option' => ['required', 'in:A,B,C,D'],
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

        return redirect()->route('quiz.index', $slug)->with('Alert', 'Pertanyaan berhasil ditambahkan.');
    }

    /**
     * Hapus pertanyaan berdasarkan ID.
     *
     * @param  string  $slug
     * @param  int  $sessionId
     * @param  int  $questionId
     * @return \Illuminate\Http\RedirectResponse
     * */
    public function deleteQuestion($slug, $sessionId, $questionId)
    {
        $session = PostTestSession::findOrFail($sessionId);
        $question = PostTest::where('session_id', $session->id)->where('id', $questionId)->firstOrFail();
        $question->delete();
        return redirect()->route('quiz.index', $slug)->with('Alert', 'Pertanyaan berhasil dihapus.');
    }

    public function editQuestion($slug, $sessionId, $questionId)
    {
        $session = PostTestSession::findOrFail($sessionId);
        $question = PostTest::where('session_id', $session->id)->where('id', $questionId)->firstOrFail();

        return view('quiz.edit-question', compact('slug', 'session', 'question'));
    }

    public function updateQuestion(Request $request, $slug, $sessionId, $questionId)
    {
        $request->validate([
            'question' => 'required|string',
            'option_a' => 'required|string',
            'option_b' => 'required|string',
            'option_c' => 'required|string',
            'option_d' => 'required|string',
            'correct_option' => 'required|in:A,B,C,D',
        ]);

        $question = PostTest::where('session_id', $sessionId)->where('id', $questionId)->firstOrFail();

        $question->question = $request->question;
        $question->option_a = $request->option_a;
        $question->option_b = $request->option_b;
        $question->option_c = $request->option_c;
        $question->option_d = $request->option_d;
        $question->correct_option = $request->correct_option;
        $question->save();

        return redirect()->route('quiz.index', $slug)->with('Alert', 'Pertanyaan berhasil diperbarui.');
    }
}
