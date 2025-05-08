<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            // Cek apakah user sudah ada berdasarkan email
            $user = User::where('email', $googleUser->getEmail())->first();

            if ($user) {
                Auth::login($user);

                // Cek apakah profil lengkap atau belum
                if (!$user->is_profile_complete) {
                    return redirect('/profile/edit')->with('success', 'Silakan lengkapi profil Anda.');
                }

                // Jika sudah login dan profil lengkap
                return redirect('/')->with('success', 'Login berhasil.');
            }

            // Jika user belum ada (registrasi)
            $user = User::create([
                'name' => $googleUser->getName(),
                'email' => $googleUser->getEmail(),
                'google_id' => $googleUser->getId(),
                'password' => Hash::make(uniqid()), // Menggunakan password acak, karena login menggunakan Google
                'is_profile_complete' => false, // Menandakan bahwa profil belum lengkap
            ]);

            Auth::login($user);

            return redirect('/profile/edit')->with('success', 'Registrasi berhasil. Silakan lengkapi profil Anda.');
        } catch (\Exception $e) {
            return redirect('/login')->withErrors(['login' => 'Gagal login atau registrasi dengan Google.']);
        }
    }
}
