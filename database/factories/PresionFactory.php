<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PresionFactory extends Factory
{
    public function definition(): array
    {
        return [
            'presions' => $this->faker->randomFloat(2, 900, 1100),
        ];
    }
}
