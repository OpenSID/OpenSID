@include('admin.layouts.components.asset_datatables')

@extends('admin.layouts.index')

@section('title')
    <h1>
        Rincian Persil
    </h1>
@endsection

@section('breadcrumb')
    <li><a href="{{ ci_route('data_persil') }}">Rincian Persil</a> </li>
    <li class="active">Rincian Persil</li>
@endsection
@push('css')
    <style type="text/css">
        #map {
            width: 100%;
            height: 310px
        }
    </style>
@endpush
@section('content')
    <div class="box box-info">
        <div class="box-header with-border">
            <a href="{{ ci_route('data_persil') }}" class="btn btn-social btn-info btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Kembali Ke Daftar PersilA"><i class="fa fa-arrow-circle-o-left"></i> Kembali Ke Daftar Persil</a>
        </div>
        <div class="box-body">
            <div class="dataTables_wrapper form-inline dt-bootstrap no-footer">
                <div class="row">
                    <div class="col-sm-12">
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
                                        <th>Kelas Tanah</td>
                                        <td>:</td>
                                        <td>{{ $persil->refKelas->kode . ' - ' . $persil->refKelas->ndesc }}</td>
                                    </tr>
                                    <tr>
                                        <th>Alamat</td>
                                        <td>:</td>
                                        <td>{{ $persil->wilayah ? $persil->wilayah->alamat : $persil->lokasi }}</td>
                                    </tr>
                                    @if ($persil['cdesa_awal'])
                                        <tr>
                                            <td>C-Desa Pemilik Awal</td>
                                            <td>:</td>
                                            <td><a href="{{ ci_route("cdesa.mutasi.{$persil['cdesa_awal']}.{$persil['id']}") }}">{{ $persil->cdesa->nomor }}</a></td>
                                        </tr>
                                    @endif
                                    @if ($persil['path'] != null || $persil['path'] != '')
                                        <tr>
                                            <td colspan="3" id="map">
                                                <input type="hidden" id="path" name="path" value="{{ $persil['path'] }}">
                                                <input type="hidden" id="zoom" name="zoom" value="">
                                            </td>
                                        </tr>
                                    @endif

                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="box-header with-border">
                            <h3 class="box-title">Daftar Mutasi Persil</h3>
                        </div>
                        <div class="box-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped dataTable table-hover">
                                    <thead class="bg-gray disabled color-palette">
                                        <tr class="bg-gray judul-besar">
                                            <th>No</th>
                                            <th>C-Desa Masuk</th>
                                            <th>C-Desa Keluar</th>
                                            <th>No. Bidang Mutasi</th>
                                            <th>Luas (M2)</th>
                                            <th>NOP</th>
                                            <th>Tanggal Mutasi</th>
                                            <th>Keterangan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($persil->mutasi as $item)
                                            <tr>
                                                <td class="text-center">{{ $loop->iteration }}</td>
                                                <td><a href="{{ ci_route('cdesa.rincian', $item->cdesaMasuk?->id) }}">{{ $item->cdesaMasuk?->nomor }}</a></td>
                                                <td><a href="{{ ci_route('cdesa.rincian', $item->cdesa_keluar) }}">{{ $item->cdesaKeluar?->id }}</a></td>
                                                <td>{{ $item['no_bidang_persil'] }}</td>
                                                <td>{{ $item['luas'] }}</td>
                                                <td>{{ $item['no_objek_pajak'] }}</td>
                                                <td>{{ tgl_indo_out($item['tanggal_mutasi']) }}</td>
                                                <td>{{ $item['keterangan'] }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    @include('admin.layouts.components.asset_peta')
    <script type="text/javascript">
        $(document).ready(function() {
            // tampilkan map
            @if (!empty($desa['lat']) && !empty($desa['lng']))
                var posisi = [{{ $desa['lat'] . ',' . $desa['lng'] }}];
                var zoom = {{ $desa['zoom'] ?: 18 }};
            @else
                var posisi = [-1.0546279422758742, 116.71875000000001];
                var zoom = 18;
            @endif

            var peta_area = L.map('map', pengaturan_peta).setView(posisi, zoom);

            //Menampilkan BaseLayers Peta
            var baseLayers = getBaseLayers(peta_area, MAPBOX_KEY, JENIS_PETA);

            if ($('input[name="path"]').val() !== '') {
                var wilayah = JSON.parse($('input[name="path"]').val());
                showCurrentArea(wilayah, peta_area, TAMPIL_LUAS);
            }

            //Geolocation IP Route/GPS
            geoLocation(peta_area);

            //Menambahkan Peta wilayah
            addPetaPoly(peta_area);
        });
    </script>
@endpush
