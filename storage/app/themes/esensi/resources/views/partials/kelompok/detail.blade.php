@extends('theme::layouts.right-sidebar')

@section('content')
    @include('theme::commons.asset_sweetalert')

    <nav role="navigation" aria-label="navigation" class="breadcrumb">
        <ol>
            <li><a href="{{ site_url() }}">Beranda</a></li>
            <li aria-current="page" id="nav-tipe"></li>
        </ol>
    </nav>

    <div id="kelompok-wrapper">
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
                    const kelompokList = document.getElementById('kelompok-wrapper');
                    kelompokList.innerHTML = `@include('theme::commons.loading')`;
                },
                success: function(data) {
                    var detail = data.data.attributes;
                    var pengurus = detail.pengurus;
                    var tipe = detail.tipe;
                    var gambar_desa = `{{ gambar_desa('${detail.logo}') }}`;

                    $('#nav-tipe').text(`Data ${tipe}`);

                    var detailElemen = `
              <h1 class="text-h2">Data ${tipe} ${detail.nama}</h1>
              <h2 class="text-h4">Rinci Data ${tipe}</h2>
              <div class="table-responsive content">
                <table class="w-full text-sm">
                  <tbody>
                    <tr>
                      <td width="20%">Nama ${tipe}</td>
                      <td width="1%">:</td>
                      <td>${detail.nama}</td>
                      <td width="20%" rowspan="5" style="text-align: center; vertical-align: middle;">
                        <img src="${gambar_desa}" alt="Logo ${tipe}" class="w-full">
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

                    var pengurusElemen = `<h2 class="text-h4">Daftar Pengurus</h2>
              <div class="table-responsive content">
                <table class="w-full text-sm">
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
              <h2 class="text-h4">Daftar Anggota</h2>
              <div class="table-responsive content">
                <table class="w-full text-sm" id="tabel-data">
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
              </div>`;

                    $('#kelompok-wrapper').html(detailElemen + pengurusElemen + anggotaElemen);

                    anggotaTable();
                },
                error: function(xhr) {
                    console.error('AJAX Error:', xhr.responseText);
                    Swal.fire('Error', 'Terjadi kesalahan saat memuat data.', 'error');
                }
            });

            const anggotaTable = () => {
                $('#tabel-data').DataTable({
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
                    }, ],
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
