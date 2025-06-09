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

    public function showDosen()
    {
        // Ambil data user dosen yang sedang login, beserta relasi 'programStudi'
        $dosen = User::with('programStudi')->find(Auth::id());

        return view('dosen.profile.index', [
            'dosen' => $dosen
        ]);
    }

    public function editDosen()
    {
        return view('dosen.profile.edit', [
            'dosen' => Auth::user()
        ]);
    }

    /**
     * [BARU] Memproses update data profil.
     */
    public function updateDosen(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'no_hp' => 'nullable|string|max:15',
            'alamat' => 'nullable|string|max:500',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Maks 2MB
        ]);

        // Update data teks
        $dataToUpdate = [
            'name' => $request->name,
            'no_hp' => $request->no_hp,
            'alamat' => $request->alamat,
        ];

        // Proses upload foto jika ada
        if ($request->hasFile('foto')) {
            // Hapus foto lama jika ada
            if ($user->foto) {
                Storage::disk('public')->delete($user->foto);
            }
            // Simpan foto baru dan update path di database
            $dataToUpdate['foto'] = $request->file('foto')->store('profile-photos', 'public');
        }

        User::where('id', $user->id)->update($dataToUpdate);

        return redirect()->route('dosen.profile')->with('success', 'Profil berhasil diperbarui!');
    }

    public function editPasswordDosen()
    {
        return view('dosen.profile.ubah-password');
    }

    /**
     * Memproses dan menyimpan password baru.
     */
    public function updatePasswordDosen(Request $request)
    {
        // 1. Validasi input
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'min:8', 'confirmed'],
        ], [
            'current_password.required' => 'Password lama wajib diisi.',
            'current_password.current_password' => 'Password lama tidak sesuai.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        // 2. Update password user yang sedang login
        User::where('id', Auth::id())->update([
            'password' => Hash::make($validated['password']),
        ]);

        // 3. Redirect kembali dengan pesan sukses
        return redirect()->route('dosen.profile')->with('success', 'Password Anda berhasil diperbarui.');
    }
}
