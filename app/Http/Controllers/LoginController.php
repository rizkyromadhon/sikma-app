<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class LoginController extends Controller
{
    public function login(Request $request) {
        $credentials = $request->only('email', 'password');

        $user = User::where('email', $credentials['email'])->first();
        if (!$user) {
            return back()->withErrors(['email' => 'Email belum terdaftar.'], 'login')->withInput();

        }

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $user = Auth::user();

            if (!$user->is_profile_complete) {
                return redirect('/profile/edit')->with('info', ' Silakan lengkapi profil Anda.');
            }

            return redirect('/home')->with('success', 'Login berhasil!');
        }

        return back()->withErrors(['email' => 'Email atau password salah'], 'login');
    }
}
