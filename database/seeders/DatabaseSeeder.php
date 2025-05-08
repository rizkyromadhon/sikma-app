<?php

// database/seeders/DatabaseSeeder.php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\GolonganSeeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            UserSeeder::class,
            GolonganSeeder::class,
            JadwalKuliahSeeder::class,
            PresensiKuliahSeeder::class,
        ]);
    }
}
