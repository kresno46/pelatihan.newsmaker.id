<?php

namespace App\Http\Controllers;

use App\Models\Ebook;
use App\Models\FolderEbook;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Services\EbookApiService;

class EbookController extends Controller
{
    protected $apiService;

    public function __construct(EbookApiService $apiService)
    {
        $this->apiService = $apiService;
    }

    /**
     * Tampilkan daftar eBook dalam folder.
     */
    public function index(Request $request, $folderSlug)
    {
        $folder = FolderEbook::where('slug', $folderSlug)->firstOrFail();

        // Sync ebooks for this folder from API
        if ($folder->isFromApi()) {
            $this->apiService->syncEbooksToDatabaseBySlug($folderSlug, $folder->id);
        }

        // Get ebooks from database (synced from API)
        $query = Ebook::where('folder_id', $folder->id)->whereNotNull('api_id');

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                    ->orWhere('deskripsi', 'like', '%' . $request->search . '%');
            });
        }

        $ebooks = $query->paginate(8);

        return view('ebook.index', compact('ebooks', 'folder'));
    }

    /**
     * Tampilkan form tambah eBook.
     * DISABLED: Admin input disabled, but method kept for compatibility
     */
    public function create($folderSlug)
    {
        // This method is disabled but kept for compatibility
        // Admin input is disabled as requested
        abort(403, 'Admin input untuk ebook dinonaktifkan. Data diambil dari API.');
    }

    /**
     * Simpan eBook baru.
     * DISABLED: Admin input disabled, but method kept for compatibility
     */
    public function store(Request $request, $folderSlug)
    {
        // This method is disabled but kept for compatibility
        // Admin input is disabled as requested
        abort(403, 'Admin input untuk ebook dinonaktifkan. Data diambil dari API.');
    }

    /**
     * Tampilkan detail eBook.
     */
    public function show($folderSlug, $ebookSlug)
    {
        $folder = FolderEbook::where('slug', $folderSlug)->firstOrFail();
        $ebook = Ebook::where('slug', $ebookSlug)->where('folder_id', $folder->id)->firstOrFail();

        return view('ebook.show', compact('ebook', 'folder'));
    }

    /**
     * Tampilkan form edit eBook.
     * DISABLED: Admin input disabled, but method kept for compatibility
     */
    public function edit($folderSlug, $ebookSlug)
    {
        // This method is disabled but kept for compatibility
        // Admin input is disabled as requested
        abort(403, 'Admin input untuk ebook dinonaktifkan. Data diambil dari API.');
    }

    /**
     * Update eBook.
     * DISABLED: Admin input disabled, but method kept for compatibility
     */
    public function update(Request $request, $folderSlug, $ebookSlug)
    {
        // This method is disabled but kept for compatibility
        // Admin input is disabled as requested
        abort(403, 'Admin input untuk ebook dinonaktifkan. Data diambil dari API.');
    }

    /**
     * Hapus eBook.
     * DISABLED: Admin input disabled, but method kept for compatibility
     */
    public function destroy($folderSlug, $ebookSlug)
    {
        // This method is disabled but kept for compatibility
        // Admin input is disabled as requested
        abort(403, 'Admin input untuk ebook dinonaktifkan. Data diambil dari API.');
    }

    /**
     * Download eBook PDF.
     */
   public function download($folderSlug, $ebookSlug)
{
    try {
        $folder = FolderEbook::where('slug', $folderSlug)->firstOrFail();
        $ebook = Ebook::where('slug', $ebookSlug)
            ->where('folder_id', $folder->id)
            ->firstOrFail();

        if ($ebook->download_url) {
            return redirect()->away($ebook->download_url);
        }

        abort(404, 'File tidak tersedia untuk diunduh.');
    } catch (\Exception $e) {
        \Log::error('Ebook download error', [
            'folder_slug' => $folderSlug,
            'ebook_slug'  => $ebookSlug,
            'error'       => $e->getMessage(),
        ]);

        abort(404, 'File tidak ditemukan.');
    }
}

}
