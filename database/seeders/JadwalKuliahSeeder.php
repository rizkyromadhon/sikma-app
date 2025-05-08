<?php

namespace Database\Seeders;

use App\Models\JadwalKuliah;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class JadwalKuliahSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        JadwalKuliah::create([
            'mata_kuliah' => 'Pemrograman Web',
            'dosen' => 'Budi Santosa, M.Kom',
            'jam_mulai' => '08:00',
            'jam_selesai' => '10:00',
            'hari' => 'Senin',
            'ruangan' => 'Lab Arsitektur Jaringan Komputer',
            'golongan_id' => 1,  // Golongan A
        ]);

        JadwalKuliah::create([
            'mata_kuliah' => 'Algoritma dan Pemrograman',
            'dosen' => 'Agus Wijaya, M.Sc',
            'jam_mulai' => '10:30',
            'jam_selesai' => '12:30',
            'hari' => 'Senin',
            'ruangan' => 'Lab Komputer',
            'golongan_id' => 2,  // Golongan B
        ]);
        JadwalKuliah::create([
            'mata_kuliah' => 'Pemrograman Web',
            'dosen' => 'Budi Hariyono, M.Kom',
            'jam_mulai' => '10:00',
            'jam_selesai' => '12:00',
            'hari' => 'Selasa',
            'ruangan' => 'Lab Sistem Komputer Kontrol',
            'golongan_id' => 3,
        ]);

        JadwalKuliah::create([
            'mata_kuliah' => 'MS Office',
            'dosen' => 'Rudi Wijaya, M.Sc',
            'jam_mulai' => '10:30',
            'jam_selesai' => '12:30',
            'hari' => 'Selasa',
            'ruangan' => 'Lab Komputer',
            'golongan_id' => 4,  // Golongan B
        ]);
    }
}
