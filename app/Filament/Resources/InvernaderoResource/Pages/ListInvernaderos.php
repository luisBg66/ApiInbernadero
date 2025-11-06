<?php

namespace App\Filament\Resources\InvernaderoResource\Pages;

use App\Filament\Resources\InvernaderoResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Actions\TurnOnFanAction;

class ListInvernaderos extends ListRecords
{
    protected static string $resource = InvernaderoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            TurnOnFanAction::make(),
        ];
    }
}