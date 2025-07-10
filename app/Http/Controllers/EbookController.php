<?php

namespace App\Http\Controllers;

use App\Models\Ebook;
use App\Models\FolderEbook;
use App\Models\PostTestResult;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class EbookController extends Controller
{
    /**
     * Tampilkan daftar eBook dalam folder.
     */
    public function index(Request $request, $folderSlug)
    {
        $folder = FolderEbook::where('slug', $folderSlug)->firstOrFail();
        $query = Ebook::where('folder_id', $folder->id);

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
     */
    public function create($folderSlug)
    {
        $folder = FolderEbook::where('slug', $folderSlug)->firstOrFail();
        return view('ebook.create', compact('folder'));
    }

    /**
     * Simpan eBook baru.
     */
    public function store(Request $request, $folderSlug)
    {
        $folder = FolderEbook::where('slug', $folderSlug)->firstOrFail();

        $request->validate([
            'title'     => 'required|max:100|unique:ebooks,title',
            'deskripsi' => 'required',
            'cover'     => 'required|mimes:jpg,jpeg,png|max:2048',
            'file'      => 'required|mimes:pdf|max:10240',
        ]);

        $ebookSlug = Str::slug($request->title) . '-' . time();

        // Upload Cover
        $coverName = time() . '_' . $request->file('cover')->getClientOriginalName();
        $request->file('cover')->move(public_path('uploads/cover'), $coverName);

        // Upload File Ebook
        $fileName = time() . '_' . $request->file('file')->getClientOriginalName();
        $request->file('file')->move(public_path('uploads/ebook'), $fileName);

        // Simpan Data
        Ebook::create([
            'folder_id' => $folder->id,
            'title'     => $request->title,
            'slug'      => $ebookSlug,
            'deskripsi' => $request->deskripsi,
            'cover'     => 'uploads/cover/' . $coverName,
            'file'      => 'uploads/ebook/' . $fileName,
        ]);

        return redirect()->route('ebook.index', $folderSlug)->with('success', 'Ebook berhasil ditambahkan!');
    }

    /**
     * Tampilkan detail eBook.
     */
    public function show($folderSlug, $ebookSlug)
    {
        $folder = FolderEbook::where('slug', $folderSlug)->firstOrFail();

        $ebook = Ebook::with(['postTestSessions.results' => function ($query) {
            $query->where('user_id', auth()->id());
        }])->where('slug', $ebookSlug)->firstOrFail();

        return view('ebook.show', compact('ebook', 'folder'));
    }

    /**
     * Tampilkan form edit eBook.
     */
    public function edit($folderSlug, $ebookSlug)
    {
        $folder = FolderEbook::where('slug', $folderSlug)->firstOrFail();
        $ebook = Ebook::where('folder_id', $folder->id)->where('slug', $ebookSlug)->firstOrFail();

        return view('ebook.edit', compact('ebook', 'folder'));
    }

    /**
     * Update eBook.
     */
    public function update(Request $request, $folderSlug, $ebookSlug)
    {
        $folder = FolderEbook::where('slug', $folderSlug)->firstOrFail();
        $ebook = Ebook::where('folder_id', $folder->id)->where('slug', $ebookSlug)->firstOrFail();

        $request->validate([
            'title'     => 'required|max:100|unique:ebooks,title,' . $ebook->id,
            'deskripsi' => 'required',
            'cover'     => 'nullable|mimes:jpg,jpeg,png|max:2048',
            'file'      => 'nullable|mimes:pdf|max:10240',
        ]);

        if ($request->hasFile('cover')) {
            if (file_exists(public_path($ebook->cover))) {
                unlink(public_path($ebook->cover));
            }
            $coverName = time() . '_' . $request->file('cover')->getClientOriginalName();
            $request->file('cover')->move(public_path('uploads/cover'), $coverName);
            $ebook->cover = 'uploads/cover/' . $coverName;
        }

        if ($request->hasFile('file')) {
            if (file_exists(public_path($ebook->file))) {
                unlink(public_path($ebook->file));
            }
            $fileName = time() . '_' . $request->file('file')->getClientOriginalName();
            $request->file('file')->move(public_path('uploads/ebook'), $fileName);
            $ebook->file = 'uploads/ebook/' . $fileName;
        }

        $ebook->title     = $request->title;
        $ebook->slug      = Str::slug($request->title) . '-' . time();
        $ebook->deskripsi = $request->deskripsi;
        $ebook->save();

        return redirect()->route('ebook.show', [$folderSlug, $ebook->slug])->with('success', 'Ebook berhasil diperbarui!');
    }

    /**
     * Hapus eBook.
     */
    public function destroy($folderSlug, $ebookSlug)
    {
        $folder = FolderEbook::where('slug', $folderSlug)->firstOrFail();
        $ebook = Ebook::where('folder_id', $folder->id)->where('slug', $ebookSlug)->firstOrFail();

        if (file_exists(public_path($ebook->cover))) {
            unlink(public_path($ebook->cover));
        }
        if (file_exists(public_path($ebook->file))) {
            unlink(public_path($ebook->file));
        }

        $ebook->delete();

        return redirect()->route('ebook.index', $folderSlug)->with('success', 'Ebook berhasil dihapus!');
    }
}
