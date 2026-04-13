{{-- Update your default.blade.php file --}}

<!-- Breadcrumb -->
<nav class="mb-6">
    <ol class="flex items-center space-x-2 text-sm text-gray-500">
        <li><a href="{{ ci_route('') }}" class="hover:text-green-600 transition-colors">Beranda</a></li>
        <li><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg></li>
        <li class="text-gray-900 font-medium">Data Statistik</li>
    </ol>
</nav>

<!-- Header Section -->
<div class="p-6 md:p-8 mb-8">
    <h1 class="text-2xl md:text-3xl font-bold mb-2">{{ $judul }}</h1>
    <p class="text-sm md:text-base">Analisis data statistik terkini untuk pengambilan keputusan yang lebih baik</p>
</div>

<!-- Year Filter -->
@if (isset($list_tahun))
<div class="bg-white shadow-sm border border-gray-200 p-4 mb-6 rounded-lg">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <label for="tahun" class="text-sm font-medium text-gray-700 flex items-center">
            <svg class="w-4 h-4 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
            </svg>
            Filter Tahun
        </label>
        <select class="px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-green-500 focus:border-green-500 bg-white text-sm" id="tahun" name="tahun">
            <option selected="" value="">Semua Tahun</option>
            @foreach ($list_tahun as $item_tahun)
                <option @selected($item_tahun == $selected_tahun) value="{{ $item_tahun }}">{{ $item_tahun }}</option>
            @endforeach
        </select>
    </div>
</div>
@endif

<!-- Chart Section -->
<div class="bg-white shadow-sm border border-gray-200 mb-8 rounded-lg">
    <!-- Chart Header -->
    <div class="px-6 py-4 border-b border-gray-200">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <h2 class="text-xl font-semibold text-gray-900 flex items-center">
                Grafik {{ $heading }}
            </h2>
            
            <!-- Control Buttons -->
            <div class="flex flex-wrap items-center gap-2">
                <!-- Chart Type Buttons -->
                <div class="flex rounded-md p-1">
                    <button class="btn-switch px-3 py-2 text-xs font-medium transition-all rounded-md" data-type="column" id="btn-column">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                        Bar
                    </button>
                    <button class="btn-switch px-3 py-2 text-xs font-medium transition-all rounded-md is-active" data-type="pie" id="btn-pie">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"></path>
                        </svg>
                        Pie
                    </button>
                    <button class="btn-switch px-3 py-2 text-xs font-medium transition-all rounded-md" id="btn-3d" onclick="toggle3D()">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                        3D
                    </button>
                </div>
                
                <!-- Action Buttons -->
                <div class="flex gap-2">
                    <a href="{{ ci_route("data-statistik.{$slug_aktif}.cetak.cetak") }}?tahun={{ $selected_tahun }}" 
                       class="inline-flex items-center px-3 py-2 bg-green-500 hover:bg-green-600 text-white text-xs font-medium rounded-md transition-colors" 
                       title="Cetak Laporan" target="_blank">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                        </svg>
                        Cetak
                    </a>
                    <a href="{{ ci_route("data-statistik.{$slug_aktif}.cetak.unduh") }}?tahun={{ $selected_tahun }}" 
                       class="inline-flex items-center px-3 py-2 bg-blue-500 hover:bg-blue-600 text-white text-xs font-medium rounded-md transition-colors" 
                       title="Unduh Laporan" target="_blank">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Unduh
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Chart Container -->
    <div class="p-6">
        <div id="container" class="min-h-[400px] flex items-center justify-center">
            <div class="text-center">
                <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-green-500 mx-auto mb-4"></div>
                <p class="text-gray-500">Memuat grafik...</p>
            </div>
        </div>
    </div>
</div>

