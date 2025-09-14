<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;

class PrecionChart extends ChartWidget
{
    protected ?string $heading = 'Precion Chart';

    protected function getData(): array
    {
        return [
            $labels = \App\Models\Presion::pluck('created_at')->map(fn ($date) => $date->format('H:i:s'))->toArray(),
            $data = \App\Models\Presion::pluck('presions')->toArray(),
            'datasets' => [
                [
                    'label' => 'Presion',
                    'data' => $data,
                    'backgroundColor' => 'rgba(255, 206, 86, 0.5)',
                    'borderColor' => 'rgba(255, 206, 86, 1)',
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
