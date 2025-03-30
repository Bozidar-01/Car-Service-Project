<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarBrand extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'logo',
        'country'
    ];

    public function cars()
    {
        return $this->hasMany(Cars::class, 'car_brand_id', 'id');
    }

}
