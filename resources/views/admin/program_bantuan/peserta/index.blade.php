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
                    @if (can('u') && $detail['status_masa_aktif'] == 'Aktif')
                        <div class="btn-group btn-group-vertical">
                            <a class="btn btn-social btn-success btn-sm" data-toggle="dropdown"><i class='fa fa-plus'></i>
                                Tambah</a>
                            <ul class="dropdown-menu" role="menu">
                                <li>
                                    <a href="{{ site_url('peserta_bantuan/aksi/1/' . $detail['id']) }}" class="btn btn-social btn-block btn-sm" title="Tambah Satu Peserta Baru "><i class="fa fa-plus"></i> Tambah Satu</a>
                                </li>
                                <li>
                                    <a href="{{ site_url('peserta_bantuan/aksi/2/' . $detail['id']) }}" class="btn btn-social btn-block btn-sm" title="Tambah Beberapa Peserta Baru"><i class="fa fa-plus"></i> Tambah Beberapa</a>
                                </li>
                            </ul>
                        </div>
                    @endif
                    @if (can('h'))
                        <a href="#confirm-delete" title="Hapus Data Terpilih" onclick="deleteAllBox('mainform', '{{ ci_route('peserta_bantuan.delete_all', $detail['id']) }}')"
                            class="btn btn-social btn-danger btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block hapus-terpilih"
                        ><i class='fa fa-trash-o'></i> Hapus</a>
                    @endif
                    <a href="{{ site_url("peserta_bantuan/daftar/{$detail['id']}/cetak") }}" class="btn btn-social bg-purple btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Cetak" target="_blank"><i class="fa fa-print"></i> Cetak</a>
                    <a href="{{ site_url("peserta_bantuan/daftar/{$detail['id']}/unduh") }}" class="btn btn-social bg-navy btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Unduh" target="_blank"><i class="fa fa-download"></i> Unduh</a>
                    <a href="{{ site_url('program_bantuan/clear') }}" class="btn btn-social btn-info btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Kembali Ke Daftar Program Bantuan"><i class="fa fa-arrow-circle-o-left"></i> Kembali Ke
                        Daftar Program Bantuan</a>
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
                        data: 'sex',
                        name: 'sex',
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
