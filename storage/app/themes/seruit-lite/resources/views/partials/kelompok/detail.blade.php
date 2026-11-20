@extends('theme::layouts.full-content')

@push('styles')
<style>
    .dataTables_wrapper .dataTables_length,
    .dataTables_wrapper .dataTables_filter {
        @apply mb-4;
    }

    .dataTables_wrapper .dataTables_length label,
    .dataTables_wrapper .dataTables_filter label {
        @apply text-sm font-medium text-gray-700 dark:text-gray-300;
    }

    .dataTables_wrapper .dataTables_length select,
    .dataTables_wrapper .dataTables_filter input {
        @apply bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 shadow-sm px-3 py-1.5 ml-2 focus:ring-blue-500 focus:border-blue-500;
        border-radius: 0 !important;
    }

    .dataTables_wrapper .dataTables_info {
        @apply text-sm text-gray-600 dark:text-gray-400 pt-4;
    }

    .dataTables_wrapper .dataTables_paginate {
        @apply pt-4;
    }
    
    .dataTables_wrapper .dataTables_paginate .paginate_button {
        @apply inline-flex items-center justify-center px-4 py-2 text-sm font-medium border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-600 dark:text-gray-300 transition-colors duration-200;
        margin-left: -1px;
        border-radius: 0 !important;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
        @apply bg-gray-100 dark:bg-gray-700 z-10;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button.current {
        @apply bg-blue-600 text-white border-blue-600 dark:bg-blue-500 dark:border-blue-500 z-20;
    }
    
    .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover {
        @apply bg-blue-700 dark:bg-blue-600;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button.disabled {
        @apply bg-gray-100 dark:bg-gray-700 text-gray-400 dark:text-gray-500 cursor-not-allowed opacity-50;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button:first-child {
        margin-left: 0;
    }
</style>
@endpush

@section('content')
<div x-data="kelompokDetail()" class="bg-[var(--bg-color-card)] p-6 shadow-xl border border-[var(--border-color)] -mt-16 relative z-10">
    <nav role="navigation" aria-label="navigation" class="breadcrumb text-sm mb-4">
        <ol class="flex items-center justify-center space-x-2 text-gray-500 dark:text-gray-400">
            <li><a href="{{ site_url() }}" class="hover:underline hover:text-blue-600">Beranda</a></li>
            <li><span class="mx-2">/</span></li>
            <li><a href="{{ site_url('kelompok') }}" class="hover:underline hover:text-blue-600">Kelompok</a></li>
            <li><span class="mx-2">/</span></li>
            <li aria-current="page" class="font-medium text-gray-700 dark:text-gray-300" x-text="detail.kategori || 'Detail'"></li>
        </ol>
    </nav>
    <div x-show="!isLoading" class="flex items-center mt-6">
        <div class="flex-grow border-t border-gray-300 dark:border-gray-700"></div>
        <h1 class="flex-shrink px-4 text-2xl lg:text-3xl font-bold uppercase text-center" x-text="detail.nama"></h1>
        <div class="flex-grow border-t border-gray-300 dark:border-gray-700"></div>
    </div>
    
    <div x-show="isLoading" class="text-center py-12">
        <svg class="animate-spin h-8 w-8 text-blue-600 mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
        <p class="mt-2 text-sm font-semibold">Memuat Detail Kelompok...</p>
    </div>

    <div x-show="!isLoading" x-cloak class="space-y-12 mt-8">
        <div>
            <h2 class="text-xl font-bold mb-4">Rincian Data Kelompok</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-center">
                <div class="md:col-span-2 overflow-x-auto">
                    <table class="w-full text-sm border-collapse">
                        <tbody>
                            <tr class="border-b dark:border-gray-700"><td class="p-2 font-semibold w-1/3 border dark:border-gray-600">Nama</td><td class="p-2 border dark:border-gray-600" x-text="detail.nama"></td></tr>
                            <tr class="border-b dark:border-gray-700"><td class="p-2 font-semibold border dark:border-gray-600">Kode</td><td class="p-2 border dark:border-gray-600" x-text="detail.kode"></td></tr>
                            <tr class="border-b dark:border-gray-700"><td class="p-2 font-semibold border dark:border-gray-600">Ketua</td><td class="p-2 border dark:border-gray-600" x-text="detail.ketua"></td></tr>
                            <tr class="border-b dark:border-gray-700"><td class="p-2 font-semibold border dark:border-gray-600">Kategori</td><td class="p-2 border dark:border-gray-600" x-text="detail.kategori"></td></tr>
                            <tr class="border-b dark:border-gray-700"><td class="p-2 font-semibold border dark:border-gray-600">Keterangan</td><td class="p-2 border dark:border-gray-600" x-text="detail.keterangan"></td></tr>
                        </tbody>
                    </table>
                </div>
                <div class="flex justify-center">
                    <img :src="detail.logo" :alt="'Logo ' + detail.nama" class="w-40 h-40 object-contain p-2 border dark:border-gray-600 bg-gray-50 dark:bg-gray-700">
                </div>
            </div>
        </div>

        <div>
            <h2 class="text-xl font-bold mb-4">Daftar Pengurus</h2>
            <div class="overflow-x-auto">
                <table class="w-full text-sm border-collapse">
                    <thead class="bg-gray-100 dark:bg-gray-700/50">
                        <tr>
                            <th class="p-3 text-center text-xs font-bold uppercase tracking-wider w-12 border border-gray-300 dark:border-gray-600">No</th>
                            <th class="p-3 text-left text-xs font-bold uppercase tracking-wider border border-gray-300 dark:border-gray-600">Jabatan</th>
                            <th class="p-3 text-left text-xs font-bold uppercase tracking-wider border border-gray-300 dark:border-gray-600">Nama</th>
                            <th class="p-3 text-left text-xs font-bold uppercase tracking-wider border border-gray-300 dark:border-gray-600">Alamat</th>
                        </tr>
                    </thead>
                    <tbody>
                        <template x-for="(pengurus, index) in pengurusList" :key="index">
                            <tr class="border-b dark:border-gray-700">
                                <td class="p-2 text-center border border-gray-300 dark:border-gray-600" x-text="index + 1"></td>
                                <td class="p-2 border border-gray-300 dark:border-gray-600" x-text="pengurus.nama_jabatan"></td>
                                <td class="p-2 border border-gray-300 dark:border-gray-600" x-text="pengurus.nama_penduduk"></td>
                                <td class="p-2 whitespace-normal border border-gray-300 dark:border-gray-600" x-text="pengurus.alamat_lengkap"></td>
                            </tr>
                        </template>
                         <tr x-show="pengurusList.length === 0">
                            <td colspan="4" class="p-4 text-center text-gray-500 border border-gray-300 dark:border-gray-600">Data pengurus tidak tersedia.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        
        <div>
            <h2 class="text-xl font-bold mb-4">Daftar Anggota</h2>
            <div class="overflow-x-auto">
                <table class="w-full text-sm border-collapse" id="tabel-anggota">
                    <thead class="bg-gray-100 dark:bg-gray-700/50">
                        <tr>
                            <th class="p-3 text-center text-xs font-bold uppercase tracking-wider border border-gray-300 dark:border-gray-600">No</th>
                            <th class="p-3 text-left text-xs font-bold uppercase tracking-wider border border-gray-300 dark:border-gray-600">No. Anggota</th>
                            <th class="p-3 text-left text-xs font-bold uppercase tracking-wider border border-gray-300 dark:border-gray-600">Nama</th>
                            <th class="p-3 text-left text-xs font-bold uppercase tracking-wider border border-gray-300 dark:border-gray-600">Alamat</th>
                            <th class="p-3 text-center text-xs font-bold uppercase tracking-wider border border-gray-300 dark:border-gray-600">L/P</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function kelompokDetail() {
        return {
            detail: {},
            pengurusList: [],
            isLoading: true,
            init() {
                const detailUrl = `{{ route('api.kelompok.detail', $slug) }}`;
                fetch(detailUrl)
                    .then(response => response.json())
                    .then(data => {
                        this.detail = data.data.attributes;
                        this.pengurusList = data.data.attributes.pengurus;
                        this.isLoading = false;
                        this.$nextTick(() => {
                            this.initAnggotaTable();
                        });
                    })
                    .catch(error => {
                        console.error('Error fetching detail:', error);
                        this.isLoading = false;
                    });
            },
            initAnggotaTable() {
                $('#tabel-anggota').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: `{{ route('api.kelompok.anggota', $slug) }}`,
                        method: 'POST',
                        data: d => ({
                            "page[size]": d.length,
                            "page[number]": (d.start / d.length) + 1,
                            "filter[search]": d.search.value,
                            "sort": d.order.length ? (d.order[0].dir === 'asc' ? '' : '-') + d.columns[d.order[0].column].name : ''
                        }),
                        dataSrc: json => {
                            json.recordsTotal = json.meta.pagination.total;
                            json.recordsFiltered = json.meta.pagination.total;
                            return json.data;
                        }
                    },
                    columns: [
                        { data: null, searchable: false, orderable: false, className: 'text-center p-2 border border-gray-300 dark:border-gray-600' },
                        { data: 'attributes.no_anggota', name: 'no_anggota', className: 'p-2 border border-gray-300 dark:border-gray-600' },
                        { data: 'attributes.anggota.nama', name: 'nama', className: 'whitespace-normal p-2 border border-gray-300 dark:border-gray-600' },
                        { data: 'attributes.alamat_lengkap', name: 'alamat', orderable: false, className: 'whitespace-normal p-2 border border-gray-300 dark:border-gray-600' },
                        { data: 'attributes.sex', name: 'jenis_kelamin', className: 'text-center p-2 border border-gray-300 dark:border-gray-600' }
                    ],
                    order: [[2, 'asc']],
                    language: { url: "{{ asset('bootstrap/js/dataTables.indonesian.lang') }}" },
                    drawCallback: function() {
                        var api = this.api();
                        api.column(0, { search: 'applied', order: 'applied' }).nodes().each((cell, i) => {
                            cell.innerHTML = api.page.info().start + i + 1;
                        });
                    }
                });
            }
        };
    }
</script>
@endpush