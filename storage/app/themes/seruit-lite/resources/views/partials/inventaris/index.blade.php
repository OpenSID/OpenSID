@extends('theme::layouts.full-content')

@section('content')
<div class="bg-white dark:bg-gray-800 p-4 sm:p-6 rounded-none shadow-xl border border-gray-200 dark:border-gray-700 -mt-16 relative z-10">
    <div class="text-center mb-8">
        <nav role="navigation" aria-label="navigation" class="breadcrumb text-sm text-gray-500 dark:text-gray-400">
            <ol class="flex items-center justify-center space-x-2">
                <li><a href="{{ site_url() }}" class="hover:underline hover:text-blue-600">Beranda</a></li>
                <li><span class="mx-2">/</span></li>
                <li aria-current="page" class="font-medium text-gray-700 dark:text-gray-300">Data Inventaris</li>
            </ol>
        </nav>
        <div class="flex items-center mt-6">
            <div class="flex-grow border-t border-gray-300 dark:border-gray-700"></div>
            <h1 class="flex-shrink px-4 text-2xl lg:text-3xl font-bold uppercase">Inventaris Desa</h1>
            <div class="flex-grow border-t border-gray-300 dark:border-gray-700"></div>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm" id="tabel-inventaris-ringkasan">
            <thead class="bg-gray-100 dark:bg-gray-700/50">
                <tr>
                    <th class="p-3 text-center text-xs font-bold uppercase tracking-wider border border-gray-300 dark:border-gray-600" rowspan="3">No</th>
                    <th class="p-3 text-left text-xs font-bold uppercase tracking-wider border border-gray-300 dark:border-gray-600" rowspan="3">Jenis Barang / Bangunan</th>
                    <th class="p-3 text-center text-xs font-bold uppercase tracking-wider border border-gray-300 dark:border-gray-600" colspan="5">Asal Barang</th>
                    <th class="p-3 text-center text-xs font-bold uppercase tracking-wider border border-gray-300 dark:border-gray-600" rowspan="3">Aksi</th>
                </tr>
                <tr>
                    <th class="p-2 text-center text-xs font-bold uppercase tracking-wider border border-gray-300 dark:border-gray-600" rowspan="2">Dibeli Sendiri</th>
                    <th class="p-2 text-center text-xs font-bold uppercase tracking-wider border border-gray-300 dark:border-gray-600" colspan="3">Bantuan</th>
                    <th class="p-2 text-center text-xs font-bold uppercase tracking-wider border border-gray-300 dark:border-gray-600" rowspan="2">Sumbangan</th>
                </tr>
                <tr>
                    <th class="p-2 text-center text-xs font-bold uppercase tracking-wider border border-gray-300 dark:border-gray-600">Pemerintah</th>
                    <th class="p-2 text-center text-xs font-bold uppercase tracking-wider border border-gray-300 dark:border-gray-600">Provinsi</th>
                    <th class="p-2 text-center text-xs font-bold uppercase tracking-wider border border-gray-300 dark:border-gray-600">Kab/Kota</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="9" class="p-8 text-center border border-gray-300 dark:border-gray-600">
                        <div class="flex items-center justify-center space-x-2 text-gray-500">
                            <svg class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                            <span>Memuat Data...</span>
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
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const apiUrl = `{{ ci_route('internal_api.inventaris') }}`;
        const tbody = document.querySelector('#tabel-inventaris-ringkasan tbody');
        const tfoot = document.querySelector('#tabel-inventaris-ringkasan tfoot');

        fetch(apiUrl)
            .then(response => response.json())
            .then(data => {
                tbody.innerHTML = '';
                tfoot.innerHTML = '';
                
                if (!data.data || data.data.length === 0) {
                    tbody.innerHTML = `<tr><td colspan="9" class="p-4 text-center text-gray-500 border border-gray-300 dark:border-gray-600">Data inventaris tidak tersedia.</td></tr>`;
                    return;
                }
                
                const inventarisData = data.data[0].attributes;
                let totals = { pribadi: 0, pemerintah: 0, provinsi: 0, kabupaten: 0, sumbangan: 0 };

                inventarisData.forEach((item, index) => {
                    Object.keys(totals).forEach(key => totals[key] += (item[key] || 0));
                    const row = `
                        <tr class="border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700/50">
                            <td class="p-2 text-center border border-gray-300 dark:border-gray-600">${index + 1}</td>
                            <td class="p-2 border border-gray-300 dark:border-gray-600">${item.jenis}</td>
                            <td class="p-2 text-center border border-gray-300 dark:border-gray-600">${item.pribadi || 0}</td>
                            <td class="p-2 text-center border border-gray-300 dark:border-gray-600">${item.pemerintah || 0}</td>
                            <td class="p-2 text-center border border-gray-300 dark:border-gray-600">${item.provinsi || 0}</td>
                            <td class="p-2 text-center border border-gray-300 dark:border-gray-600">${item.kabupaten || 0}</td>
                            <td class="p-2 text-center border border-gray-300 dark:border-gray-600">${item.sumbangan || 0}</td>
                            <td class="p-2 text-center border border-gray-300 dark:border-gray-600">
                                <a href="${item.url}" title="Lihat Data" class="btn btn-primary text-xs"><i class="fas fa-eye"></i></a>
                            </td>
                        </tr>
                    `;
                    tbody.insertAdjacentHTML('beforeend', row);
                });
                
                tfoot.innerHTML = `
                    <tr>
                        <td colspan="2" class="p-3 text-left text-xs font-bold uppercase tracking-wider border border-gray-300 dark:border-gray-600">TOTAL</td>
                        <td class="p-3 text-center border border-gray-300 dark:border-gray-600">${totals.pribadi}</td>
                        <td class="p-3 text-center border border-gray-300 dark:border-gray-600">${totals.pemerintah}</td>
                        <td class="p-3 text-center border border-gray-300 dark:border-gray-600">${totals.provinsi}</td>
                        <td class="p-3 text-center border border-gray-300 dark:border-gray-600">${totals.kabupaten}</td>
                        <td class="p-3 text-center border border-gray-300 dark:border-gray-600">${totals.sumbangan}</td>
                        <td class="border border-gray-300 dark:border-gray-600"></td>
                    </tr>
                `;
            })
            .catch(error => {
                console.error("Error fetching inventory data:", error);
                tbody.innerHTML = `<tr><td colspan="9" class="p-4 text-center text-red-500 border border-gray-300 dark:border-gray-600">Gagal memuat data inventaris.</td></tr>`;
            });
    });
</script>
@endpush