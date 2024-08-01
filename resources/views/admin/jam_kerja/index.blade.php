@include('admin.layouts.components.asset_datatables')
@extends('admin.layouts.index')

@section('title')
    <h1>
        Jam Kerja Kehadiran
    </h1>
@endsection

@section('breadcrumb')
    <li class="active">Jam Kerja Kehadiran</li>
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
                            <th>HARI</th>
                            <th>JAM MASUK</th>
                            <th>JAM KELUAR</th>
                            <th>KETERANGAN</th>
                            <th class="padat">STATUS</th>
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
                ajax: "{{ ci_route('kehadiran_jam_kerja.datatables') }}",
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
                        data: 'nama_hari',
                        name: 'nama_hari',
                        searchable: true,
                        orderable: true
                    },
                    {
                        data: 'jam_masuk',
                        name: 'jam_masuk',
                        searchable: true,
                        orderable: true
                    },
                    {
                        data: 'jam_keluar',
                        name: 'jam_keluar',
                        searchable: true,
                        orderable: true
                    },
                    {
                        data: 'keterangan',
                        name: 'keterangan',
                        searchable: true,
                        orderable: true
                    },
                    {
                        data: 'status',
                        name: 'status',
                        searchable: true,
                        orderable: true
                    },
                ],
                order: [
                    [3, 'asc']
                ]
            });

            if (ubah == 0) {
                TableData.column(1).visible(false);
            }
        });
    </script>
@endpush
