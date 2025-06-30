@extends('layanan_mandiri.layouts.index')

@section('content')
    <div class="box box-solid">
        <div class="box-header with-border bg-blue">
            <h4 class="box-title">DOKUMEN</h4>
        </div>

        <div class="box-body box-line">
            <a href="{{ ci_route('layanan-mandiri.dokumen.form') }}" class="btn btn-social btn-success visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-plus"></i>Tambah Dokumen</a>
        </div>

        <div class="box-body box-line">
            @if (session('notif'))
                @php
                    $alertClass = session('notif')['status'] == 'success' ? 'alert-success' : 'alert-danger';
                @endphp
                <div class="alert {{ $alertClass }}" role="alert">
                    {{ session('notif')['pesan'] }}
                </div>
            @endif
            @include('layanan_mandiri.layouts.components.notifikasi')
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-data" id="tabeldata">
                    <thead>
                        <tr>
                            <th class="padat">No</th>
                            <th class="aksi">Aksi</th>
                            <th width="20%">Jenis Dokumen</th>
                            <th>Nama Dokumen</th>
                            <th class="padat">Tanggal Upload</th>
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
                'ajax': "{{ ci_route('layanan-mandiri.dokumen.datatables') }}",
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
                        data: 'id_syarat',
                        name: 'id_syarat',
                        width: '20%',
                    },
                    {
                        data: 'nama',
                        name: 'nama',
                    },
                    {
                        data: 'tgl_upload',
                        name: 'tgl_upload',
                        class: 'padat',
                    },
                ],
                order: [3, 'asc'],
            });
        });
    </script>
@endpush
