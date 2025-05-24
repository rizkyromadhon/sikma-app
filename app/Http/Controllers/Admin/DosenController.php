<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\AlatPresensi;
use App\Models\ProgramStudi;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class DosenController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', 'dosen')
            ->whereNotNull('id_prodi')
            ->when($request->filled('search'), function ($query) use ($request) {
                $query->where('nip', $request->search);
            })
            ->with('programStudi');

        if ($request->filled('id_prodi')) {
            $query->where('id_prodi', $request->id_prodi);
        }

        $datas = $query->paginate(8)->appends($request->query()); // penting: mempertahankan query saat paginate
        $programStudi = ProgramStudi::all(); // ambil semua prodi untuk dropdown

        AlatPresensi::where('id', 1)->update(['mode' => 'attendance']);

        return view('admin.dosen.index', compact('datas', 'programStudi'));
    }

    public function create()
    {
        $programStudi = ProgramStudi::all();
        return view('admin.dosen.create', compact('programStudi'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => [
                'required',
                'email',
                'unique:users,email',
                'regex:/^[a-zA-Z0-9._%+-]+@gmail\.com$/'
            ],
            'nip' => 'required',
            'no_hp' => 'required',
            'alamat' => 'required',
            'program_studi' => 'required',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ], [
            'name.required' => 'Nama wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah terdaftar.',
            'email.regex' => 'Hanya email Gmail yang diperbolehkan. Contoh: example@gmail.com.',
            'nip.required' => 'NIP wajib diisi.',
            'no_hp.required' => 'Nomor HP wajib diisi.',
            'alamat.required' => 'Alamat wajib diisi.',
        ]);
        // dd($request->all());

        $defaultPassword = 'passworddosen';

        $path = null;
        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('foto-profil', 'public');
        }

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'no_hp' => $request->no_hp,
            'alamat' => $request->alamat,
            'id_prodi' => $request->program_studi,
            'role' => 'dosen',
            'password' => Hash::make($defaultPassword),
            'foto' => $path,
            'nip' => $request->nip
        ]);

        return redirect()->route('admin.dosen.index')->with('success', 'Berhasil menambahkan dosen');
    }

    public function edit($id)
    {
        $datas = User::where('role', 'dosen')->where('id', $id)->first();
        $programStudi = ProgramStudi::all();
        return view('admin.dosen.edit', compact('datas', 'programStudi'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => [
                'required',
                'email',
                'regex:/^[a-zA-Z0-9._%+-]+@gmail\.com$/'
            ],
            'nip' => 'required',
            'no_hp' => 'required|string',
            'alamat' => 'required|string',
            'program_studi' => 'required',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ], [
            'name.required' => 'Nama wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.regex' => 'Hanya email Gmail yang diperbolehkan. Contoh: example@gmail.com.',
            'no_hp.required' => 'Nomor HP wajib diisi.',
            'alamat.required' => 'Alamat wajib diisi.',
        ]);

        $user = User::where('role', 'dosen')->where('id', $id)->first();

        $user->name = $request->name;
        $user->email = $request->email;
        $user->no_hp = $request->no_hp;
        $user->alamat = $request->alamat;
        $user->id_prodi = $request->program_studi;
        $user->nip = $request->nip;

        if ($request->hasFile('foto')) {
            if ($user->foto && Storage::disk('public')->exists(path: $user->foto)) {
                Storage::disk('public')->delete($user->foto);
            }

            $path = $request->file('foto')->store('foto-profil', 'public');
            $user->foto = $path;
        }

        $user->save();

        return redirect()->route('admin.dosen.index')->with('success', 'Berhasil mengupdate dosen!');
    }

    public function destroy($id)
    {
        User::where('role', 'dosen')->where('id', $id)->delete();
        return redirect()->route('admin.dosen.index')->with('success', 'Berhasil menghapus dosen!');
    }
}
