<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PasswordReset extends Model
{
    protected $fillable=[
        "code",
        "email",
        "expired_at",
    ];

    protected $casts = [
        'expired_at' => 'datetime',
    ];

    public function user(){

        return $this->belongsTo(User::class, "email", "email");
    }
    public function driver(){

        return $this->belongsTo(Driver::class, "email", "email");
    }
}
