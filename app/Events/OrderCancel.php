<?php

namespace App\Events;
use App\Models\Order;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class OrderCancel implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $msg;
    public $order;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($msg, Order $order)
    {
        $this->msg = $msg;
        $this->order = $order;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return ['cancel-order'];
    }

    public function broadcastAs()
    {
        return "order-cancel-event";
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
            'updated_at' => $this->order->updated_at,
            'sender_role' => auth()->user()->role,
        ];
    }
}
