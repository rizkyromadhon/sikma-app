<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Ruangan;
use App\Models\Golongan;
use App\Models\Presensi;
use App\Models\Semester;
use App\Models\MataKuliah;
use App\Models\AlatPresensi;
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
            ->where('tanggal', Carbon::today())
            ->orderBy('waktu_presensi', 'desc')
            ->get();

        $daftarProdi = $daftarProdi = ProgramStudi::select('id', 'name')->distinct()->get();

        foreach ($daftarProdi as $programStudi) {
            $totalMahasiswa = User::where('id_prodi', $programStudi->id)->where('role', 'mahasiswa')->count();
            $sudahPresensi = Presensi::where('tanggal', Carbon::today())
                ->whereHas('user', function ($query) use ($programStudi) {
                    $query->where('id_prodi', $programStudi->id);
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

        AlatPresensi::where('id', 1)->update(['mode' => 'attendance']);

        return view('home', compact('presensi', 'daftarProdi', 'rekapPresensi', 'totalSudahPresensiSemua', 'totalBelumPresensiSemua', 'isOldPassword'));
    }


    public function rekapPresensiJson()
    {
        $rekapPresensi = [];
        $totalSudahPresensiSemua = 0;
        $totalBelumPresensiSemua = 0;
        // Mengambil presensi berdasarkan id_prodi yang terhubung ke program_studi
        $presensi = Presensi::with('user', 'jadwalKuliah')
            ->where('tanggal', Carbon::today())
            ->orderBy('waktu_presensi', 'desc')
            ->get();

        $daftarProdi = $daftarProdi = ProgramStudi::select('id', 'name')->distinct()->get();
        foreach ($daftarProdi as $programStudi) {
            $totalMahasiswa = User::where('id_prodi', $programStudi->id)->where('role', 'mahasiswa')->count();

            $sudahPresensi = Presensi::where('tanggal', Carbon::today())
                ->where('status', 'Hadir') // <-- TAMBAHKAN BARIS INI
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


    public function getPresensiToday(Request $request)
    {
        $programStudi = $request->query('program_studi');

        $presensi = Presensi::with([
            'user:id,name,nim,id_prodi',
            'user.programStudi:id,name', // include relasi program studi
            'mataKuliah:id,kode,name',
            'jadwalKuliah:id,hari,jam_mulai,jam_selesai,id_semester,id_ruangan,id_matkul,id_golongan',
            'jadwalKuliah.semester:id,display_name',
            'jadwalKuliah.ruangan:id,name',
            'jadwalKuliah.golongan:id,nama_golongan'
        ])
            ->when($programStudi, function ($query) use ($programStudi) {
                $query->whereHas('user.programStudi', function ($q) use ($programStudi) {
                    $q->where('name', $programStudi);
                });
            })
            ->where('tanggal', Carbon::today())
            ->where('status', 'Hadir')
            ->orderBy('waktu_presensi', 'desc')
            ->get();

        // Struktur respons yang disesuaikan untuk frontend
        $result = $presensi->map(function ($item) {
            return [
                'id' => $item->id,
                'id_jadwal_kuliah' => $item->id_jadwal_kuliah,
                'waktu_presensi' => $item->waktu_presensi,
                'tanggal' => $item->tanggal,
                'status' => $item->status,
                'user' => [
                    'id' => $item->user->id,
                    'name' => $item->user->name,
                    'nim' => $item->user->nim ?? null,
                    'programStudi' => [
                        'id' => $item->user->programStudi->id ?? null,
                        'name' => $item->user->programStudi->name ?? null,
                    ],
                ],
                'mataKuliah' => [
                    'id' => $item->mataKuliah->id ?? null,
                    'kode' => $item->mataKuliah->kode ?? null,
                    'name' => $item->mataKuliah->name ?? null,
                ],
                'jadwalKuliah' => [
                    'id' => $item->jadwalKuliah->id ?? null,
                    'hari' => $item->jadwalKuliah->hari ?? null,
                    'jam_mulai' => $item->jadwalKuliah->jam_mulai ?? null,
                    'jam_selesai' => $item->jadwalKuliah->jam_selesai ?? null,
                    'semester' => [
                        'id' => $item->jadwalKuliah->semester->id ?? null,
                        'display_name' => $item->jadwalKuliah->semester->display_name ?? null,
                    ],
                    'ruangan' => [
                        'id' => $item->jadwalKuliah->ruangan->id ?? null,
                        'name' => $item->jadwalKuliah->ruangan->name ?? null,
                    ],
                    'golongan' => [
                        'id' => $item->jadwalKuliah->golongan->id ?? null,
                        'nama_golongan' => $item->jadwalKuliah->golongan->nama_golongan ?? null,
                    ],
                ],
            ];
        });

        return response()->json($result);
    }

    public function getPresensiFiltered(Request $request)
    {
        $programStudi = $request->query('program_studi');
        $semester = $request->query('semester');
        $golongan = $request->query('golongan');
        $ruangan = $request->query('ruangan');
        $mataKuliah = $request->query('mata_kuliah');
        $search = $request->query('search');

        $presensi = Presensi::with([
            'user:id,name,nim,id_prodi',
            'user.programStudi:id,name', // include relasi program studi
            'mataKuliah:id,kode,name',
            'jadwalKuliah:id,hari,jam_mulai,jam_selesai,id_semester,id_ruangan,id_matkul,id_golongan',
            'jadwalKuliah.semester:id,display_name',
            'jadwalKuliah.ruangan:id,name',
            'jadwalKuliah.golongan:id,nama_golongan'
        ])
            ->when($programStudi, function ($query) use ($programStudi) {
                $query->whereHas('user.programStudi', function ($q) use ($programStudi) {
                    $q->where('name', $programStudi);
                });
            })
            ->when($search, function ($query) use ($search) {
                $query->whereHas('user', function ($q) use ($search) {
                    $q->where('name', 'like', "%$search%")
                        ->orWhere('nim', 'like', "%$search%");
                });
            })
            ->when($semester, function ($query) use ($semester) {
                $query->whereHas('jadwalKuliah', function ($q) use ($semester) {
                    $q->where('id_semester', $semester);
                });
            })
            ->when($golongan, function ($query) use ($golongan) {
                $query->whereHas('jadwalKuliah', function ($q) use ($golongan) {
                    $q->where('id_golongan', $golongan);
                });
            })
            ->when($ruangan, function ($query) use ($ruangan) {
                $query->whereHas('jadwalKuliah', function ($q) use ($ruangan) {
                    $q->where('id_ruangan', $ruangan);
                });
            })
            ->when($mataKuliah, function ($query) use ($mataKuliah) {
                $query->where('id_matkul', $mataKuliah);
            })
            ->where('tanggal', today())
            ->where('status', 'Hadir')
            ->orderBy('waktu_presensi', 'desc')
            ->get();

        $result = $presensi->map(function ($item) {
            return [
                'id' => $item->id,
                'id_jadwal_kuliah' => $item->id_jadwal_kuliah,
                'waktu_presensi' => $item->waktu_presensi,
                'tanggal' => $item->tanggal,
                'status' => $item->status,
                'user' => [
                    'id' => $item->user->id,
                    'name' => $item->user->name,
                    'nim' => $item->user->nim ?? null,
                    'programStudi' => [
                        'id' => $item->user->programStudi->id ?? null,
                        'name' => $item->user->programStudi->name ?? null,
                    ],
                ],
                'mataKuliah' => [
                    'id' => $item->mataKuliah->id ?? null,
                    'kode' => $item->mataKuliah->kode ?? null,
                    'name' => $item->mataKuliah->name ?? null,
                ],
                'jadwalKuliah' => [
                    'id' => $item->jadwalKuliah->id ?? null,
                    'hari' => $item->jadwalKuliah->hari ?? null,
                    'jam_mulai' => $item->jadwalKuliah->jam_mulai ?? null,
                    'jam_selesai' => $item->jadwalKuliah->jam_selesai ?? null,
                    'semester' => [
                        'id' => $item->jadwalKuliah->semester->id ?? null,
                        'display_name' => preg_replace('/[^0-9]/', '', $item->jadwalKuliah->semester->display_name) ?? null,
                    ],
                    'ruangan' => [
                        'id' => $item->jadwalKuliah->ruangan->id ?? null,
                        'name' => $item->jadwalKuliah->ruangan->name ?? null,
                    ],
                    'golongan' => [
                        'id' => $item->jadwalKuliah->golongan->id ?? null,
                        'nama_golongan' => $item->jadwalKuliah->golongan->nama_golongan ?? null,
                    ],
                ],
            ];
        });

        return response()->json($result);
    }



    public function showDetail(Request $request, $program_studi)
    {
        // Ambil data program studi berdasarkan name dari URL
        $prodi = ProgramStudi::where('name', $program_studi)->firstOrFail();

        // Ambil parameter pencarian
        $search = $request->get('search');

        // Ambil semua data presensi sesuai filter dan program studi
        $presensi = Presensi::with([
            'user.programStudi',
            'mataKuliah',
            'jadwalKuliah.ruangan',
            'jadwalKuliah.semester',
            'jadwalKuliah.golongan'
        ])
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
            ->where('tanggal', today())
            ->where('status', 'Hadir')
            ->orderBy('waktu_presensi', 'asc')
            ->get()
            ->groupBy('user_id');

        // Data untuk dropdown filter
        $golonganOptions = Golongan::where('id_prodi', $prodi->id)->get();
        $ruanganOptions = Ruangan::all();
        $mataKuliahOptions = MataKuliah::all();

        $allSemestersForFilter = Semester::all(); // Ambil semua semester
        $semesterOptions = $allSemestersForFilter->sortBy(function ($semesters) {
            if (preg_match('/(\d+)$/', $semesters->display_name, $matches)) {
                return (int) $matches[1]; // Kembalikan angka sebagai integer
            }
            if (empty($semester->display_name)) {
                return PHP_INT_MAX;
            }
            return $semesters->display_name;
        })->values();
        // $semesterOptions = Semester::all();

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
