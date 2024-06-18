@include('admin.layouts.components.asset_datatables')

@extends('admin.layouts.index')

@section('title')
    <h1>
        Kotak Pesan
    </h1>
@endsection

@section('breadcrumb')
    <li class="active">Kotak Pesan</li>
@endsection

@section('content')
    @include('admin.layouts.components.notifikasi')
    @include('admin.layouts.components.konfirmasi_hapus')

    <div class="row">
        <div class="col-md-3">
            @include('admin.mailbox.nav')
        </div>
        <div class="col-md-9">
            <div class="box box-info">
                <div class="box-header with-border">
                    @if (can('u'))
                        @if ($kategori == 2)
                            <a href="{{ ci_route('mailbox.form', $kategori) }} " class="btn btn-social btn-success btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Tulis Pesan"><i class="fa fa-plus"></i> Tulis Pesan</a>
                        @endif
                        <a href="#confirm-delete" title="Arsipkan Data" onclick="deleteAllBox('mainform', '{{ ci_route('mailbox.delete.', $kategori) }}')"
                            class="btn btn-social btn-danger btn-sm
                        visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block
                        hapus-terpilih"
                        ><i class='fa fa-archive'></i> Arsipkan</a>
                    @endif
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="dataTables_wrapper form-inline dt-bootstrap no-footer">
                                <form id="mainform" name="mainform" method="post">
                                    <div class="row mepet">
                                        <div class="col-md-4">
                                            <select class="form-control input-sm select2-nik-ajax redraw" id="nik" style="width:100%" name="nik" data-url="{{ ci_route('mailbox.list_pendaftar_mandiri_ajax') }}">
                                                <option value="">Semua Pendaftar Layanan Mandiri</option>
                                            </select>
                                        </div>
                                        <div class="col-sm-2">
                                            <select name="status" id="status" class="form-control input-sm select2 redraw">
                                                <option value="">Semua</option>
                                                <option value="1">Sudah Dibaca</option>
                                                <option value="2">Belum Dibaca</option>
                                                <option value="3">Diarsipkan</option>
                                            </select>
                                        </div>
                                    </div>
                                    <hr class="batas">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="table-responsive">
                                                <table class="table table-bordered table-hover" id="tabeldata">
                                                    <thead class="bg-gray disabled color-palette">
                                                        <tr>
                                                            <th><input type="checkbox" id="checkall" /></th>
                                                            <th>No</th>
                                                            <th>Aksi</th>
                                                            <th>{{ $kategori == 1 ? 'Pengirim' : 'Penerima' }}</th>
                                                            <th>NIK</th>
                                                            <th>Subjek Pesan</th>
                                                            <th>Status Pesan</th>
                                                            <th>Dikirimkan Pada </th>
                                                        </tr>
                                                    </thead>
                                                </table>
                                            </div>
                                        </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @include('admin.layouts.components.konfirmasi_hapus')
    @endsection

    @push('scripts')
        <script src="{{ asset('js/custom-select2.js') }}"></script>
        <script>
            $(document).ready(function() {
                var TableData = $('#tabeldata').DataTable({
                    responsive: true,
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: "{{ ci_route('mailbox.datatables') }}?tipe={{ $kategori }}",
                        data: function(req) {
                            req.status = $('#status').val();
                            req.nik = $('#nik').val();
                        }
                    },
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
                            data: 'owner',
                            name: 'owner',
                            searchable: true,
                            orderable: true,
                        },
                        {
                            data: 'penduduk.nik',
                            name: 'penduduk.nik',
                            searchable: true,
                            orderable: true,
                        },
                        {
                            data: 'subjek',
                            name: 'subjek',
                            searchable: true,
                            orderable: false
                        },
                        {
                            data: 'status',
                            name: 'status',
                            searchable: true,
                            orderable: true
                        },
                        {
                            data: 'tgl_upload',
                            name: 'tgl_upload',
                            searchable: true,
                            orderable: true
                        },
                    ],
                    order: [7, 'desc'],
                    aaSorting: [],
                    createdRow: function(row, data, dataIndex) {
                        if (data.status != 'Sudah Dibaca') {
                            $(row).addClass('bg-yellow')
                        }
                    }
                });

                if (hapus == 0) {
                    TableData.column(0).visible(false);
                }

                if (ubah == 0) {
                    TableData.column(2).visible(false);
                }

                $('#nik,#status').change(function() {
                    TableData.draw()
                })

                $('#confirm-delete').on('shown.bs.modal', function(ev) {
                    $(this).find('.modal-body').text('Apakah Anda yakin ingin mengarsipkan data ini?')
                    $(this).find('.modal-footer #ok-delete').html('<i class="fa fa-archive"></i> Arsipkan')
                })
            });
        </script>
    @endpush
