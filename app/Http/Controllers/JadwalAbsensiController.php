<?php

namespace App\Http\Controllers;

use App\Models\JadwalAbsensi;
use App\Models\PostTestSession;
use Illuminate\Http\Request;

class JadwalAbsensiController extends Controller
{
    /**
     * Menampilkan semua jadwal absensi.
     */
    public function index()
    {
        $jadwals = JadwalAbsensi::orderBy('tanggal', 'desc')->get();
        $postTestSessions = PostTestSession::all(); // Ambil semua sesi post-test
        return view('jadwal.index', compact('jadwals', 'postTestSessions'));
    }

    /**
     * Menampilkan form untuk menambahkan jadwal absensi baru.
     */
    public function create()
    {
        $postTestSessions = PostTestSession::all(); // Ambil semua sesi post-test
        return view('jadwal.index', compact('postTestSessions'));
    }

    /**
     * Menyimpan jadwal absensi baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'tanggal' => 'required|date',
            'post_test_session_id' => 'required|exists:post_test_sessions,id',
        ]);

        JadwalAbsensi::create([
            'title' => $request->title,
            'tanggal' => $request->tanggal,
            'post_test_session_id' => $request->post_test_session_id,
            'is_open' => false, // default tertutup
        ]);

        return back()->with('Alert', 'Jadwal absensi berhasil ditambahkan.');
    }

    /**
     * Menampilkan form untuk mengedit jadwal absensi yang sudah ada.
     */
    public function edit($id)
    {
        $jadwal = JadwalAbsensi::findOrFail($id);
        $postTestSessions = PostTestSession::all(); // Ambil semua sesi post-test
        return view('jadwal.index', compact('jadwal', 'postTestSessions'));
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
            'post_test_session_id' => 'required|exists:post_test_sessions,id',
        ]);

        $jadwal->update([
            'title' => $request->title,
            'tanggal' => $request->tanggal,
            'post_test_session_id' => $request->post_test_session_id,
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
