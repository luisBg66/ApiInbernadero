<?php

namespace App\Filament\Resources\Invernaderos\RelationManagers;

use App\Filament\Resources\Invernaderos\InvernaderoResource;
use Filament\Actions\CreateAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;

class SensorRelationManager extends RelationManager
{
    protected static string $relationship = 'Sensor';

    protected static ?string $relatedResource = InvernaderoResource::class;

    public function table(Table $table): Table
    {
        return $table
            ->headerActions([
                CreateAction::make(),
            ]);
    }
}
