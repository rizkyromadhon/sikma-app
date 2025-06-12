<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class RfidScanned implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $broadcastQueue = null;
    public $uid;

    public function __construct($uid)
    {
        $this->uid = $uid;
    }

    public function broadcastOn()
    {
        return new Channel('rfid-channel');
    }

    public function broadcastAs()
    {
        return 'rfid.scanned';
    }
}
