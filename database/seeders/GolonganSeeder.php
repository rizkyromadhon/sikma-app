<?php

namespace Database\Seeders;

use App\Models\Golongan;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class GolonganSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Golongan::create([
            'nama_golongan' => 'golongan A',
            'program_studi' => 'Teknik Komputer',
        ]);

        Golongan::create([
            'nama_golongan' => 'golongan B',
            'program_studi' => 'Teknik Komputer',
        ]);
        Golongan::create([
            'nama_golongan' => 'golongan C',
            'program_studi' => 'Teknik Komputer',
        ]);

        Golongan::create([
            'nama_golongan' => 'golongan D',
            'program_studi' => 'Teknik Komputer',
        ]);


        Golongan::create([
            'nama_golongan' => 'golongan A',
            'program_studi' => 'Teknik Informatika',
        ]);

        Golongan::create([
            'nama_golongan' => 'golongan B',
            'program_studi' => 'Teknik Informatika',
        ]);
        Golongan::create([
            'nama_golongan' => 'golongan C',
            'program_studi' => 'Teknik Informatika',
        ]);

        Golongan::create([
            'nama_golongan' => 'golongan D',
            'program_studi' => 'Teknik Informatika',
        ]);


        Golongan::create([
            'nama_golongan' => 'golongan A',
            'program_studi' => 'Manajemen Informatika',
        ]);

        Golongan::create([
            'nama_golongan' => 'golongan B',
            'program_studi' => 'Manajemen Informatika',
        ]);
        Golongan::create([
            'nama_golongan' => 'golongan C',
            'program_studi' => 'Manajemen Informatika',
        ]);

        Golongan::create([
            'nama_golongan' => 'golongan D',
            'program_studi' => 'Manajemen Informatika',
        ]);


        Golongan::create([
            'nama_golongan' => 'golongan A',
            'program_studi' => 'Bisnis Digital',
        ]);

        Golongan::create([
            'nama_golongan' => 'golongan B',
            'program_studi' => 'Bisnis Digital',
        ]);
        Golongan::create([
            'nama_golongan' => 'golongan C',
            'program_studi' => 'Bisnis Digital',
        ]);

        Golongan::create([
            'nama_golongan' => 'golongan D',
            'program_studi' => 'Bisnis Digital',
        ]);
    }
}
