<?php

namespace App\Events;

use App\Models\Notifikasi;
use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NotifikasiIzinBaru implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $broadcastQueue = null;
    public User $dosen;
    public Notifikasi $notifikasi;

    /**
     * Create a new event instance.
     *
     * @param \App\Models\User       $dosen      Penerima notifikasi
     * @param \App\Models\Notifikasi $notifikasi Objek notifikasi yang sudah disimpan
     */
    public function __construct(User $dosen, Notifikasi $notifikasi)
    {
        $this->dosen = $dosen;
        $this->notifikasi = $notifikasi;
    }

    /**
     * Get the channels the event should broadcast on.
     */
    public function broadcastOn(): array
    {
        // Channelnya sekarang menggunakan ID dari objek dosen
        return [new PrivateChannel('notifikasi-dosen.' . $this->dosen->id)];
    }

    /**
     * Data yang akan disiarkan, diambil dari objek notifikasi.
     */
    public function broadcastWith(): array
    {
        return [
            'id' => $this->notifikasi->id,
            'tipe' => $this->notifikasi->tipe,
            'konten' => $this->notifikasi->konten,
            'url_tujuan' => $this->notifikasi->url_tujuan,
            'read_at' => null,
            'created_at_human' => $this->notifikasi->created_at->diffForHumans(),
        ];
    }
}
