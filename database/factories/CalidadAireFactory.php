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
            'estado' => $this->faker->randomElement(['bueno', 'malo', 'regular']),
        ];
    }
}
