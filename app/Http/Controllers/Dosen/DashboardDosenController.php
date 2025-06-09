<?php

namespace App\Http\Controllers\Dosen;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Presensi;
use App\Models\JadwalKuliah;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DashboardDosenController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $dosenId = Auth::id();
        $dosenProdiId = Auth::user()->id_prodi;

        $hari = Carbon::now()->translatedFormat('l');

        $jumlahKelasHariIni = JadwalKuliah::where('id_user', $dosenId)
            ->where('hari', $hari)
            ->count();

        $jumlahMataKuliah = JadwalKuliah::where('id_user', $dosenId)
            ->distinct('id_matkul')
            ->count('id_matkul');

        $jadwalDosen = JadwalKuliah::where('id_user', $dosenId)->get();
        $jadwalIds = $jadwalDosen->pluck('id');

        $totalMenitMengajar = 0;
        foreach ($jadwalDosen as $jadwal) {
            $jamMulai = Carbon::parse($jadwal->jam_mulai);
            $jamSelesai = Carbon::parse($jadwal->jam_selesai);

            $selisihMenit = abs($jamSelesai->diffInMinutes($jamMulai, false));

            $totalMenitMengajar += $selisihMenit;
        }
        $jamMengajarPerMinggu = round($totalMenitMengajar / 60);

        $prodiIds = $jadwalDosen->pluck('id_prodi')->unique();

        // 2. Hitung semua mahasiswa yang id_prodi-nya ada di dalam daftar prodi tersebut
        $totalMahasiswa = User::where('role', 'mahasiswa')
            ->whereIn('id_prodi', $prodiIds)
            ->count();

        $totalPresensi = Presensi::whereIn('id_jadwal_kuliah', $jadwalIds)->count();
        $totalHadir = Presensi::whereIn('id_jadwal_kuliah', $jadwalIds)
            ->where('status', 'hadir')
            ->count();

        $persentaseKehadiran = ($totalPresensi > 0) ? ($totalHadir / $totalPresensi) * 100 : 0;

        return view("dosen.dashboard.index", [
            'jumlahMataKuliah' => $jumlahMataKuliah,
            'totalMahasiswa' => $totalMahasiswa,
            'jamMengajarPerMinggu' => $jamMengajarPerMinggu,
            'persentaseKehadiran' => $persentaseKehadiran,
            'jumlahKelasHariIni' => $jumlahKelasHariIni,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
