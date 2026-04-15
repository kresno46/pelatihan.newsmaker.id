<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $admin = User::whereIn('role', ['Admin', 'Admin APUPPT'])->paginate(10);

        return view('admin.index', compact('admin'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'name' => 'required|string|max:50',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'role' => 'required|string|in:Admin,Admin APUPPT',
        ]);

        // Simpan admin baru
        $admin = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
        ]);

        // Redirect ke halaman index dengan pesan sukses
        return redirect()->route('admin.index')->with('Alert', 'Admin ' . $admin->name . ' berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $admin = User::find($id);

        return view('admin.edit', compact('admin'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $admin = User::find($id);

        // Validasi input
        $validated = $request->validate([
            'name' => 'required|string|max:50',
            'email' => [
                'required',
                'email',
                Rule::unique('users')->ignore($admin->id),
            ],
            'password' => 'nullable|string|min:6|confirmed',
            'role' => 'required|string|in:Admin,Admin APUPPT',
        ]);

        // Update admin
        $admin->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $validated['password'] ? Hash::make($validated['password']) : $admin->password,
            'role' => $validated['role'],
        ]);

        // Redirect ke halaman index dengan pesan sukses
        return redirect()->route('admin.index')->with('Alert', 'Admin ' . $admin->name . ' berhasil diubah.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $admin = User::find($id);

        // Hapus admin
        $admin->delete();

        // Redirect ke halaman index dengan pesan sukses
        return redirect()->route('admin.index')->with('Alert', 'Admin ' . $admin->name . ' berhasil dihapus.');
    }
}
