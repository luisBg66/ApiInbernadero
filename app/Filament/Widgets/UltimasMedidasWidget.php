<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class UltimasMedidasWidget extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $ultimaTemp = \App\Models\Temperatura::latest('created_at')->first();
        $ultimaHum = \App\Models\Humedad::latest('created_at')->first();
        $ultimaPres = \App\Models\Presion::latest('created_at')->first();
        $ultimaIlum = \App\Models\Iluminacion::latest('created_at')->first();

        return [
            Stat::make('Última Temperatura', $ultimaTemp ? $ultimaTemp->temperatura . ' °C' : 'Sin datos')
                ->description($ultimaTemp ? $ultimaTemp->created_at->format('d/m/Y H:i') : ''),
            Stat::make('Última Humedad', $ultimaHum ? $ultimaHum->humedad . ' %' : 'Sin datos')
                ->description($ultimaHum ? $ultimaHum->created_at->format('d/m/Y H:i') : ''),
            Stat::make('Última Presión', $ultimaPres ? $ultimaPres->presions . ' hPa' : 'Sin datos')
                ->description($ultimaPres ? $ultimaPres->created_at->format('d/m/Y H:i') : ''),
            Stat::make('Última Iluminación', $ultimaIlum ? $ultimaIlum->iluminacions . ' lx' : 'Sin datos')
                ->description($ultimaIlum ? $ultimaIlum->created_at->format('d/m/Y H:i') : ''),
        ];
    }
}
