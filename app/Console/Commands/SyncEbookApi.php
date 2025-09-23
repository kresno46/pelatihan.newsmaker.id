<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\EbookApiService;

class SyncEbookApi extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ebook:sync {--clear-cache : Clear API cache before syncing}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync ebook data from external API to local database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔄 Starting ebook API sync...');

        $apiService = app()->make(EbookApiService::class);

        // Check if API is available
        if (!$apiService->isApiAvailable()) {
            $this->error('❌ API is not available. Please check your internet connection and API endpoint.');
            return Command::FAILURE;
        }

        $this->info('✅ API is available');

        // Clear cache if requested
        if ($this->option('clear-cache')) {
            $this->info('🧹 Clearing API cache...');
            $apiService->clearCache();
            $this->info('✅ Cache cleared');
        }

        // Sync folders
        $this->info('📁 Syncing folders from API...');
        $folderResult = $apiService->syncFoldersToDatabase();

        if ($folderResult) {
            $this->info('✅ Folders synced successfully');
        } else {
            $this->error('❌ Failed to sync folders');
            return Command::FAILURE;
        }

        $this->info('🔄 Syncing slugs from API data...');

            $ebooks = \App\Models\Ebook::whereNotNull('api_data')->get();
            $fixed = 0;
            $skipped = 0;

            foreach ($ebooks as $ebook) {
                $apiData = $ebook->api_data;

                if (isset($apiData['slug']) && !empty($apiData['slug'])) {
                    if ($ebook->slug !== $apiData['slug']) {
                        $old = $ebook->slug;
                        $ebook->slug = $apiData['slug'];
                        $ebook->save();

                        $this->line("✔️ Ebook ID {$ebook->id} slug updated: '{$old}' ➝ '{$ebook->slug}'");
                        $fixed++;
                    } else {
                        $skipped++;
                    }
                }
            }

            $this->info("✅ Slug sync finished. {$fixed} updated, {$skipped} already correct.");
            
        // Get sync statistics
        $folders = \App\Models\FolderEbook::syncedFromApi()->withCount('ebooks')->get();
        $totalEbooks = \App\Models\Ebook::syncedFromApi()->count();

        $this->info('📊 Sync Statistics:');
        $this->table(
            ['Folder Name', 'Ebooks Count'],
            $folders->map(function ($folder) {
                return [
                    $folder->folder_name,
                    $folder->ebooks_count
                ];
            })->toArray()
        );

        $this->info("📚 Total synced ebooks: {$totalEbooks}");

        $this->info('🎉 API sync completed successfully!');
        return Command::SUCCESS;
    }
}
