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
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:50', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults(), 'min:8'],
            'country_code' => ['required', 'string'],
            'no_tlp' => ['required', 'string', 'max:20'],
            'type_id' => ['required', 'in:KTP,SIM,Paspor,KITAS'], // validasi enum
            'no_id' => ['required', 'string', 'max:17', 'unique:users,no_id'],
        ]);

        $fullPhone = $request->country_code . ltrim($request->no_tlp, '0');

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'no_tlp' => $fullPhone,
            'type_id' => $request->type_id,
            'no_id' => $request->no_id,
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}
