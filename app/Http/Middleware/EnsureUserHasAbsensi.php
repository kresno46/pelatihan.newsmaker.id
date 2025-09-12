<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\PostTestSession;
use App\Models\Absensi;

class EnsureUserHasAbsensi
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $slug = $request->route('slug'); // ambil slug dari URL
        $userId = auth()->id();

        // Cari session berdasarkan slug
        $session = PostTestSession::where('slug', $slug)
            ->with('jadwalAbsensis')
            ->first();

        if (!$session) {
            abort(404, 'Sesi post test tidak ditemukan.');
        }

        // Proteksi: cek status post test
        if ($session->status === 'Tidak Aktif') {
            return redirect()->route('post-test.index')
                ->with('error', 'Post test ini saat ini tidak tersedia.');
        }

        // Jika tidak ada jadwal absensi terkait session ini
        if ($session->jadwalAbsensis->isEmpty()) {
            return redirect()->route('post-test.index')
                ->with('error', 'Post test ini belum memiliki jadwal absensi. Silakan hubungi admin.');
        }

        // Ambil semua jadwal id yang terkait session ini
        $jadwalIds = $session->jadwalAbsensis->pluck('id');

        // Cek apakah user sudah absen di salah satu jadwal
        $absenExists = Absensi::where('user_id', $userId)
            ->whereIn('jadwal_id', $jadwalIds)
            ->exists();

        if (!$absenExists) {
            return redirect()->route('post-test.index')
                ->with('error', 'Anda harus mengisi absensi terlebih dahulu sebelum mengerjakan post test.');
        }

        return $next($request);
    }
}
