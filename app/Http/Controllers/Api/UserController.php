<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::where('role', '!=', 'admin')   // ambil yang bukan admin
            ->get()
            ->makeVisible(['password']);             // tampilkan password

        return response()->json([
            'success' => true,
            'data' => $users,
        ], 200);
    }
}
