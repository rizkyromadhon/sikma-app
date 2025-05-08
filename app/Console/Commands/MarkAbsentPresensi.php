<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\PresensiKuliahController;

class MarkAbsentPresensi extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'presensi:mark-absent';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Menandai mahasiswa yang tidak hadir untuk jadwal kuliah yang sudah lewat';


    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Panggil fungsi yang ingin dijalankan
        app(PresensiKuliahController::class)->markAbsentForMissingPresences();

        $this->info('Presensi tidak hadir berhasil diperbarui.');
    }
}
