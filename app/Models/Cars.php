<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cars extends Model
{
    use HasFactory;

    protected $fillable = [
        'model',
        'year_of_production',
        'image',
        'car_brand_id',
    ];


    protected $casts = [
        'image' => 'array',
    ];

    public function CarBrand()
    {
        return $this->belongsTo(CarBrand::class);
    }
    public function carModels()
    {
        return $this->hasMany(CarModel::class, 'car_id', 'id');
    }

}
