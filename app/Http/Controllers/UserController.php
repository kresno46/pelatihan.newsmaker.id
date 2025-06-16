<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Tampilkan semua user dengan role Trainer (Eksternal).
     */
    public function index()
    {
        $trainer = User::where('role', 'Trainer (Eksternal)')->paginate(10);
        return view('trainer.index', compact('trainer'));
    }

    /**
     * Tampilkan show user.
     */
    public function show($id)
    {
        $trainer = User::find($id);

        return view('trainer.show', compact('trainer'));
    }


    /**
     * Tampilkan form tambah Trainer.
     */
    public function create()
    {
        return view('trainer.create');
    }

    /**
     * Simpan Trainer baru ke database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:50',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'jenis_kelamin' => 'nullable|string',
            'tempat_lahir' => 'nullable|string',
            'tanggal_lahir' => 'nullable|date',
            'warga_negara' => 'nullable|string',
            'alamat' => 'nullable|string',
            'no_id' => 'nullable|string',
            'no_tlp' => 'nullable|string',
            'pekerjaan' => 'nullable|string',
        ]);

        $trainer = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'Trainer (Eksternal)',
            'jenis_kelamin' => $validated['jenis_kelamin'] ?? null,
            'tempat_lahir' => $validated['tempat_lahir'] ?? null,
            'tanggal_lahir' => $validated['tanggal_lahir'] ?? null,
            'warga_negara' => $validated['warga_negara'] ?? null,
            'alamat' => $validated['alamat'] ?? null,
            'no_id' => $validated['no_id'] ?? null,
            'no_tlp' => $validated['no_tlp'] ?? null,
            'pekerjaan' => $validated['pekerjaan'] ?? null,
        ]);

        return redirect()->route('trainer.index')->with('Alert', 'Trainer ' . $trainer->name . ' berhasil ditambahkan.');
    }

    /**
     * Tampilkan form edit Trainer.
     */
    public function edit(string $id)
    {
        $trainer = User::findOrFail($id);

        return view('trainer.edit', compact('trainer'));
    }

    /**
     * Update data Trainer.
     */
    public function update(Request $request, string $id)
    {
        $trainer = User::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:50',
            'email' => [
                'required',
                'email',
                Rule::unique('users')->ignore($trainer->id),
            ],
            'password' => 'nullable|string|min:6|confirmed',
            'jenis_kelamin' => 'nullable|string',
            'tempat_lahir' => 'nullable|string',
            'tanggal_lahir' => 'nullable|date',
            'warga_negara' => 'nullable|string',
            'alamat' => 'nullable|string',
            'no_id' => 'nullable|string',
            'no_tlp' => 'nullable|string',
            'pekerjaan' => 'nullable|string',
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        $trainer->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $validated['password'] ? Hash::make($validated['password']) : $trainer->password,
            'jenis_kelamin' => $validated['jenis_kelamin'] ?? $trainer->jenis_kelamin,
            'tempat_lahir' => $validated['tempat_lahir'] ?? $trainer->tempat_lahir,
            'tanggal_lahir' => $validated['tanggal_lahir'] ?? $trainer->tanggal_lahir,
            'warga_negara' => $validated['warga_negara'] ?? $trainer->warga_negara,
            'alamat' => $validated['alamat'] ?? $trainer->alamat,
            'no_id' => $validated['no_id'] ?? $trainer->no_id,
            'no_tlp' => $validated['no_tlp'] ?? $trainer->no_tlp,
            'pekerjaan' => $validated['pekerjaan'] ?? $trainer->pekerjaan,
        ]);

        return redirect()->route('trainer.index')->with('Alert', 'Trainer ' . $trainer->name . ' berhasil diperbarui.');
    }

    /**
     * Hapus Trainer dari database.
     */
    public function destroy(string $id)
    {
        $trainer = User::findOrFail($id);
        $trainer->delete();

        return redirect()->route('trainer.index')->with('Alert', 'Trainer ' . $trainer->name . ' berhasil dihapus.');
    }
}
