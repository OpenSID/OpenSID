@extends('theme::layouts.full-content')

@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <style>
        .swal2-popup {
            border-radius: 0 !important;
        }
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
            <li aria-current="page" class="font-medium text-gray-700 dark:text-gray-300">Informasi Publik</li>
        </ol>
    </nav>
    <div class="flex items-center mt-6">
        <div class="flex-grow border-t border-gray-300 dark:border-gray-700"></div>
        <h1 class="flex-shrink px-4 text-2xl lg:text-3xl font-bold uppercase">Informasi Publik</h1>
        <div class="flex-grow border-t border-gray-300 dark:border-gray-700"></div>
    </div>

    <div class="overflow-x-auto mt-8">
        <table class="w-full text-sm border-collapse" id="tabel-informasi-publik">
            <thead class="bg-gray-100 dark:bg-gray-700/50">
                <tr>
                    <th class="p-3 text-center text-xs font-bold uppercase tracking-wider border border-gray-300 dark:border-gray-600">No.</th>
                    <th class="p-3 text-left text-xs font-bold uppercase tracking-wider border border-gray-300 dark:border-gray-600">Judul Informasi</th>
                    <th class="p-3 text-center text-xs font-bold uppercase tracking-wider border border-gray-300 dark:border-gray-600">Tahun</th>
                    <th class="p-3 text-left text-xs font-bold uppercase tracking-wider border border-gray-300 dark:border-gray-600">Kategori</th>
                    <th class="p-3 text-center text-xs font-bold uppercase tracking-wider border border-gray-300 dark:border-gray-600">Tanggal Upload</th>
                    <th class="p-3 text-center text-xs font-bold uppercase tracking-wider border border-gray-300 dark:border-gray-600">Aksi</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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

        const tabelData = $('#tabel-informasi-publik').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: `{{ route('api.informasi-publik') }}`,
                method: 'POST',
                data: d => ({
                    "page[size]": d.length,
                    "page[number]": (d.start / d.length) + 1,
                    "filter[search]": d.search.value,
                    "sort": d.order.length > 0 ? (d.order[0].dir === 'asc' ? '' : '-') + d.columns[d.order[0].column].name : ''
                }),
                dataSrc: json => {
                    json.recordsTotal = json.meta.pagination.total;
                    json.recordsFiltered = json.meta.pagination.total;
                    return json.data;
                }
            },
            columns: [
                { data: null, searchable: false, orderable: false, className: 'text-center p-2 border border-gray-300 dark:border-gray-600' },
                { data: 'attributes.nama', name: 'nama', className: 'whitespace-normal p-2 border border-gray-300 dark:border-gray-600' },
                { data: 'attributes.tahun', name: 'tahun', className: 'text-center p-2 border border-gray-300 dark:border-gray-600' },
                { data: 'attributes.kategori', name: 'kategori', className: 'p-2 border border-gray-300 dark:border-gray-600' },
                { data: 'attributes.tgl_upload', name: 'tgl_upload', className: 'text-center p-2 border border-gray-300 dark:border-gray-600' },
                {
                    data: null, searchable: false, orderable: false, className: 'text-center p-2 border border-gray-300 dark:border-gray-600',
                    render: function(data, type, row) {
                        const fileName = row.attributes.nama.toLowerCase().replace(/ /g, '-').replace(/[^\w-]+/g, '');
                        return `<button class="btn btn-primary text-xs lihat-dokumen"
                                    data-nama="${fileName}"
                                    data-file="${row.attributes.satuan}">
                                    <i class="fas fa-eye mr-1"></i> Lihat
                                </button>`;
                    }
                }
            ],
            order: [[4, 'desc']],
            language: { url: "{{ asset('bootstrap/js/dataTables.indonesian.lang') }}" },
            drawCallback: function() {
                var api = this.api();
                api.column(0, { search: 'applied', order: 'applied' }).nodes().each(function(cell, i) {
                    cell.innerHTML = api.page.info().start + i + 1;
                });
            }
        });

        $('#tabel-informasi-publik tbody').on('click', '.lihat-dokumen', function() {
            const nama = $(this).data('nama');
            const base64 = $(this).data('file');

            if (!base64) {
                Swal.fire('Gagal', 'File tidak ditemukan atau korup.', 'error');
                return;
            }

            Swal.fire({
                title: 'Pratinjau Dokumen',
                html: `
                    <div class="space-y-4">
                        <iframe src="${base64}" class="w-full h-96 border"></iframe>
                        <button id="swal-unduh-dokumen" class="btn btn-primary w-full">
                            <i class="fas fa-download mr-2"></i>Unduh Dokumen
                        </button>
                    </div>`,
                width: '80%',
                showCloseButton: true,
                showConfirmButton: false,
                didOpen: () => {
                    document.getElementById('swal-unduh-dokumen').addEventListener('click', () => {
                        downloadFile(base64, nama);
                    });
                }
            });
        });
    });
</script>
@endpush