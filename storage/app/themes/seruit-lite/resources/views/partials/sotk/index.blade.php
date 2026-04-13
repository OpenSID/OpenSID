@extends('theme::layouts.full-content')

@section('content')
<div 
    x-data="sotkData()" 
    x-init="init()"
    class="bg-white dark:bg-gray-800 p-6 shadow-xl border border-gray-200 dark:border-gray-700 -mt-16 relative z-10"
>
    <nav role="navigation" aria-label="navigation" class="breadcrumb text-sm mb-4">
        <ol class="flex items-center justify-center space-x-2 text-gray-500 dark:text-gray-400">
            <li><a href="{{ site_url() }}" class="hover:underline hover:text-blue-600">Beranda</a></li>
            <li><span class="mx-2">/</span></li>
            <li aria-current="page" class="font-medium text-gray-700 dark:text-gray-300">Struktur Organisasi</li>
        </ol>
    </nav>

    <div class="flex items-center mt-6 mb-8">
        <div class="flex-grow border-t border-gray-300 dark:border-gray-700"></div>
        <h1 class="flex-shrink px-4 text-2xl lg:text-3xl font-bold uppercase text-center text-gray-800 dark:text-white">Struktur Organisasi & Tata Kerja</h1>
        <div class="flex-grow border-t border-gray-300 dark:border-gray-700"></div>
    </div>

    <div class="relative min-h-[500px]">
        <div x-show="isLoading" class="absolute inset-0 flex flex-col items-center justify-center bg-white dark:bg-gray-800 z-20">
            <svg class="animate-spin h-10 w-10 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <p class="mt-4 text-sm font-bold text-gray-600 dark:text-gray-400">Menyusun Struktur...</p>
        </div>
        
        <div x-show="!isLoading && !pemerintahData.length" x-cloak class="text-center py-20 text-gray-500">
            <i class="fas fa-users-slash text-5xl mb-4 opacity-20"></i>
            <p>Data aparatur belum tersedia.</p>
        </div>

        <div x-show="!isLoading && pemerintahData.length" x-cloak class="w-full overflow-x-auto pb-8">
            <div id="sotk-chart-container" style="min-width: 900px; height: 750px;"></div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function sotkData() {
        return {
            isLoading: true,
            pemerintahData: [],
            chartInstance: null,

            async loadData() {
                try {
                    const response = await fetch(`{{ route('api.pemerintah') }}`);
                    const json = await response.json();
                    this.pemerintahData = json.data;
                    this.$nextTick(() => {
                        this.renderChart();
                        this.isLoading = false;
                    });
                } catch (e) {
                    console.error("Gagal memuat data SOTK", e);
                    this.isLoading = false;
                }
            },

            renderChart() {
                const isDarkMode = document.documentElement.classList.contains('dark');
                const nodes = [];
                const links = [];

                this.pemerintahData.forEach(item => {
                    const attr = item.attributes;
                    nodes.push({
                        id: String(item.id),
                        title: attr.nama_jabatan || '-',
                        name: attr.nama || '-',
                        image: attr.foto || '{{ theme_asset("images/placeholder.png") }}',
                        color: attr.bagan_warna || undefined,
                        column: attr.bagan_tingkat || undefined,
                        layout: attr.bagan_layout || 'normal'
                    });

                    if (attr.atasan && attr.atasan !== "0") {
                        links.push([String(attr.atasan), String(item.id)]);
                    }
                });

                this.chartInstance = Highcharts.chart('sotk-chart-container', {
                    chart: {
                        height: 750,
                        inverted: true,
                        backgroundColor: 'transparent',
                        style: { fontFamily: 'inherit' }
                    },
                    title: { text: null },
                    credits: { enabled: false },
                    series: [{
                        type: 'organization',
                        name: 'Struktur Organisasi',
                        keys: ['from', 'to'],
                        data: links,
                        nodes: nodes,
                        nodeWidth: 160,
                        nodePadding: 25,
                        borderColor: isDarkMode ? '#374151' : '#cbd5e1',
                        borderWidth: 1,
                        borderRadius: 0,
                        states: {
                            hover: {
                                brightness: 0.1,
                                borderWidth: 2
                            }
                        },
                        levels: [
                            { level: 0, color: '#1e3a8a' },
                            { level: 1, color: '#0369a1' },
                            { level: 2, color: '#0d9488' },
                            { level: 3, color: '#4b5563' }
                        ],
                        dataLabels: {
                            color: '#ffffff',
                            useHTML: true,
                            nodeFormatter: function() {
                                return `
                                <div style="padding: 12px; text-align: center; width: 140px;">
                                    <div style="margin-bottom: 8px;">
                                        <img src="${this.point.image}" style="width: 55px; height: 55px; border-radius: 50%; border: 3px solid rgba(255,255,255,0.4); object-fit: cover; background: #fff; margin: 0 auto;">
                                    </div>
                                    <div style="font-size: 11px; font-weight: 800; line-height: 1.2; margin-bottom: 5px; text-transform: uppercase; word-wrap: break-word; white-space: normal;">
                                        ${this.point.name}
                                    </div>
                                    <div style="font-size: 9px; opacity: 0.9; font-weight: 400; border-top: 1px solid rgba(255,255,255,0.2); padding-top: 5px; word-wrap: break-word; white-space: normal;">
                                        ${this.point.title}
                                    </div>
                                </div>
                                `;
                            }
                        }
                    }],
                    tooltip: {
                        outside: true,
                        useHTML: true,
                        pointFormat: '<b>{point.name}</b><br>{point.title}'
                    }
                });
            },

            init() {
                this.loadData();
                window.addEventListener('resize', () => {
                    if (this.chartInstance) this.chartInstance.reflow();
                });
            }
        };
    }
</script>
@endpush