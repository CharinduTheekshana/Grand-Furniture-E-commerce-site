<?php

namespace App\Events;

use App\Models\Product;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ProductUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public ?Product $product;
    public string $action;

    public function __construct(?Product $product = null, string $action = 'updated')
    {
        $this->product = $product;
        $this->action  = $action;
    }

    public function broadcastOn(): array
    {
        return [new Channel('products')];
    }

    public function broadcastWith(): array
    {
        return [
            'id'     => $this->product?->id,
            'name'   => $this->product?->name,
            'action' => $this->action,
        ];
    }
}