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

    public function show(Request $request, string $folderSlug, EbookApiService $ebookApi)
    {
        $page = (int) $request->query('page', 1);
        $response = $ebookApi->getEbooksFromApiBySlug($folderSlug, $page, true);
        $ebooks = $response['data'] ?? $response ?? [];

        $folderName = null;
        $foldersResponse = $ebookApi->getFoldersFromApi();
        $folders = $foldersResponse['data'] ?? $foldersResponse ?? [];
        if (is_array($folders)) {
            foreach ($folders as $folder) {
                if (($folder['slug'] ?? null) === $folderSlug) {
                    $folderName = $folder['folder_name'] ?? $folder['name'] ?? null;
                    break;
                }
            }
        }

        $pagination = null;
        if (is_array($response) && isset($response['current_page'], $response['last_page'])) {
            $currentPage = (int) $response['current_page'];
            $lastPage = (int) $response['last_page'];
            $window = 2;
            $start = max(1, $currentPage - $window);
            $end = min($lastPage, $currentPage + $window);

            $pages = [];
            for ($i = $start; $i <= $end; $i++) {
                $pages[] = [
                    'page' => $i,
                    'url' => route('edukasi.ebook.show', $folderSlug) . '?page=' . $i,
                    'is_current' => $i === $currentPage,
                ];
            }

            $pagination = [
                'current_page' => $currentPage,
                'last_page' => $lastPage,
                'prev_url' => $currentPage > 1
                    ? route('edukasi.ebook.show', $folderSlug) . '?page=' . ($currentPage - 1)
                    : null,
                'next_url' => $currentPage < $lastPage
                    ? route('edukasi.ebook.show', $folderSlug) . '?page=' . ($currentPage + 1)
                    : null,
                'pages' => $pages,
            ];
        }

        return view('edukasi.ebook-show', [
            'folderSlug' => $folderSlug,
            'folderName' => $folderName,
            'ebooks' => $ebooks ?? [],
            'pagination' => $pagination,
        ]);
    }
}
