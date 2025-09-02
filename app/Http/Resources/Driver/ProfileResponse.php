<?php

namespace App\Http\Resources\Driver;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProfileResponse extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id"                    => $this->id,
            "name"                  => $this->name,
            "email"                 => $this->email,
            "phone"                 => $this->phone,
            "joinAt"                => $this->created_at->format('d-m-Y'),
            "image profile"         => $this->image?  asset("storage/".$this->image) : asset("default/user-image.png"),
            "verify_email"          => $this->email_verified_at? true: false,
            "profil"                => [
                "card_number"       => $this->driver_profile->card_number,
                "country"           => $this->driver_profile->country,
                "city"              => $this->driver_profile->city,
                "state"             => $this->driver_profile->state,
                "driver_license"    => asset($this->driver_profile->license),
                "id_card"           => asset($this->driver_profile->id_card),
            ],
            "car"                   => [
                "brand"             => $this->car->brand,
                "model"             => $this->car->model,
                "year"              => $this->car->year,
                "color"             => $this->car->color,
                "license"           => $this->car->license,
                "image"             => $this->car->image,
            ]
        ];
    }
}
