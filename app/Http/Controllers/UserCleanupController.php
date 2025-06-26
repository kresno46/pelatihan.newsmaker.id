<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Carbon\Carbon;

class UserCleanupController extends Controller
{
    public function deleteUnverifiedUsers()
    {
        // Misalnya kita targetkan role 'user'
        $expiredUsers = User::where('role', 'user')
            ->whereNull('email_verified_at') // Belum verifikasi
            ->where('created_at', '<=', Carbon::now()->subHours(24))
            ->get();

        $count = $expiredUsers->count();

        // Hapus user
        foreach ($expiredUsers as $user) {
            $user->delete();
        }

        return redirect()->route('trainer.index')->with('Alert', $count . ' akun yang tidak diverifikasi dalam 24 jam telah dihapus.');
    }
}
