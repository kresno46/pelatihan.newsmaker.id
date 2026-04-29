<?php

namespace App\Http\Middleware;

use App\Models\ApupptAbsensi;
use App\Models\ApupptPostTestSession;
use Closure;
use Illuminate\Http\Request;

class EnsureUserHasApupptAbsensi
{
    public function handle(Request $request, Closure $next)
    {
        $slug = $request->route('slug');
        $userId = auth()->id();

        $session = ApupptPostTestSession::where('slug', $slug)->with('jadwalAbsensis')->first();

        if (! $session) {
            abort(404, 'Sesi post test APUPPT tidak ditemukan.');
        }

        if (! $session->status) {
            return redirect()->route('apuppt.test.index')->with('error', 'Post test APUPPT ini saat ini tidak tersedia.');
        }

        // Sesi yang terhubung ke ebook tidak wajib absensi.
        if (! is_null($session->ebook_id)) {
            return $next($request);
        }

        if ($session->jadwalAbsensis->isEmpty()) {
            return redirect()->route('apuppt.test.index')->with('error', 'Post test APUPPT ini belum memiliki jadwal absensi.');
        }

        $jadwalIds = $session->jadwalAbsensis->pluck('id');

        $absenExists = ApupptAbsensi::where('user_id', $userId)
            ->whereIn('jadwal_id', $jadwalIds)
            ->exists();

        if (! $absenExists) {
            return redirect()->route('apuppt.absensiUser.index')->with('error', 'Anda harus mengisi absensi APUPPT terlebih dahulu.');
        }

        return $next($request);
    }
}
