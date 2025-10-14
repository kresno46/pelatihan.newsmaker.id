<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Ambil semua user beserta password-nya
        $users = User::get()
            ->makeVisible(['password']);

        return response()->json([
            'success' => true,
            'data' => $users
        ], 200);
    }
}
