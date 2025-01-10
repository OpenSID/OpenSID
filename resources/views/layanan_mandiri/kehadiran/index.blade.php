@extends('layanan_mandiri.layouts.index')

@section('content')
    <div class="box box-solid">
        <div class="box-header with-border bg-red">
            <h4 class="box-title">KEHADIRAN PERANGKAT <?= strtoupper(setting('sebutan_desa')) ?> </h4>
        </div>
        <div class="box-body box-line">
            <h4><?= tgl_indo(date('Y-m-d')) ?></h4>
        </div>
        <div class="box-body box-line">
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-data" id="tabeldata">
                    <thead>
                        <tr class="judul">
                            <th class="padat">No</th>
                            <th>Nama</th>
                            <th>Jabatan</th>
                            <th class="padat text-center">Status Kehadiran</th>
                            <th class="padat">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @if (setting('tampilkan_kehadiran') == '1')
        <div class='modal fade' id='confirm-delete' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
            <div class='modal-dialog'>
                <div class='modal-content'>
                    <div class='modal-header'>
                        <button type='button' class='close' data-dismiss='modal' aria-hidden='true'>&times;</button>
                        <h4 class='modal-title' id='myModalLabel'><i class='fa fa-exclamation-triangle text-red'></i> Konfirmasi</h4>
                    </div>
                    <div class='modal-body btn-info'>
                        Apakah Anda yakin ingin melaporkan perangkat ini?
                    </div>
                    <div class='modal-footer'>
                        <button type="button" class="btn btn-social btn-warning btn-sm" data-dismiss="modal"><i class='fa fa-sign-out'></i> Tutup</button>
                        <a class='btn-ok'>
                            <button type="button" class="btn btn-social btn-danger btn-sm" id="ok-delete"><i class='fa fa-exclamation'></i> Laporkan</button>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            var kehadiran = '{{ setting('tampilkan_kehadiran') }}';
            var tabelData = $('#tabeldata').DataTable({
                'processing': true,
                'serverSide': true,
                'ajax': "{{ site_url('layanan-mandiri/kehadiran/datatables') }}",
                'columnDefs': [{
                        targets: [0, 3, 4],
                        searchable: false,
                    },
                    {
                        targets: [0, 3, 4],
                        orderable: false,
                    },
                ],
                columns: [{
                        data: 'DT_RowIndex',
                        class: 'padat',
                    },
                    {
                        data: 'pamong_nama',
                        name: 'pamong_nama',
                    },
                    {
                        data: 'jabatan.nama',
                        name: 'jabatan.nama',
                    },
                    {
                        data: 'status_kehadiran',
                        name: 'status_kehadiran',
                        class: 'padat',
                    },
                    {
                        data: 'aksi',
                        class: 'padat',
                    },
                ],
                aaSorting: [],
            });

            if (kehadiran == 0) {
                tabelData.column(3).visible(false);
                tabelData.column(4).visible(false);
            }
        });
    </script>
@endpush
