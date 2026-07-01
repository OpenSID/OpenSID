@include('admin.layouts.components.asset_datatables')

@extends('admin.layouts.index')

@section('title')
    <h1>
        Gawai Layanan
    </h1>
@endsection

@section('breadcrumb')
    <li class="active">Gawai Layanan</li>
@endsection

@section('content')
    @include('admin.layouts.components.notifikasi')
    <div class="box box-info">
        <div class="box-header with-border">
            <x-tambah-button 
                :url="'gawai_layanan/form'"
            />
            <x-hapus-button 
                confirmDelete="true" 
                selectData="true"
                :url="'gawai_layanan/delete'" 
            />
        </div>
        <div class="box-body">
            {!! form_open(null, 'id="mainform" name="mainform"') !!}
            <div class="row mepet">
                @include('admin.layouts.components.select_status')
            </div>
            <hr class="batas">
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
                    url: "{{ ci_route('gawai_layanan.datatables') }}",
                    data: function(req) {
                        req.status = $('#status').val();
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

            $('#status').change(function() {
                TableData.draw();
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
