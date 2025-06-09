<?php

namespace App\Events;

use App\Models\JadwalKuliah;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class KehadiranDiperbarui implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    protected $jadwal;

    public function __construct(JadwalKuliah $jadwal)
    {
        $this->jadwal = $jadwal;
    }

    /**
     * Mengirim event ke channel privat yang spesifik untuk setiap jadwal.
     * Hanya dosen yang memiliki akses ke jadwal ini yang bisa mendengarkan.
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('kelas.' . $this->jadwal->id),
        ];
    }
}
