@extends('admin.layouts.index')
@include('admin.layouts.components.asset_datatables')
@include('admin.layouts.components.jquery_ui')

@section('title')
    <h1>
        Daftar Inventaris Jalan
    </h1>
@endsection

@push('css')
    <style>
        .table .btn {
            margin-right: 2px;
        }
    </style>
@endpush

@section('breadcrumb')
    <li class="active">Daftar Inventaris Jalan</li>
@endsection

@section('content')
    @include('admin.layouts.components.notifikasi')

    <div class="row">
        <div class="col-md-3">
            @include('admin.inventaris.menu')
        </div>
        <div class="col-md-9">
            <div class="box box-info">
                <div class="box-header with-border">
                    @if (can('u'))
                        <a href="{{ ci_route('inventaris_jalan.form') }}" class="btn btn-social btn-success btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-plus"></i> Tambah</a>
                    @endif
                    <a
                        href="{{ ci_route('inventaris_jalan/dialog/cetak') }}"
                        class="btn btn-social bg-purple btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"
                        title="Cetak Konstruksi Dalam Pengerjaan"
                        data-remote="false"
                        data-toggle="modal"
                        data-target="#modalBox"
                        data-title="Cetak Konstruksi Dalam Pengerjaan"
                    ><i class="fa fa-print "></i> Cetak</a>
                    <a
                        href="{{ ci_route('inventaris_jalan/dialog/unduh') }}"
                        class="btn btn-social bg-navy btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"
                        title="Unduh Konstruksi Dalam Pengerjaan"
                        data-remote="false"
                        data-toggle="modal"
                        data-target="#modalBox"
                        data-title="Unduh Konstruksi Dalam Pengerjaan"
                    ><i class="fa fa-download"></i> Unduh</a>
                </div>
                <div class="box-body">
                    <div class="table-responsive">
                        <table id="tabel4" class="table table-bordered dataTable table-hover">
                            <thead class="bg-gray">
                                <tr>
                                    <th class="text-center" rowspan="2">No</th>
                                    <th class="text-center" rowspan="2">Aksi</th>
                                    <th class="text-center" rowspan="2">Nama Barang</th>
                                    <th class="text-center" rowspan="2">Kode Barang / Nomor Registrasi</th>
                                    <th class="text-center" rowspan="2">Kondisi (B, KB, RB)</th>
                                    <th class="text-center" rowspan="2">Jenis Konstruksi</th>
                                    <th class="text-center" rowspan="2">Luas (M<sup>2</sup>)</th>
                                    <th class="text-center" colspan="2">Dokumen Kepemilikan</th>
                                    <th class="text-center" rowspan="2">Status Tanah</th>
                                    <th class="text-center" rowspan="2">Asal Usul</th>
                                    <th class="text-center" rowspan="2">Harga (Rp)</th>
                                </tr>
                                <tr>
                                    <th class="text-center" style="text-align:center;" rowspan="1">Tanggal</th>
                                    <th class="text-center" style="text-align:center;" rowspan="1">Nomor</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="11" style="text-align: right;">Total:</th>
                                    <th></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('admin.layouts.components.konfirmasi_hapus')
@endsection

@push('scripts')
    {{-- link moment js --}}
    <script src="{{ asset('bootstrap/moment.min.js') }}"></script>
    {{-- link datetimepicker --}}
@endpush

@push('scripts')
    <script>
        $(document).ready(function() {
            var TableData = $('#tabel4').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ ci_route('inventaris_jalan.datatables') }}",
                    data: function(req) {}
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
                        name: 'nama_barang',
                        searchable: true,
                        orderable: true,
                    },
                    {
                        data: 'kode_barang_register',
                        name: 'kode_barang',
                        searchable: true,
                        orderable: true
                    },
                    {
                        data: 'kondisi',
                        name: 'kondisi',
                        empty: '-',
                        searchable: true,
                        orderable: true
                    },
                    {
                        data: 'kontruksi',
                        name: 'kontruksi',
                        empty: '-',
                        searchable: true,
                        orderable: true
                    },
                    {
                        data: 'luas',
                        name: 'luas',
                        empty: '-',
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
                aaSorting: [],
                createdRow: function(row, data, dataIndex) {
                    $(row).attr('data-id', data.id)
                },
                footerCallback: function(row, data, start, end, display) {
                    var api = this.api();

                    var columnData = api.column(11, {
                        page: 'current'
                    }).data();

                    var total = columnData.reduce(function(a, b) {
                        return a + parseFloat(b.replace(/\./g, ''));
                    }, 0);

                    $(api.column(11).footer()).html(total.toLocaleString('id-ID'));
                }
            });

            $('#status').change(function() {
                TableData.draw();
            })

            if (hapus == 0) {
                TableData.column(1).visible(false);
            }

            if (ubah == 0) {
                TableData.column(1).visible(false);
            }

        });

        $("#form_cetak").click(function(event) {
            var link = '{{ site_url('inventaris_jalan/cetak') }}' + '/' + $('#tahun_pdf').val() + '/' + $('#penandatangan_pdf').val();
            window.open(link, '_blank');
        });
        $("#form_download").click(function(event) {
            var link = '{{ site_url('inventaris_jalan/download') }}' + '/' + $('#tahun').val() + '/' + $('#penandatangan').val();
            window.open(link, '_blank');
        });
    </script>
@endpush
