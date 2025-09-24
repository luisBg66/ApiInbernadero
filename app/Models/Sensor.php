<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Sensor extends Model
{   use HasFactory;
    protected $fillable = ['nombre', 'tipo', 'invernadero_id'];

    public function invernadero()
    {
        return $this->belongsTo(Invernadero::class);
    }
}
