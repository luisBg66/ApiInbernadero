<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Invernadero extends Model
{   
    use HasFactory;
    protected $fillable = ['nombre', 'ubicacion', 'cultivo'];

    public function sensores()
    {
        return $this->hasMany(Sensor::class);
    }
}
