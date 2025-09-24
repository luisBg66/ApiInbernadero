<x-filament-widgets::widget>
    <x-filament::section>
        <h3 class="text-lg font-bold mb-2">Últimas mediciones de sensores</h3>
        <table class="min-w-full bg-white border border-gray-200">
            <thead>
                <tr>
                    <th class="px-2 py-1 border">Tipo</th>
                    <th class="px-2 py-1 border">Valor</th>
                    <th class="px-2 py-1 border">Fecha/Hora</th>
                </tr>
            </thead>
            <tbody>
                @foreach($rows as $row)
                    <tr>
                        <td class="px-2 py-1 border">{{ $row['tipo'] }}</td>
                        <td class="px-2 py-1 border">{{ $row['valor'] }}</td>
                        <td class="px-2 py-1 border">{{ $row['fecha'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </x-filament::section>
</x-filament-widgets::widget>
