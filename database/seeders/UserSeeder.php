<?php

namespace Database\Seeders;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Mohammad Rizky Romadhon',
            'email' => 'rizky@gmail.com',
            'password' => bcrypt('12345687'),
            'program_studi' => 'Teknik Komputer',
            'golongan_id' => 1,
            'nim' => 'E32222530',
            'semester_tempuh' => 4,
            'no_hp' => '085745505391',
            'alamat' => 'Jalan Raya No. 123, Jakarta',
            'foto' => null,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        User::create([
            'name' => 'Hariyono',
            'email' => 'hariyono@gmail.com',
            'password' => bcrypt('12345687'),
            'program_studi' => 'Teknik Informatika',
            'golongan_id' => 3,
            'nim' => 'E12345213',
            'semester_tempuh' => 4,
            'no_hp' => '08123456789',
            'alamat' => 'Jalan Raya No. 123, Jakarta',
            'foto' => null,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        User::create([
            'name' => 'Denny Caknan',
            'email' => 'denny@gmail.com',
            'password' => bcrypt('12345687'),
            'program_studi' => 'Manajemen Informatika',
            'golongan_id' => 2,
            'nim' => 'E12345331',
            'semester_tempuh' => 4,
            'no_hp' => '08123456789',
            'alamat' => 'Jalan Raya No. 123, Jakarta',
            'foto' => null,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);


        User::create([
            'name' => 'Siti Nur',
            'email' => 'siti@gmail.com',
            'password' => bcrypt('12345687'),
            'program_studi' => 'Bisnis Digital',
            'golongan_id' => 4,
            'nim' => 'E12345557',
            'semester_tempuh' => 4,
            'no_hp' => '08123456789',
            'alamat' => 'Jalan Raya No. 123, Jakarta',
            'foto' => null,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }
}
