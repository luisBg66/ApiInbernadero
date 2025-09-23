<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ResumenInvernadero extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $totalInvernaderos = \App\Models\Invernadero::count();
        $totalSensores = \App\Models\Sensor::count();
        $totalMediciones = \App\Models\Temperatura::count() + \App\Models\Humedad::count() + \App\Models\Presion::count() + \App\Models\Iluminacion::count();
        $ultimaActualizacion = collect([
            \App\Models\Temperatura::max('updated_at'),
            \App\Models\Humedad::max('updated_at'),
            \App\Models\Presion::max('updated_at'),
            \App\Models\Iluminacion::max('updated_at'),
        ])->filter()->max();

        return [
            Stat::make('Invernaderos', $totalInvernaderos),
            Stat::make('Sensores', $totalSensores),
            Stat::make('Total Mediciones', $totalMediciones),
            Stat::make('Última actualización', $ultimaActualizacion ? date('d/m/Y H:i', strtotime($ultimaActualizacion)) : 'Sin datos'),
        ];
    }
}