<!-- Table Section -->
<div class="bg-white shadow-sm border border-gray-200 mb-8 rounded-lg">
    <!-- Table Header -->
    <div class="px-6 py-4 border-b border-gray-200">
        <h2 class="text-xl font-semibold text-gray-900 flex items-center">
            <svg class="w-5 h-5 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
            </svg>
            Tabel {{ $heading }}
        </h2>
    </div>
    
    <!-- Table Content -->
    <div class="overflow-x-auto">
        <table class="w-full" id="table-statistik">
            <thead class="bg-gray-50">
                <tr class="border-b border-gray-200">
                    <th rowspan="2" class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                    <th rowspan="2" class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kelompok</th>
                    <th colspan="2" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border-b">Jumlah</th>
                    <th colspan="2" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border-b">Laki-laki</th>
                    <th colspan="2" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border-b">Perempuan</th>
                </tr>
                <tr class="border-b border-gray-200">
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">n</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">%</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">n</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">%</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">n</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">%</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <!-- Table rows will be populated by JavaScript -->
            </tbody>
        </table>
    </div>
    
    <!-- Table Footer -->
    <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <p class="text-sm text-red-600 flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Diperbarui pada: {{ tgl_indo($last_update) }}
            </p>
            <div class="flex gap-2">
                <button class="inline-flex items-center px-4 py-2 bg-green-500 hover:bg-green-600 text-white text-sm font-medium rounded-md transition-colors button-more" id="showData">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                    Selengkapnya
                </button>
                <button id="showZero" class="inline-flex items-center px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white text-sm font-medium rounded-md transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    Tampilkan Nol
                </button>
            </div>
        </div>
    </div>
</div>

@if (setting('daftar_penerima_bantuan') && $bantuan)
<!-- Beneficiary List Section -->
<div class="bg-white shadow-sm border border-gray-200 rounded-lg">
    <!-- Section Header -->
    <div class="px-6 py-4 border-b border-gray-200">
        <h2 class="text-xl font-semibold text-gray-900 flex items-center">
            <svg class="w-5 h-5 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
            </svg>
            Daftar {{ $heading }}
        </h2>
    </div>
    
    <!-- Table Content -->
    <div class="overflow-x-auto">
        <table class="w-full" id="peserta_program">
            <thead class="bg-gray-50">
                <tr class="border-b border-gray-200">
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Program</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Peserta</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Alamat</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <!-- Table rows will be populated by JavaScript -->
            </tbody>
        </table>
    </div>
</div>

<script>
    const bantuanUrl = '{{ ci_route('internal_api.peserta_bantuan', $key) }}?filter[tahun]={{ $selected_tahun ?? '' }}'
</script>
<input id="stat" type="hidden" value="{{ $key }}">
@endif

