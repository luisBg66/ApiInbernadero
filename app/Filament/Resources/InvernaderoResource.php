<?php

namespace App\Filament\Resources;

use App\Filament\Actions\TurnOnFanAction;
use App\Filament\Resources\InvernaderoResource\Pages;
use App\Models\Invernadero;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action;

class InvernaderoResource extends Resource
{
    protected static ?string $model = Invernadero::class;

    protected static ?string $navigationIcon = 'heroicon-o-home';
    
    protected static ?string $navigationLabel = 'Invernaderos';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nombre')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('ubicacion')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('descripcion')
                    ->maxLength(65535)
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nombre')
                    ->searchable(),
                Tables\Columns\TextColumn::make('ubicacion')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Action::make('encenderVentilador')
                    ->label('Encender Ventilador')
                    ->icon('heroicon-o-play')
                    ->color('success')
                    ->action(function () {
                        // Enviar el evento al canal de WebSocket
                        Reverb::broadcast('fan-control', 'turnOnFan', ['action' => 'on']);

                        Notification::make()
                            ->title('Ventilador Activado')
                            ->success()
                            ->send();
                    }),
            ]);
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListInvernaderos::route('/'),
            'create' => Pages\CreateInvernadero::route('/create'),
            'edit' => Pages\EditInvernadero::route('/{record}/edit'),
            'view' => Pages\ViewInvernadero::route('/{record}'),
        ];
    }    
}