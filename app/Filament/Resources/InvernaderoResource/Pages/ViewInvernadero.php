<?php

namespace App\Filament\Resources\InvernaderoResource\Pages;

use App\Filament\Resources\InvernaderoResource;
use Filament\Resources\Pages\ViewRecord;
use App\Filament\Actions\TurnOnFanAction;

class ViewInvernadero extends ViewRecord
{
    protected static string $resource = InvernaderoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            TurnOnFanAction::make(),
        ];
    }
}