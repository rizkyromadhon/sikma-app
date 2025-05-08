<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Ruangan;
use App\Models\Golongan;
use App\Models\Presensi;
use App\Models\Semester;
use App\Models\MataKuliah;
use App\Models\JadwalKuliah;
use App\Models\ProgramStudi;
use Illuminate\Http\Request;
use App\Events\PresensiCreated;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class HomeController extends Controller
{
    public function index()
    {
        $passwordUser = '';
        if (Auth::check()) {
            $passwordUser = Auth::user()->password;
        }
        $isOldPassword = Hash::check('passwordmahasiswa', $passwordUser);
        $rekapPresensi = [];
        $totalSudahPresensiSemua = 0;
        $totalBelumPresensiSemua = 0;
        // Mengambil presensi berdasarkan id_prodi yang terhubung ke program_studi
        $presensi = Presensi::with('user', 'jadwalKuliah')
            ->whereDate('waktu_presensi', Carbon::today())
            ->orderBy('waktu_presensi', 'desc')
            ->get();

        $daftarProdi = $daftarProdi = ProgramStudi::select('id', 'name')->distinct()->get();
        // dd($daftarProdi);

        foreach ($daftarProdi as $programStudi) {
            $totalMahasiswa = User::where('id_prodi', $programStudi->id)->where('role', 'mahasiswa')->count();
            $sudahPresensi = Presensi::whereDate('waktu_presensi', Carbon::today())
                ->whereHas('user', function ($query) use ($programStudi) {
                    $query->where('id_prodi', $programStudi);
                })
                ->whereNotNull('id_jadwal_kuliah')
                ->distinct('user_id')
                ->count('user_id');

            $belumPresensi = $totalMahasiswa - $sudahPresensi;
            $totalSudahPresensiSemua += $sudahPresensi;
            $totalBelumPresensiSemua += $belumPresensi;
            $rekapPresensi[] = [
                'program_studi' => $programStudi->name,
                'sudah' => $sudahPresensi,
                'belum' => $belumPresensi,
            ];
        }

        return view('home', compact('presensi', 'daftarProdi', 'rekapPresensi', 'totalSudahPresensiSemua', 'totalBelumPresensiSemua', 'isOldPassword'));
    }


    public function rekapPresensiJson()
    {
        $rekapPresensi = [];
        $totalSudahPresensiSemua = 0;
        $totalBelumPresensiSemua = 0;
        // Mengambil presensi berdasarkan id_prodi yang terhubung ke program_studi
        $presensi = Presensi::with('user', 'jadwalKuliah')
            ->whereDate('waktu_presensi', Carbon::today())
            ->orderBy('waktu_presensi', 'desc')
            ->get();

        $daftarProdi = $daftarProdi = ProgramStudi::select('id', 'name')->distinct()->get();
        foreach ($daftarProdi as $programStudi) {
            $totalMahasiswa = User::where('id_prodi', $programStudi->id)->where('role', 'mahasiswa')->count();
            $sudahPresensi = Presensi::whereDate('waktu_presensi', Carbon::today())
                ->whereHas('user', function ($query) use ($programStudi) {
                    $query->where('id_prodi', $programStudi->id);
                })
                ->distinct('user_id')
                ->count('user_id');

            $belumPresensi = $totalMahasiswa - $sudahPresensi;
            $totalSudahPresensiSemua += $sudahPresensi;
            $totalBelumPresensiSemua += $belumPresensi;
            $rekapPresensi[] = [
                'program_studi' => $programStudi->name,
                'sudah' => $sudahPresensi,
                'belum' => $belumPresensi,
            ];
        }

        return response()->json([
            'rekapPresensi' => $rekapPresensi,
            'totalSudah' => $totalSudahPresensiSemua,
            'totalBelum' => $totalBelumPresensiSemua,
        ]);
    }


    public function getPresensiToday()
    {
        $presensi = Presensi::with([
            'user',
            'mataKuliah',
            'jadwalKuliah.ruangan'
        ])
            ->whereDate('waktu_presensi', Carbon::today())
            ->where('status', 'Hadir')
            ->orderBy('waktu_presensi', 'desc')
            ->get();

        Log::info('Presensi Hari Ini:', [
            'count' => $presensi->count(),
            'data' => $presensi
        ]);

        return response()->json($presensi);
    }

    public function showDetail(Request $request, $program_studi)
    {
        $prodi = ProgramStudi::where('name', $program_studi)->first();

        $search = $request->get("search");


        if ($search) {
            $search = $request->get("search");

            $presensi = Presensi::with(['user', 'mataKuliah', 'jadwalKuliah.ruangan', 'jadwalKuliah.semester', 'jadwalKuliah.golongan'])
                ->whereHas('user', function ($query) use ($prodi, $search) {
                    $query->where('id_prodi', $prodi->id);

                    if ($search) {
                        $query->where(function ($q) use ($search) {
                            $q->where('name', 'like', '%' . $search . '%')
                                ->orWhere('nim', 'like', '%' . $search . '%');
                        });
                    }
                })
                ->when($request->semester, function ($query, $semesterId) {
                    $query->whereHas('jadwalKuliah', function ($q) use ($semesterId) {
                        $q->where('id_semester', $semesterId);
                    });
                })
                ->when($request->golongan, function ($query, $golonganId) {
                    $query->whereHas('jadwalKuliah', function ($q) use ($golonganId) {
                        $q->where('id_golongan', $golonganId);
                    });
                })
                ->when($request->ruangan, function ($query, $ruanganId) {
                    $query->whereHas('jadwalKuliah', function ($q) use ($ruanganId) {
                        $q->where('id_ruangan', $ruanganId);
                    });
                })
                ->when($request->mata_kuliah, function ($query, $matkulId) {
                    $query->where('id_matkul', $matkulId);
                })
                ->whereDate('waktu_presensi', today())
                ->where('status', 'Hadir')
                ->orderBy('waktu_presensi', 'asc')
                ->get()
                ->groupBy('user_id');
        } else {
            $presensi = Presensi::with(['user', 'mataKuliah', 'jadwalKuliah.ruangan', 'jadwalKuliah.semester', 'jadwalKuliah.golongan'])
                ->whereHas('user', function ($query) use ($prodi) {
                    $query->where('id_prodi', $prodi->id);
                })
                ->when($request->semester, function ($query, $semesterId) {
                    $query->whereHas('jadwalKuliah', function ($q) use ($semesterId) {
                        $q->where('id_semester', $semesterId);
                    });
                })
                ->when($request->golongan, function ($query, $golonganId) {
                    $query->whereHas('jadwalKuliah', function ($q) use ($golonganId) {
                        $q->where('id_golongan', $golonganId);
                    });
                })
                ->when($request->ruangan, function ($query, $ruanganId) {
                    $query->whereHas('jadwalKuliah', function ($q) use ($ruanganId) {
                        $q->where('id_ruangan', $ruanganId);
                    });
                })
                ->when($request->mata_kuliah, function ($query, $matkulId) {
                    $query->where('id_matkul', $matkulId);
                })
                ->whereDate('waktu_presensi', today())
                ->where('status', 'Hadir')
                ->orderBy('waktu_presensi', 'asc')
                ->get()
                ->groupBy('user_id');
        }

        // Untuk isian dropdown
        $golonganOptions = Golongan::where('id_prodi', $prodi->id)->get();
        $ruanganOptions = Ruangan::all();
        $mataKuliahOptions = MataKuliah::all();
        $semesterOptions = Semester::all();

        return view('detail-presensi', compact(
            'presensi',
            'program_studi',
            'prodi',
            'golonganOptions',
            'ruanganOptions',
            'mataKuliahOptions',
            'semesterOptions'
        ));
    }
}
