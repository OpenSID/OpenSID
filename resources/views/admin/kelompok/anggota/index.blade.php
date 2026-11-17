@extends('admin.layouts.index')
@push('css')
    <style>
        .no-wrap {
            text-wrap: nowrap;
        }
    </style>
@endpush
@include('admin.layouts.components.asset_datatables')
@section('title')
    <h1>
        Data {{ $module_name }}
        {{ ucwords($kelompok['nama']) }}
    </h1>
@endsection

@section('breadcrumb')
    <li><a href="{{ site_url(str_replace('_anggota', '', $controller)) }}"> Data  {{ $module_name }}</a></li>
    <li class="active">
        {{ ucwords($kelompok['nama']) }}
    </li>
@endsection

@section('content')
    @include('admin.layouts.components.notifikasi')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-info">
                <div class="box-header with-border">
                    @if (can('u'))
                        <x-split-button 
                            judul="Tambah"
                            icon="fa fa-plus"
                            type="btn-success"
                            :list="[
                                [
                                    'url' => $controller . '/aksi/1/' . $kelompok['id'],
                                    'judul' => 'Tambah Satu Anggota ' . $tipe,
                                    'modal' => false,
                                ],
                                [
                                    'url' => $controller . '/aksi/2/' . $kelompok['id'],
                                    'judul' => 'Tambah Beberapa Anggota ' . $tipe,
                                    'modal' => false,
                                ],
                            ]"
                        />
                    @endif

                    <x-hapus-button 
                        confirmDelete="true"
                        selectData="true"
                        :url="$ci->controller . '/delete_all/' . $kelompok['id']"
                    />

                    @php
                        $listCetakUnduh = [
                            [
                                'url' => $controller . '/dialog/cetak/' . $kelompok['id'],
                                'judul' => 'Cetak',
                                'icon' => 'fa fa-print',
                                'modal' => true,
                            ],
                            [
                                'url' => $controller . '/dialog/unduh/' . $kelompok['id'],
                                'judul' => 'Unduh',
                                'icon' => 'fa fa-download',
                                'modal' => true,
                            ],
                        ];
                    @endphp

                    <x-split-button
                        judul="Cetak / Unduh"
                        :list="$listCetakUnduh"
                        icon="fa fa-arrow-circle-down"
                        type="bg-purple"
                        target="true"
                    />

                    <x-kembali-button 
                        :url="strtolower($tipe)"
                        :judul="'Daftar ' . $tipe"
                    />

                </div>
                <div class="box-body">
                    <h5><b>Rincian {{ $tipe }}</b></h5>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover tabel-rincian">
                            <tbody>
                                <tr>
                                    <td width="20%">Kode {{ $tipe }}</td>
                                    <td width="1">:</td>
                                    <td>
                                        {{ strtoupper($kelompok['kode']) }}
                                    </td>
                                    <td class="padat" rowspan="5">
                                        <img src="{{ $kelompok['url_logo'] }}" class="img-thumbnail" alt="Logo {{ $tipe }}" style="max-width: 150px;">
                                    </td>
                                </tr>
                                <tr>
                                    <td>Nama {{ $tipe }}
                                    </td>
                                    <td>:</td>
                                    <td>
                                        {{ strtoupper($kelompok['nama']) }}
                                    </td>
                                </tr>
                                <tr>
                                    <td>Ketua {{ $tipe }}
                                    </td>
                                    <td>:</td>
                                    <td>
                                        {{ strtoupper($kelompok['nama_ketua']) }}
                                    </td>
                                </tr>
                                <tr>
                                    <td>Kategori {{ $tipe }}
                                    </td>
                                    <td>:</td>
                                    <td>
                                        {{ strtoupper($kelompok['kategori']) }}
                                    </td>
                                </tr>
                                <tr>
                                    <td>Keterangan</td>
                                    <td>:</td>
                                    <td>
                                        {{ $kelompok['keterangan'] }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <hr style="margin-bottom: 5px;">
                <div class="box-body">
                    <h5><b>Anggota {{ $tipe }}</b></h5>
                    <hr>
                    <div class="dataTables_wrapper form-inline dt-bootstrap no-footer">
                        <form id="mainform" name="mainform" method="post">
                            <div class="table-responsive dataTables_wrapper">
                                <table class="table table-bordered table-striped dataTable table-hover tabel-daftar" id="tabeldata">
                                    <thead class="bg-gray disabled color-palette">
                                        <tr>
                                            <th><input type="checkbox" id="checkall" /></th>
                                            <th>No</th>
                                            <th>Aksi</th>
                                            <th>Foto</th>
                                            <th>No. Anggota</th>
                                            <th>NIK</th>
                                            <th>Nama</th>
                                            <th>Tempat / Tanggal Lahir</th>
                                            <th>Umur (Tahun)</th>
                                            <th>Jenis Kelamin</th>
                                            <th>Alamat</th>
                                            <th>Jabatan</th>
                                            <th>Nomor SK Jabatan</th>
                                            @if ($tipe == 'Lembaga')
                                                <th>Nomor SK Pengangkatan</th>
                                                <th>Tanggal SK Pengangkatan</th>
                                                <th>Nomor SK Pemberhentian</th>
                                                <th>Tanggal SK Pemberhentian</th>
                                                <th>Masa Jabatan (Usia/Periode)</th>
                                            @endif
                                            <th>Keterangan</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('admin.layouts.components.konfirmasi_hapus')
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            var TableData = $('#tabeldata').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                ajax: {
                    url: `{{ route($controller . '.datatables') }}`,
                    data: function(req) {
                        req.id_kelompok = '{{ $kelompok['id'] }}';
                    }
                },
                columns: [{
                        data: 'ceklist',
                        class: 'padat',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'DT_RowIndex',
                        class: 'padat',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'aksi',
                        class: 'aksi',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'foto',
                        name: 'foto',
                        searchable: false,
                        orderable: false,
                    },
                    {
                        data: 'no_anggota',
                        name: 'no_anggota',
                        searchable: true,
                        orderable: false
                    },
                    {
                        data: 'anggota.nik',
                        name: 'anggota.nik',
                        searchable: true,
                        orderable: false
                    },
                    {
                        data: 'anggota.nama',
                        name: 'anggota.nama',
                        class: 'no-wrap',
                        searchable: true,
                        orderable: false
                    },
                    {
                        data: 'tanggallahir',
                        name: 'tanggallahir',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'umur',
                        name: 'umur',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'jk',
                        name: 'jk',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'anggota.alamat_wilayah',
                        name: 'anggota.alamat_wilayah',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'jabatan',
                        name: 'jabatan',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'no_sk_jabatan',
                        name: 'no_sk_jabatan',
                        searchable: false,
                        orderable: false
                    },
                    @if ($tipe == 'Lembaga')
                        {
                            data: 'nmr_sk_pengangkatan',
                            name: 'nmr_sk_pengangkatan',
                            searchable: false,
                            orderable: false
                        }, {
                            data: 'tgl_sk_pengangkatan',
                            name: 'tgl_sk_pengangkatan',
                            searchable: false,
                            orderable: false
                        }, {
                            data: 'nmr_sk_pemberhentian',
                            name: 'nmr_sk_pemberhentian',
                            searchable: false,
                            orderable: false
                        }, {
                            data: 'tgl_sk_pemberhentian',
                            name: 'tgl_sk_pemberhentian',
                            searchable: false,
                            orderable: false
                        }, {
                            data: 'periode',
                            name: 'periode',
                            searchable: false,
                            orderable: false
                        },
                    @endif {
                        data: 'keterangan',
                        name: 'keterangan',
                        searchable: true,
                        orderable: false
                    },
                ],
                order: [
                    // [6, 'asc']
                ],
            });

            if (hapus == 0) {
                TableData.column(0).visible(false);
            }

            if (ubah == 0) {
                TableData.column(2).visible(false);
            }
        });
    </script>
@endpush
