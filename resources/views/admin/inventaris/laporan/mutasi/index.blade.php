@include('admin.layouts.components.asset_datatables')

@extends('admin.layouts.index')

@section('title')
    <h1>
        {{ $header }}
    </h1>
@endsection

@section('breadcrumb')
    <li class="active">{{ $header }}</li>
@endsection

@push('css')
    <style type="text/css">
        .jenis {
            width: 1%;
            white-space: nowrap;
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
                    @php
                        $listCetakUnduh = [
                            [
                                'url' => "laporan_inventaris/dialog/cetak/1",
                                'judul' => 'Cetak',
                                'icon' => 'fa fa-print',
                                'modal' => true,
                            ],
                            [
                                'url' => "laporan_inventaris/dialog/unduh/1",
                                'judul' => 'Unduh',
                                'icon' => 'fa fa-download',
                                'modal' => true,
                            ]
                        ];
                    @endphp

                    <x-split-button
                        judul="Cetak/Unduh"
                        :list="$listCetakUnduh"
                        :icon="'fa fa-arrow-circle-down'"
                        :type="'bg-purple'"
                        :target="true"
                    />
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
                                                    <th class="text-center" rowspan="3">No</th>
                                                    <th class="text-center" rowspan="3">Jenis Barang</th>
                                                    <th class="text-center" width="340%" rowspan="3">Keterangan</th>
                                                    <th class="text-center" colspan="5">Asal barang</th>
                                                    <th class="text-center" rowspan="3">Aksi</th>
                                                </tr>
                                                <tr>
                                                    <th class="text-center" rowspan="2">Dibeli Sendiri</th>
                                                    <th class="text-center" colspan="3">Bantuan</th>
                                                    <th class="text-center" style="text-align:center;" rowspan="2">Sumbangan</th>
                                                </tr>
                                                <tr>
                                                    <th class="text-center">Pemerintah</th>
                                                    <th class="text-center">Provinsi</th>
                                                    <th class="text-center">Kabupaten</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th colspan="3" style="text-align:right">Total</th>
                                                    <th></th>
                                                    <th></th>
                                                    <th></th>
                                                    <th></th>
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
                    url: "{{ ci_route('laporan_inventaris.datatables') }}",
                    data: function(req) {
                        req.mutasi = 1;
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        class: 'padat',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'jenis',
                        name: 'jenis',
                        class: 'jenis'
                    },
                    {
                        data: 'ket',
                        name: 'ket'
                    },
                    {
                        data: 'pribadi',
                        name: 'pribadi',
                        searchable: true,
                        orderable: true
                    },
                    {
                        data: 'pemerintah',
                        name: 'pemerintah',
                        searchable: true,
                        orderable: true
                    },
                    {
                        data: 'provinsi',
                        name: 'provinsi',
                        searchable: true,
                        orderable: true
                    },
                    {
                        data: 'kabupaten',
                        name: 'kabupaten',
                        searchable: true,
                        orderable: true
                    },
                    {
                        data: 'sumbangan',
                        name: 'sumbangan',
                        searchable: true,
                        orderable: true
                    },
                    {
                        data: 'aksi',
                        class: 'aksi',
                        searchable: false,
                        orderable: false
                    },
                ],
                order: [],
                footerCallback: function(row, data, start, end, display) {
                    var api = this.api();
                    for (var i = 3; i < api.columns().count(); i++) {
                        var columnData = api.column(i, {
                            page: 'current'
                        }).data();
                        var total = columnData.reduce(function(a, b) {
                            var a = isNaN(a) ? 0 : a;
                            // var b = b.replace(/\./g, '');
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
