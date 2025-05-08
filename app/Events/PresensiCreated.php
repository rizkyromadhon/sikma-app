<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;
use App\Models\Presensi;

class PresensiCreated implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    public $presensi;
    public $jadwalKuliah;

    public function __construct(Presensi $presensi)
    {
        $this->presensi = $presensi->load('user');
        $this->jadwalKuliah = $presensi->jadwalKuliah;
    }

    public function broadcastOn()
    {
        return new Channel('presensi');
    }

    public function broadcastAs()
    {
        return 'eventPresensi';
    }

    public function broadcastWith()
    {
        return [
            'id' => $this->presensi->id,
            'user' => $this->presensi->user,
            'jadwal_kuliah' => $this->jadwalKuliah,  // Mengirimkan jadwal kuliah termasuk ruangan
            'mata_kuliah' => $this->presensi->mata_kuliah,
            'status' => $this->presensi->status,
            'waktu_presensi' => $this->presensi->waktu_presensi,
            'ruangan' => $this->jadwalKuliah->ruangan,  // Mengirimkan ruangan
        ];
    }
}

