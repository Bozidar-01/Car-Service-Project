<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserCars extends Model
{

    use HasFactory;

    protected $casts = [
        'engine' => 'array',
    ];

    public function CarModel()
    {
        return $this->belongsTo(CarModel::class, 'car_model_id');
    }
}
