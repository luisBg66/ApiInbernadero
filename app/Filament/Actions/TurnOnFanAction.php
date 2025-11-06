<?php

namespace App\Filament\Actions;

use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Reverb\Server\Reverb;

class TurnOnFanAction extends Action
{
    public static function make(): static
    {
        return parent::make()
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
            });
    }
}