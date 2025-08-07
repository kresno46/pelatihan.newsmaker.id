<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JadwalAbsensi;
use App\Models\Absensi;

class AbsensiController extends Controller
{
    public function indexAbsensi()
    {
        $jadwals = JadwalAbsensi::all();
        $userId = auth()->user()->id;

        $absensiUser = Absensi::where('user_id', $userId)->pluck('jadwal_id')->toArray();

        return view('Absensi.index', compact('jadwals', 'absensiUser', 'userId'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'jadwal_id' => 'required|exists:jadwal_absensis,id',
        ]);

        // Cegah double absen
        $sudahAbsen = Absensi::where('user_id', $request->user_id)
            ->where('jadwal_id', $request->jadwal_id)
            ->exists();

        if ($sudahAbsen) {
            return redirect()->back()->with('error', 'Kamu sudah absen pada sesi ini.');
        }

        Absensi::create([
            'user_id' => $request->user_id,
            'jadwal_id' => $request->jadwal_id,
            'waktu_absen' => now(),
        ]);

        return redirect()->back()->with('Alert', 'Absensi berhasil disimpan!');
    }
}
