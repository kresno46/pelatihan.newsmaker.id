<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:50'],
            'email' => ['required', 'string', 'email', 'max:50', 'unique:users,email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults(), 'min:8'],
            'jenis_kelamin' => ['required', 'in:Pria,Wanita'],
            'tempat_lahir' => ['required', 'string', 'max:50'],
            'tanggal_lahir' => ['required', 'date'],
            'warga_negara' => ['nullable', 'string', 'max:50'],
            'alamat' => ['required', 'string'],
            'no_tlp' => ['required', 'string', 'max:20', 'unique:users,no_tlp'],
            'pekerjaan' => ['required', 'string', 'max:50'],
            'role' => ['nullable', 'in:Admin,Trainer (Eksternal)'], // opsional, default bisa di-model
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'jenis_kelamin' => $request->jenis_kelamin,
            'tempat_lahir' => $request->tempat_lahir,
            'tanggal_lahir' => $request->tanggal_lahir,
            'warga_negara' => $request->warga_negara,
            'alamat' => $request->alamat,
            'no_tlp' => $request->no_tlp,
            'pekerjaan' => $request->pekerjaan,
            'role' => $request->role ?? 'Trainer (Eksternal)',
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}
