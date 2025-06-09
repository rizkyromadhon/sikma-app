<?php

namespace App\Http\Controllers\Dosen;

use App\Models\JadwalKuliah;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class JadwalDosenController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $dosenId = Auth::id();

        // 1. Ambil semua jadwal milik dosen dengan relasi yang dibutuhkan
        $semuaJadwal = JadwalKuliah::with(['mataKuliah', 'ruangan', 'prodi', 'semester', 'golongan'])
            ->where('id_user', $dosenId)
            ->orderBy('jam_mulai', 'asc')
            ->get();

        // 2. Kelompokkan jadwal yang sama (mengabaikan golongan) untuk mendeteksi kelas besar
        $grupJadwalSama = $semuaJadwal->groupBy(function ($jadwal) {
            return $jadwal->hari . '-' . $jadwal->jam_mulai . '-' . $jadwal->id_matkul;
        });

        // 3. Proses grup untuk membuat jadwal gabungan
        $jadwalGabungan = collect(); // Buat koleksi baru untuk menampung hasil

        foreach ($grupJadwalSama as $grup) {
            $jadwalUtama = $grup->first()->replicate(); // Ambil jadwal pertama sebagai data utama

            if ($grup->count() > 1) {
                // Jika ada lebih dari 1 jadwal di grup ini, maka ini KELAS BESAR
                $jadwalUtama->is_kelas_besar = true;

                // Ambil semua nama golongan dari grup, urutkan, dan gabungkan menjadi satu string
                $semuaGolongan = $grup->pluck('golongan.nama_golongan')->sort()->join(', ');
                $jadwalUtama->semua_golongan_string = $semuaGolongan;
            } else {
                // Jika hanya ada 1, ini kelas biasa
                $jadwalUtama->is_kelas_besar = false;
                $jadwalUtama->semua_golongan_string = $grup->first()->golongan->nama_golongan ?? 'N/A';
            }

            $jadwalGabungan->push($jadwalUtama);
        }

        // 4. Urutkan hari pada hasil akhir
        $urutanHari = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
        $jadwalPerHari = $jadwalGabungan->sortBy('jam_mulai')->groupBy('hari')->sortBy(function ($_, $hari) use ($urutanHari) {
            return array_search($hari, $urutanHari);
        });

        // 5. Kirim data yang sudah digabungkan ke view
        return view('dosen.jadwal.index', [
            'jadwalPerHari' => $jadwalPerHari
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
