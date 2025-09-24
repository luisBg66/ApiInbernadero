<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;

class TablaMedidasWidget extends Widget
{
    protected string $view = 'filament.widgets.tabla-medidas-widget';

    public function getViewData(): array
    {
        $rows = collect([
            [
                'tipo' => 'Temperatura',
                'valor' => optional(\App\Models\Temperatura::latest('created_at')->first())->temperatura,
                'fecha' => optional(\App\Models\Temperatura::latest('created_at')->first())->created_at?->format('d/m/Y H:i'),
            ],
            [
                'tipo' => 'Humedad',
                'valor' => optional(\App\Models\Humedad::latest('created_at')->first())->humedad,
                'fecha' => optional(\App\Models\Humedad::latest('created_at')->first())->created_at?->format('d/m/Y H:i'),
            ],
            [
                'tipo' => 'Presión',
                'valor' => optional(\App\Models\Presion::latest('created_at')->first())->presions,
                'fecha' => optional(\App\Models\Presion::latest('created_at')->first())->created_at?->format('d/m/Y H:i'),
            ],
            [
                'tipo' => 'Iluminación',
                'valor' => optional(\App\Models\Iluminacion::latest('created_at')->first())->iluminacions,
                'fecha' => optional(\App\Models\Iluminacion::latest('created_at')->first())->created_at?->format('d/m/Y H:i'),
            ],
        ]);
        return compact('rows');
    }
}
