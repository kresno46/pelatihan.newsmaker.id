<?php

namespace App\Http\Controllers;

use App\Models\ApupptEbookFolder;
use Illuminate\Http\Request;

class ApupptEbookFolderController extends Controller
{
    public function index()
    {
        $search = trim((string) request('search', ''));
        $folders = ApupptEbookFolder::withCount('ebooks')
            ->when($search !== '', function ($query) use ($search) {
                $query->where('folder_name', 'like', "%{$search}%")
                    ->orWhere('deskripsi', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(8)
            ->withQueryString();

        return view('apuppt.ebook-folder.index', compact('folders'));
    }

    public function create()
    {
        return view('apuppt.ebook-folder.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'folder_name' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
        ]);

        ApupptEbookFolder::create([
            'folder_name' => $request->folder_name,
            'deskripsi' => $request->deskripsi,
            'is_active' => true,
        ]);

        return redirect()->route('apuppt.ebookfolder.index')->with('success', 'Folder Ebook APUPPT berhasil dibuat.');
    }

    public function edit($slug)
    {
        $folder = ApupptEbookFolder::where('slug', $slug)->firstOrFail();

        return view('apuppt.ebook-folder.edit', compact('folder'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'folder_name' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
        ]);

        $folder = ApupptEbookFolder::findOrFail($id);
        $folder->update([
            'folder_name' => $request->folder_name,
            'deskripsi' => $request->deskripsi,
        ]);

        return redirect()->route('apuppt.ebookfolder.index')->with('success', 'Folder Ebook APUPPT berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $folder = ApupptEbookFolder::findOrFail($id);
        $folder->delete();

        return redirect()->route('apuppt.ebookfolder.index')->with('success', 'Folder Ebook APUPPT berhasil dihapus.');
    }

    public function toggle($id)
    {
        $folder = ApupptEbookFolder::findOrFail($id);
        $folder->is_active = ! $folder->is_active;
        $folder->save();

        return back()->with('success', 'Status folder berhasil diperbarui.');
    }
}
