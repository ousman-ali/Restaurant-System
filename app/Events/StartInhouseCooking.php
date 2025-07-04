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
class StartInhouseCooking implements ShouldBroadcast
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
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn()
    {
        return ['start-inhouse-cooking'];
    }

    public function broadcastAs()
    {
        return "kitchen-inhouse-event";
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
            'cook_start_time' => $this->order->cook_start_at,
            'sender_role' => auth()->user()->role,
        ];
    }
}
