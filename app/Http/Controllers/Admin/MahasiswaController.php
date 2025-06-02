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
        // Mengambil dan mengurutkan data semester untuk dropdown filter
        $allSemestersForFilter = Semester::all(); // Ambil semua semester

        $semesters = $allSemestersForFilter->sortBy(function ($semester) {
            // Ekstrak angka dari string display_name, contoh "Semester 5" -> 5
            // Menggunakan regular expression untuk mengambil angka di akhir string
            if (preg_match('/(\d+)$/', $semester->display_name, $matches)) {
                return (int) $matches[1]; // Kembalikan angka sebagai integer
            }
            // Jika format tidak cocok atau tidak ada angka, beri nilai default
            // atau bisa juga berdasarkan kriteria lain jika display_name tidak selalu "Semester X"
            // Untuk display_name yang tidak mengandung angka di akhir, mereka akan dikelompokkan
            // berdasarkan nilai kembalian ini. PHP_INT_MAX akan menempatkannya di akhir.
            // Jika display_name bisa null atau kosong, tambahkan pengecekan:
            if (empty($semester->display_name)) {
                return PHP_INT_MAX;
            }
            // Jika tidak ada angka tapi display_name ada, urutkan secara alfabetis setelah yang berangka
            return $semester->display_name; // Fallback ke pengurutan string jika tidak ada angka
        })->values(); // ->values() untuk mereset keys array setelah sorting

        $programStudiData = ProgramStudi::all();
        $golonganData = Golongan::select('nama_golongan')
            ->distinct()
            ->orderBy('nama_golongan', 'asc')
            ->pluck('nama_golongan');

        $mahasiswaQuery = User::where('role', 'mahasiswa')
            ->whereNotNull('id_prodi')
            ->whereNotNull('id_semester')
            ->whereNotNull('id_golongan');

        if ($request->filled('semester') && $request->semester !== 'all') {
            $mahasiswaQuery->where('id_semester', $request->semester);
        }

        if ($request->filled('program_studi') && $request->program_studi !== 'all') {
            $mahasiswaQuery->where('id_prodi', $request->program_studi);
        }

        if ($request->filled('golongan') && $request->golongan !== 'all') {
            $mahasiswaQuery->whereHas('golongan', function ($q) use ($request) {
                $q->where('nama_golongan', $request->golongan);
            });
        }

        if ($request->filled('search')) {
            // Asumsi search berdasarkan NIM, nama, atau field lain yang relevan
            $searchTerm = $request->search;
            $mahasiswaQuery->where(function ($query) use ($searchTerm) {
                $query->where('nim', 'like', '%' . $searchTerm . '%')
                    ->orWhere('name', 'like', '%' . $searchTerm . '%');
                // Tambahkan field lain jika perlu ->orWhere('email', 'like', '%' . $searchTerm . '%');
            });
        }

        $mahasiswa = $mahasiswaQuery->with(['programStudi', 'semester', 'golongan'])
            ->orderBy('id_prodi', 'asc') // Pertimbangkan urutan yang lebih intuitif
            ->orderBy('id_semester', 'asc') // Mungkin ingin urutan berdasarkan tahun ajaran semester
            ->orderBy('name', 'asc')
            // ->orderBy('id_golongan', 'asc') // Urutan golongan mungkin tidak terlalu signifikan di sini
            ->paginate(9) // Anda bisa menyesuaikan jumlah item per halaman
            ->appends($request->query());

        AlatPresensi::where('id', 1)->update(['mode' => 'attendance']);

        return view('admin.mahasiswa.index', compact('mahasiswa', 'semesters', 'programStudiData', 'golonganData'));
    }


    public function create()
    {
        $allSemestersForFilter = Semester::all(); // Ambil semua semester

        $semesters = $allSemestersForFilter->sortBy(function ($semester) {
            if (preg_match('/(\d+)$/', $semester->display_name, $matches)) {
                return (int) $matches[1]; // Kembalikan angka sebagai integer
            }
            if (empty($semester->display_name)) {
                return PHP_INT_MAX;
            }
            return $semester->display_name; 
        })->values(); 
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
        $allSemestersForFilter = Semester::all(); // Ambil semua semester

        $semesters = $allSemestersForFilter->sortBy(function ($semester) {
            if (preg_match('/(\d+)$/', $semester->display_name, $matches)) {
                return (int) $matches[1]; // Kembalikan angka sebagai integer
            }
            if (empty($semester->display_name)) {
                return PHP_INT_MAX;
            }
            return $semester->display_name; // Fallback ke pengurutan string jika tidak ada angka
        })->values(); // ->values() untuk mereset keys array setelah sorting
        $user = User::where('role', 'mahasiswa')->where('id', $id)->first();
        $programStudi = ProgramStudi::all();

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
