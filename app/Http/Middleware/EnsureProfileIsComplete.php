<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureProfileIsComplete
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        // Jika user tidak login atau tidak ada data user
        if (!$user) {
            return redirect()->route('login');
        }

        // Abaikan middleware ini untuk Admin
        if ($user->role === 'Admin') {
            return $next($request);
        }

        // Cek apakah profil lengkap untuk non-Admin
        if (
            !$user->tempat_lahir ||
            !$user->tanggal_lahir ||
            !$user->alamat ||
            !$user->jenis_kelamin ||
            // !$user->warga_negara ||
            !$user->no_tlp ||
            // !$user->pekerjaan ||
            !$user->role ||
            !$user->cabang
        ) {
            return redirect()->route('profile.edit')
                ->with('error', 'Silakan lengkapi profil Anda terlebih dahulu sebelum mengakses fitur ini.');
        }

        return $next($request);
    }
}
