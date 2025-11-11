<x-filament::card>
    <div class="font-bold mb-2">Últimos estados de calidad de aire</div>
    <ul>
        @foreach ($estados as $estado)
            <li class="flex justify-between py-1 border-b text-sm">
                <span>{{ $estado->estado }}</span>
                <span class="text-gray-400">{{ $estado->created_at->format('d/m/Y H:i') }}</span>
            </li>
        @endforeach
    </ul>
</x-filament::card>