@push('styles')
<style>
    .btn-switch {
        color: #6b7280;
        background-color: transparent;
        border: none;
        cursor: pointer;
    }
    .btn-switch.is-active {
        background-color: #10b981;
        color: white;
        box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
    }
    .btn-switch:hover:not(.is-active) {
        background-color: #f3f4f6;
        color: #374151;
    }
    
    .more {
        display: none;
    }
    
    .zero {
        opacity: 0.5;
    }
    
    /* Ensure chart container has proper dimensions */
    #container {
        width: 100%;
        height: 400px;
    }
    
    /* Mobile responsiveness for table */
    @media (max-width: 640px) {
        .table-mobile {
            display: block;
        }
        .table-mobile thead,
        .table-mobile tbody,
        .table-mobile th,
        .table-mobile td,
        .table-mobile tr {
            display: block;
        }
        .table-mobile thead tr {
            position: absolute;
            top: -9999px;
            left: -9999px;
        }
        .table-mobile tr {
            border: 1px solid #e5e7eb;
            margin-bottom: 10px;
            padding: 10px;
            border-radius: 8px;
        }
        .table-mobile td {
            border: none;
            position: relative;
            padding-left: 50% !important;
            padding-top: 8px;
            padding-bottom: 8px;
        }
        .table-mobile td:before {
            content: attr(data-label) ": ";
            position: absolute;
            left: 6px;
            width: 45%;
            padding-right: 10px;
            white-space: nowrap;
            font-weight: 600;
            color: #374151;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    let dataStats = [];
    let chart = null;
    let currentType = '{{ $default_chart_type ?? 'pie' }}';
    let is3DEnabled = {{ setting('statistik_chart_3d') ? 'true' : 'false' }};
    let status_tampilkan = true;
    
    // Check if Highcharts is available
    function checkHighcharts() {
        if (typeof Highcharts === 'undefined') {
            console.error('Highcharts not loaded');
            $('#container').html(`
                <div class="text-center p-8">
                    <div class="text-red-500 mb-4">
                        <svg class="w-16 h-16 mx-auto" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <p class="text-red-600 font-medium">Error: Highcharts library not loaded</p>
                    <p class="text-gray-500 text-sm mt-2">Please refresh the page or contact administrator</p>
                </div>
            `);
            return false;
        }
        return true;
    }
    
    function createChart() {
        if (!checkHighcharts() || !dataStats.length) return;
        
        // Filter data (exclude totals)
        const chartData = dataStats.filter(item => !['666', '777', '888'].includes(item.id));
        
        const commonOptions = {
            chart: {
                renderTo: 'container',
                type: currentType,
                backgroundColor: '#ffffff',
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false,
                style: {
                    fontFamily: 'ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, "Noto Sans", sans-serif'
                },
                height: 400
            },
            title: {
                text: '{{ $heading }}',
                style: {
                    fontSize: '18px',
                    fontWeight: 'bold',
                    color: '#111827'
                }
            },
            subtitle: {
                text: 'Data Statistik {{ $selected_tahun ?? "Terkini" }}',
                style: {
                    fontSize: '14px',
                    color: '#6b7280'
                }
            },
            tooltip: {
                backgroundColor: 'rgba(0,0,0,0.8)',
                style: {
                    color: '#ffffff'
                },
                borderRadius: 8,
                shadow: true
            },
            colors: [
                '#10b981', '#3b82f6', '#8b5cf6', '#f59e0b', '#ef4444', '#06b6d4',
                '#84cc16', '#f97316', '#ec4899', '#6366f1', '#14b8a6', '#f59e0b'
            ],
            credits: {
                enabled: false
            },
            exporting: {
                enabled: true,
                buttons: {
                    contextButton: {
                        menuItems: ['viewFullscreen', 'separator', 'downloadPNG', 'downloadJPEG', 'downloadPDF']
                    }
                }
            }
        };

        // Add 3D options if enabled
        if (is3DEnabled) {
            commonOptions.chart.options3d = {
                enabled: true,
                alpha: 45,
                beta: 0,
                viewDistance: 25,
                // frame: {
                //     bottom: { size: 1, color: 'rgba(0,0,0,0.02)' },
                //     back: { size: 1, color: 'rgba(0,0,0,0.04)' },
                //     side: { size: 1, color: 'rgba(0,0,0,0.06)' }
                // }
            };
        }

        if (currentType === 'pie') {
            commonOptions.plotOptions = {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: true,
                        format: '<b>{point.name}</b><br>{point.percentage:.1f}%',
                        style: {
                            fontSize: '12px'
                        }
                    },
                    showInLegend: true,
                    ...(is3DEnabled && {
                        depth: 45,
                        innerSize: 100
                    })
                }
            };
            
            commonOptions.legend = {
                align: 'right',
                verticalAlign: 'middle',
                layout: 'vertical',
                itemStyle: {
                    fontSize: '12px'
                }
            };
            
            commonOptions.series = [{
                name: 'Jumlah',
                data: chartData.map(item => ({
                    name: item.nama,
                    y: parseInt(item.jumlah),
                    percentage: parseFloat(item.persen) || 0
                }))
            }];
        } else {
            const categories = chartData.map(item => item.nama);
            const seriesData = chartData.map(item => parseInt(item.jumlah));
            
            commonOptions.xAxis = {
                categories: categories,
                title: {
                    text: 'Kelompok',
                    style: { fontSize: '14px' }
                },
                labels: {
                    style: { fontSize: '11px' },
                    rotation: -45
                }
            };
            
            commonOptions.yAxis = {
                min: 0,
                title: {
                    text: 'Jumlah',
                    style: { fontSize: '14px' }
                },
                labels: {
                    style: { fontSize: '11px' }
                }
            };
            
            commonOptions.plotOptions = {
                column: {
                    pointPadding: 0.2,
                    borderWidth: 0,
                    dataLabels: {
                        enabled: true,
                        style: {
                            fontSize: '11px'
                        }
                    },
                    ...(is3DEnabled && {
                        depth: 25,
                        grouping: true,
                        groupZPadding: 10
                    })
                }
            };
            
            commonOptions.legend = {
                enabled: false
            };
            
            commonOptions.series = [{
                name: 'Jumlah',
                data: seriesData
            }];
        }

        try {
            // Destroy existing chart if it exists
            if (chart && typeof chart.destroy === 'function') {
                chart.destroy();
            }
            chart = new Highcharts.Chart(commonOptions);
        } catch (error) {
            console.error('Error creating chart:', error);
            $('#container').html(`
                <div class="text-center p-8">
                    <div class="text-red-500 mb-4">
                        <svg class="w-16 h-16 mx-auto" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <p class="text-red-600 font-medium">Error creating chart: ${error.message}</p>
                </div>
            `);
        }
    }
    
    function switchType(type) {
        currentType = type;
        
        // Update button states
        $('.btn-switch[data-type]').removeClass('is-active');
        $(`.btn-switch[data-type="${type}"]`).addClass('is-active');
        
        createChart();
    }
    
    function toggle3D() {
        is3DEnabled = !is3DEnabled;
        const btn = $('#btn-3d');
        if (is3DEnabled) {
            btn.addClass('is-active');
        } else {
            btn.removeClass('is-active');
        }
        createChart();
    }
    
    function tampilkan_nol(show) {
        const nolElements = $('.zero');
        nolElements.closest('tr').toggle(show);
    }

    function toggle_tampilkan() {
        $('#showData').click();
        tampilkan_nol(status_tampilkan);
        status_tampilkan = !status_tampilkan;
        
        const button = $('#showZero');
        button.html(status_tampilkan ? 
            '<svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>Tampilkan Nol' : 
            '<svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029"></path></svg>Sembunyikan Nol');
    }
    
    $(function() {
        // Initialize chart type buttons
        $('.btn-switch[data-type]').on('click', function() {
            const type = $(this).data('type');
            switchType(type);
        });
        
        // Initialize zero toggle button
        $('#showZero').on('click', function() {
            toggle_tampilkan();
        });
        
        // Load statistics data
        $.ajax({
            url: `{{ ci_route('internal_api.statistik', $key) }}?filter[tahun]={{ $selected_tahun ?? '' }}`,
            method: 'GET',
            data: {},
            beforeSend: function() {
                $('#showData').hide();
                $('#container').html(`
                    <div class="text-center">
                        <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-green-500 mx-auto mb-4"></div>
                        <p class="text-gray-500">Memuat grafik...</p>
                    </div>
                `);
            },
            success: function(json) {
                try {
                    dataStats = json.data.map(item => {
                        const { id } = item;
                        const { nama, jumlah, laki, perempuan, persen, persen1, persen2 } = item.attributes;
                        return { id, nama, jumlah, laki, perempuan, persen, persen1, persen2 };
                    });

                    const table = document.getElementById('table-statistik');
                    const tbody = table.querySelector('tbody');
                    tbody.innerHTML = ''; // Clear existing content
                    let _showBtnSelengkapnya = false;
                    
                    // Populate table rows
                    dataStats.forEach((item, index) => {
                        const row = document.createElement('tr');
                        row.className = 'hover:bg-gray-50 transition-colors';
                        
                        if (index > 11 && !['666', '777', '888'].includes(item['id'])) {
                            row.classList.add('more');
                            _showBtnSelengkapnya = true;
                        }
                        
                        // Create cells with proper styling
                        const cells = [
                            { key: 'id', value: ['666', '777', '888'].includes(item['id']) ? '' : index + 1, class: 'text-center font-medium', label: 'No' },
                            { key: 'nama', value: item['nama'], class: 'text-left font-medium text-gray-900', label: 'Kelompok' },
                            { key: 'jumlah', value: item['jumlah'], class: 'text-right', label: 'Jumlah (n)' },
                            { key: 'persen', value: item['persen'] + '%', class: 'text-right', label: 'Jumlah (%)' },
                            { key: 'laki', value: item['laki'], class: 'text-right', label: 'Laki-laki (n)' },
                            { key: 'persen1', value: item['persen1'] + '%', class: 'text-right', label: 'Laki-laki (%)' },
                            { key: 'perempuan', value: item['perempuan'], class: 'text-right', label: 'Perempuan (n)' },
                            { key: 'persen2', value: item['persen2'] + '%', class: 'text-right', label: 'Perempuan (%)' }
                        ];
                        
                        cells.forEach(cellData => {
                            const cell = document.createElement('td');
                            cell.className = `px-6 py-4 whitespace-nowrap text-sm ${cellData.class}`;
                            cell.setAttribute('data-label', cellData.label);
                            cell.textContent = cellData.value;
                            
                            if (cellData.key === 'jumlah' && item[cellData.key] <= 0) {
                                if (!['666', '777', '888'].includes(item['id'])) {
                                    cell.classList.add('zero');
                                }
                            }
                            
                            row.appendChild(cell);
                        });

                        tbody.appendChild(row);
                    });
                    
                    // Show "Selengkapnya" button if needed
                    if (_showBtnSelengkapnya) {
                        $('#showData').show();
                    }
                    
                    // Hide zero values by default
                    tampilkan_nol(false);
                    
                    // Create the chart after data is loaded
                    setTimeout(function() {
                        createChart();
                    }, 500);
                    
                } catch (error) {
                    console.error('Error processing data:', error);
                    $('#container').html(`
                        <div class="text-center p-8">
                            <div class="text-red-500 mb-4">
                                <svg class="w-16 h-16 mx-auto" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <p class="text-red-600 font-medium">Error processing statistics data</p>
                            <p class="text-gray-500 text-sm mt-2">${error.message}</p>
                        </div>
                    `);
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', error);
                $('#container').html(`
                    <div class="text-center p-8">
                        <div class="text-red-500 mb-4">
                            <svg class="w-16 h-16 mx-auto" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <p class="text-red-600 font-medium">Failed to load statistics data</p>
                        <p class="text-gray-500 text-sm mt-2">Status: ${status} - ${error}</p>
                        <button onclick="location.reload()" class="mt-4 px-4 py-2 bg-green-500 text-white rounded-md hover:bg-green-600 transition-colors">
                            Refresh Page
                        </button>
                    </div>
                `);
            }
        });
        
        // Year filter change handler
        $('#tahun').change(function() {
            const current_url = window.location.href.split('?')[0];
            window.location.href = `${current_url}?tahun=${$(this).val()}`;
        });

        // Show more data button
        $('#showData').on('click', function() {
            $('.more').show();
            $(this).hide();
            tampilkan_nol(false);
        });
        
        // Set initial chart type based on default
        const defaultChartType = '{{ $default_chart_type ?? 'pie' }}';
        if (defaultChartType === 'column') {
            setTimeout(function() {
                switchType('column');
            }, 1000);
        }
        
        // Set initial 3D state
        if (is3DEnabled) {
            $('#btn-3d').addClass('is-active');
        }
    });
    
    @if (setting('daftar_penerima_bantuan') && $bantuan)
    // Initialize DataTable for beneficiary list
    $(document).ready(function() {
        if ($('#peserta_program').length > 0) {
            const bantuanUrl = '{{ ci_route('internal_api.peserta_bantuan', $key) }}?filter[tahun]={{ $selected_tahun ?? '' }}';
            
            let pesertaDatatable = $('#peserta_program').DataTable({
                processing: true,
                serverSide: true,
                order: [],
                ajax: {
                    url: bantuanUrl,
                    type: 'GET',
                    data: function(row) {
                        return {
                            "page[size]": row.length,
                            "page[number]": (row.start / row.length) + 1,
                            "filter[search]": row.search.value,
                            "sort": (row.order[0]?.dir === "asc" ? "" : "-") + row.columns[row.order[0]?.column]?.name,
                        };
                    },
                    dataSrc: function(json) {
                        json.recordsTotal = json.meta.pagination.total;
                        json.recordsFiltered = json.meta.pagination.total;
                        return json.data;
                    },
                },
                columns: [
                    { data: null },
                    { data: 'attributes.nama', name: 'nama' },
                    { data: 'attributes.kartu_nama', name: 'kartu_nama' },
                    { data: 'attributes.kartu_alamat', name: 'kartu_alamat', orderable: false, searchable: false }
                ],
                order: [1, 'asc'],
                language: {
                    url: "{{ asset('assets/bootstrap/js/dataTables.indonesian.lang') }}"
                },
                drawCallback: function() {
                    $('.dataTables_paginate > .pagination').addClass('pagination-sm no-margin');
                }
            });

            pesertaDatatable.on('draw.dt', function() {
                var PageInfo = $('#peserta_program').DataTable().page.info();
                pesertaDatatable.column(0, { page: 'current' }).nodes().each(function(cell, i) {
                    cell.innerHTML = i + 1 + PageInfo.start;
                });
            });
        }
    });
    @endif
</script>
@endpush