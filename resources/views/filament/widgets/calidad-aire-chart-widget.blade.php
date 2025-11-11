<x-filament::card>
    <div class="font-bold mb-2">Distribución de estados de calidad de aire</div>
    <canvas id="calidadAireChart"></canvas>
    <script>
        const ctx = document.getElementById('calidadAireChart').getContext('2d');
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: @json($labels),
                datasets: [{
                    data: @json($values),
                    backgroundColor: ['#22c55e', '#ef4444', '#eab308', '#a3a3a3'],
                }]
            },
            options: {
                plugins: {
                    legend: { position: 'bottom' }
                }
            }
        });
    </script>
</x-filament::card>
