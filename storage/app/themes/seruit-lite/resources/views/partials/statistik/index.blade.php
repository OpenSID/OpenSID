@extends('theme::layouts.full-content')

@push('styles')
    <style>
        .dataTables_wrapper .dataTables_length select,
        .dataTables_wrapper .dataTables_filter input {
            @apply bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 shadow-sm px-3 py-1.5 ml-2 focus:ring-blue-500 focus:border-blue-500;
            border-radius: 0 !important;
        }
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

@php
    $daftar_statistik = daftar_statistik();
    $slug_aktif_normalized = str_replace('_', '-', $slug_aktif);
    $s_links = [
        ['target' => 'penduduk', 'label' => 'Statistik Penduduk', 'icon' => 'fa-chart-pie', 'submenu' => $daftar_statistik['penduduk']],
        ['target' => 'keluarga', 'label' => 'Statistik Keluarga', 'icon' => 'fa-chart-bar', 'submenu' => $daftar_statistik['keluarga']],
        ['target' => 'bantuan', 'label' => 'Statistik Bantuan', 'icon' => 'fa-chart-line', 'submenu' => $daftar_statistik['bantuan']],
        ['target' => 'lainnya', 'label' => 'Statistik Lainnya', 'icon' => 'fa-chart-area', 'submenu' => $daftar_statistik['lainnya']],
    ];
@endphp

