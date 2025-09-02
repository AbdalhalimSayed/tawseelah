<?php

namespace App\Http\Resources\Driver;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DriverResponse extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id"            => $this->id,
            "name"          => $this->name,
            "email"         => $this->email,
            "phone"         => $this->phone,
            "joinAt"        => $this->created_at->format('d-m-Y'),
            "image"         => $this->image?  asset("storage/".$this->image) : asset("default/user-image.png"),
            "verify_email"  => $this->email_verified_at? true: false,
            "token"         => $this->when(isset($this->token), $this->token),
        ];
    }
}
