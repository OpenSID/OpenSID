@extends('theme::layouts.full-content')
@push('styles')
<style>
    .dataTables_wrapper .dataTables_length,
    .dataTables_wrapper .dataTables_filter { @apply mb-4; }
    .dataTables_wrapper .dataTables_length label,
    .dataTables_wrapper .dataTables_filter label { @apply text-sm font-medium text-gray-700 dark:text-gray-300; }
    .dataTables_wrapper .dataTables_length select,
    .dataTables_wrapper .dataTables_filter input { @apply bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 shadow-sm px-3 py-1.5 ml-2 focus:ring-blue-500 focus:border-blue-500; border-radius: 0 !important; }
    .dataTables_wrapper .dataTables_info { @apply text-sm text-gray-600 dark:text-gray-400 pt-4; }
    .dataTables_wrapper .dataTables_paginate { @apply pt-4; }
    .dataTables_wrapper .dataTables_paginate .paginate_button { @apply inline-flex items-center justify-center px-4 py-2 text-sm font-medium border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-600 dark:text-gray-300 transition-colors duration-200; margin-left: -1px; border-radius: 0 !important; }
    .dataTables_wrapper .dataTables_paginate .paginate_button:hover { @apply bg-gray-100 dark:bg-gray-700 z-10; }
    .dataTables_wrapper .dataTables_paginate .paginate_button.current { @apply bg-blue-600 text-white border-blue-600 dark:bg-blue-500 dark:border-blue-500 z-20; }
    .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover { @apply bg-blue-700 dark:bg-blue-600; }
    .dataTables_wrapper .dataTables_paginate .paginate_button.disabled { @apply bg-gray-100 dark:bg-gray-700 text-gray-400 dark:text-gray-500 cursor-not-allowed opacity-50; }
    .dataTables_wrapper .dataTables_paginate .paginate_button:first-child { margin-left: 0; }
</style>
@endpush
@section('content')
<div class="bg-[var(--bg-color-card)] p-6 shadow-xl border border-[var(--border-color)] -mt-16 relative z-10">
    <nav role="navigation" aria-label="navigation" class="breadcrumb text-sm mb-4">
        <ol class="flex items-center justify-center space-x-2 text-gray-500 dark:text-gray-400">
            <li><a href="{{ site_url() }}" class="hover:underline hover:text-blue-600">Beranda</a></li>
            <li><span class="mx-2">/</span></li>
            <li><a href="{{ site_url('analisis') }}" class="hover:underline hover:text-blue-600">Data Analisis</a></li>
            <li><span class="mx-2">/</span></li>
            <li aria-current="page" class="font-medium text-gray-700 dark:text-gray-300">Jawaban Indikator</li>
        </ol>
    </nav>
    <div class="flex items-center mt-6">
        <div class="flex-grow border-t border-gray-300 dark:border-gray-700"></div>
        <h1 id="indikator-title" class="flex-shrink px-4 text-xl lg:text-2xl font-bold uppercase text-center">Jawaban Indikator</h1>
        <div class="flex-grow border-t border-gray-300 dark:border-gray-700"></div>
    </div>
    <div id="chart-container" class="w-full h-96 border border-gray-200 dark:border-gray-700 my-8 p-2">
        <div class="flex items-center justify-center h-full text-gray-500">Memuat Grafik...</div>
    </div>
    <div class="overflow-x-auto">
        <h3 class="font-bold text-lg mb-4">Tabel Rincian Jawaban</h3>
        <table class="w-full text-sm border-collapse" id="tabel-jawaban">
            <thead class="bg-gray-100 dark:bg-gray-700/50">
                <tr>
                    <th class="p-3 text-center text-xs font-bold uppercase tracking-wider w-12 border border-gray-300 dark:border-gray-600">No.</th>
                    <th class="p-3 text-left text-xs font-bold uppercase tracking-wider border border-gray-300 dark:border-gray-600">Jawaban</th>
                    <th class="p-3 text-center text-xs font-bold uppercase tracking-wider border border-gray-300 dark:border-gray-600">Jumlah Responden</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>
@endsection
@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const params = new URLSearchParams(window.location.search);
        const indikatorId = params.get('filter[id_indikator]');
        let chart;
        fetch(`{{ route('api.analisis.indikator') }}?filter[id]=${indikatorId}`)
            .then(res => res.json())
            .then(data => {
                if (data.data.length > 0) {
                    document.getElementById('indikator-title').textContent = data.data[0].attributes.indikator;
                }
            });
        const tabelJawaban = $('#tabel-jawaban').DataTable({
            processing: true,
            serverSide: true,
            searching: false,
            ajax: {
                url: '{{ route('api.analisis.jawaban') }}',
                method: 'POST',
                data: d => ({
                    ...@json($params),
                    "page[size]": d.length,
                    "page[number]": (d.start / d.length) + 1,
                }),
                dataSrc: json => {
                    json.recordsTotal = json.meta.pagination.total;
                    json.recordsFiltered = json.meta.pagination.total;
                    return json.data;
                }
            },
            columns: [
                { data: null, searchable: false, orderable: false, className: 'text-center p-2 border border-gray-300 dark:border-gray-600' },
                { data: 'attributes.jawaban', className: 'whitespace-normal p-2 border border-gray-300 dark:border-gray-600' },
                { data: 'attributes.jml', className: 'text-center p-2 border border-gray-300 dark:border-gray-600' }
            ],
            order: [],
            language: { url: "{{ asset('bootstrap/js/dataTables.indonesian.lang') }}" },
            drawCallback: function() {
                var api = this.api();
                api.column(0, { search: 'applied', order: 'applied' }).nodes().each(function(cell, i) {
                    cell.innerHTML = api.page.info().start + i + 1;
                });
                const chartData = api.rows().data().toArray().map(row => ({
                    name: row.attributes.jawaban,
                    y: row.attributes.jml
                }));
                renderChart(chartData);
            }
        });
        function renderChart(data) {
            const isDarkMode = document.documentElement.classList.contains('dark');
            const options = {
                chart: { type: 'column', backgroundColor: isDarkMode ? '#1f2937' : 'transparent' },
                title: { text: null },
                xAxis: { type: 'category', labels: { style: { color: isDarkMode ? '#d1d5db' : '#333333' } } },
                yAxis: { title: { text: 'Jumlah Responden', style: { color: isDarkMode ? '#d1d5db' : '#333333' } }, labels: { style: { color: isDarkMode ? '#d1d5db' : '#333333' } } },
                plotOptions: { series: { colorByPoint: true, dataLabels: { enabled: true, format: '{point.y}' } } },
                series: [{ name: 'Jumlah Responden', data: data }],
                legend: { enabled: false },
                credits: { enabled: false }
            };
            if (chart) chart.destroy();
            chart = Highcharts.chart('chart-container', options);
        }
        const observer = new MutationObserver(() => {
            if(chart && chart.series[0].data.length > 0) renderChart(chart.series[0].options.data);
        });
        observer.observe(document.documentElement, { attributes: true, attributeFilter: ['class'] });
    });
</script>
@endpush