<?php

namespace App\Http\Controllers;

use App\Models\FolderOutlook;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class FolderOutlookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = FolderOutlook::withCount('outlooks');

        if ($request->filled('search')) {
            $query->where('folder_name', 'like', '%' . $request->search . '%');
        }

        $FolderOutlooks = $query->latest()->paginate(8)->appends(['search' => $request->search]);

        return view('FolderOutlook.index', compact('FolderOutlooks'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('FolderOutlook.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'folder_name' => 'required|string|max:100',
            'deskripsi'   => 'required|string',
        ]);

        FolderOutlook::create([
            'folder_name' => $request->folder_name,
            'deskripsi'   => $request->deskripsi,
            'slug'        => Str::slug($request->folder_name),
        ]);

        return redirect()->route('outlookfolder.index')->with('success', 'Folder berhasil dibuat.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $slug)
    {
        $FolderOutlook = FolderOutlook::where('slug', $slug)->first();

        return view('FolderOutlook.edit', compact('FolderOutlook'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'folder_name' => 'required|string|max:255',
            'deskripsi'   => 'nullable|string',
        ]);

        $folder = FolderOutlook::findOrFail($id);

        $folder->update([
            'folder_name' => $request->folder_name,
            'deskripsi'   => $request->deskripsi,
            'slug'        => Str::slug($request->folder_name),
        ]);

        return redirect()->route('outlookfolder.index')->with('success', 'Folder berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $folder = FolderOutlook::findOrFail($id);
        $folder->delete();

        return redirect()->route('outlookfolder.index')->with('success', 'Folder Outlook berhasil dihapus.');
    }
}
