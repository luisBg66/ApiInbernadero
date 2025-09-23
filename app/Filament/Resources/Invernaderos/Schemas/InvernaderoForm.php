<?php

namespace App\Filament\Resources\Invernaderos\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class InvernaderoForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nombre')
                    ->required(),
                TextInput::make('ubicacion')
                    ->required(),
                TextInput::make('cultivo')
                    ->required(),
            ]);
    }
}
