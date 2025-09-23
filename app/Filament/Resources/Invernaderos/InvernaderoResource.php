<?php

namespace App\Filament\Resources\Invernaderos;

use App\Filament\Resources\Invernaderos\Pages\CreateInvernadero;
use App\Filament\Resources\Invernaderos\Pages\EditInvernadero;
use App\Filament\Resources\Invernaderos\Pages\ListInvernaderos;
use App\Filament\Resources\Invernaderos\Pages\ViewInvernadero;
use App\Filament\Resources\Invernaderos\Schemas\InvernaderoForm;
use App\Filament\Resources\Invernaderos\Schemas\InvernaderoInfolist;
use App\Filament\Resources\Invernaderos\Tables\InvernaderosTable;
use App\Models\Invernadero;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class InvernaderoResource extends Resource
{
    protected static ?string $model = Invernadero::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'Invernadero';

    public static function form(Schema $schema): Schema
    {
        return InvernaderoForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return InvernaderoInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return InvernaderosTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListInvernaderos::route('/'),
            'create' => CreateInvernadero::route('/create'),
            'view' => ViewInvernadero::route('/{record}'),
            'edit' => EditInvernadero::route('/{record}/edit'),
        ];
    }
}
