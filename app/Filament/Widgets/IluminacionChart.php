<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;

class IluminacionChart extends ChartWidget
{
    protected ?string $heading = 'Iluminacion Chart';

    protected function getData(): array
    {
        $labels = \App\Models\Iluminacion::pluck('created_at')->map(fn ($date) => $date->format('H:i:s'))->toArray();
        $data = \App\Models\Iluminacion::pluck('iluminacions')->toArray();

        return [
            'datasets' => [
                [
                    'label' => 'Iluminacion',
                    'data' => $data,
                    'backgroundColor' => 'rgba(54, 162, 235, 0.5)',
                    'borderColor' => 'rgba(54, 162, 235, 1)',
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