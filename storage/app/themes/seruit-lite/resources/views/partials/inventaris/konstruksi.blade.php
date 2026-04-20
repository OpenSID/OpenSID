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
        <table class="w-full text-sm" id="tabel-detail">
            <thead class="bg-gray-100 dark:bg-gray-700/50">
                <tr>
                    <th rowspan="2" class="p-3 text-center text-xs font-bold uppercase tracking-wider border border-gray-300 dark:border-gray-600">No</th>
                    <th rowspan="2" class="p-3 text-left text-xs font-bold uppercase tracking-wider border border-gray-300 dark:border-gray-600">Nama Barang</th>
                    <th rowspan="2" class="p-3 text-center text-xs font-bold uppercase tracking-wider border border-gray-300 dark:border-gray-600">Fisik (P,SP,D)</th>
                    <th rowspan="2" class="p-3 text-center text-xs font-bold uppercase tracking-wider border border-gray-300 dark:border-gray-600">Luas (MÂ²)</th>
                    <th colspan="2" class="p-3 text-center text-xs font-bold uppercase tracking-wider border border-gray-300 dark:border-gray-600">Dokumen</th>
                    <th rowspan="2" class="p-3 text-center text-xs font-bold uppercase tracking-wider border border-gray-300 dark:border-gray-600">Tgl Mulai</th>
                    <th rowspan="2" class="p-3 text-left text-xs font-bold uppercase tracking-wider border border-gray-300 dark:border-gray-600">Status Tanah</th>
                    <th rowspan="2" class="p-3 text-left text-xs font-bold uppercase tracking-wider border border-gray-300 dark:border-gray-600">Asal Biaya</th>
                    <th rowspan="2" class="p-3 text-right text-xs font-bold uppercase tracking-wider border border-gray-300 dark:border-gray-600">Nilai Kontrak (Rp)</th>
                </tr>
                <tr>
                    <th class="p-2 text-center text-xs font-bold uppercase tracking-wider border border-gray-300 dark:border-gray-600">Tanggal</th>
                    <th class="p-2 text-center text-xs font-bold uppercase tracking-wider border border-gray-300 dark:border-gray-600">Nomor</th>
                </tr>
            </thead>
            <tbody></tbody>
            <tfoot class="bg-gray-100 dark:bg-gray-700/50 font-bold">
                <tr>
                    <th colspan="9" class="p-3 text-right text-xs uppercase">TOTAL</th>
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
                url: `{{ ci_route('internal_api.inventaris-konstruksi') }}`,
                method: 'POST',
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
                { data: 'attributes.kondisi_bangunan', name: 'kondisi_bangunan', className: 'text-center p-2 border border-gray-300 dark:border-gray-600' },
                { data: 'attributes.luas_bangunan', name: 'luas_bangunan', className: 'text-center p-2 border border-gray-300 dark:border-gray-600' },
                { data: 'attributes.tanggal_dokument', name: 'tanggal_dokument', className: 'text-center p-2 border border-gray-300 dark:border-gray-600' },
                { data: 'attributes.no_dokument', name: 'no_dokument', className: 'text-center p-2 border border-gray-300 dark:border-gray-600' },
                { data: 'attributes.tanggal', name: 'tanggal', className: 'text-center p-2 border border-gray-300 dark:border-gray-600' },
                { data: 'attributes.status_tanah', name: 'status_tanah', className: 'p-2 border border-gray-300 dark:border-gray-600' },
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