<?php

namespace App\Http\Controllers;

use App\Models\CertificateAward;
use App\Models\PostTestResult;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Http\Request;

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

        // Ambil hasil post-test untuk user dan postTestId tertentu dengan eager loading
        $postTestResult = PostTestResult::with(['session', 'user'])  // Eager load session dan user
            ->where('user_id', $user->id)
            ->where('id', $postTestId)
            ->firstOrFail();

        // Pastikan nilai lebih dari 75 untuk sertifikat
        if ($postTestResult->score < 60) {
            return back()->with('error', 'Nilai rata-rata minimal 60 diperlukan untuk mendapatkan sertifikat.');
        }

        // Ambil title dari session yang terkait
        $sessionTitle = $postTestResult->session->title;  // Mengambil title dari relasi session

        // Simpan atau perbarui sertifikat
        $award = CertificateAward::updateOrCreate(
            [
                'user_id' => $user->id,
                'post_test_id' => $postTestId,  // Menyimpan post_test_id
            ],
            [
                'batch_number' => $this->getBatchNumber($user->id),  // Mendapatkan batch number otomatis
                'average_score' => $postTestResult->score,
                'certificate_uuid' => (string) Str::uuid(),
                'awarded_at' => now(),
            ]
        );

        $dateFormatted = Carbon::parse($award->awarded_at)->format('d F Y');

        $templateView = match ($user->role) {
            'Trainer (RFB)' => 'sertifikat.rfb.index',
            'Trainer (SGB)' => 'sertifikat.sgb.index',
            'Trainer (KPF)' => 'sertifikat.kpf.index',
            'Trainer (EWF)' => 'sertifikat.ewf.index',
            'Trainer (BPF)' => 'sertifikat.bpf.index',
            default => 'sertifikat.default.index',
        };

        // Generate PDF dengan title yang diambil dari session
        $pdf = Pdf::loadView($templateView, [
            'name' => $user->name,
            'date' => $dateFormatted,
            'uuid' => $award->certificate_uuid,
            'levelTitle' => $sessionTitle,  // Menggunakan title dari session
            'score' => $postTestResult->score,
        ])->setPaper('a4', 'landscape');

        $safeUserName = Str::slug($user->name);
        $fileName = "Sertifikat_{$safeUserName}_PostTest_{$sessionTitle}.pdf";
        $storagePath = "public/sertifikat/{$fileName}";
        $fullPath = storage_path("app/{$storagePath}");

        // Buat folder jika belum ada
        Storage::makeDirectory('public/sertifikat');

        // Simpan PDF
        file_put_contents($fullPath, $pdf->output());

        // Debug logging
        \Log::info("PDF disimpan ke: {$fullPath}");

        // Unduh
        return response()->download($fullPath, $fileName);
    }


    // Mendapatkan batch_number otomatis berdasarkan urutan
    private function getBatchNumber($userId)
    {
        // Menentukan batch_number otomatis dengan mencari yang terbesar dan menambahkannya
        $lastBatchNumber = CertificateAward::where('user_id', $userId)->max('batch_number');
        return $lastBatchNumber ? $lastBatchNumber + 1 : 1;
    }
}
