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
    public function index(Request $request)
    {
        $query = \App\Models\User::query()->where('role', 'like', 'Trainer%');

        // Filter pencarian
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                    ->orWhere('email', 'like', "%{$request->search}%");
            });
        }

        // Filter berdasarkan role (Trainer RFB / SGB / dll)
        if ($request->filled('filter_perusahaan')) {
            $query->where('role', $request->filter_perusahaan);
        }

        // Filter berdasarkan cabang
        if ($request->filled('filter_cabang')) {
            $query->where('cabang', $request->filter_cabang);
        }

        $trainer = $query->paginate(10);

        // Ambil daftar "role" dan "cabang" unik
        $perusahaanList = \App\Models\User::where('role', 'like', 'Trainer%')
            ->distinct()->pluck('role');

        $cabangList = \App\Models\User::where('role', 'like', 'Trainer%')
            ->whereNotNull('cabang')
            ->distinct()->pluck('cabang');

        return view('trainer.index', compact('trainer', 'perusahaanList', 'cabangList'));
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
     * Manually verify trainer's email.
     */
    public function verify($id)
    {
        $trainer = User::findOrFail($id);

        if (is_null($trainer->email_verified_at)) {
            $trainer->email_verified_at = now();
            $trainer->save();

            return redirect()->route('trainer.show', $trainer->id)->with('Alert', 'Email trainer '.$trainer->name.' berhasil diverifikasi.');
        }

        return redirect()->route('trainer.show', $trainer->id)->with('Alert', 'Email trainer sudah terverifikasi.');
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
            'alamat' => 'nullable|string',
            'no_tlp' => 'nullable|string',
            'cabang' => 'nullable|string',
        ]);

        $trainer = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'Trainer (Eksternal)',
            'jenis_kelamin' => $validated['jenis_kelamin'] ?? null,
            'tempat_lahir' => $validated['tempat_lahir'] ?? null,
            'tanggal_lahir' => $validated['tanggal_lahir'] ?? null,
            'alamat' => $validated['alamat'] ?? null,
            'no_tlp' => $validated['no_tlp'] ?? null,
            'cabang' => $validated['cabang'] ?? null,
        ]);

        return redirect()->route('trainer.index')->with('Alert', 'Trainer '.$trainer->name.' berhasil ditambahkan.');
    }

    /**
     * Tampilkan form edit Trainer.
     */
    public function edit(string $id)
    {
        $trainer = User::findOrFail($id);

        $kantorCabang = [
            'RFB' => [
                'Medan', 'Palembang', 'Semarang', 'Pekanbaru', 'Bandung', 'Solo', 'Yogyakarta',
                'Balikpapan', 'Jakarta - AXA Tower 1', 'Jakarta - AXA Tower 2', 'Jakarta - AXA Tower 3',
                'Jakarta - DBS Bank Tower', 'Surabaya - Ciputra World Office Tower', 'Surabaya - Pakuwon Tower',
            ],
            'SGB' => ['Semarang', 'Makassar', 'Jakarta - TCC Tower'],
            'KPF' => ['Yogyakarta', 'Bali', 'Makassar', 'Bandung', 'Semarang', 'Jakarta - Plaza Marein'],
            'EWF' => [
                'SSC Jakarta', 'Cyber 2 Jakarta', 'Surabaya Trillium', 'Manado',
                'Semarang', 'Surabaya Praxis', 'Cirebon',
            ],
            'BPF' => [
                'Jambi', 'Jakarta - Pacific Place Mall', 'Pontianak', 'Malang', 'Surabaya',
                'Medan', 'Bandung', 'Pekanbaru', 'Banjarmasin', 'Bandar Lampung', 'Semarang',
                'Jakarta - Equity Tower',
            ],
        ];

        return view('trainer.edit', compact('trainer', 'kantorCabang'));
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
            'alamat' => 'nullable|string',
            'no_tlp' => 'nullable|string',
            'role' => 'nullable|string|in:Trainer (SGB),Trainer (RFB),Trainer (EWF),Trainer (BPF),Trainer (KPF)',
            'cabang' => 'nullable|string',
        ]);

        $trainer->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $validated['password'] ? Hash::make($validated['password']) : $trainer->password,
            'jenis_kelamin' => $validated['jenis_kelamin'] ?? $trainer->jenis_kelamin,
            'tempat_lahir' => $validated['tempat_lahir'] ?? $trainer->tempat_lahir,
            'tanggal_lahir' => $validated['tanggal_lahir'] ?? $trainer->tanggal_lahir,
            'alamat' => $validated['alamat'] ?? $trainer->alamat,
            'no_tlp' => $validated['no_tlp'] ?? $trainer->no_tlp,
            'role' => $validated['role'] ?? $trainer->role,
            'cabang' => $validated['cabang'] ?? $trainer->cabang,
        ]);

        return redirect()->route('trainer.index')->with('Alert', 'Trainer '.$trainer->name.' berhasil diperbarui.');
    }

    /**
     * Hapus Trainer dari database.
     */
    public function destroy(string $id)
    {
        $trainer = User::findOrFail($id);
        $trainer->delete();

        return redirect()->route('trainer.index')->with('Alert', 'Trainer '.$trainer->name.' berhasil dihapus.');
    }
}
