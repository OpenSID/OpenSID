@extends('admin.layouts.index')

@include('admin.layouts.components.asset_datatables')
@section('title')
    <h1>
        Lokasi {{ $lokasi['judul'] }}
    </h1>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ ci_route('admin_pembangunan') }}">Daftar Pembangunan</a></li>
    <li class="active">Lokasi {{ $lokasi['judul'] }}</li>
@endsection

@section('content')
    @include('admin.layouts.components.notifikasi')

    <div class="box box-info">
        <form action="{{ $form_action }}" method="POST" enctype="multipart/form-data" class="form-horizontal">
            <div class="box-body">
                <div id="tampil-map">
                    <input type="hidden" name="id" id="id" value="{{ $lokasi['id'] }}" />
                </div>
            </div>
            <div class='box-footer'>
                <div class="form-group">
                    <label class="col-sm-3 control-label" for="lat">Lat</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control input-sm lat" name="lat" id="lat" value="{{ $lokasi['lat'] }}" />
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label" for="lng">Lng</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control input-sm lng" name="lng" id="lng" value="{{ $lokasi['lng'] }}" />
                    </div>
                </div>
                <a href="{{ ci_route('admin_pembangunan') }}" class="btn btn-social bg-purple btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Kembali"><i class="fa fa-arrow-circle-o-left"></i> Kembali</a>
                <a href="#" class="btn btn-social btn-success btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" download="OpenSID.gpx" id="exportGPX"><i class='fa fa-download'></i> Export ke GPX</a>
                <button type='reset' class='btn btn-social btn-danger btn-sm' id="reset-peta"><i class='fa fa-times'></i> Reset</button>
                <button type='submit' class='btn btn-social btn-info btn-sm pull-right' id="simpan_kantor"><i class='fa fa-check'></i> Simpan</button>
            </div>
        </form>
    </div>
