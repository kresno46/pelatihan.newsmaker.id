<?php

namespace App\Http\Controllers;

use App\Models\FolderEbook;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Services\EbookApiService;

class FolderController extends Controller
{
    protected $apiService;

    public function __construct(EbookApiService $apiService)
    {
        $this->apiService = $apiService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Try to sync from API first
        $this->apiService->syncFoldersToDatabase();

        // Get folders from database (synced from API)
        $folders = FolderEbook::syncedFromApi()
            ->withCount('ebooks')
            ->latest()
            ->paginate(8);

        return view('folder.index', compact('folders'));
    }

    /**
     * Show the form for creating a new resource.
     * DISABLED: Admin input disabled, but method kept for compatibility
     */
    public function create()
    {
        // This method is disabled but kept for compatibility
        // Admin input is disabled as requested
        abort(403, 'Admin input untuk folder dinonaktifkan. Data diambil dari API.');
    }

    /**
     * Store a newly created resource in storage.
     * DISABLED: Admin input disabled, but method kept for compatibility
     */
    public function store(Request $request)
    {
        // This method is disabled but kept for compatibility
        // Admin input is disabled as requested
        abort(403, 'Admin input untuk folder dinonaktifkan. Data diambil dari API.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $folder = FolderEbook::findOrFail($id);
        return view('folder.show', compact('folder'));
    }

    /**
     * Show the form for editing the specified resource.
     * DISABLED: Admin input disabled, but method kept for compatibility
     */
    public function edit($slug)
    {
        // This method is disabled but kept for compatibility
        // Admin input is disabled as requested
        abort(403, 'Admin input untuk folder dinonaktifkan. Data diambil dari API.');
    }

    /**
     * Update the specified resource in storage.
     * DISABLED: Admin input disabled, but method kept for compatibility
     */
    public function update(Request $request, $id)
    {
        // This method is disabled but kept for compatibility
        // Admin input is disabled as requested
        abort(403, 'Admin input untuk folder dinonaktifkan. Data diambil dari API.');
    }

    /**
     * Remove the specified resource from storage.
     * DISABLED: Admin input disabled, but method kept for compatibility
     */
    public function destroy($id)
    {
        // This method is disabled but kept for compatibility
        // Admin input is disabled as requested
        abort(403, 'Admin input untuk folder dinonaktifkan. Data diambil dari API.');
    }

    /**
     * Manual sync from API for admin
     */
    public function syncFromApi()
    {
        try {
            $result = $this->apiService->syncFoldersToDatabase();

            if ($result) {
                return redirect()->route('folder.index')
                    ->with('success', 'Data berhasil di-sync dari API.');
            } else {
                return redirect()->route('folder.index')
                    ->with('error', 'Gagal melakukan sync dari API.');
            }
        } catch (\Exception $e) {
            return redirect()->route('folder.index')
                ->with('error', 'Error saat sync: ' . $e->getMessage());
        }
    }
}
