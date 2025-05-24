<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Semester;
use App\Models\AlatPresensi;
use App\Models\ProgramStudi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        $mahasiswa = User::where('role', 'mahasiswa')->get();
        $dosen = User::where('role', 'dosen')->get();
        $jumlahMahasiswa = $mahasiswa->count();
        $jumlahDosen = $dosen->count();
        $jumlahGenderLaki = $mahasiswa->where('gender', 'Laki-laki')->count();
        $jumlahGenderPerempuan = $mahasiswa->where('gender', 'Perempuan')->count();
        $persentaseLaki = ($jumlahMahasiswa > 0) ? ($jumlahGenderLaki / $jumlahMahasiswa) * 100 : 0;
        $persentasePerempuan = ($jumlahMahasiswa > 0) ? ($jumlahGenderPerempuan / $jumlahMahasiswa) * 100 : 0;

        $semesters = Semester::orderBy('id')->get();

        $mahasiswaPerSemester = User::with('semester')
            ->where('role', 'mahasiswa')
            ->whereNotNull('id_semester')
            ->get()
            ->groupBy(fn($user) => optional($user->semester)->semester_name)
            ->map(fn($group) => $group->count());

        // Final output: key = angka semester, value = jumlah mahasiswa
        $jumlahMahasiswaPerSemester = $semesters->mapWithKeys(function ($semester) use ($mahasiswaPerSemester) {
            $angkaSemester = (int) filter_var($semester->semester_name, FILTER_SANITIZE_NUMBER_INT);
            $jumlah = $mahasiswaPerSemester->get($semester->semester_name, 0);
            return [$angkaSemester => $jumlah];
        });

        $prodis = ProgramStudi::orderBy('id')->get();
        $mahasiswaPerProdi = User::with('programStudi')
            ->where('role', 'mahasiswa')
            ->whereNotNull('id_prodi')
            ->get()
            ->groupBy(fn($user) => optional($user->programStudi)->name)
            ->map(fn($group) => $group->count());

        $jumlahMahasiswaPerProdi = $prodis->mapWithKeys(function ($prodi) use ($mahasiswaPerProdi) {
            $jumlah = $mahasiswaPerProdi->get($prodi->name, 0);
            return [$prodi->name => $jumlah];
        });

        $dosenPerProdi = User::with('programStudi')
            ->where('role', 'dosen')
            ->whereNotNull('id_prodi')
            ->get()
            ->groupBy(fn($user) => optional($user->programStudi)->name)
            ->map(fn($group) => $group->count());

        $jumlahDosenPerProdi = $prodis->mapWithKeys(function ($prodi) use ($dosenPerProdi) {
            $jumlah = (int) $dosenPerProdi->get($prodi->name, 0);
            return [$prodi->name => $jumlah];
        });

        $tabelDosenPerProdi = User::with('programStudi')
            ->where('role', 'dosen')
            ->whereNotNull('id_prodi')
            ->select('id_prodi')
            ->selectRaw('COUNT(*) as total')
            ->groupBy('id_prodi')
            ->orderBy('id_prodi')
            ->get();

        AlatPresensi::where('id', 1)->update(['mode' => 'attendance']);

        return view('admin.pages.dashboard', compact('jumlahMahasiswa', 'jumlahDosen', 'jumlahGenderLaki', 'jumlahGenderPerempuan', 'jumlahMahasiswaPerSemester', 'jumlahMahasiswaPerProdi', 'jumlahDosenPerProdi', 'tabelDosenPerProdi', 'semesters', 'prodis', 'persentaseLaki', 'persentasePerempuan'));
    }
}