@endsection
@include('admin.layouts.components.asset_peta')
@include('admin.layouts.components.konfirmasi', ['periksa_data' => true])
@push('scripts')
    <script>
        window.onload = function() {
            @if (!empty($lokasi['lat']) && !empty($lokasi['lng']))
                var posisi = [{{ $lokasi['lat'] }}, {{ $lokasi['lng'] }}];
                var zoom = 16;
            @else
                var posisi = [{{ $desa['lat'] }}, {{ $desa['lng'] }}];
                var zoom = {{ $desa['zoom'] ?: 16 }};
            @endif

            //Inisialisasi tampilan peta
            var peta_lokasi = L.map('tampil-map', pengaturan_peta).setView(posisi, zoom);

            //1. Menampilkan overlayLayers Peta Semua Wilayah
            var marker_desa = [];
            var marker_dusun = [];
            var marker_rw = [];
            var marker_rt = [];
            var marker_persil = []

            //OVERLAY WILAYAH DESA
            @if (!empty($desa['path']))
                set_marker_desa(marker_desa, {!! json_encode($desa, JSON_THROW_ON_ERROR) !!}, "{{ ucwords(setting('sebutan_desa')) }} {{ $desa['nama_desa'] }}", "{{ favico_desa() }}");
            @endif

            //OVERLAY WILAYAH DUSUN
            @if (!empty($dusun_gis))
                set_marker_multi(marker_dusun, '{!! addslashes(json_encode($dusun_gis, JSON_THROW_ON_ERROR)) !!}', '#FFFF00', '{{ ucwords(setting('sebutan_dusun')) }}', 'dusun');
            @endif

            //OVERLAY WILAYAH RW
            @if (!empty($rw_gis))
                set_marker(marker_rw, '{!! addslashes(json_encode($rw_gis, JSON_THROW_ON_ERROR)) !!}', '#8888dd', 'RW', 'rw');
            @endif

            //OVERLAY WILAYAH RT
            @if (!empty($rt_gis))
                set_marker(marker_rt, '{!! addslashes(json_encode($rt_gis, JSON_THROW_ON_ERROR)) !!}', '#008000', 'RT', 'rt');
            @endif

            //Menampilkan overlayLayers Peta Semua Wilayah
            @if (!empty($wil_atas['path']))
                var overlayLayers = overlayWil(marker_desa, marker_dusun, marker_rw, marker_rt, "{{ ucwords(setting('sebutan_desa')) }}", "{{ ucwords(setting('sebutan_dusun')) }}");
            @else
                var overlayLayers = {};
            @endif

            //Menampilkan BaseLayers Peta
            var baseLayers = getBaseLayers(peta_lokasi, MAPBOX_KEY, JENIS_PETA);

            //Menampilkan dan Menambahkan Peta wilayah + Geolocation GPS
            @if (!empty($lokasi['path']))
                var wilayah = {{ $lokasi['path'] }};
                showCurrentArea(wilayah, peta_lokasi, TAMPIL_LUAS);
            @endif
            L.Control.FileLayerLoad.LABEL = '<img class="icon-map" src="{{ asset('images/folder.svg') }}" alt="file icon"/>';
            showCurrentPoint(posisi, peta_lokasi);

            //Export/Import Peta dari file GPX
            L.Control.FileLayerLoad.LABEL = '<img class="icon-map" src="{{ asset('images/gpx.png') }}" alt="file icon"/>';
            L.Control.FileLayerLoad.TITLE = 'Impor GPX/KML';
            controlGpxPoint = eximGpxPoint(peta_lokasi);

            //Menambahkan zoom scale ke peta
            L.control.scale().addTo(peta_lokasi);

            // deklrasi variabel agar mudah di baca
            var all_area = '{!! addslashes(json_encode($all_area, JSON_THROW_ON_ERROR)) !!}';
            var all_garis = '{!! addslashes(json_encode($all_garis, JSON_THROW_ON_ERROR)) !!}';
            var all_lokasi = '{!! addslashes(json_encode($all_lokasi, JSON_THROW_ON_ERROR)) !!}';
            var all_lokasi_pembangunan = '{!! addslashes(json_encode($all_lokasi_pembangunan, JSON_THROW_ON_ERROR)) !!}';
            var all_persil = '{{ addslashes(json_encode($persil, JSON_THROW_ON_ERROR)) }}';
            var LOKASI_SIMBOL_LOKASI = '{{ base_url() . LOKASI_SIMBOL_LOKASI }}';
            var favico_desa = '{{ favico_desa() }}';
            var LOKASI_FOTO_AREA = '{{ base_url(LOKASI_FOTO_AREA) }}';
            var LOKASI_FOTO_GARIS = '{{ base_url(LOKASI_FOTO_GARIS) }}';
            var LOKASI_FOTO_LOKASI = '{{ base_url(LOKASI_FOTO_LOKASI) }}';
            var LOKASI_GALERI = '{{ base_url(LOKASI_GALERI) }}';
            var info_pembangunan = '{{ ci_route('pembangunan') }}';

            // Menampilkan OverLayer Area, Garis, Lokasi plus Lokasi Pembangunan
            var layerCustom = tampilkan_layer_area_garis_lokasi_plus(peta_lokasi, all_area, all_garis, all_lokasi, all_lokasi_pembangunan, LOKASI_SIMBOL_LOKASI, favico_desa, LOKASI_FOTO_AREA, LOKASI_FOTO_GARIS, LOKASI_FOTO_LOKASI, LOKASI_GALERI, info_pembangunan, all_persil, TAMPIL_LUAS);

            L.control.layers(baseLayers, overlayLayers, {
                position: 'topleft',
                collapsed: true
            }).addTo(peta_lokasi);
            L.control.groupedLayers('', layerCustom, {
                groupCheckboxes: true,
                position: 'topleft',
                collapsed: true
            }).addTo(peta_lokasi);

            // Reset peta type point
            resetPoint(peta_lokasi, posisi, zoom);

        }; //EOF window.onload
    </script>
    <script src="{{ asset('js/leaflet.filelayer.js') }}"></script>
    <script src="{{ asset('js/togeojson.js') }}"></script>
@endpush
