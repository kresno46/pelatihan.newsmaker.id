<?php

/**
 * Standalone script to sync ebook data from API
 * Usage: php sync-ebook-api.php
 */

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "🔄 Starting ebook API sync...\n";

try {
    $apiService = app()->make(App\Services\EbookApiService::class);

    // Check if API is available
    if (!$apiService->isApiAvailable()) {
        echo "❌ API is not available. Please check your internet connection and API endpoint.\n";
        exit(1);
    }

    echo "✅ API is available\n";

    // Clear cache
    echo "🧹 Clearing API cache...\n";
    $apiService->clearCache();
    echo "✅ Cache cleared\n";

    // Sync folders
    echo "📁 Syncing folders from API...\n";
    $folderResult = $apiService->syncFoldersToDatabase();

    if ($folderResult) {
        echo "✅ Folders synced successfully\n";
    } else {
        echo "❌ Failed to sync folders\n";
        exit(1);
    }

    // Get sync statistics
    $folders = App\Models\FolderEbook::syncedFromApi()->withCount('ebooks')->get();
    $totalEbooks = App\Models\Ebook::syncedFromApi()->count();

    echo "📊 Sync Statistics:\n";
    echo "┌─────────────────────────────┬─────────────┐\n";
    echo "│ Folder Name                 │ Ebooks Count│\n";
    echo "├─────────────────────────────┼─────────────┤\n";

    foreach ($folders as $folder) {
        printf("│ %-27s │ %-11s │\n",
            substr($folder->folder_name, 0, 27),
            $folder->ebooks_count
        );
    }

    echo "└─────────────────────────────┴─────────────┘\n";
    echo "📚 Total synced ebooks: {$totalEbooks}\n";

    echo "🎉 API sync completed successfully!\n";

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
    exit(1);
}
