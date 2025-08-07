<?php

namespace App\Http\Controllers;

use App\Models\CertificateAward;
use App\Models\PostTestResult;
use App\Models\FolderEbook;
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
        $folders = FolderEbook::with('ebooks')->get();

        $eligibleFolders = [];

        foreach ($folders as $folder) {
            $ebookIds = $folder->ebooks->pluck('id')->toArray();
            $userResults = PostTestResult::where('user_id', $user->id)
                ->whereIn('ebook_id', $ebookIds)
                ->get();

            if ($userResults->isEmpty()) continue;

            $totalEbooks = count($ebookIds);
            $totalUserResults = $userResults->count();
            $averageScore = round($userResults->avg('score'), 2);

            $canDownload = $totalUserResults === $totalEbooks && $averageScore >= 75;
            $isCompletedButFailed = $totalUserResults === $totalEbooks && $averageScore < 75;

            $eligibleFolders[] = (object)[
                'folder' => $folder,
                'average_score' => $averageScore,
                'can_download' => $canDownload,
                'is_completed_but_failed' => $isCompletedButFailed,
            ];
        }

        $awards = CertificateAward::where('user_id', $user->id)->get();

        return view('sertifikat.index', compact('eligibleFolders', 'awards'));
    }

    public function generateCertificate($folderSlug)
    {
        $user = auth()->user();
        $folder = FolderEbook::where('slug', $folderSlug)->with('ebooks')->firstOrFail();
        $ebookIds = $folder->ebooks->pluck('id')->toArray();

        $userResults = PostTestResult::where('user_id', $user->id)
            ->whereIn('ebook_id', $ebookIds)
            ->get();

        if ($userResults->isEmpty()) {
            return back()->with('error', 'Belum ada post-test yang Anda kerjakan di folder ini.');
        }

        $totalEbooks = count($ebookIds);
        $totalUserResults = $userResults->count();

        if ($totalUserResults < $totalEbooks) {
            return back()->with('error', 'Anda belum menyelesaikan seluruh eBook di materi ini.');
        }

        $averageScore = round($userResults->avg('score'), 2);

        if ($averageScore < 75) {
            return back()->with('error', 'Nilai rata-rata minimal 75 diperlukan.');
        }

        $award = CertificateAward::updateOrCreate(
            [
                'user_id' => $user->id,
                'batch_number' => $folder->id,
            ],
            [
                'average_score' => $averageScore,
                'total_ebooks' => $totalEbooks,
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

        $pdf = Pdf::loadView($templateView, [
            'name' => $user->name,
            'date' => $dateFormatted,
            'uuid' => $award->certificate_uuid,
            'batch' => $folder->id,
            'levelTitle' => $folder->folder_name,
        ])->setPaper('a4', 'landscape');

        $safeUserName = Str::slug($user->name);
        $safeFolderName = Str::slug($folder->folder_name);
        $fileName = "Sertifikat_{$safeUserName}_Folder_{$safeFolderName}.pdf";
        $storagePath = "public/sertifikat/{$fileName}";
        $fullPath = storage_path("app/{$storagePath}");

        // Buat folder jika belum ada
        Storage::makeDirectory('public/sertifikat');

        // Simpan manual menggunakan file_put_contents
        file_put_contents($fullPath, $pdf->output());

        // Debug logging
        \Log::info("PDF disimpan ke: {$fullPath}");
        \Log::info("Ukuran PDF: " . strlen($pdf->output()));
        \Log::info("Ada file? " . (file_exists($fullPath) ? 'Ya' : 'Tidak'));

        // Unduh
        return response()->download($fullPath, $fileName);
    }
}
