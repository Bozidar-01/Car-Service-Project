<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class completedServices extends Model
{
    protected $fillable = [
        'user_car_id',
        'user_id',
        'service',
    ];

    protected $casts = [
        'service' => 'array',
    ];

}
