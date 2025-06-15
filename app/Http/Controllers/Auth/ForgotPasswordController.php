<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Models\Laporan;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;

class ForgotPasswordController extends Controller
{
    public function lupaPassword()
    {
        return view('auth.lupa-password');
    }

    public function store(Request $request)
    {
        if (Auth::check()) {
            $user = Auth::user();
            $data = [
                'user_id' => $user->id,
                'nama_lengkap' => $user->name,
                'nim' => $user->nim,
                'id_prodi' => $user->id_prodi,
                'email' => $user->email,
            ];
        } else {
            $request->validate([
                'nama_lengkap' => 'required|string|max:100',
                'nim' => 'required|string|max:20',
                'id_prodi' => 'required|exists:program_studi,id',
                'email' => 'required|email|max:100',
            ]);

            $matchedUser = User::where('nim', $request->nim)->first();

            $data = [
                'user_id' => $matchedUser->id,
                'nama_lengkap' => $request->nama_lengkap,
                'nim' => $request->nim,
                'id_prodi' => $request->id_prodi,
                'email' => $request->email,
                'tipe' => 'lupa_password'
            ];
        }

        Laporan::create(array_merge($data, [
            'pesan' => 'Lupa Password',
            'status' => 'Belum Ditangani',
        ]));

        return redirect()->back()->with('success', 'Laporan Lupa Password Anda telah dikirim.');
    }

    public function passwordBaru(Request $request, $token)
    {
        return view('auth.password-baru', [
            'token' => $token,
            'email' => $request->email
        ]);
    }

    public function passwordBaruStore(Request $request)
    {
        // 1. Validasi input
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed|min:8',
        ]);

        // 2. Gunakan PasswordBroker bawaan Laravel untuk mereset
        $status = Password::broker()->reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                // 3. Update password user
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $user->save();

                // 4. Trigger event bahwa password telah direset
                event(new PasswordReset($user));
            }
        );

        // 5. Beri respon berdasarkan status
        if ($status == Password::PASSWORD_RESET) {
            return redirect()->route('login')->with('success', 'Password Anda berhasil diubah! Silakan login dengan password baru Anda.');
        }

        return back()
            ->withInput($request->only('email'))
            ->withErrors(['email' => __($status)]);
    }
}
