<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mods extends Model
{
    public function userCar()
    {
        return $this->belongsTo(UserCars::class, 'user_car_id');
    }
}
