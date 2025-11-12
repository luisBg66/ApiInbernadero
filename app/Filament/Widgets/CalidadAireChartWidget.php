<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;

class CalidadAireChartWidget extends ChartWidget
{
    protected ?string $heading = 'Calidad Aire Chart';

    protected function getData(): array
    {
        $labels = \App\Models\CalidadAire::pluck('created_at')->map(fn ($date) => $date->format('H:i:s'))->toArray();
        $data = \App\Models\CalidadAire::pluck('calidad_aire')->toArray();

        return [
            'datasets' => [
                [
                    'label' => 'Calidad Aire',
                    'data' => $data,
                    'backgroundColor' => 'rgba(255, 193, 7, 0.5)',
                    'borderColor' => 'rgba(255, 193, 7, 1)',
                    'borderWidth' => 1,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
