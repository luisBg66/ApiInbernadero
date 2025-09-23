<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
    \App\Models\User::factory(10)->create();
    \App\Models\Invernadero::factory(5)->create();
    \App\Models\Sensor::factory(20)->create();
    \App\Models\Temperatura::factory(30)->create();
    \App\Models\Presion::factory(30)->create();
    \App\Models\Iluminacion::factory(30)->create();
    \App\Models\Humedad::factory(30)->create();
    }
}
