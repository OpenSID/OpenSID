@include('admin.layouts.components.asset_datatables')

@extends('admin.layouts.index')

@section('title')
    <h1>
        Daftar Permohonan Surat
    </h1>
@endsection

@section('breadcrumb')
    <li class="active">Daftar Permohonan Surat</li>
@endsection

@section('content')
    @include('admin.layouts.components.notifikasi')
    <div class="box box-info">
        {!! form_open(null, 'id="mainform" name="mainform"') !!}
        <div class="box-header with-border form-inline">
            <div class="row">
                <div class="col-sm-2">
                    <select class="form-control input-sm select2" id="status" name="status">
                        <option value="">Semua Status</option>
                        @foreach ($list_status_permohonan as $id => $value)
                            <option value="{{ $id }}">{{ $value }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        <div class="box-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="tabeldata">
                    <thead>
                        <tr>
                            <th class="padat">NO</th>
                            <th class="aksi">AKSI</th>
                            <th>NO ANTREAN</th>
                            <th>NIK</th>
                            <th>NAMA PENDUDUK</th>
                            <th>NO HP AKTIF</th>
                            <th>JENIS SURAT</th>
                            <th>TANGGAL KIRIM</th>
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
                ajax: {
                    url: "{{ ci_route('permohonan_surat_admin.datatables') }}",
                    data: function(req) {
                        req.status = $('#status').val();
                    }
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
                        data: 'no_antrian',
                        name: 'no_antrian',
                        searchable: true,
                        orderable: true
                    },
                    {
                        data: 'penduduk.nik',
                        name: 'penduduk.nik',
                        class: 'padat',
                        searchable: true,
                        orderable: true
                    },
                    {
                        data: 'penduduk.nama',
                        name: 'penduduk.nama',
                        searchable: true,
                        orderable: true
                    },
                    {
                        data: 'no_hp_aktif',
                        name: 'no_hp_aktif',
                        searchable: true,
                        orderable: true
                    },
                    {
                        data: 'surat.nama',
                        name: 'surat.nama',
                        searchable: true,
                        orderable: true
                    },
                    {
                        data: 'created_at',
                        name: 'created_at',
                        searchable: true,
                        orderable: true
                    },
                ],
                order: [
                    [7, 'desc']
                ],
                pageLength: 25
            });

            $('#status').on('select2:select', function(e) {
                TableData.draw();
            });

            if (ubah == 0) {
                TableData.column(1).visible(false);
            }
        });
    </script>
@endpush
