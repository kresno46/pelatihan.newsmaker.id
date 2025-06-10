<?php

namespace App\Http\Controllers;

use App\Models\Ebook;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EbookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Ebook::query();

        // Pencarian
        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%')
                ->orWhere('deskripsi', 'like', '%' . $request->search . '%');
        }

        // Paginasi
        $ebooks = $query->paginate(8);

        return view('ebook.index', compact('ebooks'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('ebook.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|max:100',
            'deskripsi' => 'required',
            'cover' => 'required|mimes:jpg,jpeg,png|max:2048',
            'file' => 'required|mimes:pdf|max:10240',
        ]);

        // Buat folder kalau belum ada
        if (!file_exists(public_path('uploads/cover'))) {
            mkdir(public_path('uploads/cover'), 0777, true);
        }
        if (!file_exists(public_path('uploads/ebook'))) {
            mkdir(public_path('uploads/ebook'), 0777, true);
        }

        // Upload cover
        $cover = $request->file('cover');
        $coverName = time() . '_' . $cover->getClientOriginalName();
        $cover->move(public_path('uploads/cover'), $coverName);

        // Upload file ebook
        $file = $request->file('file');
        $fileName = time() . '_' . $file->getClientOriginalName();
        $file->move(public_path('uploads/ebook'), $fileName);

        // Simpan data
        Ebook::create([
            'title' => $request->title,
            'deskripsi' => $request->deskripsi,
            'cover' => 'uploads/cover/' . $coverName,
            'file' => 'uploads/ebook/' . $fileName,
        ]);

        return redirect()->route('ebook.index')->with('success', 'Ebook berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show($slug)
    {
        $ebook = Ebook::with('postTestSessions')->where('slug', $slug)->firstOrFail();
        return view('ebook.show', compact('ebook'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($slug)
    {
        $ebook = Ebook::where('slug', $slug)->firstOrFail();
        return view('ebook.edit', compact('ebook'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $ebook = Ebook::findOrFail($id);

        $request->validate([
            'title' => 'required|max:100',
            'deskripsi' => 'required',
            'cover' => 'nullable|mimes:jpg,jpeg,png|max:2048',
            'file' => 'nullable|mimes:pdf|max:10240',
        ]);

        // Update cover jika ada
        if ($request->hasFile('cover')) {
            if (file_exists(public_path($ebook->cover))) {
                unlink(public_path($ebook->cover));
            }
            $cover = $request->file('cover');
            $coverName = time() . '_' . $cover->getClientOriginalName();
            $cover->move(public_path('uploads/cover'), $coverName);
            $ebook->cover = 'uploads/cover/' . $coverName;
        }

        // Update file ebook jika ada
        if ($request->hasFile('file')) {
            if (file_exists(public_path($ebook->file))) {
                unlink(public_path($ebook->file));
            }
            $file = $request->file('file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/ebook'), $fileName);
            $ebook->file = 'uploads/ebook/' . $fileName;
        }

        // Update data
        $ebook->title = $request->title;
        $ebook->deskripsi = $request->deskripsi;
        $ebook->save();

        return redirect()->route('ebook.show', $ebook->slug)->with('success', 'Ebook berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $ebook = Ebook::where('id', $id)->firstOrFail();

        // Hapus file
        Storage::disk('public')->delete([$ebook->cover, $ebook->file]);

        // Hapus data
        $ebook->delete();

        return redirect()->route('ebook.index')->with('success', 'Ebook berhasil dihapus!');
    }
}
