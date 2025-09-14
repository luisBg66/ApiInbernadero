<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Temperatura;


class TemperaturaFactory extends Factory
{  
    protected $model = Temperatura::class;

    public function definition(): array
    {
        return [
            'temperatura' => $this->faker->randomFloat(2, 10, 40),
        ];
    }
}
