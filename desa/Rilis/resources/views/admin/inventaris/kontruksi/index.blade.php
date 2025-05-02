@include('admin.layouts.components.asset_datatables')

@extends('admin.layouts.index')

@section('title')
    <h1>
        Daftar Inventaris Kontruksi
    </h1>
@endsection

@section('breadcrumb')
    <li class="active">Daftar Inventaris Kontruksi</li>
@endsection

@push('css')
    <style type="text/css">
        .disabled {
            pointer-events: none;
            cursor: default;
        }
    </style>
@endpush

@section('content')
    @include('admin.layouts.components.notifikasi')
    <div class="row">
        <div class="col-sm-3">
            @include('admin.inventaris.menu')
        </div>
        <div class="col-sm-9">
            <div class="box box-info">
                <div class="box-header with-border">
                    @if (can('u'))
                        <a href="{{ ci_route('inventaris_kontruksi.form') }}" class="btn btn-social btn-success btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-plus"></i> Tambah</a>
                    @endif
                    <a
                        href="{{ ci_route('inventaris_kontruksi/dialog/cetak') }}"
                        class="btn btn-social bg-purple btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"
                        title="Cetak Konstruksi Dalam Pengerjaan"
                        data-remote="false"
                        data-toggle="modal"
                        data-target="#modalBox"
                        data-title="Cetak Konstruksi Dalam Pengerjaan"
                    ><i class="fa fa-print "></i> Cetak</a>
                    <a
                        href="{{ ci_route('inventaris_kontruksi/dialog/unduh') }}"
                        class="btn btn-social bg-navy btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"
                        title="Unduh Konstruksi Dalam Pengerjaan"
                        data-remote="false"
                        data-toggle="modal"
                        data-target="#modalBox"
                        data-title="Unduh Konstruksi Dalam Pengerjaan"
                    ><i class="fa fa-download"></i> Unduh</a>
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="table-responsive">
                                        <table id="tabeldata" class="table table-bordered table-hover">
                                            <thead class="bg-gray">
                                                <tr>
                                                    <th class="text-center" rowspan="2">No</th>
                                                    <th class="text-center" rowspan="2">Aksi</th>
                                                    <th class="text-center" rowspan="2">Nama Barang</th>
                                                    <th class="text-center" rowspan="2">Fisik Bangunan (P, SP, D)</th>
                                                    <th class="text-center" rowspan="2">Luas (M<sup>2</sup>)</th>
                                                    <th class="text-center" colspan="2">Dokumen</th>
                                                    <th class="text-center" rowspan="2">Tgl,bln,thn Mulai</th>
                                                    <th class="text-center" rowspan="2">Status Tanah</th>
                                                    <th class="text-center" rowspan="2">Asal Usul Biaya</th>
                                                    <th class="text-center" rowspan="2">Nilai Kontrak (Rp)</th>
                                                </tr>
                                                <tr>
                                                    <th class="text-center" rowspan="1">Tanggal</th>
                                                    <th class="text-center" rowspan="1">Nomor</th>
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
                                            <tfoot>
                                                <tr>
                                                    <th colspan="10" style="text-align:right">Total</th>
                                                    <th></th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @include('admin.layouts.components.konfirmasi_hapus')
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            var TableData = $('#tabeldata').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ ci_route('inventaris_kontruksi.datatables') }}"
                },
                columns: [{
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
                        data: 'nama_barang',
                        name: 'nama_barang'
                    },
                    {
                        data: 'kondisi_bangunan',
                        name: 'kondisi_bangunan'
                    },
                    {
                        data: function(data) {
                            return data.luas_bangunan ?? '-'
                        },
                        name: 'luas_bangunan'
                    },
                    {
                        data: 'tanggal_dokument',
                        name: 'tanggal_dokument'
                    },
                    {
                        data: function(data) {
                            return data.no_dokument ?? '-'
                        },
                        name: 'no_dokument'
                    },
                    {
                        data: 'tanggal',
                        name: 'tanggal'
                    },
                    {
                        data: function(data) {
                            return data.status_tanah ?? '-'
                        },
                        name: 'status_tanah'
                    },
                    {
                        data: 'asal',
                        name: 'asal'
                    },
                    {
                        data: 'harga',
                        name: 'harga',
                        class: 'text-right',
                    },
                ],
                order: [],
                footerCallback: function(row, data, start, end, display) {
                    var api = this.api();
                    for (var i = 10; i < api.columns().count(); i++) {
                        var columnData = api.column(i, {
                            page: 'current'
                        }).data();
                        var total = columnData.reduce(function(a, b) {
                            var a = isNaN(a) ? 0 : a;
                            var b = b.replace(/\./g, '');
                            return a + parseFloat(b);
                        }, 0);

                        total = isNaN(total) ? 0 : total;
                        total = total.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");

                        $(api.column(i).footer()).html(total);
                    }
                }
            });
        });
    </script>
@endpush
