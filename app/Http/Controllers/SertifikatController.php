<?php

namespace App\Http\Controllers;

use App\Models\CertificateAward;
use App\Models\PostTestResult;
use Illuminate\Support\Str;

class SertifikatController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Ambil data post-test untuk user yang terkait dengan eager loading untuk session dan user
        $userResults = PostTestResult::with(['session', 'user'])
            ->where('user_id', $user->id)
            ->get();

        if ($userResults->isEmpty()) {
            return view('sertifikat.index', [
                'userResults' => collect(), // kirim collection kosong
                'message' => 'Belum ada post-test yang Anda kerjakan.',
                'totalResults' => 0,
                'averageScore' => 0,
                'canDownload' => false,
                'awards' => collect(),
            ]);
        }

        // Hitung jumlah post-test dan rata-rata skor
        $totalResults = $userResults->count();
        $averageScore = round($userResults->avg('score'), 2);

        // Tentukan apakah user bisa mendapatkan sertifikat
        $canDownload = $averageScore >= 60;

        // Ambil data penghargaan (sertifikat) jika ada
        $awards = CertificateAward::where('user_id', $user->id)->get();

        return view('sertifikat.index', compact('userResults', 'totalResults', 'averageScore', 'canDownload', 'awards'));
    }

    public function generateCertificate($postTestId)
    {
        $user = auth()->user();

        // Ambil post-test
        $postTestResult = PostTestResult::with(['session', 'user'])
            ->where('user_id', $user->id)
            ->where('id', $postTestId)
            ->firstOrFail();

        // Ambil level (PATD / PATL)
        $testLevel = $postTestResult->session->tipe;

        // Validasi minimal nilai 60
        if ($postTestResult->score < 60) {
            return back()->with('error', "Nilai minimal 60 diperlukan untuk mendapatkan sertifikat {$testLevel}.");
        }

        // Tentukan folder PT berdasarkan role
        $baseFolder = match ($user->role) {
            'Trainer (RFB)' => 'sertifikat.rfb',
            'Trainer (SGB)' => 'sertifikat.sgb',
            'Trainer (KPF)' => 'sertifikat.kpf',
            'Trainer (EWF)' => 'sertifikat.ewf',
            'Trainer (BPF)' => 'sertifikat.BPF',
            default => 'sertifikat.default',
        };

        // Template View:
        // PATD → index.blade.php
        // PATL → patl.blade.php
        $templateView = $testLevel === 'PATD'
            ? "{$baseFolder}.index"
            : "{$baseFolder}.patl";

        // Simpan/Update sertifikat
        $award = CertificateAward::updateOrCreate(
            [
                'user_id' => $user->id,
                'post_test_id' => $postTestId,
            ],
            [
                'batch_number' => $this->getBatchNumber($user->id),
                'average_score' => $postTestResult->score,
                'certificate_uuid' => (string) Str::uuid(),
                'awarded_at' => now(),
            ]
        );

        $dateFormatted = \Carbon\Carbon::parse($award->awarded_at)->format('d F Y');

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView($templateView, [
            'name' => $user->name,
            'date' => $dateFormatted,
            'uuid' => $award->certificate_uuid,
            'levelTitle' => $postTestResult->session->title,
            'score' => $postTestResult->score,
            'testLevel' => $testLevel,
        ])->setPaper('a4', 'landscape');

        $safeUserName = \Illuminate\Support\Str::slug($user->name);
        $fileName = "Sertifikat_{$testLevel}_{$safeUserName}.pdf";

        \Storage::makeDirectory('public/sertifikat');
        file_put_contents(storage_path("app/public/sertifikat/{$fileName}"), $pdf->output());

        return response()->download(storage_path("app/public/sertifikat/{$fileName}"), $fileName);
    }

    // Mendapatkan batch_number otomatis berdasarkan urutan
    private function getBatchNumber($userId)
    {
        // Menentukan batch_number otomatis dengan mencari yang terbesar dan menambahkannya
        $lastBatchNumber = CertificateAward::where('user_id', $userId)->max('batch_number');

        return $lastBatchNumber ? $lastBatchNumber + 1 : 1;
    }
}
