<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PresensiUntukMonitoring implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $broadcastQueue = null;
    public string $status;
    public array $data;

    public function __construct(string $status, array $data)
    {
        $this->status = $status;
        $this->data = $data;
    }

    public function broadcastOn()
    {
        return new Channel('monitoring-channel');
    }
}
