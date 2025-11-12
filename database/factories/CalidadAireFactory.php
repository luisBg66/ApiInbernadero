<?php

namespace Database\Factories;

use App\Models\CalidadAire;
use Illuminate\Database\Eloquent\Factories\Factory;

class CalidadAireFactory extends Factory
{
    protected $model = CalidadAire::class;

    public function definition(): array
    {
        return [
                    'calidad_aire' => $this->faker->randomFloat(2, 0, 1000),
        ];
    }
}
