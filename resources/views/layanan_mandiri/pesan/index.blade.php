@extends('layanan_mandiri.layouts.index')

@section('content')
    <div class="box box-solid">
        <div class="box-header with-border bg-yellow">
            <h4 class="box-title">Pesan</h4>
        </div>
        <div class="box-body box-line">
            <a href="{{ ci_route('layanan-mandiri.pesan.tulis') }}" class="btn btn-social btn-success"><i class="fa fa-pencil-square-o"></i>Tulis Pesan</a>
            <a href="{{ ci_route('layanan-mandiri.pesan-masuk') }}" class="btn btn-social btn-primary"><i class="fa fa-inbox"></i>Pesan Masuk</a>
            <a href="{{ ci_route('layanan-mandiri.pesan-keluar') }}" class="btn btn-social bg-purple"><i class="fa fa-envelope-o"></i>Pesan Keluar</a>
        </div>
        <div class="box-body box-line">
            <h4><b>PESAN {{ strtoupper($judul) }}</b></h4>
        </div>
        <div class="box-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-data" id="tabeldata">
                    <thead>
                        <tr>
                            <th class="padat">No</th>
                            <th class="padat">Aksi</th>
                            <th>Subjek Pesan</th>
                            <th>Status Pesan</th>
                            <th>Dikirimkan Pada</th>
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
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ ci_route('layanan-mandiri.pesan.datatables', ['kat' => $kat]) }}",
                    dataSrc: function(json) {
                        console.log(json); // Log the response from server
                        return json.data;
                    }
                },
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
                        data: 'subjek',
                        orderable: true,
                        name: 'subjek'
                    },
                    {
                        data: 'status_baca',
                        orderable: true,
                        class: 'padat',
                        name: 'status_baca'
                    },
                    {
                        data: 'tgl_upload',
                        orderable: true,
                        class: 'padat',
                        name: 'tgl_upload'
                    }
                ],
                rowCallback: function(row, data, index) {
                    if (data.status == 2) {
                        $(row).addClass('select_row');
                    }
                },
                order: [
                    [4, 'desc']
                ],
                aaSorting: []
            });
        });
    </script>
@endpush
