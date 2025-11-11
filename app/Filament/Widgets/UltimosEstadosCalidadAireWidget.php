<?php

namespace App\Filament\Widgets;

use App\Models\CalidadAire;
use Filament\Widgets\Widget;

class UltimosEstadosCalidadAireWidget extends Widget
{
    protected static string $view = 'filament.widgets.ultimos-estados-calidad-aire-widget';

    public function getData(): array
    {
        return [
            'estados' => CalidadAire::latest()->take(5)->get(),
        ];
    }
}
