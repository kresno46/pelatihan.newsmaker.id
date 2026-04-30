<?php

namespace App\Http\Controllers;

use App\Models\ApupptEbookFolder;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ApupptEbookFolderController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $forcedRole = $user && $user->isApupptAdmin() ? $user->apuppt_pt_scope : null;
        if ($user && $user->isApupptAdmin() && ! $forcedRole) {
            abort(403, 'PT scope untuk Admin APUPPT belum diatur.');
        }

        $search = trim((string) request('search', ''));
        $folders = ApupptEbookFolder::withCount('ebooks')
            ->when($forcedRole, fn ($q) => $q->where('apuppt_pt_scope', $forcedRole))
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('folder_name', 'like', "%{$search}%")
                        ->orWhere('deskripsi', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(8)
            ->withQueryString();

        return view('apuppt.ebook-folder.index', compact('folders'));
    }

    public function create()
    {
        $user = auth()->user();
        $forcedRole = $user && $user->isApupptAdmin() ? $user->apuppt_pt_scope : null;
        $ptOptions = User::APUPPT_PT_ROLES;

        return view('apuppt.ebook-folder.create', compact('forcedRole', 'ptOptions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'folder_name' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'apuppt_pt_scope' => ['nullable', Rule::in(User::APUPPT_PT_ROLES)],
        ]);
        $user = auth()->user();
        $forcedRole = $user && $user->isApupptAdmin() ? $user->apuppt_pt_scope : null;
        $scope = $forcedRole ?: $request->apuppt_pt_scope;
        if (! $scope) {
            return back()->withErrors(['apuppt_pt_scope' => 'PT scope wajib dipilih.'])->withInput();
        }

        ApupptEbookFolder::create([
            'folder_name' => $request->folder_name,
            'deskripsi' => $request->deskripsi,
            'is_active' => true,
            'apuppt_pt_scope' => $scope,
        ]);

        return redirect()->route('apuppt.ebookfolder.index')->with('success', 'Folder Ebook APUPPT berhasil dibuat.');
    }

    public function edit($slug)
    {
        $user = auth()->user();
        $forcedRole = $user && $user->isApupptAdmin() ? $user->apuppt_pt_scope : null;
        $folder = ApupptEbookFolder::where('slug', $slug)
            ->when($forcedRole, fn ($q) => $q->where('apuppt_pt_scope', $forcedRole))
            ->firstOrFail();
        $ptOptions = User::APUPPT_PT_ROLES;

        return view('apuppt.ebook-folder.edit', compact('folder', 'forcedRole', 'ptOptions'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'folder_name' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'apuppt_pt_scope' => ['nullable', Rule::in(User::APUPPT_PT_ROLES)],
        ]);
        $user = auth()->user();
        $forcedRole = $user && $user->isApupptAdmin() ? $user->apuppt_pt_scope : null;

        $folder = ApupptEbookFolder::when($forcedRole, fn ($q) => $q->where('apuppt_pt_scope', $forcedRole))
            ->findOrFail($id);
        $scope = $forcedRole ?: $request->apuppt_pt_scope;
        if (! $scope) {
            return back()->withErrors(['apuppt_pt_scope' => 'PT scope wajib dipilih.'])->withInput();
        }
        $folder->update([
            'folder_name' => $request->folder_name,
            'deskripsi' => $request->deskripsi,
            'apuppt_pt_scope' => $scope,
        ]);

        return redirect()->route('apuppt.ebookfolder.index')->with('success', 'Folder Ebook APUPPT berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $user = auth()->user();
        $forcedRole = $user && $user->isApupptAdmin() ? $user->apuppt_pt_scope : null;
        $folder = ApupptEbookFolder::when($forcedRole, fn ($q) => $q->where('apuppt_pt_scope', $forcedRole))
            ->findOrFail($id);
        $folder->delete();

        return redirect()->route('apuppt.ebookfolder.index')->with('success', 'Folder Ebook APUPPT berhasil dihapus.');
    }

    public function toggle($id)
    {
        $user = auth()->user();
        $forcedRole = $user && $user->isApupptAdmin() ? $user->apuppt_pt_scope : null;
        $folder = ApupptEbookFolder::when($forcedRole, fn ($q) => $q->where('apuppt_pt_scope', $forcedRole))
            ->findOrFail($id);
        $folder->is_active = ! $folder->is_active;
        $folder->save();

        return back()->with('success', 'Status folder berhasil diperbarui.');
    }
}
