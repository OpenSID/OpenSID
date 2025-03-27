@extends('theme::layouts.right-sidebar')
@include('theme::commons.asset_sweetalert')

@section('content')
    <div class="single_page_area">
        <h2 class="post_titile">Produk Hukum</h2>
        <div class="box-body">
            <div class="row">
                <div class="col-sm-3">
                    <label for="tahun">Tahun</label>
                    <select class="form-control input-sm" id="list_tahun" name="tahun">
                        <option selected value="">Semua</option>
                    </select>
                </div>
                <div class="col-sm-3">
                    <label for="tahun">Kategori</label>
                    <select class="form-control input-sm" id="list_kategori" name="kategori">
                        <option selected value="">Semua</option>
                    </select>
                </div>
            </div>
            <hr>
            <div class="table-responsive">
                <table class="table table-striped table-bordered" id="tabelData">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Judul Produk Hukum</th>
                            <th>Jenis</th>
                            <th>Tahun</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            var apiTahun = '{{ route('api.tahun-produk-hukum') }}';
            $.get(apiTahun, function(data) {
                var dataTahun = data.data;
                var selectTahun = $('#list_tahun');
                dataTahun.forEach(function(item) {
                    selectTahun.append('<option value="' + item + '">' + item + '</option>');
                });
            });

            var apiKategori = '{{ route('api.kategori-produk-hukum') }}';
            $.get(apiKategori, function(data) {
                var dataKategori = data.data;
                var selectKategori = $('#list_kategori');
                dataKategori.forEach(function(item) {
                    selectKategori.append('<option value="' + item.id + '">' + item.attributes.nama + '</option>');
                });
            });

            var tabelData = $('#tabelData').DataTable({
                processing: true,
                serverSide: true,
                autoWidth: false,
                ordering: true,
                ajax: {
                    url: `{{ route('api.produk-hukum') }}`,
                    method: 'GET',
                    data: function(row) {
                        var tahun = $('#list_tahun').val();
                        var kategori = $('#list_kategori').val();
                        var params = {
                            "page[size]": row.length,
                            "page[number]": (row.start / row.length) + 1,
                            "filter[search]": row.search.value,
                            "sort": `${row.order[0]?.dir === "asc" ? "" : "-"}${row.columns[row.order[0]?.column]?.name}`
                        };

                        if (tahun) {
                            params['filter[tahun]'] = tahun;
                        }

                        if (kategori) {
                            params['filter[kategori]'] = kategori;
                        }

                        return params;
                    },
                    dataSrc: function(json) {
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
                        orderable: false,
                        className: 'text-center'
                    },
                    {
                        data: 'nama',
                        name: 'nama',
                        render: (data, type, row) => row.attributes.nama
                    },
                    {
                        data: 'kategori',
                        name: 'kategori',
                        render: (data, type, row) => row.attributes.kategori
                    },
                    {
                        data: 'tahun',
                        name: 'tahun',
                        render: (data, type, row) => row.attributes.tahun,
                        className: 'text-center'
                    },
                    {
                        data: null,
                        searchable: false,
                        orderable: false,
                        render: (data, type, row) => {
                            return `<button class="btn btn-primary btn-block lihat-dokumen"
                                    data-nama="${row.attributes.nama}"
                                    data-url="${row.attributes.url}"
                                    data-file="${row.attributes.satuan}">
                                    Lihat
                                </button>`;
                        }
                    }
                ],
                order: [
                    [3, 'desc']
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

            $(document).on('change', '#list_tahun, #list_kategori', function() {
                tabelData.ajax.reload();
            });

            $(document).on('click', '.lihat-dokumen', function() {
                var nama = $(this).data('nama');
                var file = $(this).data('url') || $(this).data('file');

                nama = nama.toLowerCase().replace(/ /g, '-').replace(/[^\w-]+/g, '');

                if (!file) {
                    Swal.fire('Error', 'File tidak ditemukan.', 'error');

                    return;
                }

                Swal.fire({
                    title: '<h4 style="margin-bottom: 10px;">Lihat</h4>',
                    html: `
                        <div style="display: flex; flex-direction: column; align-items: center; width: 100%; gap: 15px;">
                            <iframe src="${file}" style="width: 100%; min-height: 400px; border: 1px solid #ddd; border-radius: 5px;"></iframe>
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
