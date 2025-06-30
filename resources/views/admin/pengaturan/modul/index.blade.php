@include('admin.layouts.components.asset_datatables')

@extends('admin.layouts.index')

@section('title')
    <h1>
        Pengaturan {{ $utama ? 'Modul' : 'Submodul' }}
    </h1>
@endsection

@section('breadcrumb')
    @if (!$utama)
        <li><a href="{{ ci_route('modul') }}">Daftar Modul</a></li>
    @endif
    <li class="active">Pengaturan {{ $utama ? 'Modul' : 'Submodul' }}</li>
@endsection

@section('content')
    @include('admin.layouts.components.notifikasi')
    @includeWhen($utama, 'admin.pengaturan.modul.header')
    <div class="box box-info">
        <div class="box-header with-border">
            @if ($utama)
                <h4>Pengaturan Modul</h4>
                @if (can('u'))
                    <div class="row">
                        <div class="col-xs-12 text-center">
                            <a href="{{ ci_route('plugin') }}" class="btn btn-social btn-warning btn-sm"><i class="fa fa-plug"></i>Paket Tambahan</a>
                            <a href="{{ ci_route('modul.default_server') }}" class="btn btn-social btn-success btn-sm" @disabled(!setting('penggunaan_server'))><i class="fa fa-refresh"></i>Kembalikan ke default penggunaan server</a>
                        </div>
                    </div>
                @endif
            @else
                <a href="{{ ci_route('modul') }}" class="btn btn-social btn-info btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-arrow-circle-o-left"></i> Kembali Ke Daftar Modul</a>
                <div style="margin-top: 15px;">
                    <strong> Modul Utama : {{ SebutanDesa($parentName) }} </strong>
                </div>
            @endif
        </div>
        <div class="box-body">
            <div class="row mepet">
                <div class="col-sm-2">
                    <select id="status" class="form-control input-sm select2">
                        <option value="">Pilih Status</option>
                        @foreach ($status as $key => $item)
                            <option value="{{ $key }}">{{ $item }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <hr class="batas">

            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="tabeldata">
                    <thead>
                        <tr>
                            <th class="padat">No</th>
                            <th class="padat">Aksi</th>
                            <th>Nama Modul</th>
                            <th>Icon</th>
                            <th>Tampil</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
    @include('admin.pengaturan.modul.acak_modal')
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            var parent = '{{ $utama }}';
            var TableData = $('#tabeldata').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ ci_route('modul.datatables') }}?parent={{ $parent }}",
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
                        data: 'modul',
                        name: 'modul',
                        searchable: true,
                        orderable: true
                    },
                    {
                        data: 'ikon',
                        name: 'ikon',
                        class: 'padat',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'ikon',
                        name: 'ikon',
                        class: 'padat',
                        render: function(data, type, row) {
                            return `<i class="fa ${row.ikon} fa-lg"></i>`;
                        },
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'aktif',
                        name: 'aktif',
                        searchable: true,
                        orderable: false,
                        visible: false
                    },
                ],
                pageLength: 25,
                aaSorting: []
            });

            if (ubah == 0 && parent == 0) {
                TableData.column(1).visible(false);
            }

            $('#status').change(function() {
                TableData.column(5).search($(this).val()).draw()
            })
        });
    </script>
@endpush
