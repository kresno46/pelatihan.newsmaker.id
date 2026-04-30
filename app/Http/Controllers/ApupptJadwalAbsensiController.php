<?php

namespace App\Http\Controllers;

use App\Models\ApupptJadwalAbsensi;
use App\Models\ApupptPostTestSession;
use Illuminate\Http\Request;

class ApupptJadwalAbsensiController extends Controller
{
    private function forcedRole(): ?string
    {
        $user = auth()->user();

        return $user && $user->isApupptAdmin() ? $user->apuppt_pt_scope : null;
    }

    private function scopedSessionsQuery()
    {
        $forcedRole = $this->forcedRole();

        return ApupptPostTestSession::query()
            ->when($forcedRole, fn ($q) => $q->where('apuppt_pt_scope', $forcedRole));
    }

    public function index(Request $request)
    {
        $search = $request->get('search');
        $forcedRole = $this->forcedRole();
        $jadwals = ApupptJadwalAbsensi::with(['postTestSession' => function ($query) {
            $query->withCount('questions');
        }])
            ->when($forcedRole, fn ($q) => $q->whereHas('postTestSession', fn ($s) => $s->where('apuppt_pt_scope', $forcedRole)))
            ->when($search, function ($query) use ($search) {
                $query->where('title', 'like', '%' . $search . '%');
            })
            ->orderBy('tanggal', 'desc')
            ->get();

        $postTestSessions = $this->scopedSessionsQuery()->get();

        return view('apuppt.jadwal.index', compact('jadwals', 'postTestSessions', 'search'));
    }

    public function create()
    {
        $postTestSessions = $this->scopedSessionsQuery()->get();

        return view('apuppt.jadwal.create', compact('postTestSessions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:100',
            'tanggal' => 'required|date',
            'post_test_session_id' => 'required|exists:apuppt_post_test_sessions,id',
        ]);
        $session = $this->scopedSessionsQuery()->where('id', $request->post_test_session_id)->first();
        if (! $session) {
            abort(403);
        }

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
        $forcedRole = $this->forcedRole();
        $jadwal = ApupptJadwalAbsensi::when($forcedRole, fn ($q) => $q->whereHas('postTestSession', fn ($s) => $s->where('apuppt_pt_scope', $forcedRole)))
            ->findOrFail($id);
        $postTestSessions = $this->scopedSessionsQuery()->get();

        return view('apuppt.jadwal.edit', compact('jadwal', 'postTestSessions'));
    }

    public function update(Request $request, $id)
    {
        $forcedRole = $this->forcedRole();
        $jadwal = ApupptJadwalAbsensi::when($forcedRole, fn ($q) => $q->whereHas('postTestSession', fn ($s) => $s->where('apuppt_pt_scope', $forcedRole)))
            ->findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:100',
            'tanggal' => 'required|date',
            'post_test_session_id' => 'required|exists:apuppt_post_test_sessions,id',
        ]);
        $session = $this->scopedSessionsQuery()->where('id', $request->post_test_session_id)->first();
        if (! $session) {
            abort(403);
        }

        $jadwal->update([
            'title' => $request->title,
            'tanggal' => $request->tanggal,
            'apuppt_post_test_session_id' => $request->post_test_session_id,
        ]);

        return redirect()->route('apuppt.absensi.index')->with('Alert', 'Jadwal ' . $jadwal->title . ' berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $forcedRole = $this->forcedRole();
        $jadwal = ApupptJadwalAbsensi::when($forcedRole, fn ($q) => $q->whereHas('postTestSession', fn ($s) => $s->where('apuppt_pt_scope', $forcedRole)))
            ->findOrFail($id);
        $jadwal->delete();

        return back()->with('Alert', 'Jadwal ' . $jadwal->title . ' berhasil dihapus.');
    }

    public function toggle($id)
    {
        $forcedRole = $this->forcedRole();
        $jadwal = ApupptJadwalAbsensi::when($forcedRole, fn ($q) => $q->whereHas('postTestSession', fn ($s) => $s->where('apuppt_pt_scope', $forcedRole)))
            ->findOrFail($id);
        $jadwal->is_open = ! $jadwal->is_open;
        $jadwal->save();

        return back();
    }
}
