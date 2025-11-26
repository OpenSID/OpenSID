@include('admin.layouts.components.asset_datatables')
@include('admin.layouts.components.jquery_ui')
@include('admin.layouts.components.datetime_picker')

@extends('admin.layouts.index')
@section('title')
    <h1>Program Bantuan {{ $nama_excerpt }}</h1>
@endsection

@section('breadcrumb')
    <li><a href="{{ site_url('program_bantuan') }}"> Daftar Program Bantuan</a></li>
    <li class="active">Program Bantuan {{ $nama_excerpt }}</li>
@endsection

<style>
    .aksi .btn {
        margin-right: 3px;
    }
</style>

@section('content')
    @include('admin.layouts.components.notifikasi')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-info">
                <div class="box-header with-border">
                    @php 
                    $listTambah = [
                        ['url' => "peserta_bantuan/aksi/1/{$detail['id']}", 'judul' => "Tambah Satu Peserta Baru", 'icon' => 'fa fa-plus'],
                        ['url' => "peserta_bantuan/aksi/2/{$detail['id']}", 'judul' => "Tambah Beberapa Peserta Baru", 'icon' => 'fa fa-plus']
                    ];

                    $listCetakUnduh = [
                        ['url' => "peserta_bantuan/daftar/{$detail['id']}/cetak", 'judul' => "Cetak", 'icon' => 'fa fa-print', 'target' => true],
                        ['url' => "peserta_bantuan/daftar/{$detail['id']}/unduh", 'judul' => "Unduh", 'icon' => 'fa fa-download', 'target' => true]
                    ];
                    @endphp
                    <x-split-button judul="Tambah" :list="$listTambah" />
                    <x-hapus-button :url="'peserta_bantuan/delete_all/' . $detail['id']"  :confirmDelete="true" :selectData="true" />
                    <x-split-button judul="Cetak/Unduh" :list="$listCetakUnduh" :icon="'fa fa-arrow-circle-down'" :type="'bg-purple'" :target="true" />
                    <x-kembali-button judul="Kembali ke Daftar Program Bantuan" :url="'program_bantuan'" />
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <input type="hidden" id="program_id" name="program_id" value="{{ $detail['id'] }}">
                            @include('admin.program_bantuan.peserta.rincian')
                            <div class="dataTables_wrapper form-inline dt-bootstrap no-footer">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <h5><b>Daftar Peserta</b></h5>
                                    </div>
                                    <form id="mainform" name="mainform" method="post">
                                        <div class="col-sm-12">
                                            <div class="table-responsive">
                                                <table class="table table-bordered table-striped dataTable table-hover tabel-daftar" id="tabeldata">
                                                    <thead class="bg-gray color-palette">
                                                        <tr>
                                                            @if (can('u'))
                                                                <th rowspan="2" class="padat"><input type="checkbox" id="checkall" /></th>
                                                            @endif
                                                            <th rowspan="2" class="padat">No</th>
                                                            @if (can('u'))
                                                                <th rowspan="2" class="padat">Aksi</th>
                                                            @endif
                                                            <th rowspan="2" nowrap>{{ $detail['judul_peserta'] }}</th>
                                                            @if (!empty($detail['judul_peserta_plus']))
                                                                <th rowspan="2" nowrap class="text-center">{{ $detail['judul_peserta_plus'] }}</th>
                                                            @endif
                                                            <th rowspan="2" nowrap>{{ $detail['judul_peserta_info'] }}</th>
                                                            <th colspan="8">Identitas di Kartu Peserta</th>
                                                        </tr>
                                                        <tr>
                                                            <th class="padat">No. Kartu Peserta</th>
                                                            <th>NIK</th>
                                                            <th>Nama</th>
                                                            <th>Tempat Lahir</th>
                                                            <th>Tanggal Lahir</th>
                                                            <th>Jenis Kelamin</th>
                                                            <th>Alamat</th>
                                                            <th>Keterangan</th>
                                                        </tr>
                                                    </thead>
                                                </table>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
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
                serverSide: false,
                ajax: {
                    url: "{{ ci_route('peserta_bantuan.datatables', $detail['id']) }}",
                    data: function(req) {}
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
                        data: 'peserta_nama',
                        name: 'peserta_nama',
                        searchable: true,
                        orderable: false,
                    },
                    @if (!empty($detail['judul_peserta_plus']))
                        {
                            data: 'peserta_plus',
                            name: 'peserta_plus',
                            class: 'padat',
                            searchable: true,
                            orderable: false
                        },
                    @endif {
                        data: 'peserta_info',
                        name: 'peserta_info',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'no_id_kartu',
                        name: 'no_id_kartu',
                        class: 'padat',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'kartu_nik',
                        name: 'kartu_nik',
                        class: 'padat',
                        searchable: true,
                        orderable: false
                    },
                    {
                        data: 'kartu_nama',
                        name: 'kartu_nama',
                        searchable: true,
                        orderable: false
                    },
                    {
                        data: 'kartu_tempat_lahir',
                        name: 'kartu_tempat_lahir',
                        searchable: true,
                        orderable: false
                    },
                    {
                        data: 'kartu_tanggal_lahir',
                        name: 'kartu_tanggal_lahir',
                        searchable: true,
                        orderable: false
                    },
                    {
                        data: 'kartu_sex',
                        name: 'kartu_sex',
                        searchable: true,
                        orderable: false
                    },
                    {
                        data: 'kartu_alamat',
                        name: 'kartu_alamat',
                        searchable: true,
                        orderable: false
                    },
                    {
                        data: 'status_dasar',
                        name: 'status_dasar',
                        searchable: true,
                        orderable: false
                    },
                ],
                aaSorting: [],
            });
            if (hapus == 0) {
                TableData.column(0).visible(false);
            }
        });
    </script>
@endpush
