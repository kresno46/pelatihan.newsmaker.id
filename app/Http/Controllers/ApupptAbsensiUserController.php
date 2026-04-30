<?php

namespace App\Http\Controllers;

use App\Models\ApupptAbsensi;
use App\Models\ApupptJadwalAbsensi;
use Illuminate\Http\Request;

class ApupptAbsensiUserController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $jadwals = ApupptJadwalAbsensi::where('is_open', true)
            ->whereHas('postTestSession', fn ($q) => $q->where('apuppt_pt_scope', $user->role))
            ->orderBy('tanggal', 'desc')
            ->get();
        $userId = auth()->id();

        $absensiUser = ApupptAbsensi::where('user_id', $userId)->pluck('jadwal_id')->toArray();

        return view('apuppt.AbsensiUser.index', compact('jadwals', 'absensiUser', 'userId'));
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'jadwal_id' => 'required|exists:apuppt_jadwal_absensis,id',
        ]);
        if ((int) $request->user_id !== (int) $user->id) {
            abort(403);
        }
        $jadwal = ApupptJadwalAbsensi::whereHas('postTestSession', fn ($q) => $q->where('apuppt_pt_scope', $user->role))
            ->findOrFail($request->jadwal_id);

        $sudahAbsen = ApupptAbsensi::where('user_id', $request->user_id)
            ->where('jadwal_id', $jadwal->id)
            ->exists();

        if ($sudahAbsen) {
            return redirect()->back()->with('error', 'Kamu sudah absen pada sesi APUPPT ini.');
        }

        ApupptAbsensi::create([
            'user_id' => $request->user_id,
            'jadwal_id' => $jadwal->id,
            'waktu_absen' => now(),
        ]);

        return redirect()->back()->with('Alert', 'Absensi APUPPT berhasil disimpan!');
    }
}
