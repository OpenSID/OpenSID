@include('admin.layouts.components.asset_datatables')
@include('admin.layouts.components.asset_peta')

@extends('admin.layouts.index')

@section('title')
    <h1>
        Lokasi Pelapak
    </h1>
@endsection

@section('breadcrumb')
    <li><a href="{{ site_url('lapak_admin/pelapak') }}"> Pelapak</a></li>
    <li class="active">Lokasi Pelapak {{ $pelapak->pelapak }}</a></li>
@endsection

@section('content')
    @include('admin.layouts.components.notifikasi')

    <div class="box box-info">
        <form id="validasi" action="{{ $form_action }}" method="POST" class="form-horizontal">
            <div class="box-body">
                <div id="tampil-map"></div>
            </div>
            <div class='box-footer'>
                <input type="hidden" name="zoom" id="zoom" value="{{ $lokasi['zoom'] }}" />
                <div class="form-group">
                    <label class="col-sm-3 control-label" for="lat">Latitude</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control input-sm lat" name="lat" id="lat" value="{{ $lokasi['lat'] }}" />
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-3 control-label" for="lng">Longitude</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control input-sm lng" name="lng" id="lng" value="{{ $lokasi['lng'] }}" />
                    </div>
                </div>

                <a href="{{ site_url('lapak_admin/pelapak') }}" class="btn btn-social  bg-purple btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Kembali"><i class="fa fa-arrow-circle-o-left"></i> Kembali</a>
                <a href="#" class="btn btn-social  btn-success btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" download="OpenSID.gpx" id="exportGPX"><i class='fa fa-download'></i> Export ke GPX</a>
                <button type="reset" class="btn btn-social  btn-danger btn-sm" id="resetme"><i class="fa fa-times"></i> Reset</button>
                <button type="submit" class="btn btn-social  btn-info btn-sm pull-right"><i class='fa fa-check'></i> Simpan</button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        window.onload = function() {
            var posisi = [{{ $lokasi['lat'] . ',' . $lokasi['lng'] }}];
            var zoom = {{ $lokasi['zoom'] }};

            //Inisialisasi tampilan peta
            var peta_lapak = L.map('tampil-map', pengaturan_peta).setView(posisi, zoom);

            //1. Menampilkan overlayLayers Peta Semua Wilayah
            var marker_desa = [];
            var marker_dusun = [];
            var marker_rw = [];
            var marker_rt = [];

            //WILAYAH DESA
            @if (!empty($desa['path']))
                set_marker_desa(marker_desa, {!! json_encode($desa, JSON_THROW_ON_ERROR) !!}, "{{ ucwords($ci->setting->sebutan_desa) . ' ' . $desa['nama_desa'] }}", "{{ favico_desa() }}");
            @endif

            //WILAYAH DUSUN
            @if (!empty($dusun_gis))
                set_marker_multi(marker_dusun, '{!! addslashes(json_encode($dusun_gis, JSON_THROW_ON_ERROR)) !!}', '#FFFF00', '{{ ucwords($ci->setting->sebutan_dusun) }}', 'dusun');
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
                var overlayLayers = overlayWil(marker_desa, marker_dusun, marker_rw, marker_rt, "{{ ucwords($ci->setting->sebutan_desa) }}", "{{ ucwords($ci->setting->sebutan_dusun) }}");
            @else
                var overlayLayers = {};
            @endif

            //Menampilkan BaseLayers Peta
            var baseLayers = getBaseLayers(peta_lapak, MAPBOX_KEY, JENIS_PETA);

            showCurrentPoint(posisi, peta_lapak);

            @if (can('u'))
                //Export/Import Peta dari file GPX
                L.Control.FileLayerLoad.LABEL = '<img class="icon-map" src="{{ asset('images/gpx.png') }}" alt="file icon"/>';
                L.Control.FileLayerLoad.TITLE = 'Impor GPX/KML';
                controlGpxPoint = eximGpxPoint(peta_lapak);
            @endif

            //Menambahkan zoom scale ke peta
            L.control.scale().addTo(peta_lapak);
            L.control.layers(baseLayers, overlayLayers, {
                position: 'topleft',
                collapsed: true
            }).addTo(peta_lapak);
        }; //EOF window.onload
    </script>
    <script src="{{ asset('js/leaflet.filelayer.js') }}"></script>
    <script src="{{ asset('js/togeojson.js') }}"></script>
@endpush
