<?php

namespace Database\Factories;


use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Humedad;

class HumedadFactory extends Factory
{
     protected $model = Humedad::class;
    public function definition(): array
    {
        return [
            'humedad' => $this->faker->randomFloat(2, 0, 100),
        ];
    }
}
