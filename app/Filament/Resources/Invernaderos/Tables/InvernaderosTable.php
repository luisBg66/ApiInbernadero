<?php

namespace App\Filament\Resources\Invernaderos\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use App\Models\Invernadero;
use Filament\Tables\Filters\OptionsFilter;
use Filament\Support\Facades\FilamentColor;
class InvernaderosTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nombre')
                    ->searchable(),
                TextColumn::make('ubicacion')
                    ->searchable(),
                TextColumn::make('cultivo')
                    ->searchable()
                    ->color(fn (string $state): string => match ($state) {
                        'Tomate' => 'success',
                        'Lechuga' => 'success',
                        'Pepino' => 'primary',
                        'Fresa' => 'pink',
                        'Fondo' => 'gray',
                        'Transferencia' => 'secondary',
                        default => 'danger',

                        'primary' => '#3B82F6',  // Azul
                        'pink' => '#EC4899',  // Rosa
                    })
                    ->badge(),
            ])
            ->filters([
                SelectFilter::make('cultivo')
                    ->label('Cultivo')
                    ->options(fn () => Invernadero::query()->distinct()->pluck('cultivo', 'cultivo')->toArray()),
                SelectFilter::make('ubicacion')
                    ->label('Ubicación')
                    ->options(fn () => Invernadero::query()->distinct()->pluck('ubicacion', 'ubicacion')->toArray()),
                
                 
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
