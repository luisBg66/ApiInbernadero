<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;

class HumedadChart extends ChartWidget
{
    protected ?string $heading = 'Humedad Chart';

    protected function getData(): array
    {
        return [
            $labels = \App\Models\Humedad::pluck('created_at')->map(fn ($date) => $date->format('H:i:s'))->toArray(),
            $data = \App\Models\Humedad::pluck('humedad')->toArray(),
            'datasets' => [
                [
                    'label' => 'Humedad',
                    'data' => $data,
                    'backgroundColor' => 'rgba(75, 192, 192, 0.5)',
                    'borderColor' => 'rgba(75, 192, 192, 1)',
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
