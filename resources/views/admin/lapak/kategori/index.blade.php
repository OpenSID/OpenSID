@include('admin.layouts.components.asset_datatables')

@extends('admin.layouts.index')

@section('title')
    <h1>
        Kategori
        <small>Daftar Data</small>
    </h1>
@endsection

@section('breadcrumb')
    <li class="active">Daftar Data</li>
@endsection

@section('content')
    @include('admin.layouts.components.notifikasi')
    @include('admin.layouts.components.konfirmasi_hapus')

    @include('admin.lapak.navigasi', $navigasi)

    <div class="box box-info">
        <div class="box-header with-border">
            <div class="box-header with-border">
                @includeIf('admin.layouts.components.buttons.tambah', [
                    'modal' => true,
                    'url' => "lapak_admin/kategori_form/{$main->id}",
                ])
                @includeIf('admin.layouts.components.buttons.hapus', [
                    'url' => 'lapak_admin/kategori_delete_all',
                ])
                @includeIf('admin.layouts.components.buttons.cetak', [
                    'modal' => true,
                    'url' => 'lapak_admin/kategori/dialog/cetak',
                ])
                @includeIf('admin.layouts.components.buttons.unduh', [
                    'modal' => true,
                    'url' => 'lapak_admin/kategori/dialog/unduh',
                ])
            </div>
        </div>
        <form id="mainform" name="mainform" method="post">
            <div class="box-header with-border form-inline">
                <div class="row">
                    <div class="col-sm-2">
                        <select class="form-control input-sm select2" id="status" name="status">
                            <option value="">Semua Status</option>
                            <option value="1">Aktif</option>
                            <option value="2">Non Aktif</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="box-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped dataTable table-hover tabel-daftar" id="tabel-kategori">
                        <thead class="bg-gray disabled color-palette">
                            <tr>
                                <th><input type="checkbox" id="checkall" /></th>
                                <th>No</th>
                                <th>Aksi</th>
                                <th>Kategori</th>
                                <th>Jumlah Produk</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            let tabel_produk = $('#tabel-kategori').DataTable({
                'processing': true,
                'serverSide': true,
                'autoWidth': false,
                'pageLength': 10,
                'order': [
                    [3, 'desc']
                ],
                'columnDefs': [{
                        'orderable': false,
                        'targets': [0, 1, 2]
                    },
                    {
                        'searchable': false,
                        'targets': [0, 1, 2, 4]
                    },
                    {
                        'className': 'padat',
                        'targets': [0, 1, 4]
                    },
                    {
                        'className': 'aksi',
                        'targets': [2]
                    }
                ],
                'ajax': {
                    'url': "{{ ci_route('lapak_admin/kategori') }}",
                    'method': 'get',
                    'data': function(d) {
                        d.status = $('#status').val();
                    }
                },
                'columns': [{
                        'data': function(data) {
                            if (data.jumlah == 0) {
                                return `<input type="checkbox" name="id_cb[]" value="${data.id}"/>`
                            } else return ''
                        }
                    },
                    {
                        'data': 'DT_RowIndex'
                    },
                    {
                        'data': function(data) {
                            let status;
                            if (data.status == 1) {
                                status =
                                    `<a href="{{ site_url('lapak_admin/kategori_status/') }}${data.id}/2" class="btn bg-navy btn-sm" title="Non Aktifkan Kategori"><i class="fa fa-unlock"></i></a>`
                            } else {
                                status =
                                    `<a href="{{ site_url('lapak_admin/kategori_status/') }}${data.id}/1" class="btn bg-navy btn-sm" title="Aktifkan Kategori"><i class="fa fa-lock"></i></a>`
                            }

                            let hapus;
                            if (data.jumlah == 0) {
                                hapus =
                                    `<a href="#" data-href="{{ site_url('lapak_admin/kategori_delete/') }}${data.id}" class="btn bg-maroon btn-sm" title="Hapus" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash-o"></i></a>`
                            } else {
                                hapus = ''
                            }
                            return `
                        @if (can('u'))
                            <a href="{{ site_url('lapak_admin/kategori_form/') }}${data.id}" title="Edit Data" class="btn bg-orange btn-sm" data-target="#modalBox" data-remote="false" data-toggle="modal" data-backdrop="false" data-keyboard="false" data-title="Ubah Kategori"><i class="fa fa-edit"></i></a>
                            ${status}
                        @endif
                        @if (can('h'))
                            ${hapus}
                        @endif
                        `
                        }
                    },
                    {
                        'data': 'kategori'
                    },
                    {
                        'data': 'jumlah'
                    },
                ],
                'language': {
                    'url': "{{ base_url('/assets/bootstrap/js/dataTables.indonesian.lang') }}"
                }
            });

            if (hapus == 0) {
                tabel_produk.column(0).visible(false);
            }

            if (ubah == 0) {
                tabel_produk.column(2).visible(false);
            }

            $('#status').on('select2:select', function(e) {
                tabel_produk.ajax.reload();
            });
        });
    </script>
@endpush
