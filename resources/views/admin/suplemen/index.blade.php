@include('admin.layouts.components.asset_datatables')
@extends('admin.layouts.index')

@section('title')
    <h1>
        Data Suplemen
    </h1>
@endsection

@section('breadcrumb')
    <li class="active">Data Suplemen</li>
@endsection

@section('content')
    @include('admin.layouts.components.notifikasi')

    <div class="box box-info">
        <div class="box-header with-border">
            @if (can('u'))
                <a href="{{ ci_route('suplemen.form') }}" class="btn btn-social btn-success btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-plus"></i> Tambah</a>
            @endif
        </div>
        <div class="box-body">
            <div class="row mepet">
                <div class="col-sm-2">
                    <select class="form-control input-sm select2" id="sasaran" name="sasaran">
                        <option value="">Pilih Sasaran</option>
                        @foreach ($list_sasaran as $key => $value)
                            @if (in_array($key, ['1', '2']))
                                <option value="{{ $key }}">{{ $value }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
            </div>
            <hr class="batas">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="tabeldata">
                    <thead>
                        <tr>
                            <th class="padat">NO</th>
                            <th class="padat">AKSI</th>
                            <th>NAMA DATA</th>
                            <th>JUMLAH TERDATA</th>
                            <th>SASARAN</th>
                            <th width="30%">KETERANGAN</th>
                        </tr>
                    </thead>
                </table>
            </div>
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
                    url: "{{ ci_route('suplemen.datatables') }}",
                    data: function(req) {
                        req.sasaran = $('#sasaran').val();
                    },
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
                        data: 'nama',
                        name: 'nama',
                        searchable: true,
                        orderable: true
                    },
                    {
                        data: 'terdata_count',
                        name: 'terdata_count',
                        searchable: false,
                        orderable: false,
                        class: 'padat'
                    },
                    {
                        data: 'sasaran',
                        name: 'sasaran',
                        searchable: true,
                        orderable: true,
                        class: 'padat'
                    },
                    {
                        data: 'keterangan',
                        name: 'keterangan',
                        searchable: true,
                        orderable: true
                    },
                ],
                order: [
                    [2, 'asc']
                ],
            });

            $('select[name="sasaran"]').on('change', function() {
                $(this).val();
                TableData.ajax.reload();
            });
        });
    </script>
@endpush
