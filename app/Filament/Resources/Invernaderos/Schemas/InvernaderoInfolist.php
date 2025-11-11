<?php

namespace App\Filament\Resources\Invernaderos\Schemas;

use App\Helpers\ColorHelper;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;

class InvernaderoInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Información del Invernadero')
                    ->schema([
                        TextEntry::make('nombre'),
                        TextEntry::make('ubicacion')
                            ->color(fn (TextEntry $component) => ColorHelper::colorUbicacion($component->getState()))
                            ->badge(),
                        TextEntry::make('cultivo')
                            ->color(fn (TextEntry $component) => ColorHelper::colorCultivo($component->getState()))
                            ->badge(),
                    ])
                    ->columns(3)
                    ->columnSpanFull(),
            ]);
    }
}
