<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Ruangan;
use App\Models\Golongan;
use App\Models\Semester;
use App\Models\MataKuliah;
use App\Models\AlatPresensi;
use App\Models\JadwalKuliah;
use App\Models\ProgramStudi;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class JadwalKuliahController extends Controller
{
    public function index()
    {
        $datas = JadwalKuliah::with('golongan', 'mataKuliah', 'dosen', 'ruangan', 'prodi', 'semester')->paginate(9);

        AlatPresensi::where('id', 1)->update(['mode' => 'attendance']);

        return view('admin.jadwal-kuliah.index', compact('datas'));
    }

    public function create()
    {
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
        $programStudi = ProgramStudi::all();
        $golonganData = Golongan::orderBy('nama_golongan')->get()->groupBy('id_prodi');
        $mataKuliah = MataKuliah::all();
        $ruangans = Ruangan::all();
        $dosens = User::where('role', 'dosen')->get();


        return view('admin.jadwal-kuliah.create', compact('dosens', 'golonganData', 'semesters', 'mataKuliah', 'programStudi', 'ruangans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'hari' => 'required',
            'dosen' => 'required',
            'mata_kuliah' => 'required',
            'ruangan' => 'required',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required',
            'semester' => 'required',
            'program_studi' => 'required',
            'golongan' => 'required',
        ], [
            'hari.required' => 'Hari harus diisi',
            'dosen.required' => 'Dosen harus diisi',
            'mata_kuliah.required' => 'Mata kuliah harus diisi',
            'ruangan.required' => 'Ruangan harus diisi',
            'jam_mulai.required' => 'Jam mulai harus diisi',
            'jam_selesai.required' => 'Jam selesai harus diisi',
            'semester.required' => 'Semester harus diisi',
            'program_studi.required' => 'Program studi harus diisi',
            'golongan.required' => 'Golongan harus diisi',
        ]);

        JadwalKuliah::create([
            'id_user' => $request->dosen,
            'id_matkul' => $request->mata_kuliah,
            'id_ruangan' => $request->ruangan,
            'hari' => $request->hari,
            'jam_mulai' => $request->jam_mulai,
            'jam_selesai' => $request->jam_selesai,
            'id_semester' => $request->semester,
            'id_prodi' => $request->program_studi,
            'id_golongan' => $request->golongan,
        ]);

        return redirect()->route('admin.jadwal-kuliah.index')->with('success', 'Jadwal Kuliah berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $allSemestersForFilter = Semester::all();

        $semesters = $allSemestersForFilter->sortBy(function ($semester) {
            if (preg_match('/(\d+)$/', $semester->display_name, $matches)) {
                return (int) $matches[1];
            }
            if (empty($semester->display_name)) {
                return PHP_INT_MAX;
            }
            return $semester->display_name;
        })->values();
        $jadwal = JadwalKuliah::where('id', $id)->first();
        $programStudi = ProgramStudi::all();
        $golonganData = Golongan::orderBy('nama_golongan')->get()->groupBy('id_prodi');
        $mataKuliah = MataKuliah::all();
        $ruangans = Ruangan::all();
        $dosens = User::where('role', 'dosen')->get();
        return view('admin.jadwal-kuliah.edit', compact('jadwal', 'dosens', 'golonganData', 'semesters', 'mataKuliah', 'programStudi', 'ruangans'));
    }

    public function update(Request $request, $id)
    {
        $jadwal = JadwalKuliah::findOrFail($id);
        $request->validate([
            'hari' => 'required',
            'dosen' => 'required',
            'mata_kuliah' => 'required',
            'ruangan' => 'required',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required',
            'semester' => 'required',
            'program_studi' => 'required',
            'golongan' => 'required',
        ], [
            'hari.required' => 'Hari harus diisi',
            'dosen.required' => 'Dosen harus diisi',
            'mata_kuliah.required' => 'Mata kuliah harus diisi',
            'ruangan.required' => 'Ruangan harus diisi',
            'jam_mulai.required' => 'Jam mulai harus diisi',
            'jam_selesai.required' => 'Jam selesai harus diisi',
            'semester.required' => 'Semester harus diisi',
            'program_studi.required' => 'Program studi harus diisi',
            'golongan.required' => 'Golongan harus diisi',
        ]);

        $jadwal->update([
            'id_user' => $request->dosen,
            'id_matkul' => $request->mata_kuliah,
            'id_ruangan' => $request->ruangan,
            'hari' => $request->hari,
            'jam_mulai' => $request->jam_mulai,
            'jam_selesai' => $request->jam_selesai,
            'id_semester' => $request->semester,
            'id_prodi' => $request->program_studi,
            'id_golongan' => $request->golongan,
        ]);


        return redirect()->route('admin.jadwal-kuliah.index')->with('success', 'Jadwal Kuliah berhasil diubah.');
    }

    public function destroy($id)
    {
        JadwalKuliah::where('id', $id)->delete();
        return redirect()->route('admin.jadwal-kuliah.index')->with('success', 'Berhasil menghapus jadwal kuliah!');
    }
}
