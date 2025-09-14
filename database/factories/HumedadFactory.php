<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class HumedadFactory extends Factory
{
    public function definition(): array
    {
        return [
            'humedad' => $this->faker->randomFloat(2, 0, 100),
        ];
    }
}
