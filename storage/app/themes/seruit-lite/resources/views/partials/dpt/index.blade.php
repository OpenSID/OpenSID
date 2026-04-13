@extends('theme::layouts.full-content')

@section('content')
<div class="bg-[var(--bg-color-card)] p-6 shadow-xl border border-[var(--border-color)] -mt-16 relative z-10">
    <nav role="navigation" aria-label="navigation" class="breadcrumb text-sm mb-4">
        <ol class="flex items-center justify-center space-x-2 text-gray-500 dark:text-gray-400">
            <li><a href="{{ site_url() }}" class="hover:underline hover:text-blue-600">Beranda</a></li>
            <li><span class="mx-2">/</span></li>
            <li aria-current="page" class="font-medium text-gray-700 dark:text-gray-300">{{ $heading }}</li>
        </ol>
    </nav>
    <div class="flex items-center mt-6">
        <div class="flex-grow border-t border-gray-300 dark:border-gray-700"></div>
        <h1 class="flex-shrink px-4 text-2xl lg:text-3xl font-bold uppercase text-center">{{ $heading }}</h1>
        <div class="flex-grow border-t border-gray-300 dark:border-gray-700"></div>
    </div>

    <div class="mt-4 text-center text-sm text-gray-600 dark:text-gray-400">
        <p>Tanggal Pemilihan: <strong>{{ e($tanggal_pemilihan) }}</strong></p>
    </div>

    <div class="overflow-x-auto mt-8">
        <table class="w-full text-sm border-collapse" id="tabel-dpt">
            <thead class="bg-gray-100 dark:bg-gray-700/50">
                <tr>
                    <th class="p-3 text-center text-xs font-bold uppercase tracking-wider border border-gray-300 dark:border-gray-600">No</th>
                    <th class="p-3 text-left text-xs font-bold uppercase tracking-wider border border-gray-300 dark:border-gray-600">{{ ucwords(setting('sebutan_dusun')) }}</th>
                    <th class="p-3 text-center text-xs font-bold uppercase tracking-wider border border-gray-300 dark:border-gray-600">RW</th>
                    <th class="p-3 text-center text-xs font-bold uppercase tracking-wider border border-gray-300 dark:border-gray-600">Jiwa</th>
                    <th class="p-3 text-center text-xs font-bold uppercase tracking-wider border border-gray-300 dark:border-gray-600">Laki-laki</th>
                    <th class="p-3 text-center text-xs font-bold uppercase tracking-wider border border-gray-300 dark:border-gray-600">Perempuan</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="6" class="p-8 text-center border border-gray-300 dark:border-gray-600">
                        <div class="flex items-center justify-center space-x-2 text-gray-500">
                            <svg class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="http://www.w3.org/2000/svg"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                            <span>Memuat Data DPT...</span>
                        </div>
                    </td>
                </tr>
            </tbody>
            <tfoot class="bg-gray-100 dark:bg-gray-700/50 font-bold">
            </tfoot>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script type="text/javascript">
    document.addEventListener("DOMContentLoaded", function () {
        const apiUrl = `{{ ci_route('internal_api.dpt') }}?tgl_pemilihan={{ $tanggal_pemilihan }}`;
        const tbody = document.querySelector('#tabel-dpt tbody');
        const tfoot = document.querySelector('#tabel-dpt tfoot');

        fetch(apiUrl)
            .then(response => response.json())
            .then(data => {
                renderTable(data.data);
            })
            .catch(error => {
                console.error("Error fetching DPT data:", error);
                tbody.innerHTML = `<tr><td colspan="6" class="p-4 text-center text-red-500 border border-gray-300 dark:border-gray-600">Terjadi kesalahan saat memuat data.</td></tr>`;
                tfoot.innerHTML = '';
            });

        function renderTable(data) {
            tbody.innerHTML = '';
            tfoot.innerHTML = '';

            if (!data || data.length === 0) {
                tbody.innerHTML = `<tr><td colspan="6" class="p-4 text-center text-gray-500 border border-gray-300 dark:border-gray-600">Data pemilih tidak tersedia.</td></tr>`;
                return;
            }

            const groupedData = groupData(data);
            let totals = { jiwa: 0, laki: 0, perempuan: 0 };
            
            groupedData.forEach((item, index) => {
                const jiwa = item.totalLaki + item.totalPerempuan;
                totals.jiwa += jiwa;
                totals.laki += item.totalLaki;
                totals.perempuan += item.totalPerempuan;

                const row = `
                    <tr class="border-b dark:border-gray-700">
                        <td class="p-2 text-center border border-gray-300 dark:border-gray-600">${index + 1}</td>
                        <td class="p-2 border border-gray-300 dark:border-gray-600">${escapeHtml(item.dusun)}</td>
                        <td class="p-2 text-center border border-gray-300 dark:border-gray-600">${escapeHtml(item.rw)}</td>
                        <td class="p-2 text-center font-semibold border border-gray-300 dark:border-gray-600">${jiwa}</td>
                        <td class="p-2 text-center border border-gray-300 dark:border-gray-600">${item.totalLaki}</td>
                        <td class="p-2 text-center border border-gray-300 dark:border-gray-600">${item.totalPerempuan}</td>
                    </tr>
                `;
                tbody.insertAdjacentHTML('beforeend', row);
            });
            
            tfoot.innerHTML = `
                <tr>
                    <td colspan="3" class="p-3 text-left text-xs font-bold uppercase tracking-wider border border-gray-300 dark:border-gray-600">TOTAL</td>
                    <td class="p-3 text-center font-bold border border-gray-300 dark:border-gray-600">${totals.jiwa}</td>
                    <td class="p-3 text-center font-bold border border-gray-300 dark:border-gray-600">${totals.laki}</td>
                    <td class="p-3 text-center font-bold border border-gray-300 dark:border-gray-600">${totals.perempuan}</td>
                </tr>
            `;
        }

        function groupData(inputData) {
            const grouped = {};
            inputData.forEach(item => {
                const dusun = item.attributes.dusun || 'N/A';
                const rw = item.attributes.rw || 'N/A';
                const sex = item.attributes.sex;
                const total = item.attributes.total;
                const key = `${dusun}-${rw}`;

                if (!grouped[key]) {
                    grouped[key] = { dusun: dusun, rw: rw, totalLaki: 0, totalPerempuan: 0 };
                }

                if (sex === 1) {
                    grouped[key].totalLaki += total;
                } else if (sex === 2) {
                    grouped[key].totalPerempuan += total;
                }
            });
            return Object.values(grouped);
        }

        function escapeHtml(text) {
            if (typeof text !== 'string') return text;
            const map = { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;' };
            return text.replace(/[&<>"']/g, m => map[m]);
        }
    });
</script>
@endpush