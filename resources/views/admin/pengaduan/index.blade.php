@include('admin.layouts.components.asset_datatables')
@extends('admin.layouts.index')

@section('title')
    <h1>
        Pengaduan Kehadiran
    </h1>
@endsection

@section('breadcrumb')
    <li class="active">Pengaduan Kehadiran</li>
@endsection

@section('content')
    @include('admin.layouts.components.notifikasi')

    <div class="box box-info">
        <div class="box-body">
            {!! form_open(null, 'id="mainform" name="mainform"') !!}
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="tabeldata">
                    <thead>
                        <tr>
                            <th class="padat">NO</th>
                            <th class="padat">AKSI</th>
                            <th>WAKTU</th>
                            <th>PELAPOR</th>
                            <th>TERLAPOR</th>
                            <th>KETERANGAN</th>
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
                ajax: "{{ ci_route('kehadiran_pengaduan.datatables') }}",
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
                        data: 'waktu',
                        name: 'waktu',
                        searchable: true,
                        orderable: true
                    },
                    {
                        data: 'mandiri.penduduk.nama',
                        name: 'mandiri.penduduk.nama',
                        searchable: true,
                        orderable: true
                    },
                    {
                        data: function(data) {
                            return (data.pamong.pamong_nama) ? data.pamong.pamong_nama : data.pamong.penduduk.nama
                        },
                        name: 'pamong.pamong_nama',
                        searchable: true,
                        orderable: true
                    },
                    {
                        data: 'keterangan',
                        name: 'keterangan',
                        searchable: true,
                        orderable: true
                    },
                ],
                order: [
                    [2, 'desc']
                ]
            });

            if (ubah == 0) {
                TableData.column(1).visible(false);
            }
        });
    </script>
@endpush
