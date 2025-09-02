<?php

namespace App\Http\Resources\Driver;

use App\Http\Resources\User\UserResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CarResponse extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "brand"     => $this->brand,
            "model"     => $this->model,
            "year"      => $this->year,
            "color"     => $this->color,
            "license"   => asset($this->license),
            "image"     => asset($this->image),

        ];
    }
}
