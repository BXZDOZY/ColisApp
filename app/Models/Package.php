<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    protected $fillable = [
        'tracking_number',
        'sender_name',
        'sender_phone',
        'sender_address',
        'receiver_name',
        'receiver_phone',
        'receiver_address',
        'weight',
        'type',
        'description',
        'status',
    ];

    public function histories()
    {
        return $this->hasMany(ShipmentHistory::class);
    }
}
