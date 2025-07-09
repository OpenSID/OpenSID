@include('admin.layouts.components.asset_datatables')

@extends('admin.layouts.index')

@section('title')
    <h1>
        Daftar C-Desa
    </h1>
@endsection

@section('breadcrumb')
    <li class="active">Daftar C-Desa</li>
@endsection

@section('content')
    @include('admin.layouts.components.notifikasi')
    <div class="box box-info">
        <div class="box-header with-border">
            @if (can('u'))
                <a href="{{ ci_route('cdesa.form') }}" class="btn btn-social btn-success btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-plus"></i> Tambah</a>
            @endif
            @if (can('h'))
                <a href="#confirm-delete" title="Hapus Data" onclick="deleteAllBox('mainform', '{{ ci_route('cdesa.delete_all') }}')" class="btn btn-social btn-danger btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block hapus-terpilih"><i class='fa fa-trash-o'></i>
                    Hapus</a>
            @endif
            <a
                href="{{ ci_route('cdesa.dialog.cetak') }}"
                class="btn btn-social bg-purple btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"
                title="Cetak Laporan"
                data-remote="false"
                data-toggle="modal"
                data-target="#modalBox"
                data-title="Cetak Laporan"
            >
                <i class="fa fa-print "></i>Cetak
            </a>
            <a
                href="{{ ci_route('cdesa.dialog.unduh') }}"
                class="btn btn-social bg-navy btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"
                title="Unduh Laporan"
                data-remote="false"
                data-toggle="modal"
                data-target="#modalBox"
                data-title="Unduh Laporan"
            >
                <i class="fa fa-print "></i>Unduh
            </a>
        </div>
        <div class="box-body">
            {!! form_open(null, 'id="mainform" name="mainform"') !!}
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="tabeldata">
                    <thead>
                        <tr>
                            <th><input type="checkbox" id="checkall" /></th>
                            <th class="padat">NO</th>
                            <th class="padat">AKSI</th>
                            <th>NO. CDESA</th>
                            <th>NAMA DI C-DESA</th>
                            <th>NAMA PEMILIK</th>
                            <th>NIK</th>
                            <th>JUMLAH PERSIL</th>
                        </tr>
                    </thead>
                </table>
            </div>
            </form>
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
                ajax: "{{ ci_route('cdesa.datatables') }}",
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
                        data: 'nomor',
                        name: 'nomor',
                        searchable: true,
                        orderable: true
                    },
                    {
                        data: 'nama_kepemilikan',
                        name: 'nama_kepemilikan',
                        searchable: true,
                        orderable: false
                    },
                    {
                        data: 'nama_pemilik',
                        name: 'nama_pemilik',
                        searchable: true,
                        orderable: false
                    },
                    {
                        name: 'nik_pemilik',
                        data: 'nik_pemilik',
                        searchable: true,
                        orderable: false,
                        render: function(item, data, row) {
                            return row.jenis_pemilik == 1 ? `<a href='{{ ci_route('penduduk.detail') }}/${row.id_pemilik}'>${item}</a>` : item
                        },
                    },
                    {
                        data: 'jumlah',
                        name: 'jumlah',
                        searchable: false,
                        orderable: false,
                        class: 'padat'
                    },
                ],
                order: [
                    [3, 'asc']
                ]
            });

            if (hapus == 0) {
                TableData.column(0).visible(false);
                $('.akses-hapus').remove();
            }

            if (ubah == 0) {
                TableData.column(2).visible(false);
            }
        });
    </script>
@endpush
