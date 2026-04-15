<?php

namespace App\Http\Controllers;

use App\Models\ApupptCertificateAward;
use App\Models\ApupptPostTestResult;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ApupptSertifikatController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $userResults = ApupptPostTestResult::with(['session', 'user'])
            ->where('user_id', $user->id)
            ->whereHas('session', function ($q) {
                $q->where('tipe', 'APUPPT');
            })
            ->latest()
            ->get();

        if ($userResults->isEmpty()) {
            return view('apuppt.sertifikat.index', [
                'userResults' => collect(),
                'totalResults' => 0,
                'averageScore' => 0,
                'canDownload' => false,
                'awards' => collect(),
            ]);
        }

        $totalResults = $userResults->count();
        $averageScore = round($userResults->avg('score'), 2);
        $canDownload = $averageScore >= 60;
        $awards = ApupptCertificateAward::where('user_id', $user->id)->get();

        return view('apuppt.sertifikat.index', compact('userResults', 'totalResults', 'averageScore', 'canDownload', 'awards'));
    }

    public function generateCertificate($postTestId)
    {
        $user = auth()->user();

        $postTestResult = ApupptPostTestResult::with(['session', 'user'])
            ->where('user_id', $user->id)
            ->where('id', $postTestId)
            ->firstOrFail();

        if (($postTestResult->session->tipe ?? null) !== 'APUPPT') {
            return back()->with('error', 'Sertifikat ini hanya untuk post test APUPPT.');
        }

        if ($postTestResult->score < 60) {
            return back()->with('error', 'Nilai minimal 60 diperlukan untuk mendapatkan sertifikat APUPPT.');
        }

        $baseFolder = match ($user->role) {
            'Trainer (RFB)' => 'sertifikat.rfb',
            'Trainer (SGB)' => 'sertifikat.sgb',
            'Trainer (KPF)' => 'sertifikat.kpf',
            'Trainer (EWF)' => 'sertifikat.ewf',
            'Trainer (BPF)' => 'sertifikat.BPF',
            default => 'sertifikat.rfb',
        };

        $award = ApupptCertificateAward::updateOrCreate(
            [
                'user_id' => $user->id,
                'post_test_id' => $postTestId,
            ],
            [
                'average_score' => $postTestResult->score,
                'certificate_uuid' => (string) Str::uuid(),
                'awarded_at' => now(),
            ]
        );

        $dateFormatted = \Carbon\Carbon::parse($award->awarded_at)->format('d F Y');

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView("{$baseFolder}.patl", [
            'name' => $user->name,
            'date' => $dateFormatted,
            'uuid' => $award->certificate_uuid,
            'levelTitle' => $postTestResult->session->title,
            'score' => $postTestResult->score,
            'testLevel' => 'APUPPT',
        ])->setPaper('a4', 'landscape');

        $safeUserName = Str::slug($user->name);
        $fileName = "Sertifikat_APUPPT_{$safeUserName}.pdf";
        $relativePath = "sertifikat/apuppt/{$fileName}";

        Storage::disk('public')->makeDirectory('sertifikat/apuppt');
        Storage::disk('public')->put($relativePath, $pdf->output());

        return response()->download(Storage::disk('public')->path($relativePath), $fileName);
    }
}
