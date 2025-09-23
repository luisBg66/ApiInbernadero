<?php

namespace App\Filament\Resources\Invernaderos\Pages;

use App\Filament\Resources\Invernaderos\InvernaderoResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListInvernaderos extends ListRecords
{
    protected static string $resource = InvernaderoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
