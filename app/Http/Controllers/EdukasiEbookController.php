<?php

namespace App\Http\Controllers;

use App\Services\EbookApiService;
use Illuminate\Http\Request;

class EdukasiEbookController extends Controller
{
    public function index(EbookApiService $ebookApi)
    {
        $foldersResponse = $ebookApi->getFoldersFromApi();
        $folders = $foldersResponse['data'] ?? $foldersResponse ?? [];

        return view('edukasi.ebook', [
            'folders' => $folders,
        ]);
    }

    public function show(string $folderSlug, EbookApiService $ebookApi)
    {
        $ebooks = $ebookApi->getEbooksFromApiBySlug($folderSlug);

        return view('edukasi.ebook-show', [
            'folderSlug' => $folderSlug,
            'ebooks' => $ebooks ?? [],
        ]);
    }
}
