@extends('admin.layouts.index')

@include('admin.layouts.components.asset_datatables')
@section('title')
    <h1>
        Pengaturan Kategori Lokasi
    </h1>
@endsection

@section('breadcrumb')
    <li class="active">Pengaturan Kategori Lokasi</li>
@endsection

@section('content')
    @include('admin.layouts.components.notifikasi')
    <div class="row">
        <div class="col-md-3">
            @include('admin.peta.nav', ['tip' => $tip])
        </div>
        <div class="col-md-9">
            <div class="box box-info">
                <div class="box-header with-border">
                    @if (can('u'))
                        <a
                            href="{{ ci_route('point.ajax_add_sub_point', $point['id']) }}"
                            id="btn-add"
                            class="btn btn-social btn-success btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"
                            title="Tambah"
                            data-remote="false"
                            data-toggle="modal"
                            data-target="#modalBox"
                            data-title="Tambah"
                        ><i class="fa fa-plus"></i> Tambah</a>
                    @endif
                    @if (can('h'))
                        <a href="#confirm-delete" title="Hapus Data" onclick="deleteAllBox('mainform', '{{ ci_route('point.delete') }}')" class="btn btn-social btn-danger btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block hapus-terpilih"><i
                                class='fa fa-trash-o'
                            ></i>
                            Hapus</a>
                    @endif
                    <a href="<?= site_url('point') ?>" class="btn btn-social btn-info btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Tambah Artikel">
                        <i class="fa fa-arrow-circle-left "></i>Kembali ke Tipe Lokasi
                    </a>
                </div>
                <div class="box-body">
                    {!! form_open(null, 'id="mainform" name="mainform"') !!}
                    <div class="table-responsive">
                        <h5 class="box-title text-center">Kategori
                            {{ $point['nama'] }}
                        </h5>
                        <table class="table table-bordered dataTable table-hover" id="tabeldata">
                            <thead class="bg-gray disabled color-palette">
                                <tr>
                                    <th class="padat"><input type="checkbox" id="checkall" /></th>
                                    <th class="padat">No</th>
                                    <th class="aksi">Aksi</th>
                                    <th>Jenis</th>
                                    <th style="width:10%">Aktif</th>
                                    <th class="padat">Simbol</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @include('admin.layouts.components.konfirmasi_hapus')
@endsection
@push('css')
    <style>
        .select2-results__option[aria-disabled=true] {
            display: none;
        }
    </style>
@endpush
@push('scripts')
    <script>
        $(document).ready(function() {
            var TableData = $('#tabeldata').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ ci_route('point.datatables') }}",
                    data: function(req) {
                        req.status = $('#status').val();
                        req.subpoint = "{{ $point['id'] }}";
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
                        class: 'padat',
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
                        data: 'enabled',
                        name: 'enabled',
                        searchable: false,
                        orderable: true
                    },
                    {
                        data: 'simbol',
                        name: 'simbol',
                        searchable: false,
                        orderable: false
                    },
                ],
                order: [
                    [3, 'asc']
                ]
            });

            $('#status').change(function() {
                TableData.draw()
            })

            if (hapus == 0) {
                TableData.column(0).visible(false);
            }

            if (ubah == 0) {
                TableData.column(2).visible(false);
            }
        });
    </script>
@endpush
