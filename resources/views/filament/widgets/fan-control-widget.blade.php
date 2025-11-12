<x-filament-widgets::widget>
    <x-filament::section>
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                    Control de Ventilador
                </h3>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    Estado actual:
                    <span class="font-semibold {{ $fanStatus ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                        {{ $fanStatus ? 'ENCENDIDO' : 'APAGADO' }}
                    </span>
                </p>
            </div>

            <div class="flex gap-3">
                <button wire:click="turnOnFan" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-colors duration-200 flex items-center gap-2" {{ $fanStatus ? 'disabled' : '' }}>
                    Encender
                </button>

                <button wire:click="turnOffFan" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg transition-colors duration-200 flex items-center gap-2" {{ !$fanStatus ? 'disabled' : '' }}>
                    Apagar
                </button>

                <button wire:click="toggleFan" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors duration-200 flex items-center gap-2">
                    Alternar
                </button>
            </div>
        </div>

        <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
            <div class="flex items-center gap-3">
                <div class="w-4 h-4 rounded-full {{ $fanStatus ? 'bg-green-500 animate-pulse' : 'bg-gray-400' }}"></div>
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    {{ $fanStatus ? 'El ventilador está girando...' : 'El ventilador está parado' }}
                </p>
            </div>
        </div>
    </x-filament::section>

    {{-- Suscripción rápida a Echo (funciona sin recompilar assets) --}}
    <script>
        document.addEventListener('livewire:load', function () {
            try {
                if (window.Echo) {
                    window.Echo.channel('fan-control')
                        .listen('turnOnFan', function (e) {
                            console.log('Evento recibido (turnOnFan):', e);
                            if (window.Livewire) {
                                window.Livewire.emit('refreshFan');
                            }
                        });
                } else {
                    console.log('Echo no está disponible en el cliente.');
                }
            } catch (err) {
                console.error('Error suscribiéndose a Echo:', err);
            }
        });
    </script>
</x-filament-widgets::widget>
