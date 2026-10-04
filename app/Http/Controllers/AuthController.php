<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => ['required'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $user = Auth::user();
            
            if (in_array($user->role->nama_role, ['admin', 'superadmin'])) {
                return redirect()->intended('/dashboard');
            } elseif ($user->role->nama_role === 'kasir') {
                return redirect()->intended('/pos');
            }
            
            return redirect()->intended('/dashboard');
        }

        return back()->withErrors([
            'username' => 'The provided credentials do not match our records.',
        ])->onlyInput('username');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'password' => ['required', 'string', 'min:5', 'confirmed'],
        ]);

        $user = Auth::user();
        
        /** @var \App\Models\User $user */
        $user->update([
            'password' => \Illuminate\Support\Facades\Hash::make($request->password)
        ]);

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success_password', 'Password berhasil diubah! Silakan login kembali dengan password baru Anda.');
    }
}
