<?php

namespace App\Http\Controllers;

use App\Models\FolderEbook;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class FolderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $folders = FolderEbook::withCount('ebooks')->latest()->paginate(8);

        return view('folder.index', compact('folders'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('folder.create');
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

        FolderEbook::create([
            'folder_name' => $request->folder_name,
            'deskripsi'   => $request->deskripsi,
            'slug'        => Str::slug($request->folder_name),
        ]);

        return redirect()->route('folder.index')->with('success', 'Folder Outlook berhasil dibuat.');
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
     */
    public function edit($slug)
    {
        $folder = FolderEbook::where('slug', $slug)->firstOrFail();
        return view('folder.edit', compact('folder'));
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

        $folder = FolderEbook::findOrFail($id);
        $folder->update([
            'folder_name' => $request->folder_name,
            'deskripsi'   => $request->deskripsi,
            'slug'        => Str::slug($request->folder_name),
        ]);

        return redirect()->route('folder.index')->with('success', 'Folder Outlook berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $folder = FolderEbook::findOrFail($id);
        $folder->delete();

        return redirect()->route('folder.index')->with('success', 'Folder Outlook berhasil dihapus.');
    }
}
