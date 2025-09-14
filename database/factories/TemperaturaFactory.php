<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class TemperaturaFactory extends Factory
{
    public function definition(): array
    {
        return [
            'temperatura' => $this->faker->randomFloat(2, 10, 40),
        ];
    }
}
