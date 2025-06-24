<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\CertificateAward;
use App\Models\PostTestResult;

class SertifikatController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $availableBatches = $user->earnedCertificateBatches();
        $awardedBatches = $user->certificateAwards()->count();

        return view('sertifikat.index', compact('user', 'availableBatches', 'awardedBatches'));
    }

    public function download($batch)
    {
        $user = auth()->user();
        $maxBatches = $user->earnedCertificateBatches();

        if ($batch > $maxBatches || $batch < 1) {
            abort(403, 'Sertifikat tidak tersedia.');
        }

        // Cek apakah sertifikat sudah pernah dibuat
        $award = CertificateAward::where('user_id', $user->id)
            ->where('batch_number', $batch)
            ->first();

        if (!$award) {
            $results = PostTestResult::where('user_id', $user->id)
                ->groupBy('ebook_id')
                ->selectRaw('MAX(score) as score, ebook_id')
                ->orderByDesc('score')
                ->get()
                ->chunk(10);

            $chunk = $results->get($batch - 1);
            $average = $chunk->avg('score');

            $award = CertificateAward::create([
                'user_id' => $user->id,
                'batch_number' => $batch,
                'average_score' => $average,
                'total_ebooks' => $chunk->count(),
                'certificate_uuid' => (string) Str::uuid(),
                'awarded_at' => now(),
            ]);
        }

        // Gunakan Carbon::parse agar tidak error saat format
        $dateFormatted = Carbon::parse($award->awarded_at)->format('d F Y');

        $pdf = Pdf::loadView('sertifikat.certificate', [
            'name' => $user->name,
            'date' => $dateFormatted,
            'uuid' => $award->certificate_uuid,
            'batch' => $batch,
        ])->setPaper('a4', 'landscape');

        return $pdf->download("Sertifikat_{$user->name}_Batch{$batch}.pdf");
    }
}
