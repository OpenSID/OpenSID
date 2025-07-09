@extends('theme::layouts.right-sidebar')
@include('theme::commons.asset_sweetalert')

@section('content')
    <nav role="navigation" aria-label="navigation" class="breadcrumb">
        <ol>
            <li><a href="{{ site_url('/') }}">Beranda</a></li>
            <li aria-current="page">Suplemen</li>
        </ol>
    </nav>

    <h1 class="text-h2" id="judul"></h1>

    <hr style="margin: 10px 0;">
    <h2 class="text-h4" style="margin-bottom: 10px;">Rincian Data Suplemen</h2>
    <div class="table-responsive content">
        <table class="w-full text-sm">
            <tbody>
                <tr>
                    <td width="20%">Nama Data</td>
                    <td width="1%">:</td>
                    <td id="nama"></td>
                </tr>
                <tr>
                    <td>Sasaran Terdata</td>
                    <td>:</td>
                    <td id="sasaran"></td>
                </tr>
                <tr>
                    <td>Keterangan</td>
                    <td>:</td>
                    <td id="keterangan"></td>
                </tr>
            </tbody>
        </table>
    </div>

    <h2 class="text-h4" style="margin-bottom: 10px; margin-top: 20px;">Daftar Terdata</h2>
    <div class="table-responsive content">
        <table class="w-full text-sm" id="tabelData">
            <thead class="bg-gray disabled color-palette">
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Tempat Lahir</th>
                    <th>Jenis-kelamin</th>
                    <th>Alamat</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
@endsection

@push('scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            var apiSuplemen = `{{ route('api.suplemen') }}`;
            var params = {
                "filter[slug]": `{{ $slug }}`
            }

            $.get(apiSuplemen, params, function(response) {
                suplemen = response.data[0];

                if (!suplemen) {
                    Swal.fire('Error', 'Data tidak ditemukan.', 'error');
                    return;
                }

                $('#judul').text('Data Suplemen ' + suplemen.attributes.nama);
                $('#nama').text(suplemen.attributes.nama);
                $('#sasaran').text(suplemen.attributes.nama_sasaran);
                $('#keterangan').text(suplemen.attributes.keterangan);

                loadAnggota(suplemen.id);
            });

            function loadAnggota(id) {
                var routeSuplemenAnggota = `{{ route('api.suplemen') }}` + '/' + id;

                var tabelData = $('#tabelData').DataTable({
                    processing: true,
                    serverSide: true,
                    autoWidth: false,
                    ordering: true,
                    ajax: {
                        url: routeSuplemenAnggota,
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
                            orderable: false,
                            className: 'text-center'
                        },
                        {
                            data: "attributes.terdata_nama",
                            name: 'tweb_penduduk.nama',
                        },
                        {
                            data: "attributes.tempatlahir",
                            name: 'tweb_penduduk.tempatlahir',
                        },
                        {
                            data: "attributes.sex",
                            name: 'tweb_penduduk.sex',
                        },
                        {
                            data: "attributes.alamat",
                            name: 'tweb_penduduk.alamat',
                            orderable: false
                        },
                    ],
                    order: [
                        [1, 'asc']
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
            }
        });
    </script>
@endpush
