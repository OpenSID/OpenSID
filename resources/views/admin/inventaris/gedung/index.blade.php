@include('admin.layouts.components.asset_datatables')

@extends('admin.layouts.index')

@section('title')
    <h1>
        {{ $action }} {{ $header  }}
    </h1>
@endsection

@section('breadcrumb')
    <li class="active">{{ $action }} {{ $header  }}</li>
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
                    <x-tambah-button :url="'inventaris_gedung/form'" />
                    @php
                        $listCetakUnduh = [
                            [
                                'url' => "inventaris_gedung/dialog/cetak",
                                'judul' => 'Cetak',
                                'icon' => 'fa fa-print',
                                'modal' => true,
                            ],
                            [
                                'url' => "inventaris_gedung/dialog/unduh",
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
                                                    <th class="text-center" rowspan="2">No</th>
                                                    <th class="text-center" rowspan="2">Aksi</th>
                                                    <th class="text-center" rowspan="2">Nama Barang</th>
                                                    <th class="text-center" rowspan="2">Kode Barang / Nomor Registrasi</th>
                                                    <th class="text-center" rowspan="2">Kondisi Bangunan (B, KB, RB)</th>
                                                    <th class="text-center" rowspan="2">Letak/Lokasi</th>
                                                    <th class="text-center" colspan="2">Dokumen Gedung</th>
                                                    <th class="text-center" rowspan="2">Status Tanah</th>
                                                    <th class="text-center" rowspan="2">Asal Usul</th>
                                                    <th class="text-center" rowspan="2">Harga (Rp)</th>
                                                </tr>
                                                <tr>
                                                    <th class="text-center" style="text-align:center;" rowspan="1">Tanggal</th>
                                                    <th class="text-center" style="text-align:center;" rowspan="1">Nomor</th>
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
                    url: "{{ ci_route('inventaris_gedung.datatables') }}"
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
                        data: 'kode_barang_register',
                        name: 'kode_barang'
                    },
                    {
                        data: 'kondisi_bangunan',
                        name: 'kondisi_bangunan',
                        searchable: true,
                        orderable: true
                    },
                    {
                        data: 'letak',
                        name: 'letak',
                        searchable: true,
                        orderable: true
                    },
                    {
                        data: 'tanggal_dokument',
                        name: 'tanggal_dokument',
                        empty: '-',
                        searchable: true,
                        orderable: true
                    },
                    {
                        data: 'no_dokument',
                        name: 'no_dokument',
                        empty: '-',
                        searchable: true,
                        orderable: true
                    },
                    {
                        data: 'status_tanah',
                        name: 'status_tanah',
                        searchable: true,
                        orderable: true
                    },
                    {
                        data: 'asal',
                        name: 'asal',
                        searchable: true,
                        orderable: true
                    },
                    {
                        data: 'harga',
                        name: 'harga',
                        searchable: true,
                        orderable: true
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
