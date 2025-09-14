<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;

class TemperaturaChart extends ChartWidget
{
    protected ?string $heading = 'Temperatura Chart';

    protected function getData(): array
    {
        return [
            $labels = \App\Models\Temperatura::pluck('created_at')->map(fn ($date) => $date->format('H:i:s'))->toArray(),
            $data = \App\Models\Temperatura::pluck('temperatura')->toArray(),
            'datasets' => [
                [
                    'label' => 'Temperatura',
                    'data' => $data,
                    'backgroundColor' => 'rgba(255, 99, 132, 0.5)',
                    'borderColor' => 'rgba(255, 99, 132, 1)',
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
