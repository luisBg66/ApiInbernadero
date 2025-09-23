<?php

namespace App\Filament\Resources\Invernaderos\Pages;

use App\Filament\Resources\Invernaderos\InvernaderoResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewInvernadero extends ViewRecord
{
    protected static string $resource = InvernaderoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }

    protected function getFooterWidgets(): array
    {
        return [
            \App\Filament\Widgets\UltimasMedidasWidget::class,
            \App\Filament\Widgets\TemperaturaChart::class,
            \App\Filament\Widgets\HumedadChart::class,
            \App\Filament\Widgets\PrecionChart::class,
            \App\Filament\Widgets\IluminacionChart::class,
        ];
    }
}
