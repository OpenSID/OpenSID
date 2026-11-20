@extends('theme::layouts.full-content')

@section('content')
<div 
    x-data="kesehatanData()"
    x-init="init()"
    class="bg-[var(--bg-color-card)] p-6 shadow-xl border border-[var(--border-color)] -mt-16 relative z-10"
>
    <nav role="navigation" aria-label="navigation" class="breadcrumb text-sm mb-4">
        <ol class="flex items-center justify-center space-x-2 text-gray-500 dark:text-gray-400">
            <li><a href="{{ site_url() }}" class="hover:underline hover:text-blue-600">Beranda</a></li>
            <li><span class="mx-2">/</span></li>
            <li aria-current="page" class="font-medium text-gray-700 dark:text-gray-300">{{ $title }}</li>
        </ol>
    </nav>
    <div class="flex items-center mt-6">
        <div class="flex-grow border-t border-gray-300 dark:border-gray-700"></div>
        <h1 class="flex-shrink px-4 text-2xl lg:text-3xl font-bold uppercase text-center">{{ $title }}</h1>
        <div class="flex-grow border-t border-gray-300 dark:border-gray-700"></div>
    </div>

    <div class="mt-8 mb-6 p-4 bg-gray-50 dark:bg-gray-900/50 border border-gray-200 dark:border-gray-700 flex flex-col md:flex-row gap-4 items-end">
        <div class="w-full md:w-auto">
            <label for="filter-kuartal" class="block text-sm font-medium mb-1">Kuartal</label>
            <select x-model="selectedKuartal" id="filter-kuartal" class="form-input w-full dark:bg-gray-700 dark:border-gray-600">
                @foreach (kuartal2() as $item)
                    <option value="{{ $item['ke'] }}">{{ "Kuartal {$item['ke']} ({$item['bulan']})" }}</option>
                @endforeach
            </select>
        </div>
        <div class="w-full md:w-auto">
            <label for="filter-tahun" class="block text-sm font-medium mb-1">Tahun</label>
            <select x-model="selectedTahun" id="filter-tahun" class="form-input w-full dark:bg-gray-700 dark:border-gray-600">
                @foreach ($dataTahun as $item)
                    <option value="{{ $item->tahun }}">{{ $item->tahun }}</option>
                @endforeach
            </select>
        </div>
        <div class="w-full md:w-auto">
            <label for="filter-posyandu" class="block text-sm font-medium mb-1">Posyandu</label>
            <select x-model="selectedPosyandu" id="filter-posyandu" class="form-input w-full dark:bg-gray-700 dark:border-gray-600">
                <option value="">Semua Posyandu</option>
                @foreach ($posyandu as $item)
                    <option value="{{ $item->id }}">{{ e($item->nama) }}</option>
                @endforeach
            </select>
        </div>
        <button @click="loadData" class="btn btn-primary w-full md:w-auto">
            <i class="fas fa-search mr-2"></i> Terapkan Filter
        </button>
    </div>

    <div id="kesehatan-container" class="mt-8">
        <div x-show="isLoading" class="col-span-full flex flex-col items-center justify-center py-12">
            <svg class="animate-spin h-8 w-8 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
            <p class="mt-2 text-sm font-semibold">Memuat Data Kesehatan...</p>
        </div>
        
        <div x-show="!isLoading && apiData" x-cloak class="space-y-12">
            <div id="widget-container" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4"></div>
            <div id="chart-umur-container" class="grid grid-cols-1 md:grid-cols-3 gap-8"></div>
            <div id="chart-posyandu-container"><div id="chart-posyandu" class="w-full h-96 border dark:border-gray-700"></div></div>
            <div id="scorecard-container" x-html="renderScorecard()"></div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function kesehatanData() {
        return {
            isLoading: true,
            selectedTahun: '{{ $tahun }}',
            selectedKuartal: '{{ $kuartal }}',
            selectedPosyandu: '{{ $idPosyandu }}',
            apiData: null,
            
            init() {
                this.loadData();
                const observer = new MutationObserver(() => this.redrawCharts());
                observer.observe(document.documentElement, { attributes: true, attributeFilter: ['class'] });
            },

            loadData() {
                this.isLoading = true;
                this.apiData = null; 
                const params = new URLSearchParams({
                    tahun: this.selectedTahun,
                    kuartal: this.selectedKuartal,
                    idPosyandu: this.selectedPosyandu,
                });
                const apiUrl = `{{ ci_route('internal_api.stunting') }}?${params.toString()}`;

                fetch(apiUrl)
                    .then(response => response.json())
                    .then(data => {
                        if (data.data && data.data.length > 0) {
                            this.apiData = data.data[0].attributes;
                            this.renderAll();
                        } else {
                            throw new Error('Data tidak ditemukan dari API.');
                        }
                        this.isLoading = false;
                    })
                    .catch(error => {
                        console.error("Error fetching data:", error);
                        document.getElementById('kesehatan-container').innerHTML = `<div class="alert alert-danger">Gagal memuat data. Silakan coba lagi atau periksa filter Anda.</div>`;
                        this.isLoading = false;
                    });
            },

            renderAll() {
                if (!this.apiData) return;
                this.renderWidgets(this.apiData.widgets);
                this.renderAllCharts();
            },

            renderAllCharts() {
                if (!this.apiData) return;
                this.renderUmurCharts(this.apiData.chartStuntingUmurData);
                this.renderPosyanduChart(this.apiData.chartStuntingPosyanduData);
            },
            
            redrawCharts() {
                if (!this.isLoading && this.apiData) {
                    this.renderAllCharts();
                }
            },

            renderWidgets(widgets) {
                const container = document.getElementById('widget-container');
                container.innerHTML = '';
                const iconMap = ['fa-female', 'fa-child', 'fa-female', 'fa-child', 'fa-child', 'fa-child', 'fa-child'];
                const colorMap = {
                    'bg-aqua': 'bg-cyan-500', 'bg-green': 'bg-green-500', 'bg-yellow': 'bg-yellow-500', 'bg-red': 'bg-red-500', 'bg-purple': 'bg-purple-500', 'bg-maroon': 'bg-pink-600', 'bg-gray': 'bg-gray-500'
                };
                widgets.forEach(widget => {
                    const bgColor = colorMap[widget['bg-color']] || 'bg-gray-500';
                    const iconClass = iconMap[widget.icon] || 'fa-question-circle';
                    container.innerHTML += `
                        <div class="p-4 shadow-md flex items-center ${bgColor}">
                            <div class="flex-shrink-0 w-12 h-12 flex items-center justify-center text-white text-2xl"><i class="fas ${iconClass}"></i></div>
                            <div class="ml-4"><p class="text-3xl font-bold text-white">${widget.total}</p><p class="text-sm text-white/90">${widget.title}</p></div>
                        </div>`;
                });
            },
            
            renderUmurCharts(chartData) {
                const container = document.getElementById('chart-umur-container');
                container.innerHTML = '';
                const isDarkMode = document.documentElement.classList.contains('dark');
                chartData.forEach(item => {
                    const chartDiv = document.createElement('div');
                    chartDiv.id = item.id;
                    chartDiv.className = 'w-full h-72 border dark:border-gray-700';
                    container.appendChild(chartDiv);

                    Highcharts.chart(item.id, {
                        chart: { type: 'pie', backgroundColor: isDarkMode ? '#1f2937' : '#ffffff' },
                        title: { text: item.title, style: { color: isDarkMode ? '#d1d5db' : '#333333' } },
                        tooltip: { valueSuffix: '%' },
                        plotOptions: { pie: { allowPointSelect: true, cursor: 'pointer', showInLegend: true, dataLabels: { enabled: true, distance: -50, format: '{point.y:,.1f} %' } } },
                        series: [{ type: 'pie', name: 'persentase', colorByPoint: true, data: item.data }],
                        legend: { itemStyle: { color: isDarkMode ? '#d1d5db' : '#333333' } }, credits: { enabled: false }
                    });
                });
            },

            renderPosyanduChart(chartData) {
                const isDarkMode = document.documentElement.classList.contains('dark');
                Highcharts.chart('chart-posyandu', {
                    chart: { type: 'column', backgroundColor: isDarkMode ? '#1f2937' : '#ffffff' },
                    title: { text: 'Grafik Kasus Stunting per-Posyandu', style: { color: isDarkMode ? '#d1d5db' : '#333333' } },
                    xAxis: { categories: chartData.categories, labels: { style: { color: isDarkMode ? '#d1d5db' : '#333333' } } },
                    yAxis: { min: 0, title: { text: 'Angka Kasus Stunting', style: { color: isDarkMode ? '#d1d5db' : '#333333' } }, labels: { style: { color: isDarkMode ? '#d1d5db' : '#333333' } } },
                    colors: ['#028EFA', '#5EE497', '#FDB13B'],
                    series: chartData.data,
                    legend: { itemStyle: { color: isDarkMode ? '#d1d5db' : '#333333' } }, credits: { enabled: false }
                });
            },
            
            renderScorecard() {
                if (!this.apiData || !this.apiData.scorecard) {
                    return `<div class="text-center text-gray-500 py-4">Data scorecard tidak tersedia.</div>`;
                }
                const data = this.apiData.scorecard;
                
                const tkd_ibu = data.ibu_hamil.tingkatKonvergensiDesa || {};
                const tkd_anak = data.bulanan_anak.tingkatKonvergensiDesa || {};
                const JLD_TOTAL = (tkd_ibu.jumlah_diterima || 0) + (tkd_anak.jumlah_diterima || 0);
                const JYSD_TOTAL = (tkd_ibu.jumlah_seharusnya || 0) + (tkd_anak.jumlah_seharusnya || 0);
                const KONV_TOTAL = JYSD_TOTAL > 0 ? ((JLD_TOTAL / JYSD_TOTAL) * 100).toFixed(2) : '0.00';

                return `
                <div class="space-y-4">
                    <div class="flex justify-end space-x-2">
                        <a href="${this.generateCetakUrl(false)}" class="btn btn-primary text-sm" target="_blank"><i class="fas fa-print mr-2"></i>Cetak</a>
                        <a href="${this.generateCetakUrl(true)}" class="btn bg-green-600 text-white hover:bg-green-700 text-sm" target="_blank"><i class="fas fa-download mr-2"></i>Unduh</a>
                    </div>
                    <div class="overflow-x-auto text-xs border dark:border-gray-700">
                        <table class="w-full">
                            <thead class="bg-gray-100 dark:bg-gray-700/50">
                                <tr><th colspan="9" class="p-2 font-bold text-left">TABEL 1. JUMLAH SASARAN 1.000 HPK</th></tr>
                                <tr>
                                    <th rowspan="2" colspan="3" class="p-2 border dark:border-gray-600">Sasaran</th>
                                    <th rowspan="2" colspan="2" class="p-2 border dark:border-gray-600">JML TOTAL RUMAH TANGGA 1.000 HPK</th>
                                    <th colspan="2" class="p-2 border dark:border-gray-600">IBU HAMIL</th>
                                    <th colspan="2" class="p-2 border dark:border-gray-600">ANAK 0–23 BULAN</th>
                                </tr>
                                <tr>
                                    <th class="p-2 border dark:border-gray-600">TOTAL</th><th class="p-2 border dark:border-gray-600">KEK/RESTI</th>
                                    <th class="p-2 border dark:border-gray-600">TOTAL</th><th class="p-2 border dark:border-gray-600">GIZI KURANG/BURUK/STUNTING</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="text-center"><td colspan="3" class="p-2 border dark:border-gray-600 font-semibold">Jumlah</td><td colspan="2" class="p-2 border dark:border-gray-600">${data.JTRT}</td><td class="p-2 border dark:border-gray-600">${data.ibu_hamil.dataFilter?.length || 0}</td><td class="p-2 border dark:border-gray-600">${data.jumlahKekRisti}</td><td class="p-2 border dark:border-gray-600">${data.bulanan_anak.dataFilter?.length || 0}</td><td class="p-2 border dark:border-gray-600">${data.jumlahGiziBukanNormal}</td></tr>
                                
                                <tr><th colspan="9" class="p-2 font-bold text-left bg-gray-100 dark:bg-gray-700/50">TABEL 2. HASIL PENGUKURAN TIKAR PERTUMBUHAN</th></tr>
                                <tr class="bg-gray-50 dark:bg-gray-700/30 text-center"><td colspan="3" class="p-2 border dark:border-gray-600 font-semibold">Sasaran</td><td class="p-2 border dark:border-gray-600">JUMLAH TOTAL ANAK USIA 0–23 BULAN</td><td class="p-2 border dark:border-gray-600">HIJAU (NORMAL)</td><td colspan="2" class="p-2 border dark:border-gray-600">Kuning (Resiko Stunting)</td><td colspan="2" class="p-2 border dark:border-gray-600">Merah (Terindikasi Stunting)</td></tr>
                                <tr class="text-center"><td colspan="3" class="p-2 border dark:border-gray-600 font-semibold">Jumlah</td><td class="p-2 border dark:border-gray-600">${data.bulanan_anak.dataFilter?.length || 0}</td><td class="p-2 border dark:border-gray-600">${data.tikar.H}</td><td colspan="2" class="p-2 border dark:border-gray-600">${data.tikar.K}</td><td colspan="2" class="p-2 border dark:border-gray-600">${data.tikar.M}</td></tr>

                                <tr><th colspan="9" class="p-2 font-bold text-left bg-gray-100 dark:bg-gray-700/50">TABEL 3. KELENGKAPAN KONVERGENSI LAYANAN</th></tr>
                                <tr class="bg-gray-50 dark:bg-gray-700/30 text-center"><td colspan="2" class="p-2 border dark:border-gray-600 font-semibold">Sasaran</td><td class="p-2 border dark:border-gray-600 font-semibold">No</td><td colspan="3" class="p-2 border dark:border-gray-600 font-semibold">Indikator</td><td colspan="2" class="p-2 border dark:border-gray-600 font-semibold">Jumlah</td><td class="p-2 border dark:border-gray-600 font-semibold">%</td></tr>
                                
                                <tr><td colspan="2" rowspan="8" class="p-2 border dark:border-gray-600 text-center align-middle font-semibold">Ibu Hamil</td><td class="p-2 border dark:border-gray-600 text-center">1</td><td colspan="3" class="p-2 border dark:border-gray-600">Periksa kehamilan min. 4x</td><td colspan="2" class="p-2 border dark:border-gray-600 text-center">${data.ibu_hamil.capaianKonvergensi?.periksa_kehamilan?.Y || 0}</td><td class="p-2 border dark:border-gray-600 text-center">${data.ibu_hamil.capaianKonvergensi?.periksa_kehamilan?.persen || '0.00'}</td></tr>
                                <tr><td class="p-2 border dark:border-gray-600 text-center">2</td><td colspan="3" class="p-2 border dark:border-gray-600">Minum pil FE min. 90 hari</td><td colspan="2" class="p-2 border dark:border-gray-600 text-center">${data.ibu_hamil.capaianKonvergensi?.pil_fe?.Y || 0}</td><td class="p-2 border dark:border-gray-600 text-center">${data.ibu_hamil.capaianKonvergensi?.pil_fe?.persen || '0.00'}</td></tr>
                                <tr><td class="p-2 border dark:border-gray-600 text-center">3</td><td colspan="3" class="p-2 border dark:border-gray-600">Layanan nifas min. 3x</td><td colspan="2" class="p-2 border dark:border-gray-600 text-center">${data.ibu_hamil.capaianKonvergensi?.pemeriksaan_nifas?.Y || 0}</td><td class="p-2 border dark:border-gray-600 text-center">${data.ibu_hamil.capaianKonvergensi?.pemeriksaan_nifas?.persen || '0.00'}</td></tr>
                                <tr><td class="p-2 border dark:border-gray-600 text-center">4</td><td colspan="3" class="p-2 border dark:border-gray-600">Konseling gizi/kelas ibu hamil min. 4x</td><td colspan="2" class="p-2 border dark:border-gray-600 text-center">${data.ibu_hamil.capaianKonvergensi?.konseling_gizi?.Y || 0}</td><td class="p-2 border dark:border-gray-600 text-center">${data.ibu_hamil.capaianKonvergensi?.konseling_gizi?.persen || '0.00'}</td></tr>
                                <tr><td class="p-2 border dark:border-gray-600 text-center">5</td><td colspan="3" class="p-2 border dark:border-gray-600">Kunjungan rumah (KEK/RESTI)</td><td colspan="2" class="p-2 border dark:border-gray-600 text-center">${data.ibu_hamil.capaianKonvergensi?.kunjungan_rumah?.Y || 0}</td><td class="p-2 border dark:border-gray-600 text-center">${data.ibu_hamil.capaianKonvergensi?.kunjungan_rumah?.persen || '0.00'}</td></tr>
                                <tr><td class="p-2 border dark:border-gray-600 text-center">6</td><td colspan="3" class="p-2 border dark:border-gray-600">Akses air minum aman</td><td colspan="2" class="p-2 border dark:border-gray-600 text-center">${data.ibu_hamil.capaianKonvergensi?.akses_air_bersih?.Y || 0}</td><td class="p-2 border dark:border-gray-600 text-center">${data.ibu_hamil.capaianKonvergensi?.akses_air_bersih?.persen || '0.00'}</td></tr>
                                <tr><td class="p-2 border dark:border-gray-600 text-center">7</td><td colspan="3" class="p-2 border dark:border-gray-600">Jamban keluarga layak</td><td colspan="2" class="p-2 border dark:border-gray-600 text-center">${data.ibu_hamil.capaianKonvergensi?.kepemilikan_jamban?.Y || 0}</td><td class="p-2 border dark:border-gray-600 text-center">${data.ibu_hamil.capaianKonvergensi?.kepemilikan_jamban?.persen || '0.00'}</td></tr>
                                <tr><td class="p-2 border dark:border-gray-600 text-center">8</td><td colspan="3" class="p-2 border dark:border-gray-600">Jaminan layanan kesehatan</td><td colspan="2" class="p-2 border dark:border-gray-600 text-center">${data.ibu_hamil.capaianKonvergensi?.jaminan_kesehatan?.Y || 0}</td><td class="p-2 border dark:border-gray-600 text-center">${data.ibu_hamil.capaianKonvergensi?.jaminan_kesehatan?.persen || '0.00'}</td></tr>
                                
                                <tr><td colspan="2" rowspan="10" class="p-2 border dark:border-gray-600 text-center align-middle font-semibold">Anak 0-23 Bulan</td><td class="p-2 border dark:border-gray-600 text-center">1</td><td colspan="3" class="p-2 border dark:border-gray-600">Imunisasi dasar lengkap</td><td colspan="2" class="p-2 border dark:border-gray-600 text-center">${data.bulanan_anak.capaianKonvergensi?.imunisasi?.Y || 0}</td><td class="p-2 border dark:border-gray-600 text-center">${data.bulanan_anak.capaianKonvergensi?.imunisasi?.persen || '0.00'}</td></tr>
                                <tr><td class="p-2 border dark:border-gray-600 text-center">2</td><td colspan="3" class="p-2 border dark:border-gray-600">Ukur berat badan rutin</td><td colspan="2" class="p-2 border dark:border-gray-600 text-center">${data.bulanan_anak.capaianKonvergensi?.pengukuran_berat_badan?.Y || 0}</td><td class="p-2 border dark:border-gray-600 text-center">${data.bulanan_anak.capaianKonvergensi?.pengukuran_berat_badan?.persen || '0.00'}</td></tr>
                                <tr><td class="p-2 border dark:border-gray-600 text-center">3</td><td colspan="3" class="p-2 border dark:border-gray-600">Ukur tinggi badan min. 2x/tahun</td><td colspan="2" class="p-2 border dark:border-gray-600 text-center">${data.bulanan_anak.capaianKonvergensi?.pengukuran_tinggi_badan?.Y || 0}</td><td class="p-2 border dark:border-gray-600 text-center">${data.bulanan_anak.capaianKonvergensi?.pengukuran_tinggi_badan?.persen || '0.00'}</td></tr>
                                <tr><td class="p-2 border dark:border-gray-600 text-center">4</td><td colspan="3" class="p-2 border dark:border-gray-600">Konseling gizi rutin</td><td colspan="2" class="p-2 border dark:border-gray-600 text-center">0</td><td class="p-2 border dark:border-gray-600 text-center">0.00</td></tr>
                                <tr><td class="p-2 border dark:border-gray-600 text-center">5</td><td colspan="3" class="p-2 border dark:border-gray-600">Kunjungan rumah (gizi buruk/kurang/stunting)</td><td colspan="2" class="p-2 border dark:border-gray-600 text-center">${data.bulanan_anak.capaianKonvergensi?.kunjungan_rumah?.Y || 0}</td><td class="p-2 border dark:border-gray-600 text-center">${data.bulanan_anak.capaianKonvergensi?.kunjungan_rumah?.persen || '0.00'}</td></tr>
                                <tr><td class="p-2 border dark:border-gray-600 text-center">6</td><td colspan="3" class="p-2 border dark:border-gray-600">Akses air minum aman</td><td colspan="2" class="p-2 border dark:border-gray-600 text-center">${data.bulanan_anak.capaianKonvergensi?.air_bersih?.Y || 0}</td><td class="p-2 border dark:border-gray-600 text-center">${data.bulanan_anak.capaianKonvergensi?.air_bersih?.persen || '0.00'}</td></tr>
                                <tr><td class="p-2 border dark:border-gray-600 text-center">7</td><td colspan="3" class="p-2 border dark:border-gray-600">Jamban layak</td><td colspan="2" class="p-2 border dark:border-gray-600 text-center">${data.bulanan_anak.capaianKonvergensi?.jamban_sehat?.Y || 0}</td><td class="p-2 border dark:border-gray-600 text-center">${data.bulanan_anak.capaianKonvergensi?.jamban_sehat?.persen || '0.00'}</td></tr>
                                <tr><td class="p-2 border dark:border-gray-600 text-center">8</td><td colspan="3" class="p-2 border dark:border-gray-600">Akta kelahiran</td><td colspan="2" class="p-2 border dark:border-gray-600 text-center">${data.bulanan_anak.capaianKonvergensi?.akta_lahir?.Y || 0}</td><td class="p-2 border dark:border-gray-600 text-center">${data.bulanan_anak.capaianKonvergensi?.akta_lahir?.persen || '0.00'}</td></tr>
                                <tr><td class="p-2 border dark:border-gray-600 text-center">9</td><td colspan="3" class="p-2 border dark:border-gray-600">Jaminan layanan kesehatan</td><td colspan="2" class="p-2 border dark:border-gray-600 text-center">${data.bulanan_anak.capaianKonvergensi?.jaminan_kesehatan?.Y || 0}</td><td class="p-2 border dark:border-gray-600 text-center">${data.bulanan_anak.capaianKonvergensi?.jaminan_kesehatan?.persen || '0.00'}</td></tr>
                                <tr><td class="p-2 border dark:border-gray-600 text-center">10</td><td colspan="3" class="p-2 border dark:border-gray-600">Mengikuti kelas pengasuhan PAUD</td><td colspan="2" class="p-2 border dark:border-gray-600 text-center">${data.bulanan_anak.capaianKonvergensi?.pengasuhan_paud?.Y || 0}</td><td class="p-2 border dark:border-gray-600 text-center">${data.bulanan_anak.capaianKonvergensi?.pengasuhan_paud?.persen || '0.00'}</td></tr>
                                
                                <tr><th colspan="9" class="p-2 font-bold text-left bg-gray-100 dark:bg-gray-700/50">TABEL 4. TINGKAT KONVERGENSI DESA</th></tr>
                                <tr class="bg-gray-50 dark:bg-gray-700/30 text-center"><td class="p-2 border dark:border-gray-600 font-semibold">No</td><td colspan="3" class="p-2 border dark:border-gray-600 font-semibold">SASARAN</td><td colspan="3" class="p-2 border dark:border-gray-600 font-semibold">JUMLAH INDIKATOR</td><td colspan="2" class="p-2 border dark:border-gray-600 font-semibold">TINGKAT KONVERGENSI (%)</td></tr>
                                <tr class="bg-gray-50 dark:bg-gray-700/30 text-center"><td></td><td colspan="3"></td><td class="p-2 border dark:border-gray-600">YANG DITERIMA</td><td colspan="2" class="p-2 border dark:border-gray-600">SEHARUSNYA DITERIMA</td><td colspan="2"></td></tr>
                                <tr class="text-center"><td class="p-2 border dark:border-gray-600">1</td><td colspan="3" class="p-2 border dark:border-gray-600 text-left">Ibu Hamil</td><td class="p-2 border dark:border-gray-600">${tkd_ibu.jumlah_diterima || 0}</td><td colspan="2" class="p-2 border dark:border-gray-600">${tkd_ibu.jumlah_seharusnya || 0}</td><td colspan="2" class="p-2 border dark:border-gray-600">${tkd_ibu.persen || '0.00'}</td></tr>
                                <tr class="text-center"><td class="p-2 border dark:border-gray-600">2</td><td colspan="3" class="p-2 border dark:border-gray-600 text-left">Anak 0 - 23 Bulan</td><td class="p-2 border dark:border-gray-600">${tkd_anak.jumlah_diterima || 0}</td><td colspan="2" class="p-2 border dark:border-gray-600">${tkd_anak.jumlah_seharusnya || 0}</td><td colspan="2" class="p-2 border dark:border-gray-600">${tkd_anak.persen || '0.00'}</td></tr>
                                <tr class="text-center font-bold"><td colspan="4" class="p-2 border dark:border-gray-600">TOTAL TINGKAT KONVERGENSI DESA</td><td class="p-2 border dark:border-gray-600">${JLD_TOTAL}</td><td colspan="2" class="p-2 border dark:border-gray-600">${JYSD_TOTAL}</td><td colspan="2" class="p-2 border dark:border-gray-600">${KONV_TOTAL}</td></tr>

                                <tr><th colspan="9" class="p-2 font-bold text-left bg-gray-100 dark:bg-gray-700/50">TABEL 5. PENGGUNAAN DANA DESA DALAM PENCEGAHAN STUNTING</th></tr>
                                <tr class="text-center"><td class="p-2 border dark:border-gray-600">1</td><td colspan="3" class="p-2 border dark:border-gray-600 text-left">Bidang Pembangunan Desa</td><td colspan="1" class="p-2 border dark:border-gray-600"></td><td colspan="2" class="p-2 border dark:border-gray-600"></td><td colspan="2" class="p-2 border dark:border-gray-600">%</td></tr>
                                <tr class="text-center"><td class="p-2 border dark:border-gray-600">2</td><td colspan="3" class="p-2 border dark:border-gray-600 text-left">Bidang Pemberdayaan Masyarakat Desa</td><td colspan="1" class="p-2 border dark:border-gray-600"></td><td colspan="2" class="p-2 border dark:border-gray-600"></td><td colspan="2" class="p-2 border dark:border-gray-600">%</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                `;
            },

            generateCetakUrl(isUnduh) {
                const action = isUnduh ? 'unduh' : 'cetak';
                const base = `{{ ci_route('data-kesehatan.cetak.${action}') }}`;
                return `${base}?kuartal=${this.selectedKuartal}&tahun=${this.selectedTahun}&id=${this.selectedPosyandu}`;
            }
        };
    }
</script>
@endpush