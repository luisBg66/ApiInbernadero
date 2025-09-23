<?php

namespace Database\Factories;

use App\Models\Invernadero;
use Illuminate\Database\Eloquent\Factories\Factory;

class InvernaderoFactory extends Factory
{
    protected $model = Invernadero::class;

    public function definition(): array
    {
        return [
            'nombre' => $this->faker->company . ' Invernadero',
            'ubicacion' => $this->faker->city,
            'cultivo' => $this->faker->randomElement(['Tomate', 'Lechuga', 'Pepino', 'Fresa', 'Pimiento']),
        ];
    }
}
