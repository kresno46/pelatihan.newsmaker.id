<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Models\Question;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    /**
     * Tampilkan semua soal dari kuis tertentu (opsional).
     */
    public function index($slug)
    {
        $quiz = Quiz::where('slug', $slug)->firstOrFail();
        $questions = $quiz->questions;
        return view('question.index', compact('quiz', 'questions'));
    }

    /**
     * Tampilkan form tambah soal.
     */
    public function create($slug)
    {
        $quiz = Quiz::where('slug', $slug)->firstOrFail();
        return view('question.create', compact('quiz'));
    }

    /**
     * Simpan soal ke dalam kuis.
     */
    public function store(Request $request, $slug)
    {
        $quiz = Quiz::where('slug', $slug)->firstOrFail();

        $validated = $request->validate([
            'question' => 'required|string',
            'option_a' => 'required|string',
            'option_b' => 'required|string',
            'option_c' => 'required|string',
            'option_d' => 'required|string',
            'correct_option' => 'required|in:A,B,C,D',
        ]);

        $validated['quiz_id'] = $quiz->id;

        Question::create($validated);

        return redirect()->route('quiz.show', $quiz->slug)->with('success', 'Soal berhasil ditambahkan.');
    }

    /**
     * Tampilkan detail soal tertentu (opsional).
     */
    public function show($id)
    {
        $question = Question::findOrFail($id);
        return view('question.show', compact('question'));
    }

    /**
     * Tampilkan form edit soal.
     */
    public function edit($slug, $id)
    {
        $quiz = Quiz::where('slug', $slug)->firstOrFail();
        $question = $quiz->questions()->findOrFail($id);

        return view('question.edit', compact('question', 'quiz'));
    }

    /**
     * Update soal di database.
     */
    public function update(Request $request, $slug, $id)
    {
        // Cari quiz berdasarkan slug, kalau nggak ada langsung 404
        $quiz = Quiz::where('slug', $slug)->firstOrFail();

        // Cari question berdasarkan id dan pastikan question itu milik quiz yang sama
        $question = Question::where('id', $id)->where('quiz_id', $quiz->id)->firstOrFail();

        // Validasi input
        $validated = $request->validate([
            'question' => 'required|string',
            'option_a' => 'required|string',
            'option_b' => 'required|string',
            'option_c' => 'required|string',
            'option_d' => 'required|string',
            'correct_option' => 'required|in:A,B,C,D',
        ]);

        // Update question dengan data valid
        $question->update($validated);

        // Redirect ke halaman quiz show dengan slug, dan kirim pesan sukses
        return redirect()->route('quiz.show', $quiz->slug)->with('success', 'Soal berhasil diperbarui.');
    }

    /**
     * Hapus soal dari database.
     */
    public function destroy($slug, $id)
    {
        // Cari quiz berdasarkan slug, kalau nggak ada langsung 404
        $quiz = Quiz::where('slug', $slug)->firstOrFail();

        // Cari question berdasarkan id dan pastikan question itu milik quiz yang sama
        $question = Question::where('id', $id)->where('quiz_id', $quiz->id)->firstOrFail();

        // Simpan slug quiz sebelum hapus soal
        $quizSlug = $quiz->slug;

        // Hapus soal
        $question->delete();

        // Redirect ke halaman quiz show dengan pesan sukses
        return redirect()->route('quiz.show', $quizSlug)->with('success', 'Soal berhasil dihapus.');
    }
}
