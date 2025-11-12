<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;
use Filament\Notifications\Notification;
use App\Events\FanControl;

class FanControlWidget extends Widget
{
    protected static ?string $heading = 'Control de Ventilador';

    protected static ?int $sort = 3;

    // Inicializar la propiedad de vista (no estática) para evitar errores en PHP 8.3
    protected string $view = 'filament.widgets.fan-control-widget';

    // Permite refrescar el componente desde JS (Livewire.emit('refreshFan'))
    protected $listeners = [
        'refreshFan' => '$refresh',
    ];

    public bool $fanStatus = false;

    protected function getViewData(): array
    {
        return [
            'fanStatus' => $this->fanStatus,
        ];
    }

    public function mount()
    {
        // Por simplicidad, inicializamos en false. Si tienes un modelo, puedes cargar el estado actual aquí.
        $this->fanStatus = false;
    }

    public function toggleFan()
    {
        $this->fanStatus = !$this->fanStatus;

        event(new FanControl($this->fanStatus ? 'on' : 'off'));

        Notification::make()
            ->title($this->fanStatus ? 'Ventilador encendido' : 'Ventilador apagado')
            ->success()
            ->send();
    }

    public function turnOnFan()
    {
        $this->fanStatus = true;
        event(new FanControl('on'));

        Notification::make()
            ->title('Ventilador encendido')
            ->success()
            ->send();
    }

    public function turnOffFan()
    {
        $this->fanStatus = false;
        event(new FanControl('off'));

        Notification::make()
            ->title('Ventilador apagado')
            ->success()
            ->send();
    }
}
