<?php

namespace App\Filament\Actions;

use App\Events\FanControl;
use Filament\Actions\Action;
use Filament\Notifications\Notification;

class TurnOnFanAction extends Action
{
    public static function make(): static
    {
        return parent::make()
            ->label('Encender Ventilador')
            ->icon('heroicon-o-play')
            ->color('success')
            ->action(function () {
                // Emitir evento que será transmitido por el driver de broadcasting configurado
                event(new FanControl('on'));

                Notification::make()
                    ->title('Ventilador Activado')
                    ->success()
                    ->send();
            });
    }
}