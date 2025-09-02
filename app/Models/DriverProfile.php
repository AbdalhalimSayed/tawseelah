<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DriverProfile extends Model
{
    protected $fillable = [
        "driver_id",
        "card_number",
        "country",
        "state",
        "city",
        "license",
        "id_card",
    ];

    public function driver(){
        return $this->belongsTo(Driver::class);
    }
}
