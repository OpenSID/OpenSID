@include('admin.layouts.components.asset_datatables')

@extends('admin.layouts.index')

@section('title')
    <h1>
        Data Tamu
    </h1>
@endsection

@section('breadcrumb')
    <li class="active">Data Tamu</li>
@endsection

@section('content')
    @include('admin.layouts.components.notifikasi')
    <div class="box box-info">
        <div class="box-header with-border">
            @if (can('h'))
                <a href="#confirm-delete" title="Hapus Data"
                    onclick="deleteAllBox('mainform', '{{ route('buku_tamu.delete') }}')"
                    class="btn btn-social btn-danger btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block hapus-terpilih"><i
                        class='fa fa-trash-o'></i> Hapus</a>
            @endif
            <a href="{{ route('buku_tamu.cetak') }}" target="_blank" title="Cetak Data"
                class="btn btn-social bg-purple btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i
                    class='fa fa-print'></i> Cetak</a>
        </div>
        {!! form_open(null, 'id="mainform" name="mainform"') !!}
        <div class="box-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="tabeldata">
                    <thead>
                        <tr>
                            <th><input type="checkbox" id="checkall" /></th>
                            <th class="padat">NO</th>
                            <th class="padat">AKSI</th>
                            <th>HARI / TANGGAL</th>
                            <th>NAMA</th>
                            <th>TELPON</th>
                            <th>INSTANSI</th>
                            <th>JENIS KELAMIN</th>
                            <th>ALAMAT</th>
                            <th>BERTEMU</th>
                            <th>KEPERLUAN</th>
                            <th>FOTO</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
        </form>
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
                ajax: "{{ route('buku_tamu') }}",
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
                        data: 'created_at',
                        name: 'created_at',
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
                        data: 'telepon',
                        name: 'telepon',
                        searchable: true,
                        orderable: true
                    },
                    {
                        data: 'instansi',
                        name: 'instansi',
                        searchable: true,
                        orderable: true
                    },
                    {
                        data: 'jk.nama',
                        name: 'jk.nama',
                        searchable: true,
                        orderable: true
                    },
                    {
                        data: 'alamat',
                        name: 'alamat',
                        searchable: true,
                        orderable: true
                    },
                    {
                        data: 'bidang.nama',
                        name: 'bidang.nama',
                        searchable: true,
                        orderable: true
                    },
                    {
                        data: 'keperluan.keperluan',
                        name: 'keperluan.keperluan',
                        searchable: true,
                        orderable: true
                    },
                    {
                        data: 'tampil_foto',
                        name: 'tampil_foto',
                        searchable: false,
                        orderable: false
                    },
                ],
                order: [
                    [3, 'desc']
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
