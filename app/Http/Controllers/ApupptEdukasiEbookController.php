<?php

namespace App\Http\Controllers;

use App\Models\ApupptEbook;
use App\Models\ApupptEbookFolder;
use App\Models\ApupptPostTestResult;

class ApupptEdukasiEbookController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $folders = ApupptEbookFolder::withCount('ebooks')
            ->where('is_active', true)
            ->where('apuppt_pt_scope', $user->role)
            ->latest()
            ->get();

        return view('apuppt.edukasi.ebook', compact('folders'));
    }

    public function show(string $folderSlug)
    {
        $user = auth()->user();
        $folder = ApupptEbookFolder::where('slug', $folderSlug)
            ->where('is_active', true)
            ->where('apuppt_pt_scope', $user->role)
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
