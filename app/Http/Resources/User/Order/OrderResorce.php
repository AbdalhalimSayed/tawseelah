<?php

namespace App\Http\Resources\User\Order;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResorce extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id"        => $this->id,
            "user"      => $this->user?->name,
            "driver"    => $this->driver?->name,
            "price"     => $this->price,
            "pickup"    => $this->pickup_lat . "," . $this->pickup_lng,
            "dropoff"   => $this->dropoff_lat . "," . $this->dropoff_lng,
            "status"    => $this->status,
            "OrderedAt" => $this->created_at->diffForHumans(),

        ];
    }
}
