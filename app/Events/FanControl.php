<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class FanControl implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public string $action;

    public function __construct(string $action)
    {
        $this->action = $action;
    }

    public function broadcastOn(): Channel
    {
        return new Channel('fan-control');
    }

    public function broadcastAs(): string
    {
        return 'turnOnFan';
    }

    public function broadcastWith(): array
    {
        return [
            'action' => $this->action,
        ];
    }
}
