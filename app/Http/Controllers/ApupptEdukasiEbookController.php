<?php

namespace App\Http\Controllers;

use App\Models\ApupptEbook;
use App\Models\ApupptEbookFolder;
use App\Models\ApupptPostTestResult;

class ApupptEdukasiEbookController extends Controller
{
    public function index()
    {
        $folders = ApupptEbookFolder::withCount('ebooks')
            ->where('is_active', true)
            ->latest()
            ->get();

        return view('apuppt.edukasi.ebook', compact('folders'));
    }

    public function show(string $folderSlug)
    {
        $folder = ApupptEbookFolder::where('slug', $folderSlug)
            ->where('is_active', true)
            ->firstOrFail();
        $ebooks = ApupptEbook::with('postTestSession')
            ->where('folder_id', $folder->id)
            ->latest()
            ->paginate(12)
            ->withQueryString();

        $userId = auth()->id();
        $sessionIds = $ebooks->getCollection()
            ->pluck('postTestSession.id')
            ->filter()
            ->values();

        $resultsBySession = ApupptPostTestResult::where('user_id', $userId)
            ->whereIn('session_id', $sessionIds)
            ->latest()
            ->get()
            ->unique('session_id')
            ->keyBy('session_id');

        return view('apuppt.edukasi.ebook-show', [
            'folderSlug' => $folderSlug,
            'folderName' => $folder->folder_name,
            'ebooks' => $ebooks,
            'resultsBySession' => $resultsBySession,
        ]);
    }
}
