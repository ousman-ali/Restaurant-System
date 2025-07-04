<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\InhouseOrder;
class CompleteInhouseCooking implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $order;

    /**
     * Create a new event instance.
     *
     * @param InhouseOrder $order
     */
    public function __construct(InhouseOrder $order)
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
        return ['complete-inhouse-cooking'];
    }

    public function broadcastAs()
    {
        return "complete-inhouse-cooking-event";
    }

    public function broadcastWith()
    {

        return  $this->prepareData();

    }

    protected function prepareData()
    {
        return [
            'id'   =>    $this->order->id,
            'order_no'   =>    $this->order->order_no,
            'cook_complete_time' =>$this->order->cook_complete_at,
            'sender_role' => auth()->user()->role,
        ];
    }
}
