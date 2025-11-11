<?php

namespace App\Filament\Widgets;

use App\Models\CalidadAire;
use Filament\Widgets\Widget;

class CalidadAireCountWidget extends Widget
{
    protected static string $view = 'filament.widgets.calidad-aire-count-widget';

    public function getData(): array
    {
        return [
            'count' => CalidadAire::count(),
        ];
    }
}
