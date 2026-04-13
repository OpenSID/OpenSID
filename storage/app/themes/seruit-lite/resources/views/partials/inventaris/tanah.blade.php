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
<div class="bg-white dark:bg-gray-800 p-4 sm:p-6 rounded-none shadow-xl border border-gray-200 dark:border-gray-700 -mt-16 relative z-10">
    <div class="text-center mb-8">
        <nav role="navigation" aria-label="navigation" class="breadcrumb text-sm text-gray-500 dark:text-gray-400">
            <ol class="flex items-center justify-center space-x-2">
                <li><a href="{{ site_url() }}" class="hover:underline hover:text-blue-600">Beranda</a></li>
                <li><span class="mx-2">/</span></li>
                <li><a href="{{ site_url('inventaris') }}" class="hover:underline hover:text-blue-600">Inventaris</a></li>
                <li><span class="mx-2">/</span></li>
                <li aria-current="page" class="font-medium text-gray-700 dark:text-gray-300">{{ $judul }}</li>
            </ol>
        </nav>
        <div class="flex items-center mt-6">
            <div class="flex-grow border-t border-gray-300 dark:border-gray-700"></div>
            <h1 class="flex-shrink px-4 text-2xl lg:text-3xl font-bold uppercase">{{ $judul }}</h1>
            <div class="flex-grow border-t border-gray-300 dark:border-gray-700"></div>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm border-collapse" id="tabel-detail">
            <thead class="bg-gray-100 dark:bg-gray-700/50">
                <tr>
                    <th class="p-3 text-center text-xs font-bold uppercase tracking-wider border border-gray-300 dark:border-gray-600">No</th>
                    <th class="p-3 text-left text-xs font-bold uppercase tracking-wider border border-gray-300 dark:border-gray-600">Nama Barang</th>
                    <th class="p-3 text-left text-xs font-bold uppercase tracking-wider border border-gray-300 dark:border-gray-600">Kode / Reg.</th>
                    <th class="p-3 text-center text-xs font-bold uppercase tracking-wider border border-gray-300 dark:border-gray-600">Luas (M²)</th>
                    <th class="p-3 text-center text-xs font-bold uppercase tracking-wider border border-gray-300 dark:border-gray-600">Tahun</th>
                    <th class="p-3 text-left text-xs font-bold uppercase tracking-wider border border-gray-300 dark:border-gray-600">Alamat</th>
                    <th class="p-3 text-left text-xs font-bold uppercase tracking-wider border border-gray-300 dark:border-gray-600">No. Sertifikat</th>
                    <th class="p-3 text-left text-xs font-bold uppercase tracking-wider border border-gray-300 dark:border-gray-600">Asal</th>
                    <th class="p-3 text-right text-xs font-bold uppercase tracking-wider border border-gray-300 dark:border-gray-600">Harga (Rp)</th>
                </tr>
            </thead>
            <tbody></tbody>
            <tfoot class="bg-gray-100 dark:bg-gray-700/50 font-bold">
                <tr>
                    <th colspan="8" class="p-3 text-right text-xs uppercase">TOTAL</th>
                    <th id="total-harga" class="p-3 text-right text-xs"></th>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function formatRupiah(angka) {
        if (angka == null || isNaN(angka)) return 'Rp 0';
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0,
            maximumFractionDigits: 0
        }).format(angka);
    }

    $(document).ready(function() {
        $('#tabel-detail').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: `{{ ci_route('internal_api.inventaris-tanah') }}`,
                method: 'GET',
                dataSrc: function(json) {
                    json.recordsTotal = json.meta.pagination.total;
                    json.recordsFiltered = json.meta.pagination.total;
                    
                    const totalHarga = json.meta.total_harga || 0;
                    $('#total-harga').text(formatRupiah(totalHarga));
                    
                    return json.data;
                }
            },
            columns: [
                { data: null, searchable: false, orderable: false, className: 'text-center p-2 border border-gray-300 dark:border-gray-600' },
                { data: 'attributes.nama_barang', name: 'nama_barang', className: 'p-2 border border-gray-300 dark:border-gray-600' },
                { 
                    data: null, 
                    name: 'register', 
                    className: 'p-2 border border-gray-300 dark:border-gray-600',
                    render: (data, type, row) => `${row.attributes.kode_barang || ''}<br>${row.attributes.register || ''}` 
                },
                { data: 'attributes.luas', name: 'luas', className: 'text-center p-2 border border-gray-300 dark:border-gray-600' },
                { data: 'attributes.tahun_pengadaan', name: 'tahun_pengadaan', className: 'text-center p-2 border border-gray-300 dark:border-gray-600' },
                { data: 'attributes.letak', name: 'letak', className: 'p-2 border border-gray-300 dark:border-gray-600 whitespace-normal' },
                { data: 'attributes.no_sertifikat', name: 'no_sertifikat', className: 'p-2 border border-gray-300 dark:border-gray-600' },
                { data: 'attributes.asal', name: 'asal', className: 'p-2 border border-gray-300 dark:border-gray-600' },
                { 
                    data: 'attributes.harga', 
                    name: 'harga', 
                    className: 'text-right p-2 border border-gray-300 dark:border-gray-600',
                    render: data => formatRupiah(data) 
                }
            ],
            order: [[1, 'asc']],
            language: { url: "{{ asset('bootstrap/js/dataTables.indonesian.lang') }}" },
            drawCallback: function () {
                var api = this.api();
                api.column(0, { search: 'applied', order: 'applied' }).nodes().each((cell, i) => cell.innerHTML = api.page.info().start + i + 1);
            }
        });
    });
</script>
@endpush