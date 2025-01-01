@extends('admin.layouts.index')

@include('admin.layouts.components.asset_datatables')
@section('title')
    <h1>
        <h1>Lokasi Tempat Tinggal {{ $penduduk['nama'] }}</h1>
    </h1>
@endsection

@section('breadcrumb')
    @if ($edit == '2')
        <li><a href="{{ ci_route('penduduk') }}"> Daftar Penduduk</a></li>
    @else
        <li><a href="{{ ci_route("penduduk.form.{$id}.1") }}"> Biodata Penduduk</a></li>
        <li><a href=#> Lokasi Tempat Tinggal</a></li>
    @endif
@endsection

@section('content')
    @include('admin.layouts.components.notifikasi')

    <div class="box box-info">
        <form id="validasi" action="{{ $form_action }}" method="POST" class="form-horizontal">
            <div class="box-body">
                <div id="tampil-map"></div>
            </div>
            <div class="box-footer">
                <div class="form-group">
                    <label class="col-sm-3 control-label" for="lat">Latitude</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control input-sm lng" {{ $edit == 0 ? 'readonly="readonly"' : 'name=lat id=lat' }} value="{{ $penduduk['lat'] }}" />
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-3 control-label" for="lng">Longitude</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control input-sm lng" {{ $edit == 0 ? 'readonly="readonly"' : 'name=lng id=lng' }} value="{{ $penduduk['lng'] }}" />
                    </div>
                </div>

                @if ($edit == '0')
                    <a href="{{ ci_route('penduduk') }}" class="btn btn-social bg-purple btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Kembali"><i class="fa fa-arrow-circle-o-left"></i> Kembali</a>
                    <a href="{{ ci_route("penduduk.ajax_penduduk_maps.{$id}.2") }}" class="btn btn-social btn-warning btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Ubah"><i class="fa fa-edit"></i> Ubah</a>
                @elseif ($edit == '1')
                    <a href="{{ ci_route("penduduk.form.{$id}.1") }}" class="btn btn-social bg-purple btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Kembali"><i class="fa fa-arrow-circle-o-left"></i> Kembali</a>
                    <a href="#" class="btn btn-social btn-success btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" download="OpenSID.gpx" id="exportGPX"><i class='fa fa-download'></i> Export ke GPX</a>
                    <button type='reset' class='btn btn-social btn-danger btn-sm' id="reset-peta"><i class='fa fa-times'></i> Reset</button>
                    @if ($penduduk['status_dasar'] == 1 || !isset($penduduk['status_dasar']))
                        <button type='submit' class='btn btn-social btn-info btn-sm pull-right'><i class='fa fa-check'></i>
                            Simpan</button>
                    @endif
                @elseif ($edit == '2')
                    <a href="{{ ci_route('penduduk') }}" class="btn btn-social bg-purple btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Kembali"><i class="fa fa-arrow-circle-o-left"></i> Kembali</a>
                    <a href="#" class="btn btn-social btn-success btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" download="OpenSID.gpx" id="exportGPX"><i class='fa fa-download'></i> Export ke GPX</a>
                    <button type='reset' class='btn btn-social btn-danger btn-sm' id="reset-peta"><i class='fa fa-times'></i> Reset</button>
                    @if ($penduduk['status_dasar'] == 1 || !isset($penduduk['status_dasar']))
                        <button type='submit' class='btn btn-social btn-info btn-sm pull-right'><i class='fa fa-check'></i>
                            Simpan</button>
                    @endif
                @endif
            </div>
        </form>
    </div>
@endsection
@include('admin.layouts.components.asset_peta')
@push('scripts')
    <script src="{{ asset('js/leaflet.filelayer.js') }}"></script>
    <script src="{{ asset('js/togeojson.js') }}"></script>
    <script>
        window.onload = function() {
            var mode = '{{ (bool) $edit }}';
            //Jika posisi kantor dusun belum ada, maka posisi peta akan menampilkan peta desa
            @if (!empty($penduduk['lat']))
                var posisi = [{{ $penduduk['lat'] . ',' . $penduduk['lng'] }}]
                var zoom = {{ $desa['zoom'] ?: 10 }};
            @else
                var posisi = [{{ $desa['lat'] . ',' . $desa['lng'] }}]
                var zoom = 10;
            @endif

            //Inisialisasi tampilan peta
            var peta_penduduk = L.map('tampil-map', pengaturan_peta).setView(posisi, zoom);

            //1. Menampilkan overlayLayers Peta Semua Wilayah
            var marker_desa = []
            var marker_dusun = []
            var marker_rw = []
            var marker_rt = []
            var marker_persil = []
            //WILAYAH DESA
            @if (!empty($desa['path']))
                set_marker_desa(marker_desa, {!! json_encode($desa, JSON_THROW_ON_ERROR) !!},
                    "{{ ucwords(setting('sebutan_desa')) . ' ' . $desa['nama_desa'] }}", "{{ favico_desa() }}");
            @endif

            //WILAYAH DUSUN
            @if (!empty($dusun_gis))
                set_marker_multi(marker_dusun, '{!! addslashes(json_encode($dusun_gis, JSON_THROW_ON_ERROR)) !!}', '#FFFF00',
                    '{{ ucwords(setting('sebutan_dusun')) }}', 'dusun');
            @endif

            //WILAYAH RW
            @if (!empty($rw_gis))
                set_marker(marker_rw, '{!! addslashes(json_encode($rw_gis, JSON_THROW_ON_ERROR)) !!}', '#8888dd', 'RW', 'rw');
            @endif

            //WILAYAH RT
            @if (!empty($rt_gis))
                set_marker(marker_rt, '{!! addslashes(json_encode($rt_gis, JSON_THROW_ON_ERROR)) !!}', '#008000', 'RT', 'rt');
            @endif

            //2. Menampilkan overlayLayers Peta Semua Wilayah
            @if (!empty($wil_atas['path']))
                var overlayLayers = overlayWil(marker_desa, marker_dusun, marker_rw, marker_rt, marker_persil,
                    "{{ ucwords(setting('sebutan_desa')) }}", "{{ ucwords(setting('sebutan_dusun')) }}");
            @else
                var overlayLayers = {};
            @endif

            //Menampilkan BaseLayers Peta
            var baseLayers = getBaseLayers(peta_penduduk, MAPBOX_KEY, JENIS_PETA);

            //Menampilkan dan Menambahkan Peta wilayah + Geolocation GPS + Exim GPX/KML
            L.Control.FileLayerLoad.LABEL = '<img class="icon-map" src="{{ asset('images/folder.svg') }}" alt="file icon"/>';
            showCurrentPoint(posisi, peta_penduduk, mode);

            if (ubah && mode == 1) {
                //Export/Import Peta dari file GPX
                eximGpxPoint(peta_penduduk);
            }

            L.control.scale().addTo(peta_penduduk);

            L.control.layers(baseLayers, overlayLayers, {
                position: 'topleft',
                collapsed: true
            }).addTo(peta_penduduk);

            // Reset peta type point
            resetPoint(peta_penduduk, posisi, zoom);

        }; //EOF window.onload
    </script>
@endpush
