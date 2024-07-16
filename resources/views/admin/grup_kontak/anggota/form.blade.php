@include('admin.layouts.components.asset_datatables')

@extends('admin.layouts.index')

@section('title')
    <h1>
        Anggota Grup {{ $anggotaGrup->nama_grup }}
        <small>Tambah Data</small>
    </h1>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ ci_route('daftar_kontak') }}">Grup Kontak</a></li>
    <li class="active">Tambah Data</li>
@endsection

@section('content')
    @include('admin.layouts.components.notifikasi')

    <div class="row">
        <div class="col-md-3">
            @include('admin.daftar_kontak.navigasi')
        </div>
        <div class="col-md-9">
            {!! form_open($formAction, 'class="form-horizontal" id="validasi"') !!}
            <input type="hidden" name="id_grup" value="{{ $grupKontak->id_grup }}" />
            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                    <li class="active"><a href="#data-penduduk" data-toggle="tab"> {{ SebutanDesa('Daftar Penduduk [desa]') }}</a></li>
                    <li><a href="#data-kontak" data-toggle="tab">Kontak Eksternal</a></li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active" id="data-penduduk">
                        <div class="box-header with-border">
                            <a href="{{ ci_route('grup_kontak.anggota', $grupKontak->id_grup) }}" class="btn btn-social btn-info btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block">
                                <i class="fa fa-arrow-circle-left "></i>Kembali ke Grup Kontak
                            </a>
                        </div>
                        <div class="box-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover" id="tabeldatapenduduk" style="width: 100%;">
                                    <thead>
                                        <tr>
                                            <th><input type="checkbox" id="checkallpenduduk" /></th>
                                            <th>NO</th>
                                            <th>NAMA</th>
                                            <th>TELEPON</th>
                                            <th>EMAIL</th>
                                            <th>TELEGRAM</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane" id="data-kontak">
                        <div class="box-header with-border">
                            <a href="{{ ci_route('grup_kontak.anggota', $grupKontak->id_grup) }}" class="btn btn-social btn-info btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block">
                                <i class="fa fa-arrow-circle-left "></i>Kembali ke Grup Kontak
                            </a>
                        </div>
                        <div class="box-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover" id="tabeldatakontak" style="width: 100%;">
                                    <thead>
                                        <tr>
                                            <th><input type="checkbox" id="checkallkontak" /></th>
                                            <th>NO</th>
                                            <th>NAMA</th>
                                            <th>TELEPON</th>
                                            <th>EMAIL</th>
                                            <th>TELEGRAM</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="box-footer">
                    <button type="reset" class="btn btn-social btn-danger btn-sm"><i class="fa fa-times"></i> Batal</button>
                    <button type="submit" class="btn btn-social btn-info btn-sm pull-right"><i class="fa fa-check"></i> Simpan</button>
                </div>
            </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {

            checkAllHeader("id_penduduk[]");
            checkAllBody("#checkallpenduduk", "#tabeldatapenduduk", "id_penduduk[]");

            var TableDataPenduduk = $('#tabeldatapenduduk').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                ajax: "{{ ci_route('grup_kontak.penduduk', $grupKontak->id_grup) }}",
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
                        data: 'nama',
                        name: 'nama',
                        searchable: true,
                        orderable: true
                    },
                    {
                        data: 'telepon',
                        name: 'telepon',
                        searchable: true,
                        orderable: true
                    },
                    {
                        data: 'email',
                        name: 'email',
                        searchable: true,
                        orderable: true
                    },
                    {
                        data: 'telegram',
                        name: 'telegram',
                        searchable: true,
                        orderable: true
                    },
                ],
                order: [
                    [2, 'asc']
                ]
            });

            checkAllHeader("id_kontak[]");
            checkAllBody("#checkallkontak", "#tabeldatakontak", "id_kontak[]");

            var TableDataKontak = $('#tabeldatakontak').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                ajax: "{{ ci_route('grup_kontak.kontak', $grupKontak->id_grup) }}",
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
                        data: 'nama',
                        name: 'nama',
                        searchable: true,
                        orderable: true
                    },
                    {
                        data: 'telepon',
                        name: 'telepon',
                        searchable: true,
                        orderable: true
                    },
                    {
                        data: 'email',
                        name: 'email',
                        searchable: true,
                        orderable: true
                    },
                    {
                        data: 'telegram',
                        name: 'telegram',
                        searchable: true,
                        orderable: true
                    },
                ],
                order: [
                    [2, 'asc']
                ]
            });
        });
    </script>
@endpush
