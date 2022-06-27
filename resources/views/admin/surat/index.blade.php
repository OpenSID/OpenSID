@include('admin.layouts.components.asset_validasi')
@include('admin.layouts.components.asset_datatables')

@extends('admin.layouts.index')

@section('title')
    <h1>
        Cetak Layanan Surat
    </h1>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('surat') }}">Cetak Layanan Surat</a></li>
@endsection

@section('content')
    @include('admin.layouts.components.notifikasi')
    <div class="box box-info">
        {!! form_open($formAction, 'id="validasi"') !!}
        <div class="box-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="favorit">
                    <thead class="bg-gray">
                        <tr>
                            <th class="padat">NO</th>
                            <th class="aksi">AKSI</th>
                            <th>Layanan Administrasi Surat (Favorit)</th>
                            <th class="padat">Kode Surat</th>
                            <th class="aksi">Lampiran</th>
                        </tr>
                    </thead>
                </table>
            </div>
            <hr>
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="tabeldata">
                    <thead class="bg-gray">
                        <tr>
                            <th class="padat">NO</th>
                            <th class="aksi">AKSI</th>
                            <th>Layanan Administrasi Surat</th>
                            <th class="padat">Kode Surat</th>
                            <th class="aksi">Lampiran</th>
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
            var TableData = $('#favorit').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                searching: false,
                info: false,
                paging: false,
                ajax: {
                    url: "{{ route('surat.datatablesFavorit') }}",
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
                        data: 'nama',
                        name: 'nama',
                        searchable: true,
                        orderable: true
                    },
                    {
                        data: 'kode_surat',
                        name: 'kode_surat',
                        class: 'padat',
                        searchable: true,
                        orderable: true
                    },
                    {
                        data: 'lampiran',
                        name: 'lampiran',
                        class: 'aksi',
                        searchable: true,
                        orderable: true
                    },
                ],
                order: [
                    [3, 'asc']
                ],
                pageLength: 10,
                createdRow: function(row, data, dataIndex) {
                    if (data.jenis == 2 || data.jenis == 4) {
                        $(row).addClass('select-row');
                    }
                }
            });

            if (ubah == 0) {
                TableData.column(1).visible(false);
                TableData.column(4).visible(false);
            }

        });

        $(document).ready(function() {
            var TableData = $('#tabeldata').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('surat.datatables') }}",
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
                        data: 'nama',
                        name: 'nama',
                        searchable: true,
                        orderable: true
                    },
                    {
                        data: 'kode_surat',
                        name: 'kode_surat',
                        class: 'padat',
                        searchable: true,
                        orderable: true
                    },
                    {
                        data: 'lampiran',
                        name: 'lampiran',
                        class: 'aksi',
                        searchable: true,
                        orderable: true
                    },
                ],
                order: [
                    [3, 'asc']
                ],
                pageLength: 25,
                createdRow: function(row, data, dataIndex) {
                    if (data.jenis == 2 || data.jenis == 4) {
                        $(row).addClass('select-row');
                    }
                }
            });

            if (ubah == 0) {
                TableData.column(1).visible(false);
                TableData.column(4).visible(false);
            }

        });
    </script>
@endpush
