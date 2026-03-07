<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class OrderStatusChanged implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $order_number;
    public $status;
    public $fulfillment_status;

    public function __construct($orderNumber, $status = null, $fulfillment = null)
    {
        $this->order_number = $orderNumber;
        $this->status = $status;
        $this->fulfillment_status = $fulfillment;
    }

    public function broadcastOn(): array
    {
        return [new Channel('order.' . $this->order_number)];
    }

    public function broadcastWith(): array
    {
        return [
            'order_number' => $this->order_number,
            'status' => $this->status,
            'fulfillment_status' => $this->fulfillment_status,
        ];
    }
}
