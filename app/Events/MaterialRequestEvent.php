<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MaterialRequestEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $item;

    public function __construct($item)
    {
        $this->item = $item;
    }

     public function broadcastOn()
    {
        return ['material-request'];
    }

    public function broadcastAs()
    {
        return "material-request-event";
    }

    public function broadcastWith()
    {
        return [
            'type' => $this->item['type'],
            'name' => $this->item['name'],
            'minimum_stock_threshold' => $this->item['minimum_stock_threshold'],
            'time' => $this->item['time'],
            'user' => $this->item['user'],
            'requested' => $this->item['requested'],
            'notify' => $this->item['notify'],
            'not_type' => $this->item['not_type'],
        ];
    }
}
