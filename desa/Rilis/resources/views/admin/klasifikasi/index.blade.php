@include('admin.layouts.components.asset_datatables')
@extends('admin.layouts.index')

@section('title')
    <h1>
        Klasifikasi Surat
    </h1>
@endsection

@section('breadcrumb')
    <li class="active">Klasifikasi Surat</li>
@endsection

@section('content')
    @include('admin.layouts.components.notifikasi')
    {!! form_open(null, 'id="mainform" name="mainform"') !!}
    <div class="row">
        <div class="{{ $modul_ini != 'sekretariat' ? 'col-md-9' : 'col-md-12' }}">
            <div class="box box-info">
                <div class="box-header with-border">
                    @if (can('u'))
                        <a href="{{ ci_route('klasifikasi.form') }}" class="btn btn-social  btn-success btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Tambah">
                            <i class="fa fa-plus"></i>Tambah
                        </a>
                    @endif
                    @if (can('h'))
                        <a href="#confirm-delete" title="Hapus Data" onclick="deleteAllBox('mainform', '{{ ci_route('klasifikasi.delete_all') }}')" class="btn btn-social btn-danger btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block hapus-terpilih"><i
                                class='fa fa-trash-o'
                            ></i> Hapus</a>
                    @endif
                    @if (can('u'))
                        <a
                            href="{{ ci_route('klasifikasi.impor') }}"
                            class="btn btn-social bg-black btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"
                            title="Impor"
                            data-remote="false"
                            data-toggle="modal"
                            data-target="#modalBox"
                            data-title="Impor"
                        ><i class="fa fa-upload "></i> Impor</a>
                    @endif
                    <a href="{{ ci_route('klasifikasi.ekspor') }}" class="btn btn-social bg-purple btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Ekspor"><i class="fa fa-download"></i> Unduh</a>
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <form id="mainform" name="mainform" method="post">
                                <input name="kategori" type="hidden" value="{{ $kat }}">
                                <div class="row mepet">
                                    <div class="col-sm-2">
                                        <select class="form-control input-sm select2" name="enable">
                                            <option value="">Pilih Status</option>
                                            <option value="1" selected>Aktif</option>
                                            <option value="0">Tidak Aktif</option>
                                        </select>
                                    </div>
                                </div>
                                <hr class="batas">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-striped dataTable table-hover" id="tabeldata">
                                                <thead class="bg-gray disabled color-palette">
                                                    <tr>
                                                        <th>
                                                            @if (can('u'))
                                                                <input type="checkbox" id="checkall" />
                                                            @endif
                                                        </th>
                                                        <th>No</th>
                                                        <th>Aksi</th>
                                                        <th class="nowrap"> Kode </th>
                                                        <th> Nama </th>
                                                        <th>Keterangan</th>
                                                    </tr>
                                                </thead>

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
    </form>
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
                    url: "{{ ci_route('klasifikasi.datatables') }}",
                    data: function(req) {
                        req.enable = $('select[name="enable"]').val();
                    },
                },
                columns: [{
                        data: 'checkbox',
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
                        data: 'kode',
                        name: 'kode',
                        searchable: true,
                        orderable: true
                    },
                    {
                        data: 'nama',
                        name: 'nama',
                        searchable: true,
                        orderable: true
                    },
                    {
                        data: 'uraian',
                        name: 'Keterangan',
                        class: 'padat',
                        searchable: false,
                        orderable: false
                    },
                ],
                order: [
                    [3, 'asc']
                ],
                pageLength: 25,
                createdRow: function(row, data, dataIndex) {
                    if (data.jenis == 0 || data.jenis == 1) {
                        $(row).addClass('select-row');
                    }
                }
            });

            $('select[name="enable"]').on('change', function() {
                $(this).val();
                TableData.ajax.reload();
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
