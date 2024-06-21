@extends('layanan_mandiri.layouts.index')

@section('content')
    <div class="box box-solid">
        <div class="box-header with-border bg-aqua">
            <h4 class="box-title">Bantuan</h4>
        </div>
        <div class="box-body box-line">
            <h4><b>BANTUAN PENDUDUK</b></h4>
        </div>
        <div class="box-body box-line">
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-data" id="tabeldata">
                    <thead>
                        <tr class="judul">
                            <th class="padat">No</th>
                            <th class="padat">Aksi</th>
                            <th>Waktu / Tanggal</th>
                            <th>Nama Program</th>
                            <th>Keterangan</th>
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
    <link rel="stylesheet" href="{{ asset('bootstrap/css/jquery-ui.min.css') }}">
    <script src="{{ asset('bootstrap/js/jquery-ui.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            var tabelData = $('#tabeldata').DataTable({
                'processing': true,
                'serverSide': true,
                'ajax': "{{ ci_route('layanan-mandiri.bantuan.datatables') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        class: 'padat',
                        orderable: false,
                        searchable: false,
                    },
                    {
                        data: 'aksi',
                        class: 'padat',
                        orderable: false,
                        searchable: false,
                    },
                    {
                        data: 'waktu',
                        name: 'waktu',
                    },
                    {
                        data: 'bantuan.nama',
                        name: 'bantuan.nama',
                    },
                    {
                        data: 'bantuan.ndesc',
                        name: 'bantuan.ndesc',
                    },
                ],
                aaSorting: [],
            });
        });

        function show_kartu_peserta(elem) {
            var id = elem.attr('target');
            var title = elem.attr('title');
            var url = elem.attr('href');
            $('#' + id + '').remove();

            $('body').append('<div id="' + id + '" title="' + title + '" style="display:none;position:relative;overflow:scroll;"></div>');

            $('#' + id + '').dialog({
                resizable: true,
                draggable: true,
                width: 500,
                height: 'auto',
                open: function(event, ui) {
                    $('#' + id + '').load(url);
                }
            });
            $('#' + id + '').dialog('open');
        }
    </script>
@endpush
