<?php

namespace App\Events;

use App\Models\Order;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class StartCooking implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $order;

    /**
     * Create a new event instance.
     *
     * @param Order $order
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return ['start-cooking'];
    }

    public function broadcastAs()
    {
        return "kitchen-event";
    }

    public function broadcastWith()
    {

        return  $this->prepareData();

    }

    protected function prepareData()
    {
        return [
            'id'   =>    $this->order->id,
        ];
    }

}
