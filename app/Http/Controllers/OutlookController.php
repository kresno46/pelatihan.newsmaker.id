<?php

namespace App\Http\Controllers;

use App\Models\Outlook;
use App\Models\OutlookFolder;
use App\Models\PostTestResult;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class OutlookController extends Controller
{
    /**
     * Tampilkan daftar Outlook dalam folder.
     */
    public function index(Request $request, $folderSlug)
    {
        $folder = OutlookFolder::where('slug', $folderSlug)->firstOrFail();
        $query = Outlook::where('folder_id', $folder->id);

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                    ->orWhere('deskripsi', 'like', '%' . $request->search . '%');
            });
        }

        $outlooks = $query->paginate(8);

        return view('outlook.index', compact('outlooks', 'folder'));
    }

    /**
     * Tampilkan form tambah Outlook.
     */
    public function create($folderSlug)
    {
        $folder = OutlookFolder::where('slug', $folderSlug)->firstOrFail();
        return view('outlook.create', compact('folder'));
    }

    /**
     * Simpan Outlook baru.
     */
    public function store(Request $request, $folderSlug)
    {
        $folder = OutlookFolder::where('slug', $folderSlug)->firstOrFail();

        $request->validate([
            'title'     => 'required|max:100|unique:outlooks,title',
            'deskripsi' => 'required',
            'cover'     => 'required|mimes:jpg,jpeg,png|max:2048',
            'file'      => 'required|mimes:pdf|max:10240',
        ]);

        $outlookSlug = Str::slug($request->title) . '-' . time();

        // Upload Cover
        $coverName = time() . '_' . $request->file('cover')->getClientOriginalName();
        $request->file('cover')->move(public_path('uploads/cover'), $coverName);

        // Upload File Outlook
        $fileName = time() . '_' . $request->file('file')->getClientOriginalName();
        $request->file('file')->move(public_path('uploads/outlook'), $fileName);

        // Simpan Data
        Outlook::create([
            'folder_id' => $folder->id,
            'title'     => $request->title,
            'slug'      => $outlookSlug,
            'deskripsi' => $request->deskripsi,
            'cover'     => 'uploads/cover/' . $coverName,
            'file'      => 'uploads/outlook/' . $fileName,
        ]);

        return redirect()->route('outlook.index', $folderSlug)->with('success', 'Outlook berhasil ditambahkan!');
    }

    /**
     * Tampilkan detail Outlook.
     */
    public function show($folderSlug, $outlookSlug)
    {
        $folder = OutlookFolder::where('slug', $folderSlug)->firstOrFail();

        $outlook = Outlook::with(['postTestSessions.results' => function ($query) {
            $query->where('user_id', auth()->id());
        }])->where('slug', $outlookSlug)->firstOrFail();

        return view('outlook.show', compact('outlook', 'folder'));
    }

    /**
     * Tampilkan form edit Outlook.
     */
    public function edit($folderSlug, $outlookSlug)
    {
        $folder = OutlookFolder::where('slug', $folderSlug)->firstOrFail();
        $outlook = Outlook::where('folder_id', $folder->id)->where('slug', $outlookSlug)->firstOrFail();

        return view('outlook.edit', compact('outlook', 'folder'));
    }

    /**
     * Update Outlook.
     */
    public function update(Request $request, $folderSlug, $outlookSlug)
    {
        $folder = OutlookFolder::where('slug', $folderSlug)->firstOrFail();
        $outlook = Outlook::where('folder_id', $folder->id)->where('slug', $outlookSlug)->firstOrFail();

        $request->validate([
            'title'     => 'required|max:100|unique:outlooks,title,' . $outlook->id,
            'deskripsi' => 'required',
            'cover'     => 'nullable|mimes:jpg,jpeg,png|max:2048',
            'file'      => 'nullable|mimes:pdf|max:10240',
        ]);

        if ($request->hasFile('cover')) {
            if (file_exists(public_path($outlook->cover))) {
                unlink(public_path($outlook->cover));
            }
            $coverName = time() . '_' . $request->file('cover')->getClientOriginalName();
            $request->file('cover')->move(public_path('uploads/cover'), $coverName);
            $outlook->cover = 'uploads/cover/' . $coverName;
        }

        if ($request->hasFile('file')) {
            if (file_exists(public_path($outlook->file))) {
                unlink(public_path($outlook->file));
            }
            $fileName = time() . '_' . $request->file('file')->getClientOriginalName();
            $request->file('file')->move(public_path('uploads/outlook'), $fileName);
            $outlook->file = 'uploads/outlook/' . $fileName;
        }

        $outlook->title     = $request->title;
        $outlook->slug      = Str::slug($request->title) . '-' . time();
        $outlook->deskripsi = $request->deskripsi;
        $outlook->save();

        return redirect()->route('outlook.show', [$folderSlug, $outlook->slug])->with('success', 'Outlook berhasil diperbarui!');
    }

    /**
     * Hapus Outlook.
     */
    public function destroy($folderSlug, $outlookSlug)
    {
        $folder = OutlookFolder::where('slug', $folderSlug)->firstOrFail();
        $outlook = Outlook::where('folder_id', $folder->id)->where('slug', $outlookSlug)->firstOrFail();

        if (file_exists(public_path($outlook->cover))) {
            unlink(public_path($outlook->cover));
        }
        if (file_exists(public_path($outlook->file))) {
            unlink(public_path($outlook->file));
        }

        $outlook->delete();

        return redirect()->route('outlook.index', $folderSlug)->with('success', 'Outlook berhasil dihapus!');
    }
}
