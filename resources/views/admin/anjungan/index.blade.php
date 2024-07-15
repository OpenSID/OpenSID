@include('admin.layouts.components.asset_datatables')

@extends('admin.layouts.index')

@section('title')
    <h1>
        Anjungan
    </h1>
@endsection

@section('breadcrumb')
    <li class="active">Anjungan</li>
@endsection

@section('content')
    @include('admin.layouts.components.notifikasi')
    @if (!cek_anjungan())
        @include('admin.anjungan.peringatan')
    @else
        <div class="box box-info">
            <div class="box-header with-border">
                @if (can('u'))
                    <a href="{{ ci_route('anjungan.form') }}" class="btn btn-social btn-success btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-plus"></i> Tambah</a>
                @endif
                @if (can('h'))
                    <a href="#confirm-delete" title="Hapus Data" onclick="deleteAllBox('mainform', '{{ ci_route('anjungan.delete') }}')" class="btn btn-social btn-danger btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block hapus-terpilih"><i
                            class='fa fa-trash-o'></i>
                        Hapus</a>
                @endif
            </div>
            <div class="box-body">
                {!! form_open(null, 'id="mainform" name="mainform"') !!}
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="tabeldata">
                        <thead>
                            <tr>
                                <th><input type="checkbox" id="checkall" /></th>
                                <th class="padat">NO</th>
                                <th class="padat">AKSI</th>
                                <th>IP ADDRESS</th>
                                <th>MAC ADDRESS</th>
                                <th>ID PENGUNJUNG</th>
                                <th>IP ADDRESS PRINTER & PORT</th>
                                <th>VIRTUAL KEYBOARD</th>
                                <th>STATUS</th>
                                <th>KETERANGAN</th>
                            </tr>
                        </thead>
                    </table>
                </div>
                </form>
            </div>
        </div>
    @endif

    @include('admin.layouts.components.konfirmasi_hapus')
@endsection
@push('scripts')
    <script>
        $(document).ready(function() {
            var TableData = $('#tabeldata').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                ajax: "{{ ci_route('anjungan.datatables') }}",
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
                        data: 'ip_address',
                        name: 'ip_address',
                        searchable: true,
                        orderable: true
                    },
                    {
                        data: 'mac_address',
                        name: 'mac_address',
                        searchable: true,
                        orderable: true
                    },
                    {
                        data: 'id_pengunjung',
                        name: 'id_pengunjung',
                        searchable: true,
                        orderable: true
                    },
                    {
                        data: 'ip_address_port_printer',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'keyboard',
                        class: 'padat',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'status',
                        class: 'padat',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'keterangan',
                        name: 'keterangan',
                        searchable: true,
                        orderable: true
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
