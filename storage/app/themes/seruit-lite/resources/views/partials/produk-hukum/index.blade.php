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
<div class="bg-[var(--bg-color-card)] p-6 shadow-xl border border-[var(--border-color)] -mt-16 relative z-10">
    <nav role="navigation" aria-label="navigation" class="breadcrumb text-sm mb-4">
        <ol class="flex items-center justify-center space-x-2 text-gray-500 dark:text-gray-400">
            <li><a href="{{ site_url() }}" class="hover:underline hover:text-blue-600">Beranda</a></li>
            <li><span class="mx-2">/</span></li>
            <li aria-current="page" class="font-medium text-gray-700 dark:text-gray-300">Produk Hukum</li>
        </ol>
    </nav>
    <div class="flex items-center mt-6">
        <div class="flex-grow border-t border-gray-300 dark:border-gray-700"></div>
        <h1 class="flex-shrink px-4 text-2xl lg:text-3xl font-bold uppercase">Produk Hukum Desa</h1>
        <div class="flex-grow border-t border-gray-300 dark:border-gray-700"></div>
    </div>

    <div class="mt-8 mb-6 p-4 bg-gray-50 dark:bg-gray-900/50 border border-gray-200 dark:border-gray-700 flex flex-col md:flex-row gap-4 items-center">
        <div class="w-full md:w-1/3">
            <label for="filter-tahun" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Filter Tahun</label>
            <select id="filter-tahun" class="form-input w-full dark:bg-gray-700 dark:border-gray-600">
                <option value="">Semua Tahun</option>
            </select>
        </div>
        <div class="w-full md:w-1/3">
            <label for="filter-kategori" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Filter Kategori</label>
            <select id="filter-kategori" class="form-input w-full dark:bg-gray-700 dark:border-gray-600">
                <option value="">Semua Kategori</option>
            </select>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm border-collapse" id="tabel-produk-hukum">
            <thead class="bg-gray-100 dark:bg-gray-700/50">
                <tr>
                    <th class="p-3 text-center text-xs font-bold uppercase tracking-wider border border-gray-300 dark:border-gray-600">No.</th>
                    <th class="p-3 text-left text-xs font-bold uppercase tracking-wider border border-gray-300 dark:border-gray-600">Judul Produk Hukum</th>
                    <th class="p-3 text-left text-xs font-bold uppercase tracking-wider border border-gray-300 dark:border-gray-600">Jenis</th>
                    <th class="p-3 text-center text-xs font-bold uppercase tracking-wider border border-gray-300 dark:border-gray-600">Tahun</th>
                    <th class="p-3 text-center text-xs font-bold uppercase tracking-wider border border-gray-300 dark:border-gray-600">Aksi</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function () {
        function downloadFile(base64Data, fileName) {
            const mimeTypeMatch = base64Data.match(/^data:([^;]+);base64,/);
            const mimeType = mimeTypeMatch ? mimeTypeMatch[1] : 'application/octet-stream';
            const extension = mimeType.split('/')[1] || 'bin';
            const finalFileName = `${fileName}.${extension}`;
            
            const base64WithoutPrefix = base64Data.split(',')[1];
            const byteCharacters = atob(base64WithoutPrefix);
            const byteNumbers = new Array(byteCharacters.length);
            for (let i = 0; i < byteCharacters.length; i++) {
                byteNumbers[i] = byteCharacters.charCodeAt(i);
            }
            const byteArray = new Uint8Array(byteNumbers);
            const blob = new Blob([byteArray], { type: mimeType });
            const blobUrl = URL.createObjectURL(blob);
            
            const link = document.createElement('a');
            link.href = blobUrl;
            link.download = finalFileName;
            
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
            URL.revokeObjectURL(blobUrl);
        }

        $.get('{{ route('api.tahun-produk-hukum') }}', function(data) {
            const selectTahun = $('#filter-tahun');
            data.data.forEach(function(tahun) {
                selectTahun.append(`<option value="${tahun}">${tahun}</option>`);
            });
        });

        $.get('{{ route('api.kategori-produk-hukum') }}', function(data) {
            const selectKategori = $('#filter-kategori');
            data.data.forEach(function(item) {
                selectKategori.append(`<option value="${item.id}">${item.attributes.nama}</option>`);
            });
        });

        const tabelData = $('#tabel-produk-hukum').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '{{ route('api.produk-hukum') }}',
                method: 'POST',
                data: function(d) {
                    d['filter[tahun]'] = $('#filter-tahun').val();
                    d['filter[kategori]'] = $('#filter-kategori').val();
                    d['page[size]'] = d.length;
                    d['page[number]'] = (d.start / d.length) + 1;
                    d['filter[search]'] = d.search.value;
                    if (d.order.length > 0) {
                        const sortDir = d.order[0].dir === 'asc' ? '' : '-';
                        d.sort = sortDir + d.columns[d.order[0].column].name;
                    }
                },
                dataSrc: function(json) {
                    json.recordsTotal = json.meta.pagination.total;
                    json.recordsFiltered = json.meta.pagination.total;
                    return json.data;
                }
            },
            columns: [
                { data: null, searchable: false, orderable: false, className: 'text-center p-2 border border-gray-300 dark:border-gray-600' },
                { data: 'attributes.nama', name: 'nama', className: 'whitespace-normal p-2 border border-gray-300 dark:border-gray-600' },
                { data: 'attributes.kategori', name: 'kategori', className: 'p-2 border border-gray-300 dark:border-gray-600' },
                { data: 'attributes.tahun', name: 'tahun', className: 'text-center p-2 border border-gray-300 dark:border-gray-600' },
                {
                    data: null, searchable: false, orderable: false, className: 'text-center p-2 border border-gray-300 dark:border-gray-600',
                    render: function(data, type, row) {
                        const fileName = row.attributes.nama.toLowerCase().replace(/ /g, '-').replace(/[^\w-]+/g, '');
                        return `<button class="btn btn-primary text-xs unduh-dokumen"
                                    data-nama="${fileName}"
                                    data-file="${row.attributes.satuan}">
                                    <i class="fas fa-download mr-1"></i> Unduh
                                </button>`;
                    }
                }
            ],
            order: [[3, 'desc']],
            language: { url: "{{ asset('bootstrap/js/dataTables.indonesian.lang') }}" },
            drawCallback: function() {
                var api = this.api();
                api.column(0, { search: 'applied', order: 'applied' }).nodes().each(function(cell, i) {
                    cell.innerHTML = api.page.info().start + i + 1;
                });
            }
        });

        $('#filter-tahun, #filter-kategori').on('change', function() {
            tabelData.ajax.reload();
        });
        
        $('#tabel-produk-hukum tbody').on('click', '.unduh-dokumen', function() {
            const base64 = $(this).data('file');
            const fileName = $(this).data('nama');
            if (base64) {
                downloadFile(base64, fileName);
            } else {
                alert('File tidak tersedia untuk diunduh.');
            }
        });
    });
</script>
@endpush