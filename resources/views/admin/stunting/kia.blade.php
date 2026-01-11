@include('admin.layouts.components.asset_datatables')

@extends('admin.layouts.index')

@section('title')
    <h1>
        Stunting
        <small>KIA</small>
    </h1>
@endsection

@section('breadcrumb')
    <li class="active">KIA</li>
@endsection

@section('content')
    @include('admin.layouts.components.notifikasi')

    @include('admin.stunting.widget')

    <div class="row">
        @include('admin.stunting.navigasi')

        <div class="col-md-9 col-lg-9">
            <div class="box box-info">
                <div class="box-header with-border">
                    @if (can('u'))
                      <x-tambah-button :url="'stunting/formKia'" />
                    @endif
                    @if (can('h'))
                        <x-hapus-button confirmDelete="true" selectData="true" :url="'stunting/deleteAllKia'" />
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
                                    <th>NOMOR KIA</th>
                                    <th>NAMA IBU</th>
                                    <th>NIK IBU</th>
                                    <th>NAMA ANAK</th>
                                    <th>NIK ANAK</th>
                                    <th>PERKIRAAN LAHIR</th>
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
@push('scripts')
    <script>
        $(document).ready(function() {
            var TableData = $('#tabeldata').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                ajax: "{{ ci_route('stunting.datatablesKia') }}",
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
                        data: 'no_kia',
                        name: 'no_kia',
                        searchable: true,
                        orderable: true
                    },
                    {
                        data: function(data) {
                            return data.ibu?.nama ?? '-'
                        },
                        name: 'ibu.nama',
                        searchable: true,
                        orderable: true
                    },
                    {
                        data: function(data) {
                            return data.ibu?.nik ?? '-'
                        },
                        name: 'ibu.nik',
                        searchable: true,
                        orderable: true
                    },
                    {
                        data: function(data) {
                            return data.anak?.nama ?? '-'
                        },
                        name: 'anak.nama',
                        searchable: true,
                        orderable: true
                    },
                    {
                        data: function(data) {
                            return data.anak?.nik ?? '-'
                        },
                        name: 'anak.nik',
                        searchable: true,
                        orderable: true
                    },
                    {
                        data: 'hari_perkiraan_lahir',
                        name: 'hari_perkiraan_lahir',
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
