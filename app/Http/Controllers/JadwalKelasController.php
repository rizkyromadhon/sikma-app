<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\JadwalKuliah;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class JadwalKelasController extends Controller
{
    public function index()
    {
        // Ambil data pengguna yang sedang login
        $user = Auth::user();
        $jadwal = [];
        $jadwalGrouped = [];

        if ($user) {
            $jadwal = JadwalKuliah::where('id_golongan', $user->id_golongan)
                ->where('id_prodi', $user->id_prodi)
                ->where('id_semester', $user->id_semester)
                ->with('mataKuliah', 'dosen', 'ruangan', 'golongan', 'semester', 'prodi')
                ->get();

            $jadwalGrouped = $jadwal->groupBy('hari');
        }

        return view('jadwal-kelas', compact('jadwal', 'jadwalGrouped'));
    }
}
