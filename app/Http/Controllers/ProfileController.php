<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    /**
     * Tampilkan form edit profil user.
     */
    public function edit(Request $request)
    {
        $user = $request->user();

        // Daftar kantor cabang
        $kantorCabang = [
            'RFB' => ['Palembang', 'Balikpapan', 'Solo', 'Jakarta DBS Tower', 'Jakarta AXA Tower', 'Medan', 'Semarang', 'Surabaya Pakuwon', 'Surabaya Ciputra', 'Pekanbaru', 'Bandung', 'Yogyakarta'],
            'SGB' => ['Jakarta', 'Semarang', 'Makassar'],
            'KPF' => ['Jakarta', 'Yogyakarta', 'Bali', 'Makassar', 'Bandung', 'Semarang'],
            'EWF' => ['SCC Jakarta', 'Cyber 2 Jakarta', 'Surabaya Trilium', 'Manado', 'Semarang', 'Surabaya Praxis', 'Cirebon'],
            'BPF' => ['Equity Tower Jakarta', 'Jambi', 'Jakarta - Pacific Place Mall', 'Pontianak', 'Malang', 'Surabaya', 'Medan', 'Bandung', 'Pekanbaru', 'Banjarmasin', 'Bandar Lampung', 'Semarang'],
        ];

        $selectedCabang = old('cabang', $user->cabang ?? '');

        return view('profile.edit', compact('user', 'kantorCabang', 'selectedCabang'));
    }

    /**
     * Update informasi profil user.
     */
    public function update(Request $request)
    {
        $user = $request->user();

        // Validasi data manual
        $validator = Validator::make($request->all(), [
            'name' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'jenis_kelamin' => ['nullable', 'in:Pria,Wanita'],
            'tempat_lahir' => ['nullable', 'string', 'max:255'],
            'tanggal_lahir' => ['nullable', 'date'],
            'warga_negara' => ['nullable', 'string', 'max:255'],
            'alamat' => ['nullable', 'string'],
            'no_id' => ['nullable', 'string', 'max:255'],
            'no_tlp' => ['nullable', 'string', 'max:20'],
            'pekerjaan' => ['nullable', 'string', 'max:255'],
            'cabang' => [
                function ($attribute, $value, $fail) use ($request) {
                    // Wajib isi cabang kalau role adalah trainer
                    if (str_starts_with($request->role, 'Trainer') && empty($value)) {
                        $fail('Kantor cabang wajib diisi untuk role trainer.');
                    }
                },
                'nullable',
                'string',
            ],
        ]);

        if ($validator->fails()) {
            return Redirect::back()
                ->withErrors($validator)
                ->withInput();
        }

        $validatedData = $validator->validated();

        // Update atribut user
        $user->fill($validatedData);

        // Reset verifikasi email jika email diubah
        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Hapus akun user dengan verifikasi password.
     */
    public function destroy(Request $request)
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/')->with('success', 'Akun berhasil dihapus.');
    }
}
