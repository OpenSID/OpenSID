@extends('theme::layouts.right-sidebar')
@include('theme::commons.asset_sweetalert')

@section('content')
    <div class="single_page_area" id="kelompok-wrapper">
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            var route = "{{ route('api.' . $tipe . '.detail', ['slug' => $slug]) }}";
            $.ajax({
                url: route,
                method: 'GET',
                beforeSend: function() {
                    $('#kelompok-wrapper').html(`<div class="fa fa-circle-o-notch fa-spin fa-4x" role="status">
                    <span class="sr-only">Loading...</span>
                </div>`);
                },
                success: function(data) {
                    var detail = data.data.attributes;
                    var pengurus = detail.pengurus;
                    var tipe = detail.tipe;
                    var gambar_desa = `{{ gambar_desa('${detail.logo}') }}`;

                    var detailElemen = `<h2 class="post_titile">Data ${tipe} ${detail.nama}</h2>
                <h3 class="post_titile">Rinci Data ${tipe}</h3>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <tbody>
                            <tr>
                                <tr>
                                    <td width="20%">Nama ${tipe}</td>
                                    <td width="1%">:</td>
                                    <td>${detail.nama}</td>
                                    <td width="20%" rowspan="5" style="text-align: center; vertical-align: middle;">
                                        <img src="${gambar_desa}" alt="Logo ${tipe}" height="120px;">
                                    </td>
                                </tr>
                                <tr>
                                    <td>Kode ${tipe}</td>
                                    <td>:</td>
                                    <td>${detail.kode}</td>
                                </tr>
                                <tr>
                                    <td>Kategori ${tipe}</td>
                                    <td>:</td>
                                    <td>${detail.kategori}</td>
                                </tr>
                                <tr>
                                    <td>No. SK Pendirian</td>
                                    <td>:</td>
                                    <td>${detail.no_sk_pendirian}</td>
                                </tr>
                                <tr>
                                    <td>Keterangan</td>
                                    <td>:</td>
                                    <td>${detail.keterangan}</td>
                                </tr>
                        </tbody>
                    </table>
                </div>`;

                    var pengurusElemen = `<h3 class="post_titile">Daftar Pengurus</h3>
                <div class="box-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered" id="table-pengurus">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Jabatan</th>
                                    <th>Nama</th>
                                    <th>Alamat</th>
                                </tr>
                            </thead>
                            <tbody>`;

                    pengurus.forEach((data, key) => {
                        pengurusElemen += `
                                    <tr>
                                        <td>${key + 1}</td>
                                        <td>${data.nama_jabatan}</td>
                                        <td nowrap>${data.nama_penduduk}</td>
                                        <td>${data.alamat_lengkap}</td>
                                    </tr>`;
                    });

                    pengurusElemen += `</tbody>
                        </table>
                    </div>`;

                    var anggotaElemen = `
                    <h3 class="post_titile">Daftar Anggota</h3>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered" id="table-anggota">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>No. Anggota</th>
                                    <th>Nama</th>
                                    <th>Alamat</th>
                                    <th>Jenis Kelamin</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                `;

                    $('#kelompok-wrapper').html(detailElemen + pengurusElemen + anggotaElemen);

                    anggotaTable();
                },
                error: function(xhr) {
                    console.error('AJAX Error:', xhr.responseText);
                    Swal.fire('Error', 'Terjadi kesalahan saat memuat data.', 'error');
                }
            });


            const anggotaTable = () => {
                $('#table-anggota').DataTable({
                    processing: true,
                    serverSide: true,
                    autoWidth: false,
                    ordering: true,
                    ajax: {
                        url: `{{ route('api.kelompok.anggota', ['slug' => $slug]) }}`,
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
                        },
                        {
                            targets: [0, 4],
                            className: 'text-center'
                        },
                        {
                            targets: [0],
                            orderable: false
                        }
                    ],
                    columns: [{
                            data: null,
                            searchable: false,
                            orderable: false
                        },
                        {
                            data: 'no_anggota',
                            name: 'no_anggota',
                            render: (data, type, row) => row.attributes.no_anggota
                        },
                        {
                            data: 'nama',
                            name: 'nama',
                            className: 'text-wrap',
                            render: (data, type, row) => row.attributes.anggota.nama
                        },
                        {
                            data: 'alamat',
                            name: 'alamat',
                            render: (data, type, row) => row.attributes.alamat_lengkap
                        },
                        {
                            data: 'jenis_kelamin',
                            name: 'jenis_kelamin',
                            render: (data, type, row) => row.attributes.sex
                        },
                    ],
                    order: [
                        [2, 'desc']
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
