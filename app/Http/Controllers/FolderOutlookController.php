<?php

namespace App\Http\Controllers;

use App\Models\OutlookFolder;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class FolderOutlookController extends Controller
{
    /**
     * Display a listing of the resource for Outlook.
     */
    public function index()
    {
        $folderoutlook = OutlookFolder::withCount('outlooks')->latest()->paginate(8);

        return view('folderoutlook.index', compact('folderoutlook'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('folderoutlook.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'folder_name' => 'required|string|max:255',
            'deskripsi'   => 'nullable|string',
        ]);

        OutlookFolder::create([
            'folder_name' => $request->folder_name,
            'deskripsi'   => $request->deskripsi,
            'slug'        => Str::slug($request->folder_name),
        ]);

        return redirect()->route('outlook.index')->with('success', 'Folder Outlook berhasil dibuat.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($slug)
    {
        $folder = OutlookFolder::where('slug', $slug)->firstOrFail();
        return view('outlook.folder_edit', compact('folder'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'folder_name' => 'required|string|max:255',
            'deskripsi'   => 'nullable|string',
        ]);

        $folder = OutlookFolder::findOrFail($id);
        $folder->update([
            'folder_name' => $request->folder_name,
            'deskripsi'   => $request->deskripsi,
            'slug'        => Str::slug($request->folder_name),
        ]);

        return redirect()->route('outlook.folder.index')->with('success', 'Folder Outlook berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $folder = OutlookFolder::findOrFail($id);
        $folder->delete();

        return redirect()->route('outlook.folder.index')->with('success', 'Folder Outlook berhasil dihapus.');
    }
}
