<?php

namespace App\Http\Controllers;

use App\Models\PostTestResult;
use App\Models\PostTestSession;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $laporans = PostTestResult::with([
            'user:id,name',
            'ebook:id,title,deskripsi,cover',
            'session:id,title,duration'
        ])
            ->when($search, function ($query, $search) {
                $query->whereHas('user', function ($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%');
                })
                    ->orWhereHas('ebook', function ($q) use ($search) {
                        $q->where('title', 'like', '%' . $search . '%');
                    })
                    ->orWhereHas('session', function ($q) use ($search) {
                        $q->where('title', 'like', '%' . $search . '%');
                    });
            })
            ->orderByDesc('created_at')
            ->paginate(10)
            ->appends(['search' => $search]); // agar pagination menyimpan kata kunci pencarian

        return view('laporan.index', compact('laporans'));
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $laporan = PostTestResult::with([
            'user:id,name,email,jenis_kelamin,tempat_lahir,tanggal_lahir,warga_negara,alamat,no_id,no_tlp,pekerjaan',
            'ebook:id,title,deskripsi,cover',
            'session:id,title,duration'
        ])->find($id);

        if (!$laporan) {
            abort(404);
        }

        return view('laporan.show', compact('laporan'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $laporan = PostTestResult::find($id);

        if ($laporan) {
            $laporan->delete();
            return redirect()->route('laporan.index')->with('Alert', 'Laporan berhasil dihapus!');
        }

        abort(404);
    }
}
