<?php

namespace App\Http\Controllers;

use App\Models\ApupptAbsensi;
use App\Models\ApupptJadwalAbsensi;
use Illuminate\Http\Request;

class ApupptAbsensiUserController extends Controller
{
    public function index()
    {
        $jadwals = ApupptJadwalAbsensi::where('is_open', true)->orderBy('tanggal', 'desc')->get();
        $userId = auth()->id();

        $absensiUser = ApupptAbsensi::where('user_id', $userId)->pluck('jadwal_id')->toArray();

        return view('apuppt.AbsensiUser.index', compact('jadwals', 'absensiUser', 'userId'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'jadwal_id' => 'required|exists:apuppt_jadwal_absensis,id',
        ]);

        $sudahAbsen = ApupptAbsensi::where('user_id', $request->user_id)
            ->where('jadwal_id', $request->jadwal_id)
            ->exists();

        if ($sudahAbsen) {
            return redirect()->back()->with('error', 'Kamu sudah absen pada sesi APUPPT ini.');
        }

        ApupptAbsensi::create([
            'user_id' => $request->user_id,
            'jadwal_id' => $request->jadwal_id,
            'waktu_absen' => now(),
        ]);

        return redirect()->back()->with('Alert', 'Absensi APUPPT berhasil disimpan!');
    }
}