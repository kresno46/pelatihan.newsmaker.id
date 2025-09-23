<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use App\Models\FolderEbook;
use App\Models\Ebook;

class EbookApiService
{
    protected $apiBaseUrl = 'https://ebook.newsmaker.id/api';
    protected $cacheTtl = 3600; // 1 hour

    /**
     * Fetch folders from API
     */
    public function getFoldersFromApi()
    {
        try {
            $cacheKey = 'ebook_api_folders';

            return Cache::remember($cacheKey, $this->cacheTtl, function () {
                $response = Http::timeout(30)->get("{$this->apiBaseUrl}/folders");

                if ($response->successful()) {
                    $data = $response->json();

                    Log::info('Successfully fetched folders from API', [
                        'count' => count($data),
                        'api_url' => "{$this->apiBaseUrl}/folders"
                    ]);

                    return $data;
                }

                Log::error('Failed to fetch folders from API', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);

                return [];
            });
        } catch (\Exception $e) {
            Log::error('Exception when fetching folders from API', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return [];
        }
    }

    /**
     * Fetch ebooks from API by folder
     */
    public function getEbooksFromApi($folderId)
    {
        try {
            $cacheKey = "ebook_api_ebooks_folder_{$folderId}";

            return Cache::remember($cacheKey, $this->cacheTtl, function () use ($folderId) {
                $response = Http::timeout(30)->get("{$this->apiBaseUrl}/folders/{$folderId}/ebooks");

                if ($response->successful()) {
                    $data = $response->json();

                    Log::info('Successfully fetched ebooks from API', [
                        'folder_id' => $folderId,
                        'count' => count($data),
                        'api_url' => "{$this->apiBaseUrl}/folders/{$folderId}/ebooks"
                    ]);
                    $ebooks = $data['data'] ?? $data;
                    return $data;
                }

                Log::error('Failed to fetch ebooks from API', [
                    'folder_id' => $folderId,
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);

                return [];
            });
        } catch (\Exception $e) {
            Log::error('Exception when fetching ebooks from API', [
                'folder_id' => $folderId,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return [];
        }
    }

    /**
     * Fetch ebooks from API by folder slug
     */
    public function getEbooksFromApiBySlug($folderSlug)
    {
        try {
            $cacheKey = "ebook_api_ebooks_slug_{$folderSlug}";

            return Cache::remember($cacheKey, $this->cacheTtl, function () use ($folderSlug) {
                $response = Http::timeout(30)->get("{$this->apiBaseUrl}/ebooks/folder/{$folderSlug}");

                if ($response->successful()) {
                    $data = $response->json();

                    // Handle both paginated and direct array responses
                    $ebooks = $data['data'] ?? $data;

                    Log::info('Successfully fetched ebooks from API by slug', [
                        'folder_slug' => $folderSlug,
                        'count' => count($ebooks),
                        'api_url' => "{$this->apiBaseUrl}/ebooks/folder/{$folderSlug}"
                    ]);
                     $ebooks = $data['data'] ?? $data;
                    return $ebooks;
                }

                Log::error('Failed to fetch ebooks from API by slug', [
                    'folder_slug' => $folderSlug,
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);

                return [];
            });
        } catch (\Exception $e) {
            Log::error('Exception when fetching ebooks from API by slug', [
                'folder_slug' => $folderSlug,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return [];
        }
    }

    /**
     * Sync folders from API to local database
     */
    public function syncFoldersToDatabase()
    {
        try {
            $apiFolders = $this->getFoldersFromApi();

            if (empty($apiFolders)) {
                Log::warning('No folders received from API for sync');
                return false;
            }

            $syncedCount = 0;

            foreach ($apiFolders as $apiFolder) {
                $folder = FolderEbook::updateOrCreate(
                    ['api_id' => $apiFolder['id'] ?? null],
                    [
                        'folder_name' => $apiFolder['name'] ?? $apiFolder['folder_name'] ?? 'Unknown Folder',
                        'deskripsi' => $apiFolder['description'] ?? $apiFolder['deskripsi'] ?? '',
                        'slug' => $apiFolder['slug'] ?? \Str::slug($apiFolder['name'] ?? $apiFolder['folder_name'] ?? 'unknown-folder'),
                        'api_data' => json_encode($apiFolder),
                        'synced_at' => now(),
                    ]
                );

                // Sync ebooks for this folder
                if (isset($apiFolder['slug'])) {
                    $this->syncEbooksToDatabase($apiFolder['slug'], $folder->id);
                }

                $syncedCount++;
            }

            Log::info('Successfully synced folders from API to database', [
                'synced_count' => $syncedCount,
                'total_api_folders' => count($apiFolders)
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error('Exception when syncing folders to database', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return false;
        }
    }

    /**
     * Sync ebooks from API to local database
     */
    public function syncEbooksToDatabase($apiFolderId, $localFolderId)
    {
        try {
            $apiEbooks = $this->getEbooksFromApi($apiFolderId);

            if (empty($apiEbooks)) {
                Log::info('No ebooks received from API for folder', ['folder_id' => $apiFolderId]);
                return false;
            }

            $syncedCount = 0;

            foreach ($apiEbooks as $apiEbook) {
                Ebook::updateOrCreate(
                    ['api_id' => $apiEbook['id'] ?? null],
                    [
                        'folder_id' => $localFolderId,
                        'title' => $apiEbook['title'] ?? 'Unknown Title',
                        'slug' => $apiEbook['slug'] ?? \Str::slug($apiEbook['title'] ?? 'unknown-title'),
                        'deskripsi' => $apiEbook['description'] ?? $apiEbook['deskripsi'] ?? '',
                        'cover' => $apiEbook['cover'] ?? '/default-cover.jpg',
                        'file' => $apiEbook['file'] ?? '',
                        'api_data'  => $apiEbook,  // ✅ simpan array langsung
                        'synced_at' => now(),
                    ]
                );

                $syncedCount++;
            }

            Log::info('Successfully synced ebooks from API to database', [
                'folder_id' => $apiFolderId,
                'synced_count' => $syncedCount,
                'total_api_ebooks' => count($apiEbooks)
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error('Exception when syncing ebooks to database', [
                'folder_id' => $apiFolderId,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return false;
        }
    }

    /**
     * Sync ebooks from API to local database by folder slug
     */
    public function syncEbooksToDatabaseBySlug($folderSlug, $localFolderId)
    {
        try {
            $apiEbooks = $this->getEbooksFromApiBySlug($folderSlug);

            if (empty($apiEbooks)) {
                Log::info('No ebooks received from API for folder slug', ['folder_slug' => $folderSlug]);
                return false;
            }

            $syncedCount = 0;

            foreach ($apiEbooks as $apiEbook) {
                Ebook::updateOrCreate(
                    ['api_id' => $apiEbook['id'] ?? null],
                    [
                        'folder_id' => $localFolderId,
                        'title' => $apiEbook['title'] ?? 'Unknown Title',
                        'slug' => $apiEbook['slug'] ?? \Str::slug($apiEbook['title'] ?? 'unknown-title'),
                        'deskripsi' => $apiEbook['deskripsi'] ?? $apiEbook['description'] ?? '',
                        'cover' => $apiEbook['cover'] ?? '/default-cover.jpg',
                        'file' => $apiEbook['file'] ?? '',
                        'api_data' => $apiEbook,
                        'synced_at' => now(),
                    ]
                );

                $syncedCount++;
            }

            Log::info('Successfully synced ebooks from API to database by slug', [
                'folder_slug' => $folderSlug,
                'synced_count' => $syncedCount,
                'total_api_ebooks' => count($apiEbooks)
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error('Exception when syncing ebooks to database by slug', [
                'folder_slug' => $folderSlug,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return false;
        }
    }

    /**
     * Clear API cache
     */
    public function clearCache()
    {
        Cache::forget('ebook_api_folders');
        Log::info('Cleared ebook API cache');
    }

    /**
     * Check if API is available
     */
    public function isApiAvailable()
    {
        try {
            $response = Http::timeout(10)->get("{$this->apiBaseUrl}/folders");
            return $response->successful();
        } catch (\Exception $e) {
            return false;
        }
    }
}
