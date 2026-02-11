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

        // Tentukan cabang berdasarkan role
        $branches = $this->getBranchesByRole($user->role);
        $allBranches = $this->getAllBranches();

        return view('profile.edit', compact('user', 'branches', 'allBranches'));
    }

    private function getAllBranches()
    {
        return [
            'Admin' => [],
            'Trainer (SGB)' => [
                'Semarang',
                'Makassar',
                'Jakarta – TCC Tower',
            ],

            'Trainer (RFB)' => [
                'Medan',
                'Palembang',
                'Semarang',
                'Pekanbaru',
                'Bandung',
                'Solo',
                'Yogyakarta',
                'Balikpapan',
                'Jakarta - AXA Tower 1',
                'Jakarta - AXA Tower 2',
                'Jakarta - AXA Tower 3',
                'Jakarta - DBS Bank Tower',
                'Surabaya - Ciputra World Office Tower',
                'Surabaya - Pakuwon Tower',
            ],

            'Trainer (EWF)' => [
                'Surabaya Trillium',
                'Manado',
                'Jakarta',
                'Semarang',
                'Surabaya Praxis',
                'Cirebon',
                'SSC Jakarta',
                'Jakarta Cyber 2',
            ],

            'Trainer (BPF)' => [
                'Jambi',
                'Jakarta – Pacific Place Mall',
                'Pontianak',
                'Malang',
                'Surabaya',
                'Medan',
                'Bandung',
                'Pekanbaru',
                'Banjarmasin',
                'Bandar Lampung',
                'Semarang',
                'Jakarta - Equity Tower',
            ],

            'Trainer (KPF)' => [
                'Yogyakarta',
                'Bali',
                'Makassar',
                'Bandung',
                'Semarang',
                'Jakarta - Plaza Marein',
            ],
        ];
    }

    private function getBranchesByRole($role)
    {
        $allBranches = $this->getAllBranches();

        return $allBranches[$role] ?? [];
    }

    /**
     * Update informasi profil user.
     */
    public function update(Request $request)
    {
        $user = $request->user();

        // Validasi data manual
        $validator = Validator::make($request->all(), [
            'name' => ['nullable', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:50', Rule::unique('users')->ignore($user->id)],
            'jenis_kelamin' => ['nullable', 'in:Pria,Wanita'],
            'tempat_lahir' => ['nullable', 'string', 'max:20'],
            'tanggal_lahir' => ['nullable', 'date'],
            'alamat' => ['nullable', 'string'],
            'no_tlp' => ['nullable', 'string', 'max:20'],
            'jabatan' => ['nullable', 'in:BC,SBC,BsM,SBM,EM,SEM,VBM,BrM'],
            'role' => ['nullable', 'string', Rule::in(['Trainer (SGB)', 'Trainer (RFB)', 'Trainer (EWF)', 'Trainer (BPF)', 'Trainer (KPF)'])],
            'cabang' => ['nullable', 'string'],
        ]);

        if ($validator->fails()) {
            return Redirect::back()
                ->withErrors($validator)
                ->withInput();
        }

        // 🚫 Jika user sudah punya jabatan, jangan izinkan mengubahnya
        if ($user->jabatan !== null && $request->jabatan !== $user->jabatan) {
            return Redirect::back()
                ->with('error', 'Jabatan tidak dapat diubah karena sudah ditetapkan.')
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