@section('content')
    <div class="bg-[var(--bg-color-card)] p-6 shadow-xl border border-[var(--border-color)] -mt-16 relative z-10">
        <nav role="navigation" aria-label="navigation" class="breadcrumb text-sm mb-4">
            <ol class="flex items-center justify-center space-x-2 text-gray-500 dark:text-gray-400">
                <li><a href="{{ site_url() }}" class="hover:underline hover:text-blue-600">Beranda</a></li>
                <li><span class="mx-2">/</span></li>
                <li aria-current="page" class="font-medium text-gray-700 dark:text-gray-300">{{ $heading }}</li>
            </ol>
        </nav>

        <div class="flex items-center mt-6 mb-8">
            <div class="flex-grow border-t border-gray-300 dark:border-gray-700"></div>
            <h1 class="flex-shrink px-4 text-2xl lg:text-3xl font-bold uppercase text-center">{{ $heading }}</h1>
            <div class="flex-grow border-t border-gray-300 dark:border-gray-700"></div>
        </div>

        <div class="flex flex-col lg:flex-row gap-8">
            <aside class="lg:w-1/4 w-full">
                <div x-data="{ open: '{{ collect($s_links)->firstWhere(fn($item) => in_array($slug_aktif_normalized, array_column($item['submenu'], 'slug')))['target'] ?? '' }}' }" class="sticky top-20 space-y-1">
                    @foreach ($s_links as $statistik)
                        <div class="border border-gray-200 dark:border-gray-700">
                            <button @click="open = open === '{{ $statistik['target'] }}' ? '' : '{{ $statistik['target'] }}'" class="w-full flex justify-between items-center p-3 bg-gray-50 dark:bg-gray-800/50 hover:bg-gray-100 dark:hover:bg-gray-800">
                                <span class="font-semibold"><i class="fas {{ $statistik['icon'] }} mr-2"></i> {{ $statistik['label'] }}</span>
                                <i class="fas fa-chevron-down transition-transform" :class="{'rotate-180': open === '{{ $statistik['target'] }}'}"></i>
                            </button>
                            <div x-show="open === '{{ $statistik['target'] }}'" x-transition x-cloak class="p-2 bg-white dark:bg-gray-800">
                                <ul class="space-y-1">
                                    @foreach ($statistik['submenu'] as $submenu)
                                        @php
                                            $stat_slug = in_array($statistik['target'], ['bantuan', 'lainnya']) ? str_replace('first/', '', $submenu['url']) : 'statistik/' . $submenu['key'];
                                            if ($stat_slug == 'data-dpt') $stat_slug = 'dpt';
                                        @endphp
                                        @if (isset($statistik_aktif[$stat_slug]))
                                            <li>
                                                <a href="{{ site_url($submenu['url']) }}" class="block px-3 py-2 text-sm {{ $submenu['slug'] == $slug_aktif_normalized ? 'bg-blue-600 text-white font-semibold' : 'hover:bg-gray-100 dark:hover:bg-gray-700' }}">{{ $submenu['label'] }}</a>
                                            </li>
                                        @endif
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endforeach
                </div>
            </aside>

            <main class="lg:w-3/4 w-full space-y-8">
                <div class="space-y-4">
                    @if (isset($list_tahun))
                        <div class="flex justify-start items-center space-x-2">
                            <label for="filter-tahun" class="text-sm font-medium">Tahun:</label>
                            <select id="filter-tahun" class="form-input w-auto">
                                <option value="">Semua</option>
                                @foreach ($list_tahun as $tahun)
                                    <option value="{{ $tahun }}" @if($tahun == ($selected_tahun ?? null)) selected @endif>{{ $tahun }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif

                    <div class="flex flex-col md:flex-row justify-between items-center gap-4 p-4 border dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50">
                        <h2 class="text-lg font-bold">Grafik {{ $heading }}</h2>
                        <div class="flex flex-wrap gap-2">
                            <div x-data="{ chartType: '{{ $default_chart_type ?? 'pie' }}' }" class="flex">
                                <button @click="chartType = 'column'; renderChart(allData, chartType)" :class="{'bg-blue-600 text-white': chartType === 'column'}" class="btn bg-gray-200 dark:bg-gray-600 rounded-r-none"><i class="fas fa-chart-bar"></i></button>
                                <button @click="chartType = 'pie'; renderChart(allData, chartType)" :class="{'bg-blue-600 text-white': chartType === 'pie'}" class="btn bg-gray-200 dark:bg-gray-600 rounded-l-none"><i class="fas fa-chart-pie"></i></button>
                            </div>
                            <a href="{{ ci_route("data-statistik.{$slug_aktif}.cetak.cetak") }}?tahun={{ $selected_tahun }}" class="btn bg-gray-600 text-white" target="_blank"><i class="fas fa-print mr-2"></i> Cetak</a>
                            <a href="{{ ci_route("data-statistik.{$slug_aktif}.cetak.unduh") }}?tahun={{ $selected_tahun }}" class="btn bg-green-600 text-white"><i class="fas fa-download mr-2"></i> Unduh</a>
                        </div>
                    </div>

                    <div id="chart-container" class="w-full h-96 border border-gray-200 dark:border-gray-700 p-2"></div>
                </div>

                <div class="space-y-4">
                    <h2 class="text-lg font-bold">Tabel Data {{ $heading }}</h2>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm border-collapse" id="tabel-statistik">
                            <thead class="bg-gray-100 dark:bg-gray-700/50">
                                <tr>
                                    <th rowspan="2" class="p-3 text-center text-xs font-bold uppercase tracking-wider border border-gray-300 dark:border-gray-600">No</th>
                                    <th rowspan="2" class="p-3 text-left text-xs font-bold uppercase tracking-wider border border-gray-300 dark:border-gray-600">Kelompok</th>
                                    <th colspan="2" class="p-3 text-center text-xs font-bold uppercase tracking-wider border border-gray-300 dark:border-gray-600">Jumlah</th>
                                    <th colspan="2" class="p-3 text-center text-xs font-bold uppercase tracking-wider border border-gray-300 dark:border-gray-600">Laki-laki</th>
                                    <th colspan="2" class="p-3 text-center text-xs font-bold uppercase tracking-wider border border-gray-300 dark:border-gray-600">Perempuan</th>
                                </tr>
                                <tr>
                                    <th class="p-2 text-center text-xs font-bold uppercase tracking-wider border border-gray-300 dark:border-gray-600">n</th>
                                    <th class="p-2 text-center text-xs font-bold uppercase tracking-wider border border-gray-300 dark:border-gray-600">%</th>
                                    <th class="p-2 text-center text-xs font-bold uppercase tracking-wider border border-gray-300 dark:border-gray-600">n</th>
                                    <th class="p-2 text-center text-xs font-bold uppercase tracking-wider border border-gray-300 dark:border-gray-600">%</th>
                                    <th class="p-2 text-center text-xs font-bold uppercase tracking-wider border border-gray-300 dark:border-gray-600">n</th>
                                    <th class="p-2 text-center text-xs font-bold uppercase tracking-wider border border-gray-300 dark:border-gray-600">%</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                    <p class="text-xs text-gray-500">Diperbarui pada: {{ tgl_indo($last_update) }}</p>
                    <div class="flex justify-between items-center">
                        <button id="showData" class="btn btn-primary" style="display: none;">Selengkapnya...</button>
                        <button id="showZero" class="btn bg-gray-200 dark:bg-gray-600">Tampilkan Nol</button>
                    </div>
                </div>

                @if (setting('daftar_penerima_bantuan') && $bantuan)
                    <div class="space-y-4">
                        <h2 class="text-lg font-bold">Daftar {{ $heading }}</h2>
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm border-collapse" id="peserta_program">
                                <thead class="bg-gray-100 dark:bg-gray-700/50">
                                    <tr>
                                        <th class="p-3 text-center text-xs font-bold uppercase tracking-wider border border-gray-300 dark:border-gray-600">No</th>
                                        <th class="p-3 text-left text-xs font-bold uppercase tracking-wider border border-gray-300 dark:border-gray-600">Program</th>
                                        <th class="p-3 text-left text-xs font-bold uppercase tracking-wider border border-gray-300 dark:border-gray-600">Nama Peserta</th>
                                        <th class="p-3 text-left text-xs font-bold uppercase tracking-wider border border-gray-300 dark:border-gray-600">Alamat</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                @endif
            </main>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        let allData = [];
        let chart;
        const enable3d = {{ setting('statistik_chart_3d') ? 'true' : 'false' }};

        function renderTable(data, showZero = false) {
            const tbody = document.querySelector('#tabel-statistik tbody');
            tbody.innerHTML = '';
            let showMoreButton = false;
            
            const filteredData = showZero 
                ? data 
                : data.filter(item => Number(item.attributes.jumlah) > 0 || ['666', '777', '888'].includes(String(item.id)));
                
            filteredData.forEach((item, index) => {
                const row = document.createElement('tr');
                row.className = 'border-b dark:border-gray-700';
                
                if (index > 11 && !['666', '777', '888'].includes(String(item.id))) {
                    row.classList.add('hidden', 'more-row');
                    showMoreButton = true;
                }
                
                const attributes = item.attributes;
                const isTotal = ['666', '777', '888'].includes(String(item.id));
                if (isTotal) row.classList.add('font-bold', 'bg-gray-50', 'dark:bg-gray-800/50');
                
                row.innerHTML = `
                    <td class="p-2 text-center border border-gray-300 dark:border-gray-600">${isTotal ? '' : index + 1}</td>
                    <td class="p-2 text-left border border-gray-300 dark:border-gray-600">${attributes.nama}</td>
                    <td class="p-2 text-right border border-gray-300 dark:border-gray-600">${attributes.jumlah}</td>
                    <td class="p-2 text-right border border-gray-300 dark:border-gray-600">${attributes.persen}</td>
                    <td class="p-2 text-right border border-gray-300 dark:border-gray-600">${attributes.laki}</td>
                    <td class="p-2 text-right border border-gray-300 dark:border-gray-600">${attributes.persen1}</td>
                    <td class="p-2 text-right border border-gray-300 dark:border-gray-600">${attributes.perempuan}</td>
                    <td class="p-2 text-right border border-gray-300 dark:border-gray-600">${attributes.persen2}</td>
                `;
                tbody.appendChild(row);
            });
            $('#showData').toggle(showMoreButton);
        }

        function renderChart(data, chartType = 'pie') {
            const isDarkMode = document.documentElement.classList.contains('dark');
            
            const chartData = data
                .filter(item => !['TOTAL', 'JUMLAH', 'PENERIMA', '666', '777', '888'].includes(String(item.attributes.nama).toUpperCase()))
                .map(item => ({ name: item.attributes.nama, y: Number(item.attributes.jumlah) }));

            const options = {
                chart: { type: chartType, backgroundColor: isDarkMode ? '#1f2937' : 'transparent', options3d: { enabled: enable3d, alpha: 45, beta: 0 } },
                title: { text: null },
                xAxis: { type: 'category', categories: chartData.map(d => d.name), labels: { style: { color: isDarkMode ? '#d1d5db' : '#333333' } } },
                yAxis: { title: { text: 'Jumlah', style: { color: isDarkMode ? '#d1d5db' : '#333333' } }, labels: { style: { color: isDarkMode ? '#d1d5db' : '#333333' } } },
                plotOptions: {
                    series: { colorByPoint: true },
                    pie: { allowPointSelect: true, cursor: 'pointer', depth: 35, showInLegend: true, dataLabels: { enabled: true, format: '<b>{point.name}</b>: {point.percentage:.1f} %' } },
                    column: { depth: 25, dataLabels: { enabled: true } }
                },
                series: [{ name: 'Jumlah', data: chartData }],
                legend: { enabled: chartType === 'pie', itemStyle: { color: isDarkMode ? '#d1d5db' : '#333333' } },
                credits: { enabled: false }
            };

            if (chart) chart.destroy();
            chart = Highcharts.chart('chart-container', options);
        }

        document.addEventListener('DOMContentLoaded', function() {
            const defaultChartType = '{{ $default_chart_type ?? 'pie' }}';
            let isZeroShown = false;
            
            fetch(`{{ ci_route('internal_api.statistik', $key) }}?tahun={{ $selected_tahun ?? '' }}`)
                .then(response => response.json())
                .then(data => {
                    allData = data.data;
                    renderTable(allData, isZeroShown);
                    renderChart(allData, defaultChartType);
                    const activeBtn = document.querySelector(`button[data-type='${defaultChartType}']`);
                    if(activeBtn) activeBtn.click();
                });

            $('#filter-tahun').on('change', function() {
                const currentUrl = window.location.href.split('?')[0];
                window.location.href = `${currentUrl}?tahun=${$(this).val()}`;
            });

            $('#showData').on('click', function() {
                $('.more-row').toggle();
                $(this).text($(this).text() === 'Selengkapnya...' ? 'Sembunyikan...' : 'Selengkapnya...');
            });

            $('#showZero').on('click', function() {
                isZeroShown = !isZeroShown;
                renderTable(allData, isZeroShown);
                $(this).text(isZeroShown ? 'Sembunyikan Nol' : 'Tampilkan Nol');
                $('#showData').hide(); 
            });

            document.querySelectorAll('button[data-type]').forEach(button => {
                button.addEventListener('click', function() {
                    const type = this.dataset.type;
                    renderChart(allData, type);
                });
            });

            const observer = new MutationObserver(() => { if (chart) renderChart(allData, chart.options.chart.type); });
            observer.observe(document.documentElement, { attributes: true, attributeFilter: ['class'] });

            @if (setting('daftar_penerima_bantuan') && $bantuan)
                $('#peserta_program').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: { url: `{{ ci_route('internal_api.peserta_bantuan', $key) }}?filter[tahun]={{ $selected_tahun ?? '' }}`, method: 'GET', dataSrc: json => {
                        json.recordsTotal = json.meta.pagination.total;
                        json.recordsFiltered = json.meta.pagination.total;
                        return json.data;
                    }},
                    columns: [
                        { data: null, searchable: false, orderable: false, className: 'text-center p-2 border border-gray-300 dark:border-gray-600' },
                        { data: 'attributes.program', name: 'program', className: 'p-2 border border-gray-300 dark:border-gray-600' },
                        { data: 'attributes.nama', name: 'nama', className: 'p-2 border border-gray-300 dark:border-gray-600' },
                        { data: 'attributes.alamat', name: 'alamat', orderable: false, className: 'p-2 border border-gray-300 dark:border-gray-600' },
                    ],
                    language: { url: "{{ asset('bootstrap/js/dataTables.indonesian.lang') }}" },
                    drawCallback: function() {
                        var api = this.api();
                        api.column(0, { search: 'applied', order: 'applied' }).nodes().each((cell, i) => cell.innerHTML = api.page.info().start + i + 1);
                    }
                });
            @endif
        });
    </script>
@endpush