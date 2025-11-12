<?php

namespace App\Filament\Widgets;

use App\Models\CalidadAire;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class CalidadAireCountWidget extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Registros de calidad de aire', CalidadAire::count()),
        ];
    }
}
