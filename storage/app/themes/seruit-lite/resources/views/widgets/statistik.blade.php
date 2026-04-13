@php
    defined('BASEPATH') OR exit('No direct script access allowed');
    $gradient_lite = 'from-green-500 to-teal-500';
@endphp

<div class="bg-white dark:bg-gray-800 shadow-md border border-gray-100 dark:border-gray-700 overflow-hidden flex flex-col">
    <div class="p-4 bg-gradient-to-r {{ $gradient_lite }} text-white">
        <h3 class="font-bold text-sm uppercase tracking-wider flex items-center">
            <i class="fas fa-chart-pie mr-3"></i>{{ $judul_widget }}
        </h3>
    </div>

    <div class="p-4">
        <div id="container_widget_{{ $widget['id'] }}" class="h-64 w-full"></div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const isDarkMode = document.documentElement.classList.contains('dark');
    
    Highcharts.chart('container_widget_{{ $widget['id'] }}', {
        chart: {
            type: 'column',
            backgroundColor: 'transparent',
            style: { fontFamily: 'inherit' }
        },
        title: { text: null },
        xAxis: {
            categories: [
                @foreach ($stat_widget as $data)
                    @if ($data['jumlah'] > 0 && $data['nama'] != 'JUMLAH') '{{ $data['nama'] }}', @endif
                @endforeach
            ],
            labels: { style: { color: isDarkMode ? '#9ca3af' : '#6b7280', fontSize: '9px' } }
        },
        yAxis: {
            title: { text: null },
            labels: { style: { color: isDarkMode ? '#9ca3af' : '#6b7280' } },
            gridLineColor: isDarkMode ? '#374151' : '#f3f4f6'
        },
        legend: { enabled: false },
        credits: { enabled: false },
        plotOptions: {
            column: {
                color: '#14b8a6',
                borderWidth: 0,
                dataLabels: {
                    enabled: true,
                    style: { color: isDarkMode ? '#d1d5db' : '#374151', fontSize: '10px', textOutline: 'none' }
                }
            }
        },
        series: [{
            name: 'Jiwa',
            data: [
                @foreach ($stat_widget as $data)
                    @if ($data['jumlah'] > 0 && $data['nama'] != 'JUMLAH') {{ $data['jumlah'] }}, @endif
                @endforeach
            ]
        }]
    });
});
</script>
@endpush