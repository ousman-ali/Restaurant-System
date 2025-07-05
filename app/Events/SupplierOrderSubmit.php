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
class SupplierOrderSubmit implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $order;
    public $type;

    /**
     * Create a new event instance.
     *
     * @param SupplierOrder $order
     * @param string|null $type
     */
    public function __construct(SupplierOrder $order, $type = null)
    {
        $this->order = $order;
        $this->type = $type;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return ['supplier-order-submit'];
    }

    public function broadcastAs()
    {
        return "supplier-order-submit-event";
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
            'sender_role' => auth()->user()->role,
            'type' => $this->type,
            'created_at' => $this->order->created_at,
        ];
    }
}
