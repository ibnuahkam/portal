<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\User;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        // VALIDASI
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        // CARI USER
        $user = User::where('email', $request->email)->first();

        // CEK USER & PASSWORD
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Email atau password salah',
            ], 401);
        }

        // LOGIN MANUAL (INI KUNCI NYA 🔥)
        Auth::login($user, $request->filled('remember'));
        $request->session()->regenerate();

        return response()->json([
            'success'  => true,
            'redirect' => route('dashboard'),
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
