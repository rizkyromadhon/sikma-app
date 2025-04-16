<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\User;

class ProfileController extends Controller
{
    public function edit()
    {
        return view('edit-profile');
    }

    public function update(Request $request)
    {
    /** @var \App\Models\User $user */
    $user = Auth::user();

    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'nim' => 'required|string|max:20|unique:users,nim,' . $user->id,
        'program_studi' => 'nullable|string|max:100',
        'kelas' => 'nullable|string|max:20',
        'no_hp' => 'nullable|string|max:20|regex:/^[0-9+]+$/',
        'email' => 'required|email|max:255|unique:users,email,' . $user->id,
        'alamat' => 'nullable|string|max:255',
        'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
    ], [
        'nim.unique' => 'NIM sudah digunakan oleh Mahasiswa/i lain. Silahkan gunakan NIM yang berbeda.'
    ]);

    $user->name = $validated['name'];
    $user->email = $validated['email'];

    $user->nim = $validated['nim'];
    $user->program_studi = $validated['program_studi'];
    $user->kelas = $validated['kelas'];
    $user->no_hp = $validated['no_hp'];
    $user->alamat = $validated['alamat'];

    if ($request->hasFile('foto')) {
        if ($user->foto && Storage::exists('public/' . $user->foto)) {
            Storage::delete('public/' . $user->foto);
        }

        $path = $request->file('foto')->store('foto-profil', 'public');
        $user->foto = $path;
    }

    $user->is_profile_complete = true;
    $user->save();


    return redirect('/profile')->with('success', ' Profil berhasil diperbarui!');

    }


}
