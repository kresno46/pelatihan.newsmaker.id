<?php

namespace App\Http\Controllers;

use App\Models\ApupptJadwalAbsensi;
use App\Models\ApupptPostTestSession;
use Illuminate\Http\Request;

class ApupptJadwalAbsensiController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $jadwals = ApupptJadwalAbsensi::with(['postTestSession' => function ($query) {
            $query->withCount('questions');
        }])
            ->when($search, function ($query) use ($search) {
                $query->where('title', 'like', '%' . $search . '%');
            })
            ->orderBy('tanggal', 'desc')
            ->get();

        $postTestSessions = ApupptPostTestSession::all();

        return view('apuppt.jadwal.index', compact('jadwals', 'postTestSessions', 'search'));
    }

    public function create()
    {
        $postTestSessions = ApupptPostTestSession::all();

        return view('apuppt.jadwal.create', compact('postTestSessions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:100',
            'tanggal' => 'required|date',
            'post_test_session_id' => 'required|exists:apuppt_post_test_sessions,id',
        ]);

        ApupptJadwalAbsensi::create([
            'title' => $request->title,
            'tanggal' => $request->tanggal,
            'apuppt_post_test_session_id' => $request->post_test_session_id,
            'is_open' => false,
        ]);

        return redirect()->route('apuppt.absensi.index')->with('Alert', 'Jadwal absensi APUPPT berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $jadwal = ApupptJadwalAbsensi::findOrFail($id);
        $postTestSessions = ApupptPostTestSession::all();

        return view('apuppt.jadwal.edit', compact('jadwal', 'postTestSessions'));
    }

    public function update(Request $request, $id)
    {
        $jadwal = ApupptJadwalAbsensi::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:100',
            'tanggal' => 'required|date',
            'post_test_session_id' => 'required|exists:apuppt_post_test_sessions,id',
        ]);

        $jadwal->update([
            'title' => $request->title,
            'tanggal' => $request->tanggal,
            'apuppt_post_test_session_id' => $request->post_test_session_id,
        ]);

        return redirect()->route('apuppt.absensi.index')->with('Alert', 'Jadwal ' . $jadwal->title . ' berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $jadwal = ApupptJadwalAbsensi::findOrFail($id);
        $jadwal->delete();

        return back()->with('Alert', 'Jadwal ' . $jadwal->title . ' berhasil dihapus.');
    }

    public function toggle($id)
    {
        $jadwal = ApupptJadwalAbsensi::findOrFail($id);
        $jadwal->is_open = ! $jadwal->is_open;
        $jadwal->save();

        return back();
    }
}