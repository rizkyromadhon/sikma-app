<?php

namespace Database\Seeders;

use App\Models\Presensi;
use Illuminate\Database\Seeder;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PresensiKuliahSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Presensi::create([
            'user_id' => 4,  // Mengacu ke id di tabel users
            'mata_kuliah' => 'Pemrograman Web',
            'jadwal_kuliah_id' => 1,  // Mengacu ke id di tabel jadwal_kuliah
            'waktu_presensi' => Carbon::now(),
            'tanggal' => Carbon::today()->format('Y-m-d'),
            'status' => 'Hadir',
            'keterangan' => 'Tidak ada keterangan',
        ]);

        Presensi::create([
            'user_id' => 5,
            'mata_kuliah' => 'Algoritma dan Pemrograman',
            'jadwal_kuliah_id' => 2,
            'waktu_presensi' => Carbon::now(),
            'tanggal' => Carbon::today()->format('Y-m-d'),
            'status' => 'Hadir',
            'keterangan' => 'Tidak ada keterangan',
        ]);
    }
}
