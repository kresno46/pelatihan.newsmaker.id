<?php

namespace App\Http\Controllers;

use App\Models\PostTestResult;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RiwayaController extends Controller
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
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        // Ambil hasil post test milik user yang sedang login
        $result = PostTestResult::with(['session.ebook', 'session.questions'])
            ->where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $session = $result->session;

        return view('riwayat.show', compact('result', 'session'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
