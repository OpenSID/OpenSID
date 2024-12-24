@extends('admin.layouts.index')

@section('title')
    <h1>Pesan</h1>
@endsection

@section('breadcrumb')
    <li class="active">Pesan</li>
@endsection

@section('content')
    @include('admin.layouts.components.notifikasi')
    <div class="row">
        <div class="col-md-4 col-lg-3">
            @if (can('u'))
                <a href="{{ site_url('opendk_pesan/form') }}" class="btn btn-primary btn-block margin-bottom">Buat Pesan</a>
            @endif
            <div class="box box-solid">
                <div class="box-body no-padding">
                    <div class="box-footer no-padding">
                        <ul class="nav nav-stacked">
                            <li {{ jecho($selected_nav, 'pesan', 'class="active"') }}><a href="{{ ci_route('opendk_pesan.clear') }}">Pesan</a></li>
                            <li {{ jecho($selected_nav, 'arsip', 'class="active"') }}><a href="{{ ci_route('opendk_pesan.clear.arsip') }}">Arsip</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-8 col-lg-9">

            <div class="box box-info">
                <div class="box-header with-border">
                    <div class="row mepet">
                        <div class="col-sm-2">
                            <select name="status" id="status" class="form-control input-sm select2">
                                <option value="">Pilih Status</option>
                                <option value="1">Sudah Dibaca</option>
                                <option value="0">Belum Dibaca</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="box-body">
                    <div class="dataTables_wrapper form-inline dt-bootstrap no-footer">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover" id="tabeldata">
                                <thead>
                                    <tr>
                                        <th class="padat">No</th>
                                        <th class="padat">Aksi</th>
                                        <th>Judul</th>
                                        <th>Tipe</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>

    @if (can('h'))
        <div class="modal fade" id="confirm-arsip" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title" id="myModalLabel"><i class="fa fa-exclamation-triangle text-red"></i> Konfirmasi</h4>
                    </div>
                    <div class="modal-body btn-info">
                        Apakah Anda yakin ingin Arsipkan Pesan ini ?
                    </div>
                    <div class="modal-footer">
                        {!! form_open(site_url('opendk_pesan/arsipkan'), 'name="arsip"') !!}
                        <input type="hidden" name="array_id">
                        <button type="button" class="btn btn-social btn-warning btn-sm" data-dismiss="modal"><i class="fa fa-sign-out"></i> Tutup</button>
                        <a class="btn-ok">
                            <button type="button" class="btn btn-social btn-danger btn-sm" id="arsip-action"><i class="fa fa-trash-o"></i> Arsipkan</button>
                        </a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection
@include('admin.layouts.components.asset_datatables')
@push('scripts')
    <script>
        $(function() {
            var TableData = $('#tabeldata').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ ci_route('opendk_pesan.datatables') }}",
                    data: function(req) {
                        req.status = $('#status').val();
                        @if ($selected_nav == 'arsip')
                            req.arsip = 1;
                        @endif
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
                        data: 'judul',
                        name: 'judul',
                        searchable: true,
                        orderable: false,
                    },
                    {
                        data: 'tipe',
                        name: 'tipe',
                        searchable: true,
                        orderable: false
                    },
                    {
                        data: 'status',
                        name: 'status',
                        searchable: true,
                        orderable: false
                    },
                ],
                aaSorting: [],
            });

            $('#status').change(function() {
                TableData.draw();
            });

            if (hapus == 0) {
                TableData.column(1).visible(false);
            }

            if (ubah == 0) {
                TableData.column(3).visible(false);
            }
        });
    </script>
@endpush
