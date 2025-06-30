@include('admin.layouts.components.asset_datatables')
@include('admin.layouts.components.asset_peta')
@extends('admin.layouts.index')

@section('title')
    <h1>
        Rincian C-Desa
    </h1>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ ci_route('cdesa') }}"> Daftar C-Desa</a></li>
    <li class="active">Rincian C-Desa</li>
@endsection

@section('content')
    @include('admin.layouts.components.notifikasi')
    <div class="box box-info">
        <div class="box-header with-border">
            @if (can('u'))
                <a href="{{ route('cdesa.create_mutasi', ['id_cdesa' => $rincian['id']]) }}" class="btn btn-social btn-success btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Tambah Persil">
                    <i class="fa fa-plus"></i>Tambah Mutasi Persil
            @endif
            </a>
            <a href="{{ ci_route('cdesa.form_c_desa', $rincian['id']) }}" class="btn btn-social bg-purple btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Cetak Data" target="_blank">
                <i class="fa fa-print"></i>Cetak C-DESA
            </a>
            <a href="{{ ci_route('cdesa') }}" class="btn btn-social btn-info btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Kembali Ke Daftar C-DESA"><i class="fa fa-arrow-circle-o-left"></i> Kembali Ke Daftar C-DESA</a>
        </div>
        <div class="box-body">
            {!! form_open(null, 'id="mainform" name="mainform"') !!}
            <div class="box-header with-border">
                <h3 class="box-title">Rincian C-DESA</h3>
            </div>
            <div class="box-body">
                <table class="table table-bordered table-striped table-hover tabel-rincian">
                    <tbody>
                        <tr>
                            <td width="20%">Nama Pemilik</td>
                            <td width="1%">:</td>
                            <td>
                                {{ $rincian['nama_pemilik'] }}
                            </td>
                        </tr>
                        <tr>
                            <td>NIK</td>
                            <td>:</td>
                            <td>
                                {{ $rincian['nik_pemilik'] }}
                            </td>
                        </tr>
                        <tr>
                            <td>Alamat</td>
                            <td>:</td>
                            <td>
                                {{ $rincian['alamat'] }}
                            </td>
                        </tr>
                        <tr>
                            <td>Nomor C-DESA</td>
                            <td>:</td>
                            <td>
                                {{ sprintf('%04s', $rincian['nomor']) }}
                            </td>
                        </tr>
                        <tr>
                            <td>Nama Pemilik Tertulis di C-Desa</td>
                            <td>:</td>
                            <td>
                                {{ $rincian['nama_kepemilikan'] }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="box-header with-border">
                <h3 class="box-title">Daftar Persil C-Desa</h3>
            </div>
            <div class="box-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="tabeldata">
                        <thead>
                            <tr class="bg-gray judul-besar">
                                <th class="padat">No</th>
                                <th class="padat">Aksi</th>
                                <th>No. Persil : No. Urut Bidang</th>
                                <th>Kelas Tanah</th>
                                <th>Lokasi</th>
                                <th>Luas Keseluruhan Persil (M2)</th>
                                <th>Jumlah Mutasi</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
            </form>
        </div>
    </div>

    @include('admin.pertanahan.cdesa.lokasi_tanah')

    @include('admin.layouts.components.konfirmasi_hapus')
@endsection
@push('scripts')
    <script>
        $(document).ready(function() {
            var TableData = $('#tabeldata').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                paging: false,
                searching: false,
                ajax: "{{ route('cdesa.datatables_rincian', $rincian->id) }}",
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
                        data: 'nomor_persil',
                        name: 'nomor_persil',
                        searchable: true,
                        orderable: false
                    },
                    {
                        data: 'kelas_tanah',
                        name: 'kelas_tanah',
                        searchable: true,
                        orderable: false
                    },
                    {
                        data: 'lokasi',
                        name: 'lokasi',
                        searchable: true,
                        orderable: false
                    },
                    {
                        name: 'luas_persil',
                        data: 'luas_persil',
                        searchable: true,
                        orderable: false,
                        // render: function(item, data, row) {
                        //     return `<a href='{{ ci_route('penduduk.detail') }}/${row.id_pemilik}'>${item}</a>`
                        // },
                    },
                    {
                        data: 'jml_mutasi',
                        name: 'jml_mutasi',
                        searchable: true,
                        orderable: false,
                        class: 'padat'
                    },
                ],
                order: [
                    [2, 'asc']
                ]
            });

            if (hapus == 0) {
                TableData.column(0).visible(false);
                $('.akses-hapus').remove();
            }

            if (ubah == 0) {
                TableData.column(2).visible(false);
            }
        });
    </script>
@endpush
