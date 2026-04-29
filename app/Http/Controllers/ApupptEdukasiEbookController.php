<?php

namespace App\Http\Controllers;

use App\Models\ApupptEbook;
use App\Models\ApupptEbookFolder;

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
        $ebooks = ApupptEbook::where('folder_id', $folder->id)->latest()->paginate(12)->withQueryString();

        return view('apuppt.edukasi.ebook-show', [
            'folderSlug' => $folderSlug,
            'folderName' => $folder->folder_name,
            'ebooks' => $ebooks,
        ]);
    }
}
