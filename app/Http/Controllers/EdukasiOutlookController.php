<?php

namespace App\Http\Controllers;

use App\Services\EbookApiService;

class EdukasiOutlookController extends Controller
{
    public function index(EbookApiService $ebookApi)
    {
        $page = (int) request()->query('page', 1);

        $dailyResponse = $ebookApi->getOutlookFoldersFromApi('daily', $page);
        $weeklyResponse = $ebookApi->getOutlookFoldersFromApi('weekly', $page);

        $foldersDaily = $dailyResponse['data'] ?? $dailyResponse ?? [];
        $foldersWeekly = $weeklyResponse['data'] ?? $weeklyResponse ?? [];

        return view('edukasi.outlook', [
            'foldersDaily' => $foldersDaily,
            'foldersWeekly' => $foldersWeekly,
        ]);
    }

    public function show(string $folderSlug, EbookApiService $ebookApi)
    {
        $page = (int) request()->query('page', 1);
        $response = $ebookApi->getOutlooksFromApiBySlug($folderSlug, $page);
        $outlooks = $response['data'] ?? (is_array($response) ? $response : []);
        $folder = $ebookApi->getOutlookFolderFromApiBySlug($folderSlug);

        return view('edukasi.outlook-show', [
            'folderSlug' => $folderSlug,
            'outlooks' => $outlooks ?? [],
            'folder' => $folder,
            'pagination' => [
                'current_page' => $response['current_page'] ?? null,
                'last_page' => $response['last_page'] ?? null,
                'links' => $response['links'] ?? [],
                'prev_page_url' => $response['prev_page_url'] ?? null,
                'next_page_url' => $response['next_page_url'] ?? null,
            ],
        ]);
    }
}
