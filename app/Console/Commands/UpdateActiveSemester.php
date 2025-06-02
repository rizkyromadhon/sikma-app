<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Semester;
use Carbon\Carbon;

class UpdateActiveSemester extends Command
{
    protected $signature = 'semester:update-active';
    protected $description = 'Update semester is_active based on current date';

    public function handle(): void
    {
        $today = Carbon::now()->startOfDay();

        $semesters = Semester::all();

        foreach ($semesters as $semester) {
            $isActive = false;
            if ($semester->start_date && $semester->end_date) {
                $startDate = Carbon::parse($semester->start_date)->startOfDay();
                $endDate = Carbon::parse($semester->end_date)->endOfDay();

                if ($today->between($startDate, $endDate)) { // Pengecekan inti
                    $isActive = true;
                }
            }

            if ($semester->is_currently_active !== $isActive) {
                $semester->is_currently_active = $isActive;
                $semester->save();
            }
        }

        $activeCount = Semester::where('is_currently_active', true)->count();

        $this->info("Berhasil update $activeCount semester(s) sebagai semester aktif");
    }
}
