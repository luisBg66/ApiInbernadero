<?php

namespace App\Filament\Resources\InvernaderoResource\Pages;

use App\Filament\Resources\InvernaderoResource;
use Filament\Resources\Pages\ViewRecord;
use App\Filament\Actions\TurnOnFanAction;
use App\Filament\Widgets\CalidadAireCountWidget;
use App\Filament\Widgets\UltimosEstadosCalidadAireWidget;
use App\Filament\Widgets\CalidadAireChartWidget;

class ViewInvernadero extends ViewRecord
{
    protected static string $resource = InvernaderoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            TurnOnFanAction::make(),
        ];
    }

    protected function getFooterWidgets(): array
    {
        return [
            CalidadAireCountWidget::class,
            UltimosEstadosCalidadAireWidget::class,
            CalidadAireChartWidget::class,
        ];
    }
}