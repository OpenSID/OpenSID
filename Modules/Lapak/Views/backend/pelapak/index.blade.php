@include('admin.layouts.components.asset_datatables')

@extends('admin.layouts.index')

@section('title')
    <h1>
        Pelapak
        <small>Daftar Data</small>
    </h1>
@endsection

@section('breadcrumb')
    <li class="active">Daftar Data</li>
@endsection

@section('content')
    @include('admin.layouts.components.notifikasi')
    @include('admin.layouts.components.konfirmasi_hapus')

    @include('lapak::backend.navigasi', $navigasi)

    <div class="box box-info">
        <div class="box-header with-border">
            @includeIf('admin.layouts.components.buttons.tambah', [
                'modal' => true,
                'url' => "lapak_admin/pelapak_form/{$main->id}",
            ])
            @includeIf('admin.layouts.components.buttons.hapus', [
                'url' => 'lapak_admin/pelapak_delete_all',
            ])
            @includeIf('admin.layouts.components.buttons.cetak', [
                'modal' => true,
                'url' => 'lapak_admin/pelapak/dialog/cetak',
            ])
            @includeIf('admin.layouts.components.buttons.unduh', [
                'modal' => true,
                'url' => 'lapak_admin/pelapak/dialog/unduh',
            ])
        </div>
        <form id="mainform" name="mainform" method="post">
            <div class="box-body">
                <div class="row mepet">
                    <div class="col-sm-2">
                        <select class="form-control input-sm select2" id="status" name="status">
                            <option value="">Pilih Status</option>
                            <option value="1" selected>Aktif</option>
                            <option value="0">Tidak Aktif</option>
                        </select>
                    </div>
                </div>
                <hr class="batas">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped dataTable table-hover tabel-daftar" id="tabel-pelapak">
                        <thead class="bg-gray disabled color-palette">
                            <tr>
                                <th><input type="checkbox" id="checkall" /></th>
                                <th>No</th>
                                <th>Aksi</th>
                                <th>Pelapak</th>
                                <th>No. Telelpon</th>
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
            let tabel_produk = $('#tabel-pelapak').DataTable({
                'processing': true,
                'serverSide': true,
                'autoWidth': false,
                'pageLength': 10,
                'order': [
                    [3, 'desc']
                ],
                'columnDefs': [{
                        'searchable': false,
                        'targets': [0, 1, 2, 5]
                    },
                    {
                        'orderable': false,
                        'targets': [0, 1, 2]
                    },
                    {
                        'className': 'padat',
                        'targets': [0, 1, 4, 5]
                    },
                    {
                        'className': 'aksi',
                        'targets': [2]
                    }
                ],
                'ajax': {
                    'url': "{{ site_url('lapak_admin/pelapak') }}",
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
                                    `<a href="{{ site_url('lapak_admin/pelapak_status/') }}${data.id}" class="btn bg-navy btn-sm" title="Non Aktifkan Pelapak"><i class="fa fa-unlock"></i></a>`
                            } else {
                                status =
                                    `<a href="{{ site_url('lapak_admin/pelapak_status/') }}${data.id}" class="btn bg-navy btn-sm" title="Aktifkan Pelapak"><i class="fa fa-lock"></i></a>`
                            }

                            let hapus;
                            if (data.jumlah == 0) {
                                hapus =
                                    `<a href="#" data-href="{{ site_url('lapak_admin/pelapak_delete/') }}${data.id}" class="btn bg-maroon btn-sm" title="Hapus" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash-o"></i></a>`
                            } else {
                                hapus = ''
                            }

                            return `
                        @if (can('u'))
                            <a href="{{ site_url('lapak_admin/pelapak_form/') }}${data.id}" title="Edit Data" class="btn bg-orange btn-sm" data-target="#modalBox" data-remote="false" data-toggle="modal" data-backdrop="false" data-keyboard="false" data-title="Ubah Pelapak"><i class="fa fa-edit"></i></a>
                            ${status}
                        @endif
                        @if (can('h'))
                            ${hapus}
                        @endif
                        @if (can('u'))
                            <a href="{{ site_url('lapak_admin/pelapak_maps/') }}${data.id}" class="btn bg-green btn-sm" title="Lokasi"><i class="fa fa-map"></i></a>
                        @endif
                        `
                        }
                    },
                    {
                        'data': 'pelapak',
                        'name': 'p.nama'
                    },
                    {
                        'data': 'telepon'
                    },
                    {
                        'data': 'jumlah'
                    }
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
