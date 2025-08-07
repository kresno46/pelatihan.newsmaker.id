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
        // Get category from request, default to 'daily' if not specified
        $category = $request->get('category', 'daily');

        $query = FolderOutlook::withCount('outlooks')
            ->where('category', $category);

        if ($request->filled('search')) {
            $query->where('folder_name', 'like', '%' . $request->search . '%');
        }

        $FolderOutlooks = $query->orderBy('position')
            ->paginate(8)
            ->appends(['search' => $request->search, 'category' => $category]);

        return view('folderoutlook.index', compact('FolderOutlooks'));
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
            'cover_folder' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'folder_name' => 'required|string|max:100',
            'deskripsi'   => 'required|string',
            'category'    => 'required|in:daily,weekly',
        ]);

        // Simpan file ke folder publik
        $file = $request->file('cover_folder');
        $filename = time() . '_' . $file->getClientOriginalName();
        $file->move(public_path('uploads/cover-outlook-folder'), $filename);

        FolderOutlook::create([
            'cover_folder' => 'uploads/cover-outlook-folder/' . $filename,
            'folder_name'  => $request->folder_name,
            'deskripsi'    => $request->deskripsi,
            'slug'         => Str::slug($request->folder_name),
            'category'     => $request->category,
        ]);

        return redirect()->route('outlookfolder.index')->with('success', 'Folder berhasil dibuat.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $slug)
    {
        $FolderOutlook = FolderOutlook::where('slug', $slug)->first();

        return view('folderoutlook.edit', compact('FolderOutlook'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'folder_name' => 'required|string|max:255',
            'deskripsi'   => 'nullable|string',
            'cover_folder' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'category'    => 'required|in:daily,weekly',
        ]);

        $folder = FolderOutlook::findOrFail($id);

        $data = [
            'folder_name' => $request->folder_name,
            'deskripsi'   => $request->deskripsi,
            'slug'        => Str::slug($request->folder_name),
            'category'    => $request->category,
        ];

        if ($request->hasFile('cover_folder')) {
            // Hapus file lama jika ada
            if ($folder->cover_folder && file_exists(public_path($folder->cover_folder))) {
                unlink(public_path($folder->cover_folder));
            }

            $file = $request->file('cover_folder');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/cover-outlook-folder'), $filename);
            $data['cover_folder'] = 'uploads/cover-outlook-folder/' . $filename;
        }

        $folder->update($data);

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

    public function reorder(Request $request)
    {
        foreach ($request->positions as $index => $id) {
            FolderOutlook::where('id', $id)->update(['position' => $index]);
        }

        return response()->json(['message' => 'Urutan folder berhasil diperbarui.']);
    }
}
