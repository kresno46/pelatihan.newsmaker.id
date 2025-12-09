<?php

namespace App\Http\Middleware;

use App\Models\PostTestSession;
use App\Models\PostTestResult;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPATLAccess
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Ambil ID atau slug dari parameter route
        $sessionId = $request->route('id') ?? $request->route('slug');

        if (! $sessionId) {
            return redirect()->route('post-test.index')
                ->with('error', 'Session tidak ditemukan.');
        }

        // Ambil data session dari database
        $session = PostTestSession::where('id', $sessionId)
            ->orWhere('slug', $sessionId)
            ->first();

        if (! $session) {
            return redirect()->route('post-test.index')
                ->with('error', 'Session tidak ditemukan.');
        }

        // Cek tipe soal, jika PATL maka hanya jabatan tertentu yang boleh akses
        if ($session->tipe === 'PATL') {
            $user = auth()->user();

            // Cek apakah user sudah lulus PATD
            $hasPassedPATD = PostTestResult::where('user_id', $user->id)
                ->whereHas('session', function ($query) {
                    $query->where('tipe', 'PATD');
                })
                ->where('score', '>=', 60)
                ->exists();

            // Jika belum lulus PATD dan jabatan tidak termasuk yang diizinkan, tolak akses
            if (! $hasPassedPATD && ! in_array($user->jabatan, ['SBC', 'BsM', 'SBM', 'EM', 'SEM', 'VBM', 'BrM'])) {
                return redirect()->route('post-test.index')
                    ->with('error', 'Anda tidak memiliki akses untuk tipe soal PATL.');
            }
        }

        // Jika tipe PATD, jabatan BC tidak boleh akses
        // if ($session->tipe === 'PATD') {
        //     $user = auth()->user();

        //     if ($user->jabatan === 'BC') {
        //         return redirect()->route('post-test.index')
        //             ->with('error', 'Anda tidak memiliki akses untuk tipe soal PATD.');
        //     }
        // }

        return $next($request);
    }
}
