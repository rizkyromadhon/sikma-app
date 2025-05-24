<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\Golongan;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function index()
    {
        $profileComplete = '';
        if (Auth::check()) {
            $profileComplete = Auth::user()->is_profile_complete;
        }
        $isProfileCompleted = $profileComplete == "0";

        if (!Auth::user()->is_profile_complete) {
            return redirect()->route('profile.edit')->with('info', 'Mohon lengkapi profil Anda.');
        }

        return view("profile", compact('isProfileCompleted'));
    }

    public function edit()
    {
        $user = Auth::user();

        // Cek jika profil belum lengkap

        return view('edit-profile', [
            'user' => Auth::user(),
        ]);
    }

    public function update(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!$request->filled('no_hp') || !$request->filled('alamat')) {
            return redirect('/profile/edit')->with('info', 'Mohon lengkapi profil Anda.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'no_hp' => 'required|string|max:20|regex:/^[0-9+]+$/',
            'alamat' => 'required|string|max:255',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $path = null;
        if ($request->hasFile('foto')) {
            if ($user->foto && Storage::disk('public')->exists($user->foto)) {
                Storage::disk('public')->delete($user->foto);
            }

            $path = $request->file('foto')->store('foto-profil', 'public');
            $user->foto = $path;
        }

        // Update data pengguna
        $user->name = $validated['name'];
        $user->no_hp = $validated['no_hp'];
        $user->alamat = $validated['alamat'];

        $user->is_profile_complete = true;

        $user->save();

        return redirect('/profile')->with('success', 'Profil berhasil diperbarui!');
    }

    public function changePassword()
    {
        return view('auth.ganti-password');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'oldPassword' => 'required',
            'password' => [
                'required',
                'min:6',
                'confirmed',
                Rule::notIn([
                    '123456',
                    '12345678',
                    'password',
                    'admin',
                    'qwerty',
                    'abc123',
                    '000000'
                ]),
            ],
        ], [
            'oldPassword.required' => 'Password lama wajib diisi.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 6 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'password.not_in' => 'Password terlalu mudah ditebak, silakan gunakan password yang lebih kuat.',
        ]);

        $user = User::find(Auth::id());

        if (!Hash::check($request->oldPassword, $user->password)) {
            return back()->withErrors(['error' => 'Password lama tidak sesuai.'])->withInput();
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect('/')->with('success', 'Password berhasil diperbarui!');
    }
}
