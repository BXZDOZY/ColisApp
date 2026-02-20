<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupportTicket extends Model
{
    protected $fillable = [
        'customer_name',
        'email',
        'phone',
        'package_id',
        'subject',
        'message',
        'admin_response',
        'status',
    ];

    public function package()
    {
        return $this->belongsTo(Package::class);
    }
}
