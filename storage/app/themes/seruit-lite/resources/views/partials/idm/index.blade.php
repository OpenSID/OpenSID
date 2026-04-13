@extends('theme::layouts.full-content')

@section('content')
<div class="bg-[var(--bg-color-card)] p-6 shadow-xl border border-[var(--border-color)] -mt-16 relative z-10">
    <nav role="navigation" aria-label="navigation" class="breadcrumb text-sm mb-4">
        <ol class="flex items-center justify-center space-x-2 text-gray-500 dark:text-gray-400">
            <li><a href="{{ site_url() }}" class="hover:underline hover:text-blue-600">Beranda</a></li>
            <li><span class="mx-2">/</span></li>
            <li aria-current="page" class="font-medium text-gray-700 dark:text-gray-300">Status IDM</li>
        </ol>
    </nav>
    <div class="flex items-center mt-6">
        <div class="flex-grow border-t border-gray-300 dark:border-gray-700"></div>
        <h1 class="flex-shrink px-4 text-2xl lg:text-3xl font-bold uppercase text-center">Indeks Desa Membangun (IDM) {{ $tahun }}</h1>
        <div class="flex-grow border-t border-gray-300 dark:border-gray-700"></div>
    </div>
    
    <div id="idm-container" class="mt-8">
        <div id="idm-loading" class="col-span-full flex flex-col items-center justify-center py-12">
            <svg class="animate-spin h-8 w-8 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
            <p class="mt-2 text-sm font-semibold">Memuat Data IDM Tahun {{ $tahun }}...</p>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const tahun = '{{ $tahun }}';
        const apiUrl = `{{ route('api.idm', $tahun) }}`;
        const container = document.getElementById('idm-container');

        fetch(apiUrl)
            .then(response => {
                if (!response.ok) {
                    throw new Error(`Data IDM tahun ${tahun} tidak ditemukan.`);
                }
                return response.json();
            })
            .then(data => {
                if (data.error_msg) {
                    throw new Error(data.error_msg);
                }
                renderContent(data.data[0].attributes);
            })
            .catch(error => {
                console.error("Error fetching IDM data:", error);
                container.innerHTML = `<div class="alert alert-danger text-center">${error.message}</div>`;
            });
            
        function renderContent(data) {
            const { SUMMARIES: summaries, ROW: row, IDENTITAS: identitas } = data;
            const iks = parseFloat(row[35].SKOR || 0);
            const ike = parseFloat(row[48].SKOR || 0);
            const ikl = parseFloat(row[52].SKOR || 0);

            let tableRows = '';
            row.forEach(item => {
                tableRows += `
                    <tr class="border-b dark:border-gray-700">
                        <td class="p-2 text-center border-x dark:border-gray-600">${item.NO || ''}</td>
                        <td class="p-2 whitespace-normal border-x dark:border-gray-600">${item.INDIKATOR || ''}</td>
                        <td class="p-2 text-center border-x dark:border-gray-600">${item.SKOR || ''}</td>
                        <td class="p-2 whitespace-normal border-x dark:border-gray-600">${item.KETERANGAN || ''}</td>
                        <td class="p-2 whitespace-normal border-x dark:border-gray-600">${item.KEGIATAN || ''}</td>
                        <td class="p-2 text-center border-x dark:border-gray-600">${item.NILAI || ''}</td>
                        <td class="p-2 text-center border-x dark:border-gray-600">${item.PUSAT || ''}</td>
                        <td class="p-2 text-center border-x dark:border-gray-600">${item.PROV || ''}</td>
                        <td class="p-2 text-center border-x dark:border-gray-600">${item.KAB || ''}</td>
                        <td class="p-2 text-center border-x dark:border-gray-600">${item.DESA || ''}</td>
                        <td class="p-2 text-center border-x dark:border-gray-600">${item.CSR || ''}</td>
                        <td class="p-2 text-center border-x dark:border-gray-600">${item.LAINNYA || ''}</td>
                    </tr>
                `;
            });

            container.innerHTML = `
                <div class="space-y-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 text-white">
                        <div class="bg-blue-500 p-4 shadow-lg"><p class="text-sm uppercase text-blue-100">SKOR IDM SAAT INI</p><p class="text-3xl font-bold">${parseFloat(summaries.SKOR_SAAT_INI).toFixed(4)}</p></div>
                        <div class="bg-yellow-500 p-4 shadow-lg"><p class="text-sm uppercase text-yellow-100">STATUS IDM</p><p class="text-3xl font-bold">${summaries.STATUS}</p></div>
                        <div class="bg-green-500 p-4 shadow-lg"><p class="text-sm uppercase text-green-100">TARGET STATUS</p><p class="text-3xl font-bold">${summaries.TARGET_STATUS}</p></div>
                        <div class="bg-red-500 p-4 shadow-lg"><p class="text-sm uppercase text-red-100">SKOR MINIMAL</p><p class="text-3xl font-bold">${parseFloat(summaries.SKOR_MINIMAL).toFixed(4)}</p></div>
                    </div>
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-start">
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm border-collapse border dark:border-gray-600">
                                <tbody>
                                    <tr class="border-b dark:border-gray-600"><th class="p-2 font-bold text-left border-r dark:border-gray-600">PROVINSI</th><td class="p-2">${identitas[0].nama_provinsi || ''}</td></tr>
                                    <tr class="border-b dark:border-gray-600"><th class="p-2 font-bold text-left border-r dark:border-gray-600">KABUPATEN</th><td class="p-2">${identitas[0].nama_kab_kota || ''}</td></tr>
                                    <tr class="border-b dark:border-gray-600"><th class="p-2 font-bold text-left border-r dark:border-gray-600">${setting.sebutan_kecamatan.toUpperCase()}</th><td class="p-2">${identitas[0].nama_kecamatan || ''}</td></tr>
                                    <tr><th class="p-2 font-bold text-left border-r dark:border-gray-600">${setting.sebutan_desa.toUpperCase()}</th><td class="p-2">${identitas[0].nama_desa || ''}</td></tr>
                                </tbody>
                            </table>
                        </div>
                        <div><div id="chart-container" class="w-full h-64 border dark:border-gray-700"></div></div>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm border-collapse border dark:border-gray-600">
                            <thead class="bg-gray-100 dark:bg-gray-700/50">
                                <tr class="border-b dark:border-gray-600">
                                    <th class="p-3 text-center text-xs font-bold uppercase tracking-wider border-r dark:border-gray-600" rowspan="2">NO</th>
                                    <th class="p-3 text-left text-xs font-bold uppercase tracking-wider border-r dark:border-gray-600" rowspan="2">INDIKATOR IDM</th>
                                    <th class="p-3 text-center text-xs font-bold uppercase tracking-wider border-r dark:border-gray-600" rowspan="2">SKOR</th>
                                    <th class="p-3 text-left text-xs font-bold uppercase tracking-wider border-r dark:border-gray-600" rowspan="2">KETERANGAN</th>
                                    <th class="p-3 text-left text-xs font-bold uppercase tracking-wider border-r dark:border-gray-600" rowspan="2">KEGIATAN</th>
                                    <th class="p-3 text-center text-xs font-bold uppercase tracking-wider border-r dark:border-gray-600" rowspan="2">+NILAI</th>
                                    <th class="p-3 text-center text-xs font-bold uppercase tracking-wider" colspan="6">PELAKSANA KEGIATAN</th>
                                </tr>
                                <tr class="border-b dark:border-gray-600">
                                    <th class="p-2 text-center text-xs font-bold uppercase tracking-wider border-x dark:border-gray-600">PUSAT</th>
                                    <th class="p-2 text-center text-xs font-bold uppercase tracking-wider border-r dark:border-gray-600">PROV</th>
                                    <th class="p-2 text-center text-xs font-bold uppercase tracking-wider border-r dark:border-gray-600">KAB</th>
                                    <th class="p-2 text-center text-xs font-bold uppercase tracking-wider border-r dark:border-gray-600">DESA</th>
                                    <th class="p-2 text-center text-xs font-bold uppercase tracking-wider border-r dark:border-gray-600">CSR</th>
                                    <th class="p-2 text-center text-xs font-bold uppercase tracking-wider">LAINNYA</th>
                                </tr>
                            </thead>
                            <tbody>${tableRows}</tbody>
                        </table>
                    </div>
                </div>
            `;
            
            renderChart(tahun, iks, ike, ikl);
        }
        
        function renderChart(tahun, iks, ike, ikl) {
            const isDarkMode = document.documentElement.classList.contains('dark');
            Highcharts.chart('chart-container', {
                chart: { type: 'pie', options3d: { enabled: true, alpha: 45 }, backgroundColor: isDarkMode ? '#1f2937' : 'transparent' },
                title: { text: `Indeks Desa Membangun (IDM) ${tahun}`, style: { color: isDarkMode ? '#d1d5db' : '#333333' } },
                subtitle: { text: 'SKOR : IKS, IKE, IKL', style: { color: isDarkMode ? '#9ca3af' : '#666666' } },
                plotOptions: {
                    pie: {
                        allowPointSelect: true, cursor: 'pointer', depth: 35, innerSize: 70,
                        dataLabels: { enabled: true, format: '<b>{point.name}</b>: {point.y:,.4f}', style: { color: isDarkMode ? '#d1d5db' : '#333333' } }
                    }
                },
                series: [{ name: 'SKOR', data: [['IKS', iks], ['IKE', ike], ['IKL', ikl]] }],
                credits: { enabled: false }
            });
        }
    });
</script>
@endpush