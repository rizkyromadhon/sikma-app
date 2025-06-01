<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Golongan;
use App\Models\Presensi;
use App\Models\Semester;
use App\Models\AlatPresensi;
use App\Models\ProgramStudi;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class MahasiswaController extends Controller
{
    public function index(Request $request)
    {
        $semesters = Semester::all();
        $programStudiData = ProgramStudi::all();
        $golonganData = Golongan::select('nama_golongan')
            ->distinct()
            ->orderBy('nama_golongan', 'asc')
            ->pluck('nama_golongan');

        $mahasiswa = User::where('role', 'mahasiswa')
            ->whereNotNull('id_prodi')
            ->whereNotNull('id_semester')
            ->whereNotNull('id_golongan')
            ->when($request->filled('semester') && $request->semester !== 'all', function ($query) use ($request) {
                $query->where('id_semester', $request->semester);
            })
            ->when($request->filled('program_studi') && $request->program_studi !== 'all', function ($query) use ($request) {
                $query->where('id_prodi', $request->program_studi);
            })
            ->when($request->filled('golongan') && $request->golongan !== 'all', function ($query) use ($request) {
                $query->whereHas('golongan', function ($q) use ($request) {
                    $q->where('nama_golongan', $request->golongan);
                });
            })
            ->when($request->filled('search'), function ($query) use ($request) {
                $query->where('nim', $request->search);
            })
            ->with(['programStudi', 'semester', 'golongan']) // tambahkan eager loading
            ->orderBy('id_prodi', 'asc')
            ->orderBy('id_semester', 'asc')
            ->orderBy('name', 'asc')
            ->orderBy('id_golongan', 'asc')
            ->paginate(9)
            ->appends($request->query());

        AlatPresensi::where('id', 1)->update(['mode' => 'attendance']);

        return view('admin.mahasiswa.index', compact('mahasiswa', 'semesters', 'programStudiData', 'golonganData'));
    }


    public function create()
    {
        $semesters = Semester::all();
        $programStudi = ProgramStudi::all();
        $golonganData = Golongan::orderBy('nama_golongan')->get()->groupBy('id_prodi');

        return view('admin.mahasiswa.create', compact('semesters', 'programStudi', 'golonganData'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => [
                'required',
                'email',
                'unique:users,email',
                'regex:/^[a-zA-Z0-9._%+-]+@gmail\.com$/'
            ],
            'nim' => 'required',
            'no_hp' => 'nullable',
            'alamat' => 'nullable|string',
            'semester' => 'required',
            'program_studi' => 'required',
            'golongan' => 'required',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'gender' => 'required'
        ], [
            'name.required' => 'Nama wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah terdaftar.',
            'email.regex' => 'Hanya email Gmail yang diperbolehkan. Contoh: example@gmail.com.',
        ]);

        $defaultPassword = 'passwordmahasiswa';


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
            'id_golongan' => $request->golongan,
            'id_semester' => $request->semester,
            'role' => 'mahasiswa',
            'password' => Hash::make($defaultPassword),
            'foto' => $path,
            'nim' => $request->nim,
            'gender' => $request->gender
        ]);

        return redirect()->route('admin.mahasiswa.index')->with('success', 'Berhasil menambahkan mahasiswa baru!');
    }

    public function edit($id)
    {
        $user = User::where('role', 'mahasiswa')->where('id', $id)->first();
        $semesters = Semester::all();
        $programStudi = ProgramStudi::all();

        // Filter Golongan berdasarkan Program Studi yang terpilih
        $golonganData = Golongan::where('id_prodi', $user->id_prodi)->get();

        return view('admin.mahasiswa.edit', compact('user', 'semesters', 'programStudi', 'golonganData'));
    }

    public function update(Request $request, $id)
    {
        $prevPage = $request->get('page');

        // Validasi input
        $request->validate([
            'name' => 'required|string',
            'email' => [
                'required',
                'email',
                'regex:/^[a-zA-Z0-9._%+-]+@gmail\.com$/'
            ],
            'nim' => 'required',
            'no_hp' => 'nullable',
            'alamat' => 'nullable|string',
            'id_semester' => 'required|exists:semesters,id',
            'id_golongan' => 'required|exists:golongan,id',
            'id_prodi' => 'required|exists:program_studi,id',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'gender' => 'required'
        ], [
            'name.required' => 'Nama wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.regex' => 'Hanya email Gmail yang diperbolehkan. Contoh: example@gmail.com.',
        ]);

        // Cari user berdasarkan ID
        $user = User::where('role', 'mahasiswa')->where('id', $id)->firstOrFail();

        // Update data user
        $user->name = $request->name;
        $user->email = $request->email;
        $user->no_hp = $request->no_hp;
        $user->alamat = $request->alamat;
        $user->id_semester = $request->id_semester; // Pastikan sesuai dengan form
        $user->id_golongan = $request->id_golongan; // Pastikan sesuai dengan form
        $user->gender = $request->gender;

        // Cari ID dari program studi berdasarkan nama
        $prodi = ProgramStudi::where('id', $request->id_prodi)->first();
        if ($prodi) {
            $user->id_prodi = $prodi->id;
        }

        $user->nim = $request->nim;

        // Update foto jika ada
        if ($request->hasFile('foto')) {
            // Hapus foto lama jika ada
            if ($user->foto && Storage::disk('public')->exists($user->foto)) {
                Storage::disk('public')->delete($user->foto);
            }

            // Simpan foto baru
            $path = $request->file('foto')->store('foto-profil', 'public');
            $user->foto = $path;
        }

        // Simpan perubahan
        $user->save();

        return redirect()->route('admin.mahasiswa.index', ['page' => $prevPage])->with('success', 'Berhasil mengupdate mahasiswa!');
    }



    public function destroy($id)
    {
        Presensi::where('user_id', $id)->delete();
        User::where('role', 'mahasiswa')->where('id', $id)->delete();
        return redirect()->route('admin.mahasiswa.index')->with('success', 'Berhasil menghapus mahasiswa!');
    }
}
