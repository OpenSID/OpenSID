@extends('admin.layouts.index')

@include('admin.layouts.components.asset_datatables')
@section('title')
    <h1>
        <h1>Lokasi Kantor {{ $nama_wilayah }}</h1>
    </h1>
@endsection

@section('breadcrumb')
    @foreach ($breadcrumb as $tautan)
        <li><a href="{{ $tautan['link'] }}"> {{ $tautan['judul'] }}</a></li>
    @endforeach
@endsection

@section('content')
    @include('admin.layouts.components.notifikasi')

    <div class="box box-info">
        <form action="{{ $form_action }}" method="POST" enctype="multipart/form-data" class="form-horizontal">
            <div class="box-body">
                <div id="tampil-map">
                    <input type="hidden" name="zoom" id="zoom" value="{{ $wil_ini['zoom'] }}" />
                    <input type="hidden" name="map_tipe" id="map_tipe" value="{{ $wil_ini['map_tipe'] }}" />
                    <input type="hidden" name="id" id="id" value="{{ $wil_ini['id'] }}" />
                </div>
            </div>
            <div class="box-footer">
                <div class="form-group">
                    <label class="col-sm-3 control-label" for="lat">Latitude</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control input-sm lat" name="lat" id="lat" value="{{ $wil_ini['lat'] }}" />
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label" for="lat">Longitude</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control input-sm lng" name="lng" id="lng" value="{{ $wil_ini['lng'] }}" />
                    </div>
                </div>
                <a href="{{ $tautan['link'] }}" class="btn btn-social bg-purple btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Kembali"><i class="fa fa-arrow-circle-o-left"></i> Kembali</a>
                <a href="#" class="btn btn-social btn-success btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" download="OpenSID.gpx" id="exportGPX"><i class='fa fa-download'></i> Export ke GPX</a>
                <button type='reset' class='btn btn-social btn-danger btn-sm' id="resetme"><i class='fa fa-times'></i> Reset</button>
                @if (can('u'))
                    <button type='submit' class='btn btn-social btn-info btn-sm pull-right' id="simpan_kantor"><i class='fa fa-check'></i> Simpan</button>
                @endif
            </div>
        </form>
    </div>
