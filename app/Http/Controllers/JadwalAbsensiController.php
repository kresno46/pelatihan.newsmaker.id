<?php

namespace App\Http\Controllers;

use App\Models\JadwalAbsensi;
use Illuminate\Http\Request;

class JadwalAbsensiController extends Controller
{
    /**
     * Menampilkan semua jadwal absensi.
     */
    public function index()
    {
        $jadwals = JadwalAbsensi::orderBy('tanggal', 'desc')->get();
        return view('jadwal.index', compact('jadwals'));
    }

    /**
     * Menyimpan jadwal absensi baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'tanggal' => 'required|date',
        ]);

        JadwalAbsensi::create([
            'title' => $request->title,
            'tanggal' => $request->tanggal,
            'is_open' => false, // default tertutup
        ]);

        return back()->with('Alert', 'Jadwal absensi berhasil ditambahkan.');
    }

    /**
     * Memperbarui jadwal absensi.
     */
    public function update(Request $request, $id)
    {
        $jadwal = JadwalAbsensi::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'tanggal' => 'required|date',
        ]);

        $jadwal->update([
            'title' => $request->title,
            'tanggal' => $request->tanggal,
        ]);

        return back()->with('Alert', 'Jadwal ' . $jadwal->title . ' berhasil diperbarui.');
    }

    /**
     * Menghapus jadwal absensi.
     */
    public function destroy($id)
    {
        $jadwal = JadwalAbsensi::findOrFail($id);
        $jadwal->delete();

        return back()->with('Alert', 'Jadwal ' . $jadwal->title . ' berhasil dihapus.');
    }

    /**
     * Mengaktifkan / menonaktifkan jadwal absensi (toggle).
     */
    public function toggle($id)
    {
        $jadwal = JadwalAbsensi::findOrFail($id);
        $jadwal->is_open = !$jadwal->is_open;
        $jadwal->save();

        return back();
    }
}
