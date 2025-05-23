<?php

namespace App\Http\Controllers;

use App\Models\ebook;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class EbookController extends Controller
{
    public function index()
    {
        $ebooks = ebook::all();
        return view('ebook.index', compact('ebooks'));
    }

    public function create()
    {
        return view('ebook.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string',
            'deskripsi' => 'nullable|string',
            'penulis' => 'required|string|max:100',
            'tahun_terbit' => 'required|string|max:5',
            'file_ebook' => 'nullable|file|mimes:pdf|max:10240',
            'cover_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        // Simpan file ebook ke storage/app/public/ebooks
        $ebookFile = $request->file('file_ebook')->store('ebooks', 'public');

        // Simpan cover image ke public/covers
        $coverFile = $request->file('cover_image');
        $coverName = uniqid() . '.' . $coverFile->getClientOriginalExtension();
        $coverFile->move(public_path('covers'), $coverName);

        ebook::create([
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'penulis' => $request->penulis,
            'tahun_terbit' => $request->tahun_terbit,
            'file_ebook' => $ebookFile,
            'cover_image' => 'covers/' . $coverName,
        ]);

        return redirect()->route('ebook.index')->with('success', 'Ebook berhasil ditambahkan.');
    }

    public function show(string $id)
    {
        $ebook = ebook::findOrFail($id);
        return view('ebook.show', compact('ebook'));
    }

    public function edit(string $id)
    {
        $ebook = ebook::findOrFail($id);
        return view('ebook.edit', compact('ebook'));
    }

    public function update(Request $request, string $id)
    {
        $ebook = ebook::findOrFail($id);

        $request->validate([
            'judul' => 'required|string',
            'deskripsi' => 'nullable|string',
            'penulis' => 'required|string|max:100',
            'tahun_terbit' => 'required|string|max:5',
            'file_ebook' => 'nullable|file|mimes:pdf|max:10240',
            'cover_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        $data = [
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'penulis' => $request->penulis,
            'tahun_terbit' => $request->tahun_terbit,
        ];

        // Ganti file ebook jika ada upload baru
        if ($request->hasFile('file_ebook')) {
            if ($ebook->file_ebook && Storage::disk('public')->exists($ebook->file_ebook)) {
                Storage::disk('public')->delete($ebook->file_ebook);
            }

            $data['file_ebook'] = $request->file('file_ebook')->store('ebooks', 'public');
        }

        // Ganti cover jika ada upload baru
        if ($request->hasFile('cover_image')) {
            if ($ebook->cover_image && file_exists(public_path($ebook->cover_image))) {
                File::delete(public_path($ebook->cover_image));
            }

            $coverFile = $request->file('cover_image');
            $coverName = uniqid() . '.' . $coverFile->getClientOriginalExtension();
            $coverFile->move(public_path('covers'), $coverName);
            $data['cover_image'] = 'covers/' . $coverName;
        }

        $ebook->update($data);

        return redirect()->route('ebook.show', $ebook->id)->with('success', 'Ebook berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $ebook = ebook::findOrFail($id);

        // Hapus file ebook dari storage
        if ($ebook->file_ebook && Storage::disk('public')->exists($ebook->file_ebook)) {
            Storage::disk('public')->delete($ebook->file_ebook);
        }

        // Hapus cover dari public folder
        if ($ebook->cover_image && file_exists(public_path($ebook->cover_image))) {
            File::delete(public_path($ebook->cover_image));
        }

        $ebook->delete();

        return redirect()->route('ebook.index')->with('success', 'Ebook berhasil dihapus.');
    }
}
