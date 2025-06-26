<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\CertificateAward;
use App\Models\PostTestResult;
use App\Mail\CertificateEmail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class SertifikatController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $availableBatches = $user->earnedCertificateBatches();
        $awardedBatches = $user->certificateAwards()->count();

        return view('sertifikat.index', compact('user', 'availableBatches', 'awardedBatches'));
    }

    public function sendCertificateByEmail($batch)
    {
        $user = auth()->user();
        $maxBatches = $user->earnedCertificateBatches();

        if ($batch > $maxBatches || $batch < 1) {
            abort(403, 'Sertifikat tidak tersedia.');
        }

        $titles = ['Fundamental', 'Beginner', 'Intermediate', 'Advanced', 'Expert'];
        $title = $titles[$batch - 1] ?? 'Level Tidak Diketahui';

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

        $dateFormatted = Carbon::parse($award->awarded_at)->format('d F Y');

        // Buat PDF
        $pdf = Pdf::loadView('sertifikat.certificate', [
            'name' => $user->name,
            'date' => $dateFormatted,
            'uuid' => $award->certificate_uuid,
            'batch' => $batch,
            'levelTitle' => $title
        ])->setPaper('a4', 'landscape');

        // Simpan PDF ke storage sementara
        $fileName = "Sertifikat_{$user->id}_Batch{$batch}.pdf";
        $filePath = storage_path("app/public/sertifikat/{$fileName}");
        Storage::makeDirectory('public/sertifikat');
        file_put_contents($filePath, $pdf->output());

        // Kirim email
        Mail::to($user->email)->send(new CertificateEmail($user->name, $batch, $title, $filePath));

        return back()->with('Alert', 'Sertifikat berhasil dikirim ke email Anda!');
    }
}
