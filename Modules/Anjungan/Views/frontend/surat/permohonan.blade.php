@extends('anjungan::frontend.beranda.index')

@push('css')
    <style>
        .table-data>thead>tr>th {
            vertical-align: middle;
            white-space: nowrap;
            text-transform: uppercase;
            background-color: #d2d6de !important;
            text-align: center;
            white-space: nowrap;
        }

        #tabeldata_info {
            font-size: 13px !important;
            line-height: 1.42857143;
            color: #333;
        }
    </style>
@endpush

@section('content')
    <div class="area-content">
        <div class="area-content-inner">
            <section class="content-header">
                <div class="row">
                    <div class="col-lg-12 text-center">
                        <h3>Daftar Permohonan Surat</h3>
                    </div>
                    <div class="col-lg-12">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover table-data" id="tabeldata">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Aksi</th>
                                        <th>No Antrean</th>
                                        <th>Jenis Surat</th>
                                        <th>Tanggal Kirim</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
            </section>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            var tabelData = $('#tabeldata').DataTable({
                'processing': true,
                'serverSide': true,
                // 'paging' : false,
                'ajax': '{{ route('anjungan.permohonan') }}',
                'scrollCollapse': true,
                'scrollY': '317px',
                'order': [
                    [4, 'desc']
                ],
                'columnDefs': [{
                        'searchable': false,
                        'targets': [0, 1]
                    },
                    {
                        'orderable': false,
                        'targets': [0, 1]
                    }
                ],
                'columns': [{
                        data: 'DT_RowIndex',
                        class: 'padat',
                    },
                    {
                        data: 'aksi',
                        class: 'aksi',
                    },
                    {
                        data: 'no_antrian',
                        class: 'padat',
                    },
                    {
                        data: 'surat.nama',
                        name: 'surat.nama',
                    },
                    {
                        data: 'created_at',
                        class: 'padat',
                    },
                ],
            });

            $('button.keterangan').click(function(event) {
                $(this).popover('show');
            });

        });
    </script>
@endpush
