@include('admin.layouts.components.asset_datatables')
@extends('admin.layouts.index')

@section('title')
    <h1>
        Pengaturan Klasifikasi - {{ $analisis_master['nama'] }}
    </h1>
@endsection

@section('breadcrumb')
    <li>Master Klasifikasi</li>
    <li>{{ $analisis_master['nama'] }}</li>
    <li class="active">Pengaturan Klasifikasi</li>
@endsection

@section('content')
    @include('admin.layouts.components.notifikasi')
    <div class="row">
        <div class="col-md-4 col-lg-3">
            @include('analisis::master.menu')
        </div>
        <div class="col-md-8 col-lg-9">

            <div class="box box-info">
                <div class="box-header with-border">
                    @if (can('u'))
                        <a href="{{ ci_route('analisis_klasifikasi.' . $analisis_master['id'] . '.form') }}" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Tambah Data" class="btn btn-social btn-success btn-sm"><i class='fa fa-plus'></i>
                            Tambah</a>
                    @endif
                    @if (can('h'))
                        <a href="#confirm-delete" title="Hapus Data" onclick="deleteAllBox('mainform', '{{ ci_route('analisis_klasifikasi.' . $analisis_master['id'] . '.delete') }}')"
                            class="btn btn-social btn-danger btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block hapus-terpilih"
                        ><i class='fa fa-trash-o'></i>
                            Hapus</a>
                    @endif
                    <a href="{{ ci_route('analisis_master.menu', $analisis_master['id']) }}" class="btn btn-social btn-info btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-arrow-circle-left "></i>{{ $analisis_master['nama'] }}</a>
                </div>
                <div class="box-body">
                    {!! form_open(null, 'id="mainform" name="mainform"') !!}
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="dataTables_wrapper form-inline dt-bootstrap no-footer">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="table-responsive">
                                            <table id="tabeldata" class="table table-bordered table-striped dataTable table-hover">
                                                <thead class="bg-gray disabled color-palette judul-besar">
                                                    <tr>
                                                        <th><input type="checkbox" id="checkall" /></th>
                                                        <th>No</th>
                                                        <th>Aksi</th>
                                                        <th>Klasifikasi</th>
                                                        <th>Minimal</th>
                                                        <th>Maksimal</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    </form>
                </div>
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
                ajax: "{{ ci_route('analisis_klasifikasi.' . $analisis_master['id'] . '.datatables') }}",
                columns: [{
                        data: 'ceklist',
                        class: 'padat',
                        searchable: false,
                        orderable: false
                    },
                    {
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
                        data: 'minval',
                        name: 'minval',
                        searchable: true,
                        orderable: true,
                        class: 'padat'
                    },
                    {
                        data: 'maxval',
                        name: 'maxval',
                        searchable: true,
                        orderable: true,
                        class: 'padat'
                    },
                ],
                order: [
                    [3, 'asc']
                ]
            });

            if (hapus == 0) {
                TableData.column(0).visible(false);
            }

            if (ubah == 0) {
                TableData.column(2).visible(false);
            }
        });
    </script>
@endpush
