<?php

namespace App\Filament\Resources\Invernaderos\Pages;

use App\Filament\Resources\Invernaderos\InvernaderoResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditInvernadero extends EditRecord
{
    protected static string $resource = InvernaderoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
