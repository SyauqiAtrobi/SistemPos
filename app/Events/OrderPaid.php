<?php

namespace App\Events;

use App\Models\Order;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow; // Gunakan ShouldBroadcastNow
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class OrderPaid implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $order;

    /**
     * Create a new event instance.
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * Tentukan Channel mana yang akan mendengarkan event ini.
     * Kita gunakan Public Channel dengan nama unik berdasarkan nomor order.
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('order.' . $this->order->order_number),
        ];
    }

    /**
     * Data apa saja yang akan dikirim ke Frontend (JavaScript)
     */
    public function broadcastWith(): array
    {
        return [
            'order_number' => $this->order->order_number,
            'status' => $this->order->status,
            'message' => 'Pembayaran berhasil! Terima kasih telah berbelanja di ' . config('app.name') . '.'
        ];
    }
}