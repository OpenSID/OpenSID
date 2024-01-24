@extends('admin.layouts.index')
@include('admin.layouts.components.asset_datatables')
@include('admin.layouts.components.jquery_ui')

@section('title')
    <h1>
        Dokumentasi Pembangunan
    </h1>
@endsection

@section('breadcrumb')
    <li class="active">Dokumentasi Pembangunan</li>
@endsection

@section('content')
    @include('admin.layouts.components.notifikasi')

    <div class="box box-info">
        <div class="box-header with-border">
            @if (can('u'))
                <a href="{{ ci_route('pembangunan_dokumentasi.form-dokumentasi', $pembangunan->id) }}" class="btn btn-social btn-success btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-plus"></i> Tambah</a>
            @endif
            <a href='{{ ci_route('pembangunan_dokumentasi.dialog', "{$pembangunan->id}/cetak") }}' class="btn btn-social bg-purple btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" data-remote="false" data-toggle="modal" data-target="#modalBox"
                data-title="Cetak Data"
            ><i class="fa fa-print "></i> Cetak</a>
            <a
                href='{{ ci_route('pembangunan_dokumentasi.dialog', "{$pembangunan->id}/unduh") }}'
                title="Unduh Data"
                class="btn btn-social bg-navy btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"
                data-remote="false"
                data-toggle="modal"
                data-target="#modalBox"
                data-title="Unduh Data"
            ><i class="fa fa-download"></i></i> Unduh</a>
            <a href="{{ ci_route('admin_pembangunan') }}" class="btn btn-social btn-info btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-arrow-circle-left"></i> Kembali Ke Daftar Pembangunan</a>
        </div>
        <div class="box-body">
            <h5 class="text-bold">Rincian Dokumentasi Pembangunan</h5>
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover tabel-rincian">
                    <tbody>
                        <tr>
                            <td width="20%">Nama Kegiatan</td>
                            <td width="1">:</td>
                            <td>{{ $pembangunan->judul }}</td>
                        </tr>
                        <tr>
                            <td>Sumber Dana</td>
                            <td> : </td>
                            <td>{{ $pembangunan->sumber_dana }}</td>
                        </tr>
                        <tr>
                            <td>Lokasi Pembangunan</td>
                            <td> : </td>
                            <td>{{ $pembangunan->wilayah->dusun }}</td>
                        </tr>
                        <tr>
                            <td>Keterangan</td>
                            <td> : </td>
                            <td>{{ $pembangunan->keterangan }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <hr style="margin-bottom: 5px; margin-top: -5px;">
        <div class="box-body">
            {!! form_open(null, 'id="mainform" name="mainform"') !!}
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="tabeldata">
                    <thead>
                        <tr>
                            <th class="padat">NO</th>
                            <th class="padat">AKSI</th>
                            <th class="padat">GAMBAR</th>
                            <th>PERSENTASE</th>
                            <th>KETERANGAN</th>
                            <th>TANGGAL REKAM</th>
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
            var TableData = $('#tabeldata').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                ajax: "{{ ci_route('pembangunan_dokumentasi.datatables-dokumentasi') }}/{{ $pembangunan->id }}",
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
                        data: 'gambar',
                        name: 'gambar',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'persentase',
                        name: 'persentase',
                        searchable: true,
                        orderable: true
                    },
                    {
                        data: 'keterangan',
                        name: 'keterangan',
                        searchable: true,
                        orderable: true
                    },
                    {
                        data: 'created_at',
                        name: 'created_at',
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
