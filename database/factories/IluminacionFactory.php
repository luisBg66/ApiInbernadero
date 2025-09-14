<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class IluminacionFactory extends Factory
{
    public function definition(): array
    {
        return [
            'iluminacions' => $this->faker->randomFloat(2, 0, 1000),
        ];
    }
}
