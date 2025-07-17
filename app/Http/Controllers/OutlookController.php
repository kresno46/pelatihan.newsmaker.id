<?php

namespace App\Http\Controllers;

use App\Models\FolderOutlook;
use App\Models\Outlook;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class OutlookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, $slug)
    {
        $folder = FolderOutlook::where('slug', $slug)->firstOrFail();
        $query = Outlook::where('folderOutlook_id', $folder->id);

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                    ->orWhere('deskripsi', 'like', '%' . $request->search . '%');
            });
        }

        $outlooks = $query->paginate(8)->appends(['search' => $request->search]); // <-- ini penting

        return view('outlook.index', compact('outlooks', 'folder'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($slug)
    {
        $folder = FolderOutlook::where('slug', $slug)->firstOrFail();
        return view('outlook.create', compact('folder'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $slug)
    {
        $folder = FolderOutlook::where('slug', $slug)->firstOrFail();

        $request->validate([
            'title'     => 'required|max:100|unique:ebooks,title',
            'deskripsi' => 'required',
            'cover'     => 'required|mimes:jpg,jpeg,png|max:2048',
            'file'      => 'required|mimes:pdf|max:10240',
        ]);

        $ebookSlug = Str::slug($request->title) . '-' . time();

        // Upload Cover
        $coverName = time() . '_' . $request->file('cover')->getClientOriginalName();
        $request->file('cover')->move(public_path('uploads/outlook/cover'), $coverName);

        // Upload File Ebook
        $fileName = time() . '_' . $request->file('file')->getClientOriginalName();
        $request->file('file')->move(public_path('uploads/outlook/file'), $fileName);

        // Simpan Data
        Outlook::create([
            'folderOutlook_id' => $folder->id, // ✅ ini yang benar
            'title'     => $request->title,
            'slug'      => $ebookSlug,
            'deskripsi' => $request->deskripsi,
            'cover'     => 'uploads/outlook/cover/' . $coverName,
            'file'      => 'uploads/outlook/file/' . $fileName,
        ]);

        return redirect()->route('outlook.index', $slug)->with('success', 'Outlook berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $slug, string $outlookSlug)
    {
        $folder = FolderOutlook::where('slug', $slug)->firstOrFail();
        $outlook = Outlook::where('slug', $outlookSlug)->firstOrFail();

        return view('outlook.show', compact('folder', 'outlook'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $slug, $outlookSlug)
    {
        $folder = FolderOutlook::where('slug', $slug)->firstOrFail();
        $outlook = Outlook::where('slug', $outlookSlug)->firstOrFail();

        return view('outlook.edit', compact('outlook', 'folder'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $slug, $outlookSlug)
    {
        $folder = FolderOutlook::where('slug', $slug)->firstOrFail();
        $outlook = Outlook::where('folderOutlook_id', $folder->id)->where('slug', $outlookSlug)->firstOrFail();

        $request->validate([
            'title'     => 'required|max:100|unique:ebooks,title,' . $outlook->id,
            'deskripsi' => 'required',
            'cover'     => 'nullable|mimes:jpg,jpeg,png|max:2048',
            'file'      => 'nullable|mimes:pdf|max:10240',
        ]);

        if ($request->hasFile('cover')) {
            if (file_exists(public_path($outlook->cover))) {
                unlink(public_path($outlook->cover));
            }
            $coverName = time() . '_' . $request->file('cover')->getClientOriginalName();
            $request->file('cover')->move(public_path('uploads/outlook/cover/'), $coverName);
            $outlook->cover = 'uploads/outlook/cover/' . $coverName;
        }

        if ($request->hasFile('file')) {
            if (file_exists(public_path($outlook->file))) {
                unlink(public_path($outlook->file));
            }
            $fileName = time() . '_' . $request->file('file')->getClientOriginalName();
            $request->file('file')->move(public_path('uploads/outlook/file/'), $fileName);
            $outlook->file = 'uploads/outlook/file/' . $fileName;
        }

        $outlook->title     = $request->title;
        $outlook->slug      = Str::slug($request->title) . '-' . time();
        $outlook->deskripsi = $request->deskripsi;
        $outlook->save();

        return redirect()->route('outlook.show', [$slug, $outlook->slug])->with('success', 'Ebook berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($slug, $outlookSlug)
    {
        $folder = FolderOutlook::where('slug', $slug)->firstOrFail();
        $outlook = Outlook::where('folderOutlook_id', $folder->id)->where('slug', $outlookSlug)->firstOrFail();

        if (file_exists(public_path($outlook->cover))) {
            unlink(public_path($outlook->cover));
        }
        if (file_exists(public_path($outlook->file))) {
            unlink(public_path($outlook->file));
        }

        $outlook->delete();

        return redirect()->route('outlook.index', $slug)->with('success', 'Ebook berhasil dihapus!');
    }
}
