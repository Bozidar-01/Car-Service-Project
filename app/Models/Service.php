<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable = [
        'user_car_id',
        'user_id',
        'default_service_id',
        'price',
        'status',
        'last_serviced',
        'next_service',
        'product_used',
    ];

    public function userCar()
    {
        return $this->belongsTo(UserCars::class, 'user_car_id');
    }

    public function defaultService()
    {
        return $this->belongsTo(DefaultService::class, 'default_service_id');
    }
}
