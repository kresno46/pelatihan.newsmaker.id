<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Illuminate\Support\Facades\Validator;

class NewPasswordController extends Controller
{
    public function create(Request $request)
    {
        $token = $request->route('token');
        $email = $request->query('email');

        if (is_array($email)) {
            $email = $email[0] ?? '';
        }

        $data = ['token' => $token, 'email' => $email];

        // return response()->b

        return view('auth.reset-password', compact('token', 'email'));

        // $token = $request->route('token');
        // $email = $request->query('email');

        // // Validasi dan amankan data email
        // if (is_array($email)) {
        //     $email = $email[0] ?? '';
        // }

        // // Validasi manual
        // $validator = Validator::make(
        //     ['email' => $email, 'token' => $token],
        //     [
        //         'email' => 'required|email',
        //         'token' => 'required|string'
        //     ]
        // );

        // if ($validator->fails()) {
        //     return response()->json([
        //         'success' => false,
        //         'errors' => $validator->errors(),
        //     ], 422);
        // }

        // return response()->json([
        //     'success' => true,
        //     'data' => [
        //         'email' => $email,
        //         'token' => $token,
        //     ]
        // ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user) use ($request) {
                $user->forceFill([
                    'password' => Hash::make($request->password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('login')->with('status', __($status))
            : back()->withInput($request->only('email'))->withErrors(['email' => __($status)]);
    }
}
