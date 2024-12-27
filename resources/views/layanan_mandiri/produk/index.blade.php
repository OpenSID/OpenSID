@extends('layanan_mandiri.layouts.index')

@section('content')
    <div class="box box-solid">
        <div class="box-header with-border bg-aqua">
            <h4 class="box-title">PRODUK</h4>
        </div>
        <div class="box-body box-line">
            <a href="{{ site_url('layanan-mandiri/produk/form') }}" class="btn btn-social btn-success visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-pencil-square-o"></i>Tambah Produk</a>
            <a href="{{ site_url('layanan-mandiri/produk/pengaturan') }}" class="btn btn-social bg-purple visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-gear"></i>Pengaturan Lapak</a>
        </div>
        <div class="box-body box-line">
            <h4><b>DAFTAR PRODUK</b></h4>
        </div>
        <div class="box-body">
            @include('layanan_mandiri.layouts.components.notifikasi')

            <div class="table-responsive">
                <table class="table table-bordered table-hover table-data" id="tabeldata">
                    <thead>
                        <tr>
                            <th width="1%">No</th>
                            <th width="1%">Aksi</th>
                            <th>PRODUK</th>
                            <th width="15%">KATEGORI</th>
                            <th width="10%">HARGA</th>
                            <th width="10%">SATUAN</th>
                            <th width="10%">POTONGAN</th>
                            <th width="10%">STATUS</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            var tabelData = $('#tabeldata').DataTable({
                'processing': true,
                'serverSide': true,
                'ajax': "{{ ci_route('layanan-mandiri.produk.datatables') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        class: 'padat',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'aksi',
                        class: 'padat',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'nama'
                    },
                    {
                        data: 'id_produk_kategori',
                        class: 'padat'
                    },
                    {
                        data: 'harga',
                        class: 'padat'
                    },
                    {
                        data: 'satuan',
                        class: 'padat'
                    },
                    {
                        data: 'potongan',
                        class: 'padat'
                    },
                    {
                        data: 'status',
                        class: 'padat'
                    },
                ],
                order: [7, 'desc'],
            });
        });
    </script>
@endpush