@endsection
@include('admin.layouts.components.asset_peta')
@include('admin.layouts.components.konfirmasi', ['periksa_data' => true])
@push('scripts')
    <script>
        window.onload = function() {
            @if (!empty($wil_ini['lat']) && !empty($wil_ini['lng']))
                var posisi = [{{ $wil_ini['lat'] }}, {{ $wil_ini['lng'] }}];
                var zoom = {{ $wil_ini['zoom'] ?: 18 }};
            @elseif (!empty($wil_atas['lat']) && !empty($wil_atas['lng']))
                // Jika posisi saat ini belum ada, maka posisi peta akan menampilkan peta desa
                var posisi = [{{ $wil_atas['lat'] . ', ' . $wil_atas['lng'] }}];
                var zoom = {{ $wil_atas['zoom'] }};
            @else
                var posisi = [-1.0546279422758742, 116.71875000000001];
                var zoom = 4;
            @endif

            // Inisialisasi tampilan peta
            var peta_kantor = L.map('tampil-map', pengaturan_peta).setView(posisi, zoom);

            // 1. Menampilkan overlayLayers Peta Semua Wilayah
            var marker_desa = [];
            var marker_dusun = [];
            var marker_rw = [];
            var marker_rt = [];

            // OVERLAY WILAYAH DESA
            @if (!empty($desa['path']))
                set_marker_desa(marker_desa, {!! json_encode($desa, JSON_THROW_ON_ERROR) !!}, "{{ ucwords(setting('sebutan_desa')) . ' ' . $desa['nama_desa'] }}", "{{ favico_desa() }}");
            @endif

            // OVERLAY WILAYAH DUSUN
            @if (!empty($dusun_gis))
                set_marker_multi(marker_dusun, '{!! addslashes(json_encode($dusun_gis, JSON_THROW_ON_ERROR)) !!}', '{{ ucwords(setting('sebutan_dusun')) }}', 'dusun', "{{ favico_desa() }}");
            @endif

            // OVERLAY WILAYAH RW
            @if (!empty($rw_gis))
                set_marker(marker_rw, '{!! addslashes(json_encode($rw_gis, JSON_THROW_ON_ERROR)) !!}', 'RW', 'rw', "{{ favico_desa() }}");
            @endif

            // OVERLAY WILAYAH RT
            @if (!empty($rt_gis))
                set_marker(marker_rt, '{!! addslashes(json_encode($rt_gis, JSON_THROW_ON_ERROR)) !!}', 'RT', 'rt', "{{ favico_desa() }}");
            @endif

            // 2. Menampilkan overlayLayers Peta Semua Wilayah
            @if (!empty($wil_atas['path']))
                var overlayLayers = overlayWil(marker_desa, marker_dusun, marker_rw, marker_rt, "{{ ucwords(setting('sebutan_desa')) }}", "{{ ucwords(setting('sebutan_dusun')) }}");
            @else
                var overlayLayers = {};
            @endif

            // Menampilkan BaseLayers Peta
            var baseLayers = getBaseLayers(peta_kantor, MAPBOX_KEY, JENIS_PETA);

            // Menampilkan dan Menambahkan Peta wilayah + Geolocation GPS
            showCurrentPoint(posisi, peta_kantor);

            @if (can('u'))
                //Export/Import Peta dari file GPX
                eximGpxPoint(peta_kantor);
            @endif

            // Menambahkan zoom scale ke peta
            L.control.scale().addTo(peta_kantor);

            // Mencetak peta ke PNG
            cetakPeta(peta_kantor);

            // Menambahkan Legenda Ke Peta
            var legenda_desa = L.control({
                position: 'bottomright'
            });
            var legenda_dusun = L.control({
                position: 'bottomright'
            });
            var legenda_rw = L.control({
                position: 'bottomright'
            });
            var legenda_rt = L.control({
                position: 'bottomright'
            });

            peta_kantor.on('overlayadd', function(eventLayer) {
                if (eventLayer.name === 'Peta Wilayah Desa') {
                    setlegendPetaDesa(legenda_desa, peta_kantor, {!! json_encode($desa, JSON_THROW_ON_ERROR) !!}, '{{ ucwords(setting('sebutan_desa')) }}', '{{ $desa['nama_desa'] }}');
                }

                if (eventLayer.name === 'Peta Wilayah Dusun') {
                    setlegendPeta(legenda_dusun, peta_kantor, '{!! addslashes(json_encode($dusun_gis, JSON_THROW_ON_ERROR)) !!}', '{{ ucwords(setting('sebutan_dusun')) }}', 'dusun', '', '');
                }

                if (eventLayer.name === 'Peta Wilayah RW') {
                    setlegendPeta(legenda_rw, peta_kantor, '{!! addslashes(json_encode($rw_gis, JSON_THROW_ON_ERROR)) !!}', 'RW', 'rw', '{{ ucwords(setting('sebutan_dusun')) }}');
                }

                if (eventLayer.name === 'Peta Wilayah RT') {
                    setlegendPeta(legenda_rt, peta_kantor, '{!! addslashes(json_encode($rt_gis, JSON_THROW_ON_ERROR)) !!}', 'RT', 'rt', 'RW');
                }
            });

            peta_kantor.on('overlayremove', function(eventLayer) {
                if (eventLayer.name === 'Peta Wilayah Desa') {
                    peta_kantor.removeControl(legenda_desa);
                }

                if (eventLayer.name === 'Peta Wilayah Dusun') {
                    peta_kantor.removeControl(legenda_dusun);
                }

                if (eventLayer.name === 'Peta Wilayah RW') {
                    peta_kantor.removeControl(legenda_rw);
                }

                if (eventLayer.name === 'Peta Wilayah RT') {
                    peta_kantor.removeControl(legenda_rt);
                }
            });

            L.control.layers(baseLayers, overlayLayers, {
                position: 'topleft',
                collapsed: true
            }).addTo(peta_kantor);

            // Menampilkan notif error path
            view_error_path();
        }; //EOF window.onload
    </script>
    <script src="{{ asset('js/leaflet.filelayer.js') }}"></script>
    <script src="{{ asset('js/togeojson.js') }}"></script>
@endpush
