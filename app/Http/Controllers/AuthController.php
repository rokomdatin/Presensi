<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $user = User::where('username', $request->username)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'username' => ['Username atau password salah.'],
            ]);
        }

        if (!$user->is_active) {
            throw ValidationException::withMessages([
                'username' => ['Akun Anda tidak aktif. Hubungi administrator.'],
            ]);
        }

        Auth::login($user, $request->boolean('remember'));

        $user->update(['last_login' => now()]);
        
        LogAktivitas::log('login');

        return redirect()->intended(route('dashboard'));
    }

    public function logout(Request $request)
    {
        LogAktivitas::log('logout');
        
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
