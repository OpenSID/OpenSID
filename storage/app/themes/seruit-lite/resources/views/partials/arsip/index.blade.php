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
    <div class="text-center mb-8">
        <nav aria-label="Breadcrumb" class="text-sm text-gray-500 dark:text-gray-400">
            <ol class="flex items-center justify-center space-x-2">
                <li><a href="{{ site_url() }}" class="hover:underline">Beranda</a></li>
                <li><span class="mx-2">/</span></li>
                <li aria-current="page" class="font-medium">Arsip Artikel</li>
            </ol>
        </nav>
        <div class="flex items-center mt-6">
            <div class="flex-grow border-t border-gray-300 dark:border-gray-700"></div>
            <h1 class="flex-shrink px-4 text-2xl lg:text-3xl font-bold uppercase">
                Arsip Artikel
            </h1>
            <div class="flex-grow border-t border-gray-300 dark:border-gray-700"></div>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm border-collapse" id="arsip-artikel">
            <thead class="bg-gray-100 dark:bg-gray-700/50">
                <tr>
                    <th class="p-3 text-center text-xs font-bold uppercase tracking-wider border border-gray-300 dark:border-gray-600">No.</th>
                    <th class="p-3 text-left text-xs font-bold uppercase tracking-wider border border-gray-300 dark:border-gray-600">Tanggal</th>
                    <th class="p-3 text-left text-xs font-bold uppercase tracking-wider border border-gray-300 dark:border-gray-600">Judul Artikel</th>
                    <th class="p-3 text-left text-xs font-bold uppercase tracking-wider border border-gray-300 dark:border-gray-600">Penulis</th>
                    <th class="p-3 text-center text-xs font-bold uppercase tracking-wider border border-gray-300 dark:border-gray-600">Dibaca</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        const arsipTable = $('#arsip-artikel').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ ci_route('internal_api.arsip') }}",
                type: 'POST',
                dataSrc: function (json) {
                    json.recordsTotal = json.meta.pagination.total;
                    json.recordsFiltered = json.meta.pagination.total;
                    return json.data;
                }
            },
            columns: [
                { 
                    data: null, 
                    searchable: false, 
                    orderable: false, 
                    className: 'text-center p-2 border border-gray-300 dark:border-gray-600' 
                },
                { 
                    data: "attributes.tgl_upload_local", 
                    name: "tgl_upload", 
                    className: 'whitespace-nowrap p-2 border border-gray-300 dark:border-gray-600' 
                },
                {
                    data: "attributes.judul",
                    name: "judul",
                    orderable: false,
                    className: 'p-2 border border-gray-300 dark:border-gray-600',
                    render: function(data, type, row) {
                        return `<a href="${row.attributes.url_slug}" class="font-semibold text-blue-600 hover:underline dark:text-blue-400">${data}</a>`;
                    }
                },
                { 
                    data: "attributes.author.nama", 
                    name: "id_user", 
                    searchable: false, 
                    orderable: false, 
                    defaultContent: '', 
                    className: 'p-2 border border-gray-300 dark:border-gray-600' 
                },
                { 
                    data: "attributes.hit", 
                    name: "hit", 
                    searchable: false, 
                    className: 'text-center p-2 border border-gray-300 dark:border-gray-600' 
                }
            ],
            order: [[1, 'desc']],
            language: { url: "{{ asset('bootstrap/js/dataTables.indonesian.lang') }}" },
            drawCallback: function(settings) {
                var api = this.api();
                api.column(0, { search: 'applied', order: 'applied' }).nodes().each(function(cell, i) {
                    cell.innerHTML = api.page.info().start + i + 1;
                });
            }
        });
    });
</script>
@endpush