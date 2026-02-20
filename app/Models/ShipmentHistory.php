<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShipmentHistory extends Model
{
    protected $fillable = [
        'package_id',
        'status',
        'location',
        'details',
    ];

    public function package()
    {
        return $this->belongsTo(Package::class);
    }
}
