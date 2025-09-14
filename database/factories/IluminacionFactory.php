<?php

namespace Database\Factories;


use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Iluminacion;

class IluminacionFactory extends Factory
{
    protected $model = Iluminacion::class;
    public function definition(): array

    {
        
        return [
            'iluminacions' => $this->faker->randomFloat(2, 0, 1000),
        ];
    }
}
