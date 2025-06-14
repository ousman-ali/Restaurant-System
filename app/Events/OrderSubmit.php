<?php

namespace App\Events;

use App\Models\Order;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class OrderSubmit implements ShouldBroadcast
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
        return ['order'];
    }

    public function broadcastAs()
    {
        return "order-event";
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
            'status'   =>    $this->order->status,
            'payment'   =>    $this->order->payment,
            'table_id'   =>    $this->order->table_id,
            'user_id'   =>    $this->order->user_id,
            'vat'   =>    $this->order->vat,
            'kitchen_id'   =>    $this->order->kitchen_id,
            'change_amount'   =>    $this->order->change_ammount,
            'created_at'   =>    $this->order->created_at,
            'updated_at'   =>    $this->order->updated_at,
            'order_details'   => $this->order->orderDetails,
            'served_by'  => $this->order->servedBy,
        ];
    }


}
