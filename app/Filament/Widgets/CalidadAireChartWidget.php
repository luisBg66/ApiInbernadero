<?php

namespace App\Filament\Widgets;

use App\Models\CalidadAire;
use Filament\Widgets\Widget;

class CalidadAireChartWidget extends Widget
{
    protected static string $view = 'filament.widgets.calidad-aire-chart-widget';

    public function getData(): array
    {
        $data = CalidadAire::selectRaw('estado, COUNT(*) as total')
            ->groupBy('estado')
            ->pluck('total', 'estado')
            ->toArray();

        return [
            'labels' => array_keys($data),
            'values' => array_values($data),
        ];
    }
}
