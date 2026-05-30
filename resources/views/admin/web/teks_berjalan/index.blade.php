@include('admin.layouts.components.asset_datatables')
@include('admin.layouts.components.jquery_ui')

@extends('admin.layouts.index')

@section('title')
<h1>
    Teks Berjalan
</h1>
@endsection

@section('breadcrumb')
<li class="active">Teks Berjalan</li>
@endsection

@section('content')
@include('admin.layouts.components.notifikasi')

<form id="mainform" name="mainform" method="post">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-info">
                <div class="box-header with-border">
                    <x-tambah-button :url="'teks_berjalan/form'" />
                    <x-hapus-button confirmDelete="true" selectData="true" :url="'teks_berjalan/delete'" />
                </div>
                <div class="box-body">
                    <div class="row mepet">
                        @include('admin.layouts.components.select_status')
                    </div>
                    <hr class="batas">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="dataTables_wrapper form-inline dt-bootstrap no-footer">
                                <form id="mainform" name="mainform" method="post">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="table-responsive">
                                                <table class="table table-bordered table-hover" id="tabeldata">
                                                    <thead>
                                                        <tr>
                                                            <th class="padat">#</th>
                                                            <th><input type="checkbox" id="checkall" /></th>
                                                            <th class="padat">No</th>
                                                            <th class="padat">Aksi</th>
                                                            <th>Isi Teks Berjalan</th>
                                                            <th width="30%">Tautan</th>
                                                            <th class="padat">Status</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="dragable">
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

@include('admin.layouts.components.konfirmasi_hapus')
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#status').val('1').trigger('change');

        var TableData = $('#tabeldata').DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ site_url('teks_berjalan/datatables') }}",
                type: "GET",
                data: function(req) {
                    req.status = $('#status').val();
                }
            },
            columns: [{
                    data: 'drag-handle',
                    class: 'padat',
                    searchable: false,
                    orderable: false
                },
                {
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
                    data: 'teks',
                    name: 'teks',
                    searchable: true,
                    orderable: true
                },
                {
                    data: 'judul_tautan',
                    name: 'judul_tautan',
                    searchable: true,
                    orderable: false
                },
                {
                    data: 'status_label',
                    name: 'status',
                    searchable: false,
                    orderable: true,
                    class: 'padat'
                },
            ],
            aaSorting: [],
            createdRow: function(row, data, dataIndex) {
                $(row).attr('data-id', data.id)
                $(row).addClass('dragable-handle');
            },
        });

        if (hapus == 0) {
            TableData.column(1).visible(false);
        }

        if (ubah == 0) {
            TableData.column(0).visible(false);
            TableData.column(3).visible(false);
        }
        $('#status').change(function() {
            TableData.draw();
        })
        @include('admin.layouts.components.draggable', ['urlDraggable' => ci_route('teks_berjalan.tukar')])
    });
</script>
@endpush