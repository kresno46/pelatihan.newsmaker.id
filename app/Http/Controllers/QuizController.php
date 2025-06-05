<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\Quiz;
use Illuminate\Http\Request;

class QuizController extends Controller
{
    /**
     * Tampilkan semua kuis (untuk admin), dengan pencarian dan pagination.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $quizzes = Quiz::with('questions')
            ->when($search, function ($query, $search) {
                $query->where('title', 'like', "%{$search}%");
            })
            // Cek role user, filter hanya public jika bukan admin
            ->when(auth()->user()->role !== 'Admin', function ($query) {
                $query->where('status', 'public');
            })
            ->latest()
            ->paginate(8);

        return view('quiz.index', compact('quizzes', 'search'));
    }

    /**
     * Tampilkan form tambah kuis.
     */
    public function create()
    {
        return view('quiz.create');
    }

    /**
     * Simpan kuis yang baru dibuat oleh admin.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            // 'status' => 'required|in:public,private',
        ]);

        // Simpan kuis baru
        $quiz = Quiz::create([
            'title' => $request->title,
            'description' => $request->description,
            // 'status' => $request->status,
        ]);

        return redirect()->route('quiz.index')->with('success', 'Kuis berhasil ditambahkan!');
    }

    /**
     * Tampilkan form edit kuis.
     */
    public function edit($id)
    {
        $quiz = Quiz::findOrFail($id);

        return view('quiz.edit', compact('quiz'));
    }

    /**
     * Update kuis yang sudah ada diedit oleh admin.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        // Update kuis
        $quiz = Quiz::find($id);

        $quiz->update([
            'title' => $request->title,
            'description' => $request->description,
        ]);

        return redirect()->route('quiz.index')->with('success', 'Kuis berhasil diupdate!');
    }

    /**
     * Tampilkan detail kuis dan semua pertanyaannya.
     *
     */
    public function show($slug)
    {
        // Ambil kuis berdasarkan slug
        $quiz = Quiz::where('slug', $slug)->with('questions')->firstOrFail();

        $questions = Question::where('quiz_id', $quiz->id)->get();

        return view('quiz.show', compact('quiz', 'questions'));
    }

    /**
     * Hapus kuis yang sudah ada oleh admin.
     */
    public function destroy($id)
    {
        $quiz = Quiz::find($id);

        $quiz->delete();

        return redirect()->route('quiz.index')->with('success', 'Kuis berhasil dihapus.');
    }
}
