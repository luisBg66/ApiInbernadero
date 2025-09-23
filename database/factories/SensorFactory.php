<?php

namespace Database\Factories;

use App\Models\Sensor;
use App\Models\Invernadero;
use Illuminate\Database\Eloquent\Factories\Factory;

class SensorFactory extends Factory
{
    protected $model = Sensor::class;

    public function definition(): array
    {
       
        return [
            'nombre' => $this->faker->word . ' Sensor',
            'tipo' => $this->faker->randomElement(['temperatura', 'humedad', 'presion', 'iluminacion']),
            'invernadero_id' => Invernadero::factory(),
        ];
    }
}
