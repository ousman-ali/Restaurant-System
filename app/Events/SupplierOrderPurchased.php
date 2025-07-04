<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\SupplierOrder;
class SupplierOrderPurchased implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $msg;
    public $order;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($msg, SupplierOrder $order)
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
        return ['supplier-order-purchased'];
    }

    public function broadcastAs()
    {
        return "supplier-order-purchased-event";
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
            'purchase_time' => $this->order->purchased_at,
            'sender_role' => auth()->user()->role,
        ];
    }
}
