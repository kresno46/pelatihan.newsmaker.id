<?php

namespace App\Http\Controllers;

use App\Models\ApupptEbook;
use App\Models\ApupptEbookFolder;
use App\Models\ApupptPostTestSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Validation\Rule;

class ApupptEbookController extends Controller
{
    private function findScopedFolder(string $folderSlug): ApupptEbookFolder
    {
        $user = auth()->user();
        $forcedRole = $user && $user->isApupptAdmin() ? $user->apuppt_pt_scope : null;

        return ApupptEbookFolder::where('slug', $folderSlug)
            ->when($forcedRole, fn ($q) => $q->where('apuppt_pt_scope', $forcedRole))
            ->firstOrFail();
    }

    public function index(Request $request, $folderSlug)
    {
        $folder = $this->findScopedFolder($folderSlug);
        $query = ApupptEbook::where('folder_id', $folder->id);

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                    ->orWhere('deskripsi', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('tanggal_dari')) {
            $query->whereDate('created_at', '>=', $request->tanggal_dari);
        }

        if ($request->filled('tanggal_sampai')) {
            $query->whereDate('created_at', '<=', $request->tanggal_sampai);
        }

        $ebooks = $query->latest()->paginate(8)->withQueryString();

        return view('apuppt.ebook.index', compact('ebooks', 'folder'));
    }

    public function create($folderSlug)
    {
        $folder = $this->findScopedFolder($folderSlug);

        return view('apuppt.ebook.create', compact('folder'));
    }

    public function store(Request $request, $folderSlug)
    {
        $folder = $this->findScopedFolder($folderSlug);

        $request->validate([
            'title' => [
                'required',
                'max:100',
                Rule::unique('apuppt_ebooks', 'title')->where(function ($query) use ($folder) {
                    return $query->where('folder_id', $folder->id);
                }),
            ],
            'deskripsi' => 'required',
            'cover' => 'required|mimes:jpg,jpeg,png|max:2048',
            'file' => 'required|file|extensions:pdf|max:10240',
        ]);

        File::ensureDirectoryExists(public_path('uploads/apuppt/cover'));
        File::ensureDirectoryExists(public_path('uploads/apuppt/ebook'));

        $coverName = time() . '_' . $request->file('cover')->getClientOriginalName();
        $request->file('cover')->move(public_path('uploads/apuppt/cover'), $coverName);

        $fileName = time() . '_' . $request->file('file')->getClientOriginalName();
        $request->file('file')->move(public_path('uploads/apuppt/ebook'), $fileName);

        ApupptEbook::create([
            'folder_id' => $folder->id,
            'title' => $request->title,
            'deskripsi' => $request->deskripsi,
            'cover' => 'uploads/apuppt/cover/' . $coverName,
            'file' => 'uploads/apuppt/ebook/' . $fileName,
        ]);

        return redirect()->route('apuppt.ebook.index', $folderSlug)->with('success', 'Ebook APUPPT berhasil ditambahkan!');
    }

    public function show($folderSlug, $ebookSlug)
    {
        $folder = $this->findScopedFolder($folderSlug);
        $ebook = ApupptEbook::with('postTestSession')->where('folder_id', $folder->id)->where('slug', $ebookSlug)->firstOrFail();

        return view('apuppt.ebook.show', compact('ebook', 'folder'));
    }

    public function edit($folderSlug, $ebookSlug)
    {
        $folder = $this->findScopedFolder($folderSlug);
        $ebook = ApupptEbook::where('folder_id', $folder->id)->where('slug', $ebookSlug)->firstOrFail();

        return view('apuppt.ebook.edit', compact('ebook', 'folder'));
    }

    public function update(Request $request, $folderSlug, $ebookSlug)
    {
        $folder = $this->findScopedFolder($folderSlug);
        $ebook = ApupptEbook::where('folder_id', $folder->id)->where('slug', $ebookSlug)->firstOrFail();

        $request->validate([
            'title' => [
                'required',
                'max:100',
                Rule::unique('apuppt_ebooks', 'title')
                    ->ignore($ebook->id)
                    ->where(function ($query) use ($folder) {
                        return $query->where('folder_id', $folder->id);
                    }),
            ],
            'deskripsi' => 'required',
            'cover' => 'nullable|mimes:jpg,jpeg,png|max:2048',
            'file' => 'nullable|file|extensions:pdf|max:10240',
        ]);

        if ($request->hasFile('cover')) {
            if ($ebook->cover && file_exists(public_path($ebook->cover))) {
                unlink(public_path($ebook->cover));
            }
            File::ensureDirectoryExists(public_path('uploads/apuppt/cover'));
            $coverName = time() . '_' . $request->file('cover')->getClientOriginalName();
            $request->file('cover')->move(public_path('uploads/apuppt/cover'), $coverName);
            $ebook->cover = 'uploads/apuppt/cover/' . $coverName;
        }

        if ($request->hasFile('file')) {
            if ($ebook->file && file_exists(public_path($ebook->file))) {
                unlink(public_path($ebook->file));
            }
            File::ensureDirectoryExists(public_path('uploads/apuppt/ebook'));
            $fileName = time() . '_' . $request->file('file')->getClientOriginalName();
            $request->file('file')->move(public_path('uploads/apuppt/ebook'), $fileName);
            $ebook->file = 'uploads/apuppt/ebook/' . $fileName;
        }

        $ebook->title = $request->title;
        $ebook->deskripsi = $request->deskripsi;
        $ebook->save();

        return redirect()->route('apuppt.ebook.show', [$folderSlug, $ebook->slug])->with('success', 'Ebook APUPPT berhasil diperbarui!');
    }

    public function destroy($folderSlug, $ebookSlug)
    {
        $folder = $this->findScopedFolder($folderSlug);
        $ebook = ApupptEbook::where('folder_id', $folder->id)->where('slug', $ebookSlug)->firstOrFail();

        if ($ebook->cover && file_exists(public_path($ebook->cover))) {
            unlink(public_path($ebook->cover));
        }
        if ($ebook->file && file_exists(public_path($ebook->file))) {
            unlink(public_path($ebook->file));
        }

        $ebook->delete();

        return redirect()->route('apuppt.ebook.index', $folderSlug)->with('success', 'Ebook APUPPT berhasil dihapus!');
    }

    public function manageQuiz($folderSlug, $ebookSlug)
    {
        $folder = $this->findScopedFolder($folderSlug);
        $ebook = ApupptEbook::where('folder_id', $folder->id)->where('slug', $ebookSlug)->firstOrFail();

        $session = ApupptPostTestSession::firstOrCreate(
            ['ebook_id' => $ebook->id],
            [
                'title' => 'Soal Ebook APUPPT - '.$ebook->title,
                'duration' => 30,
                'status' => true,
                'tipe' => 'APUPPT',
                'apuppt_pt_scope' => $folder->apuppt_pt_scope,
            ]
        );

        return redirect()
            ->route('apuppt.posttest.edit', $session)
            ->with('success', 'Silakan kelola soal untuk ebook ini.');
    }
}
