<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PostTestSession;
use App\Models\PostTestResult;
use App\Models\Absensi;

class TestController extends Controller
{
    public function index(Request $request)
    {
        $tipe = $request->get('tipe', 'PATD'); // default PATD
        $userId = auth()->id();

        $tests = PostTestSession::where('tipe', $tipe)->get()->map(function ($test) use ($userId) {
            $result = PostTestResult::where('user_id', $userId)
                ->where('session_id', $test->id)
                ->latest()
                ->first();

            if (!$result) {
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

        return view('post-test.index', compact('tests'));
    }

    // Mengerjakan Kuis berdasarkan id
    public function showQuiz(PostTestSession $session)
    {
        $session->load('questions');

        // Proteksi: cek status
        if (!$session->status) { // Status now boolean
            return redirect()->route('post-test.index')
                ->with('error', 'Post test ini saat ini tidak tersedia.');
        }

        $userId = auth()->id();

        // Cek apakah user sudah absen untuk session ini
        $jadwalIds = $session->jadwalAbsensis->pluck('id');
        $absenExists = Absensi::where('user_id', $userId)
            ->whereIn('jadwal_id', $jadwalIds)
            ->exists();

        if (!$absenExists) {
            return redirect()->route('post-test.index')
                ->with('error', 'Anda harus mengisi absensi terlebih dahulu sebelum mengerjakan post test.');
        }

        // Cek hasil sebelumnya
        $existingResult = PostTestResult::where('user_id', $userId)
            ->where('session_id', $session->id)
            ->latest()
            ->first();

        if ($existingResult && $existingResult->score >= 60) {
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

        return view('post-test.attempt', compact('session', 'questions'));
    }

    // Submit Kuis
    public function submitQuiz(Request $request, PostTestSession $session)
    {
        if (!auth()->check()) {
            abort(403, 'Unauthorized.');
        }

        $session->load('questions');

        // Proteksi: cek status
        if (!$session->status) { // Status now boolean
            return redirect()->route('post-test.index')
                ->with('error', 'Post test ini saat ini tidak tersedia.');
        }

        $userId = auth()->id();

        // Cek apakah user sudah absen untuk session ini
        $jadwalIds = $session->jadwalAbsensis->pluck('id');
        $absenExists = Absensi::where('user_id', $userId)
            ->whereIn('jadwal_id', $jadwalIds)
            ->exists();

        if (!$absenExists) {
            return redirect()->route('post-test.index')
                ->with('error', 'Anda harus mengisi absensi terlebih dahulu sebelum mengerjakan post test.');
        }

        // Cek hasil sebelumnya
        $latestResult = PostTestResult::where('user_id', $userId)
            ->where('session_id', $session->id)
            ->latest()
            ->first();

        if ($latestResult && $latestResult->score >= 60) {
            return redirect()->route('dashboard')
                ->with('info', 'Anda sudah mengerjakan post test ini dengan nilai yang cukup.');
        }

        // Hapus nilai lama jika ada (dan skor < 75)
        if ($latestResult && $latestResult->score < 60) {
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

        return redirect()->route('post-test.result', $result->id)
            ->with('success', 'Post test berhasil dikumpulkan.');
    }

    // Lihat Hasil
    public function showResult(PostTestResult $result)
    {
        $user = $result->user;
        $session = PostTestSession::with('questions')->findOrFail($result->session_id);

        return view('post-test.result', compact('session', 'result', 'user'));
    }
}
