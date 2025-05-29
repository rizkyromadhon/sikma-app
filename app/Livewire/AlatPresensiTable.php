<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\AlatPresensi;

class AlatPresensiTable extends Component
{
    public $alatPresensi;

    public function mount()
    {
        $this->updateStatus();
    }

    public function updateStatus()
    {
        $this->alatPresensi = AlatPresensi::with('ruangan')->get()->map(function ($alat) {
            $now = now()->format('H:i:s');
            $isActive = ($now >= $alat->jadwal_nyala && $now < $alat->jadwal_mati) ? 1 : 0;

            if ($alat->status != $isActive) {
                $alat->update(['status' => $isActive]);
            }

            return $alat;
        });
    }

    public function render()
    {
        $this->updateStatus(); // dipanggil setiap render ulang
        return view('livewire.alat-presensi-table');
    }
}
