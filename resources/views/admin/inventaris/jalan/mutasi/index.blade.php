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
                <div class="box-body">
                    <div class="table-responsive">
                        <table id="tabel-data" class="table table-bordered dataTable table-hover">
                            <thead class="bg-gray">
                                <tr>
                                    <th class="padat">No</th>
                                    <th class="padat">Aksi</th>
                                    <th class="text-center">Nama Barang</th>
                                    <th class="text-center">Kode Barang / Nomor Registrasi</th>
                                    <th class="text-center">Tahun Pengadaan</th>
                                    <th class="text-center">Tanggal Mutasi</th>
                                    <th class="text-center">Status Jalan</th>
                                    <th class="text-center">Jenis Mutasi</th>
                                    <th class="text-center" width="300px">Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
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
            var TableData = $('#tabel-data').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ ci_route('inventaris_jalan_mutasi.datatables') }}",
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
                        name: 'kode_barang_register',
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
                        data: 'tanggal_mutasi',
                        name: 'tanggal_mutasi',
                        empty: '-',
                        searchable: true,
                        orderable: true
                    },
                    {
                        data: 'mutasi.status_mutasi',
                        name: 'mutasi.status_mutasi',
                        empty: '-',
                        searchable: true,
                        orderable: true
                    },
                    {
                        data: 'mutasi.jenis_mutasi',
                        name: 'mutasi.jenis_mutasi',
                        empty: '-',
                        searchable: true,
                        orderable: true
                    },
                    {
                        data: 'mutasi.keterangan',
                        name: 'mutasi.keterangan',
                        empty: '-',
                        searchable: true,
                        orderable: true
                    }
                ],
                order: [
                    [5, 'desc']
                ],
                createdRow: function(row, data, dataIndex) {
                    $(row).attr('data-id', data.id)
                }
            });

            if (hapus == 0) {
                TableData.column(1).visible(false);
            }

            if (ubah == 0) {
                TableData.column(1).visible(false);
            }

        });
    </script>
@endpush
