<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarModel extends Model
{

    use HasFactory;
    protected $table = 'car_model';

    protected $fillable = [
        'manufacture_date',
    ];
    protected function casts(): array
    {
        return [
            'engines' => 'array',
            'colors' => 'array',

        ];
    }

    public function Cars()
    {
        return $this->belongsTo(Cars::class, 'car_id');
    }
}
