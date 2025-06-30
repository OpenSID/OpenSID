@extends('admin.layouts.index')
@include('admin.layouts.components.asset_peta')
@include('admin.layouts.components.asset_datatables')

@section('title')
    <h1>
        Rincian Mutasi C-DESA
    </h1>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ ci_route('cdesa') }}"> Daftar C-Desa</a></li>
    <li class="breadcrumb-item"><a href="{{ ci_route('cdesa.rincian', $cdesa['id']) }}"> Rincian C-DESA</a></li>
    <li class="active">Mutasi C-Desa</li>
@endsection
<style>
    table .btn-sm {
        margin: 0 2px;
    }
</style>
@section('content')
    @include('admin.layouts.components.notifikasi')
    <div class="box box-info">
        <div class="box-header with-border">
            @if (can('u'))
                <a href="{{ route('cdesa.create_mutasi', ['id_cdesa' => $cdesa['id']]) }}" class="btn btn-social btn-success btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Tambah Persil">
                    <i class="fa fa-plus"></i>Tambah Mutasi Persil
                </a>
            @endif
            <a href="{{ ci_route('cdesa') }}" class="btn btn-social btn-primary btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Kembali Ke Daftar C-DESA"><i class="fa fa-arrow-circle-o-left"></i> Kembali Ke Daftar C-DESA</a>
            <a href="{{ ci_route('cdesa.rincian', $cdesa['id']) }}" class="btn btn-social btn-info btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Kembali Ke Daftar C-DESA"><i class="fa fa-arrow-circle-o-left"></i> Kembali Ke Rincian
                C-DESA</a>
        </div>
        <div class="box-body">
            {{-- rincian cdesa --}}
            {{-- apakah bisa jadikan komponen? karna sudah dipakai juga di rincian cdesa --}}
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
                                {{ $cdesa['nama_pemilik'] }}
                            </td>
                        </tr>
                        <tr>
                            <td>NIK</td>
                            <td>:</td>
                            <td>
                                {{ $cdesa['nik_pemilik'] }}
                            </td>
                        </tr>
                        <tr>
                            <td>Alamat</td>
                            <td>:</td>
                            <td>
                                {{ $cdesa['alamat'] }}
                            </td>
                        </tr>
                        <tr>
                            <td>Nomor C-DESA</td>
                            <td>:</td>
                            <td>
                                {{ sprintf('%04s', $cdesa['nomor']) }}
                            </td>
                        </tr>
                        <tr>
                            <td>Nama Pemilik Tertulis di C-Desa</td>
                            <td>:</td>
                            <td>
                                {{ $cdesa['nama_kepemilikan'] }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            {{-- end rincian cdesa --}}

            {{-- rincian persil --}}
            <div class="box-header with-border">
                <h3 class="box-title">Rincian Persil</h3>
            </div>
            <div class="box-body">
                <table class="table table-bordered table-striped table-hover tabel-rincian">
                    <tbody>
                        <tr>
                            <td width="20%">No. Persil : No. Urut Bidang</td>
                            <td width="1%">:</td>
                            <td>{{ $persil['nomor'] . ' : ' . $persil['nomor_urut_bidang'] }}</td>
                        </tr>
                        <tr>
                            <td>Kelas Tanah</td>
                            <td>:</td>
                            <td>{{ $persil->refKelas['kode'] . ' - ' . $persil->refKelas['ndesc'] }}</td>
                        </tr>
                        <tr>
                            <td>Luas Keseluruhan (M2)</td>
                            <td>:</td>
                            <td>{{ $persil['luas_persil'] }}</td>
                        </tr>
                        <tr>
                            <td>Lokasi</td>
                            <td>:</td>
                            <td>{{ $persil['lokasi'] ?: $persil->wilayah['dusun'] }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            {{-- end rincian persil --}}

            <div class="box-header with-border">
                <h3 class="box-title">Daftar Mutasi Persil {{ $persil['nomor'] }}</h3>
            </div>
            <div class="box-body">
                {!! form_open(null, 'id="mainform" name="mainform"') !!}
                <div class="table-responsive">
                    <table id="tableMutasi" class="table table-bordered table-striped dataTable table-hover">
                        <thead class="bg-gray disabled color-palette">
                            <tr>
                                <th class="padat">No</th>
                                <th class="padat">Aksi</th>
                                <th>No. Bidang Mutasi</th>
                                <th>Luas Masuk (M2)</th>
                                <th>Luas Keluar (M2)</th>
                                <th>NOP</th>
                                <th>Tanggal Mutasi</th>
                                <th>Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
                </form>
            </div>
        </div>
    </div>

    @include('admin.pertanahan.cdesa.lokasi_tanah')

    @include('admin.layouts.components.konfirmasi_hapus')
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            var TableData = $('#tableMutasi').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                searching: false,
                ajax: {
                    url: "{{ route('cdesa.datatables_mutasi', ['id_cdesa' => $cdesa['id'], 'id_persil' => $persil['id']]) }}",
                    type: 'GET'
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
                        data: 'no_bidang_persil',
                        name: 'no_bidang_persil',
                        searchable: true,
                        orderable: false
                    },
                    {
                        data: 'luas_masuk',
                        name: 'luas_masuk',
                        searchable: true,
                        orderable: false
                    },
                    {
                        data: 'luas_keluar',
                        name: 'luas_keluar',
                        searchable: true,
                        orderable: false
                    },
                    {
                        name: 'no_objek_pajak',
                        data: 'no_objek_pajak',
                        searchable: true,
                        orderable: false,
                        // render: function(item, data, row) {
                        //     return `<a href='{{ ci_route('penduduk.detail') }}/${row.id_pemilik}'>${item}</a>`
                        // },
                    },
                    {
                        data: 'tanggal_mutasi',
                        name: 'tanggal_mutasi',
                        searchable: true,
                        orderable: false,
                        class: 'padat'
                    },
                    {
                        data: 'keterangan',
                        name: 'keterangan',
                        searchable: true,
                        orderable: false,
                        class: 'padat'
                    },
                ],
                order: [
                    [2, 'asc']
                ]
            });
        });
    </script>
@endpush
