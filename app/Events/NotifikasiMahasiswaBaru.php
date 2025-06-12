<?php

namespace App\Events;

use App\Models\Notifikasi;
use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NotifikasiMahasiswaBaru implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $broadcastQueue = null;
    public Notifikasi $notifikasi;
    public User $user;


    /**
     * Create a new event instance.
     */
    public function __construct(User $user, Notifikasi $notifikasi)
    {
        $this->user = $user;
        $this->notifikasi = $notifikasi;
    }

    public function broadcastOn(): array
    {
        return [new PrivateChannel('notifikasi.' . $this->user->id)];
    }

    // Data yang akan disiarkan dalam format array
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
