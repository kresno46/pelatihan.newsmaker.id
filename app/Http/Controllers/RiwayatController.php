<?php

namespace App\Http\Controllers;

use App\Models\PostTestResult;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RiwayatController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();

        $results = PostTestResult::with(['ebook', 'session', 'user'])
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('riwayat.index', compact('results'));
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        // Ambil hasil post test milik user yang sedang login
        $result = PostTestResult::with(['session.ebook', 'session.ebook.folderEbook', 'session.questions'])
            ->where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $session = $result->session;

        return view('riwayat.show', compact('result', 'session'));
    }
}
