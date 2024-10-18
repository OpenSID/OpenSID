@include('admin.layouts.components.asset_datatables')

@extends('admin.layouts.index')

@section('title')
    <h1>
        PRODUK
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
            @includeIf('admin.layouts.components.buttons.tambah', ['url' => 'lapak_admin/produk_form'])
            @includeIf('admin.layouts.components.buttons.hapus', [
                'url' => 'lapak_admin/produk_delete_all',
            ])
            @includeIf('admin.layouts.components.buttons.cetak', [
                'modal' => true,
                'url' => 'lapak_admin/produk/dialog/cetak',
            ])
            @includeIf('admin.layouts.components.buttons.unduh', [
                'modal' => true,
                'url' => 'lapak_admin/produk/dialog/unduh',
            ])
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
                    <div class="col-sm-2">
                        <select class="form-control input-sm select2" id="id_pend" name="id_pend">
                            <option value="">Semua Pelapak</option>
                            @foreach ($pelapak as $pel)
                                <option value="{{ $pel->id_pend }}">{{ $pel->nik . ' - ' . $pel->pelapak }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-sm-2">
                        <select class="form-control input-sm select2" id="id_produk_kategori" name="id_produk_kategori">
                            <option value="">Semua Kategori</option>
                            @foreach ($kategori as $kat)
                                <option value="{{ $kat->id }}">{{ $kat->kategori }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="box-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped dataTable table-hover tabel-daftar" id="tabel-produk">
                        <thead class="bg-gray disabled color-palette">
                            <tr>
                                <th><input type="checkbox" id="checkall" /></th>
                                <th>No</th>
                                <th>Aksi</th>
                                <th>Pelapak</th>
                                <th>Produk</th>
                                <th>Kategori</th>
                                <th>Harga</th>
                                <th>Satuan</th>
                                <th>Potongan</th>
                                <th>Deskripsi</th>
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
            let tabel_produk = $('#tabel-produk').DataTable({
                'processing': true,
                'serverSide': true,
                'autoWidth': false,
                'pageLength': 10,
                'order': [
                    [4, 'desc']
                ],
                'columnDefs': [{
                        'orderable': false,
                        'targets': [0, 1, 2]
                    },
                    {
                        'className': 'padat',
                        'targets': [0, 1, 7, 8]
                    },
                    {
                        'className': 'aksi',
                        'targets': [2]
                    },
                    {
                        'className': 'dt-nowrap',
                        'targets': [9],
                        'width': '30%'
                    }
                ],
                'ajax': {
                    'url': "{{ ci_route("{$controller}/produk") }}",
                    'method': 'get',
                    'data': function(d) {
                        d.status = $('#status').val();
                        d.id_pend = $('#id_pend').val();
                        d.id_produk_kategori = $('#id_produk_kategori').val();
                    }
                },
                'columns': [{
                        orderable: false,
                        searchable: false,
                        'data': function(data) {
                            return `<input type="checkbox" name="id_cb[]" value="${data.id}"/>`
                        }
                    },
                    {
                        orderable: false,
                        searchable: false,
                        'data': 'DT_RowIndex'
                    },
                    {
                        orderable: false,
                        searchable: false,
                        'data': function(data) {
                            let status;
                            if (data.status == 1) {
                                status =
                                    `<a href="{{ ci_route("{$controller}/produk_status/") }}${data.id}/2" class="btn bg-navy btn-sm" title="Non Aktifkan Produk"><i class="fa fa-unlock"></i></a>`
                            } else {
                                status =
                                    `<a href="{{ ci_route("{$controller}/produk_status/") }}${data.id}/1" class="btn bg-navy btn-sm" title="Aktifkan Produk"><i class="fa fa-lock"></i></a>`
                            }

                            return `
                        @if (can('u'))
                            <a href="{{ ci_route("{$controller}/produk_form/") }}${data.id}" title="Edit Data"  class="btn bg-orange btn-sm"><i class="fa fa-edit"></i></a>
                            ${status}
                        @endif
                        @if (can('h')) 
                            <a href="#" data-href="{{ ci_route("{$controller}/produk_delete/") }}${data.id}" class="btn bg-maroon btn-sm"  title="Hapus" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash-o"></i></a>
                        @endif
                        <a href="{{ ci_route("{$controller}/produk_detail/") }}${data.id}" class="btn bg-blue btn-sm" title="Tampilkan" data-target="#modalBox" data-remote="false" data-toggle="modal" data-backdrop="false" data-keyboard="false" data-title="Detail Produk"><i class="fa fa-eye"></i></a>
                        `
                        }
                    },
                    {
                        searchable: false,
                        'data': 'pelapak'
                    },
                    {
                        'data': 'nama'
                    },
                    {
                        'name': 'pk.kategori',
                        'data': 'kategori'
                    },
                    {
                        'data': 'harga',
                        'render': $.fn.dataTable.render.number('.', ',', 0, 'Rp. ')
                    },
                    {
                        'data': 'satuan'
                    },
                    {
                        'name': 'potongan',
                        'data': function(data) {
                            return `${(data.tipe_potongan == 1) ? data.potongan + '%' : 'Rp. ' + formatRupiah(data.potongan.toString())}`
                        }
                    },
                    {
                        name: 'deskripsi',
                        'data': 'deskripsi',
                        'render': function(data) {
                            return data.length > 150 ? data.substr(0, 150) + '…' : data;
                        }
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

            $('#id_pend').on('select2:select', function(e) {
                tabel_produk.ajax.reload();
            });

            $('#id_produk_kategori').on('select2:select', function(e) {
                tabel_produk.ajax.reload();
            });
        });
    </script>
@endpush
