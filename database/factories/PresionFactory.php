<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Presion;


class PresionFactory extends Factory
{   
    protected $model = Presion::class;

    public function definition(): array
    {
        return [
            'presions' => $this->faker->randomFloat(2, 10, 40),
        ];
    }
}
