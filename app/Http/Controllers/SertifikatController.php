<?php

namespace App\Http\Controllers;

use App\Models\CertificateAward;
use App\Models\PostTestResult;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Mail\CertificateEmail;
use App\Models\FolderEbook;

class SertifikatController extends Controller
{
    /**
     * Menampilkan halaman daftar sertifikat user
     */
    public function index()
    {
        $user = auth()->user();
        $allFolders = FolderEbook::with('ebooks')->get();

        $eligibility = [];

        foreach ($allFolders as $folder) {
            $ebookIds = $folder->ebooks->pluck('id')->toArray();

            $userResults = collect($ebookIds)->map(function ($ebookId) use ($user) {
                return PostTestResult::where('user_id', $user->id)
                    ->where('ebook_id', $ebookId)
                    ->max('score');
            });

            $completed = !$userResults->contains(null);
            $allAbove75 = $userResults->every(fn($score) => !is_null($score) && $score >= 75);

            $eligibility[$folder->id] = $completed && $allAbove75;
        }

        $awards = CertificateAward::where('user_id', $user->id)->get();

        return view('sertifikat.index', [
            'allFolders' => $allFolders,
            'awards' => $awards,
            'eligibility' => $eligibility,
            'user' => $user,
        ]);
    }

    /**
     * Generate dan unduh sertifikat berdasarkan folder
     */
    public function generateCertificate($folderSlug)
    {
        $user = auth()->user();
        $folder = FolderEbook::where('slug', $folderSlug)->with('ebooks')->firstOrFail();
        $ebookIds = $folder->ebooks->pluck('id')->toArray();

        $userResults = collect($ebookIds)->map(function ($ebookId) use ($user) {
            return PostTestResult::where('user_id', $user->id)
                ->where('ebook_id', $ebookId)
                ->max('score');
        });

        // Validasi semua post-test selesai
        if ($userResults->contains(null) || $userResults->contains(false)) {
            return back()->with('error', 'Anda belum menyelesaikan semua post-test dalam materi ini.');
        }

        // Validasi skor minimal 75
        if (!$userResults->every(fn($score) => !is_null($score) && $score >= 75)) {
            return back()->with('error', 'Nilai minimal 75 diperlukan untuk mendapatkan sertifikat.');
        }

        // Pastikan data valid
        if ($userResults->count() === 0) {
            return back()->with('error', 'Data post-test tidak valid.');
        }

        $averageScore = round($userResults->sum() / $userResults->count(), 2);

        $award = CertificateAward::firstOrCreate(
            [
                'user_id' => $user->id,
                'batch_number' => $folder->id,
            ],
            [
                'average_score' => $averageScore,
                'total_ebooks' => count($ebookIds),
                'certificate_uuid' => (string) Str::uuid(),
                'awarded_at' => now(),
            ]
        );

        $dateFormatted = Carbon::parse($award->awarded_at)->format('d F Y');
        $levelTitle = $folder->folder_name ?? 'Level Tidak Diketahui';

        $pdf = Pdf::loadView('sertifikat.certificate', [
            'name' => $user->name,
            'date' => $dateFormatted,
            'uuid' => $award->certificate_uuid,
            'batch' => $folder->id,
            'levelTitle' => $levelTitle,
        ])->setPaper('a4', 'landscape');

        $safeFolderName = Str::slug($folder->folder_name);
        $fileName = "Sertifikat_{$user->id}_Folder_{$safeFolderName}.pdf";
        $filePath = storage_path("app/public/sertifikat/{$fileName}");

        Storage::makeDirectory('public/sertifikat');
        file_put_contents($filePath, $pdf->output());

        try {
            Mail::to($user->email)->send(new CertificateEmail($user->name, $folder->id, $folder->folder_name, $filePath));
        } catch (\Exception $e) {
            // Bisa log error jika perlu
        }

        return response()->download($filePath, $fileName);
    }
}
