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

        $userResults = PostTestResult::with(['session', 'user'])
            ->where('user_id', $user->id)
            ->get();

        if ($userResults->isEmpty()) {
            return view('sertifikat.index', [
                'userResults' => collect(),
                'message' => 'Belum ada post-test yang Anda kerjakan.',
                'totalResults' => 0,
                'averageScore' => 0,
                'canDownload' => false,
                'awards' => collect(),
            ]);
        }

        $totalResults = $userResults->count();
        $averageScore = round($userResults->avg('score'), 2);
        $canDownload = $averageScore >= 60;
        $awards = CertificateAward::where('user_id', $user->id)->get();

        return view('sertifikat.index', compact('userResults', 'totalResults', 'averageScore', 'canDownload', 'awards'));
    }

    public function generateCertificate($postTestId)
    {
        $user = auth()->user();

        $postTestResult = PostTestResult::with(['session', 'user'])
            ->where('user_id', $user->id)
            ->where('id', $postTestId)
            ->firstOrFail();

        if ($postTestResult->score < 60) {
            return back()->with('error', 'Nilai rata-rata minimal 60 diperlukan untuk mendapatkan sertifikat.');
        }

        $session = $postTestResult->session;
        $sessionTitle = $session->title;
        $sessionTipe = $session->tipe; // Ambil tipe soal (PATD / PATL)

        $awardDate = Carbon::parse($postTestResult->created_at);

        $award = CertificateAward::updateOrCreate(
            [
                'user_id' => $user->id,
                'post_test_id' => $postTestId,
            ],
            [
                'batch_number' => $this->getBatchNumber($user->id),
                'average_score' => $postTestResult->score,
                'certificate_uuid' => (string) Str::uuid(),
                'awarded_at' => $awardDate,
            ]
        );

        $dateFormatted = Carbon::parse($award->awarded_at)->format('d F Y');

        /**
         * 📝 Pilih template sertifikat
         * - Jika tipe soal = PATL → pakai template khusus PATL
         * - Jika tipe soal bukan PATL → pakai template default sesuai role
         */
        if ($sessionTipe === 'PATL') {
            $templateView = match ($user->role) {
                'Trainer (RFB)' => 'sertifikat.rfb.patl',
                'Trainer (SGB)' => 'sertifikat.sgb.patl',
                'Trainer (KPF)' => 'sertifikat.kpf.patl',
                'Trainer (EWF)' => 'sertifikat.ewf.patl',
                'Trainer (BPF)' => 'sertifikat.bpf.patl',
                default => 'sertifikat.default.index',
            }; // pastikan kamu punya view ini
        } else {
            $templateView = match ($user->role) {
                'Trainer (RFB)' => 'sertifikat.rfb.index',
                'Trainer (SGB)' => 'sertifikat.sgb.index',
                'Trainer (KPF)' => 'sertifikat.kpf.index',
                'Trainer (EWF)' => 'sertifikat.ewf.index',
                'Trainer (BPF)' => 'sertifikat.bpf.index',
                default => 'sertifikat.default.index',
            };
        }

        $pdf = Pdf::loadView($templateView, [
            'name' => $user->name,
            'date' => $dateFormatted,
            'uuid' => $award->certificate_uuid,
            'levelTitle' => $sessionTitle,
            'score' => $postTestResult->score,
        ])->setPaper('a4', 'landscape');

        $safeUserName = Str::slug($user->name);
        $fileName = "Sertifikat_{$safeUserName}_PostTest_{$sessionTitle}.pdf";
        $storagePath = "public/sertifikat/{$fileName}";
        $fullPath = storage_path("app/{$storagePath}");

        Storage::makeDirectory('public/sertifikat');
        file_put_contents($fullPath, $pdf->output());

        \Log::info("PDF disimpan ke: {$fullPath}");

        return response()->download($fullPath, $fileName);
    }

    private function getBatchNumber($userId)
    {
        $lastBatchNumber = CertificateAward::where('user_id', $userId)->max('batch_number');
        return $lastBatchNumber ? $lastBatchNumber + 1 : 1;
    }
}
