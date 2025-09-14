<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Humedad extends Model
{
    use HasFactory;
    protected $fillable = ['humedad'];
}
