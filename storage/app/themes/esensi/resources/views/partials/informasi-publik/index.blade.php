@extends('theme::layouts.right-sidebar')
@include('theme::commons.asset_sweetalert')

@section('content')
    <div class="content py-1">
        <div class="box box-danger" style="padding-bottom: 2rem;">
            <div class="box-header with-border" style="margin-bottom: 20px;">
                <h3 class="box-title">Informasi Publik</h3>
            </div>
            <div class="box-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered" id="tabelData">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Judul Informasi</th>
                                <th>Tahun</th>
                                <th>Kategori</th>
                                <th>Tanggal Upload</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tfoot></tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            var route = `{{ route('api.informasi-publik') }}`;

            var tabelData = $('#tabelData').DataTable({
                processing: true,
                serverSide: true,
                autoWidth: false,
                ordering: true,
                ajax: {
                    url: route,
                    method: 'GET',
                    data: row => ({
                        "page[size]": row.length,
                        "page[number]": (row.start / row.length) + 1,
                        "filter[search]": row.search.value,
                        "sort": `${row.order[0]?.dir === "asc" ? "" : "-"}${row.columns[row.order[0]?.column]?.name}`
                    }),
                    dataSrc: json => {
                        json.recordsTotal = json.meta.pagination.total;
                        json.recordsFiltered = json.meta.pagination.total;
                        return json.data;
                    },
                    error: function(xhr) {
                        console.error('AJAX Error:', xhr.responseText);
                        Swal.fire('Error', 'Terjadi kesalahan saat memuat data.', 'error');
                    }
                },
                columnDefs: [{
                    targets: '_all',
                    className: 'text-nowrap'
                }, ],
                columns: [{
                        data: null,
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'nama',
                        name: 'nama',
                        render: (data, type, row) => row.attributes.nama
                    },
                    {
                        data: 'tahun',
                        name: 'tahun',
                        render: (data, type, row) => row.attributes.tahun
                    },
                    {
                        data: 'kategori',
                        name: 'kategori',
                        render: (data, type, row) => row.attributes.kategori
                    },
                    {
                        data: 'tgl_upload',
                        name: 'tgl_upload',
                        render: (data, type, row) => row.attributes.tgl_upload
                    },
                    {
                        data: null,
                        searchable: false,
                        orderable: false,
                        render: (data, type, row) => {
                            return `<button class="btn btn-xs btn-primary lihat-dokumen"
                                    data-nama="${row.attributes.nama}"
                                    data-url="${row.attributes.url}"
                                    data-file="${row.attributes.satuan}">
                                    Lihat
                                </button>`;
                        }
                    }
                ],
                order: [
                    [4, 'desc']
                ],
                drawCallback: function(settings) {
                    var api = this.api();
                    api.column(0, {
                        search: 'applied',
                        order: 'applied'
                    }).nodes().each(function(cell, i) {
                        cell.innerHTML = api.page.info().start + i + 1;
                    });
                }
            });

            // Event listener untuk tombol lihat dokumen
            $(document).on('click', '.lihat-dokumen', function() {
                var nama = $(this).data('nama');
                var file = $(this).data('file') || $(this).data('url');

                nama = nama.toLowerCase().replace(/ /g, '-').replace(/[^\w-]+/g, '');

                if (!file) {
                    Swal.fire('Error', 'File tidak ditemukan.', 'error');

                    return;
                }

                Swal.fire({
                    title: '<h4 style="margin-bottom: 10px;">Lihat</h4>',
                    html: `
                        <div style="display: flex; flex-direction: column; align-items: center; width: 100%; gap: 15px;">
                            <iframe src="${file}" style="width: 100%; min-height: 400px; border: 1px solid #ddd; border-radius: 5px; display: flex; align-items: center; justify-content: center;"></iframe>
                            <button class="btn btn-primary btn-sm unduh-dokumen" data-nama="${nama}" data-file="${file}"
                                style="padding: 8px 20px; font-size: 14px; border-radius: 5px; cursor: pointer;">
                                Unduh File
                            </button>
                        </div>
                    `,
                    width: '60%',
                    heightAuto: true,
                    showCloseButton: true,
                    showConfirmButton: false,
                    showCancelButton: false,
                    didOpen: () => {
                        $(".unduh-dokumen").on("click", function() {
                            let pdfUrl = $(this).data("file");
                            let fileName = $(this).data("nama") || "document.pdf";

                            let link = $("<a>")
                                .attr("href", pdfUrl)
                                .attr("download", fileName)
                                .appendTo("body");

                            link[0].click();
                            link.remove();
                        });
                    }
                });
            });
        });
    </script>
@endpush
