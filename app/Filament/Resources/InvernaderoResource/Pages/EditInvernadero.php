<?php

namespace App\Filament\Resources\InvernaderoResource\Pages;

use App\Filament\Resources\InvernaderoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Actions\TurnOnFanAction;

class EditInvernadero extends EditRecord
{
    protected static string $resource = InvernaderoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            TurnOnFanAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}