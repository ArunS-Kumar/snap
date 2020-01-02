<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class SyncEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $eventType;
    public $data;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($eventType, $data)
    {
        $this->eventType = $eventType;
        $this->data = $data;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        // Intentionally commented - since we dont use broadcast as of now.
        // return new PrivateChannel('channel-name');
    }
}
