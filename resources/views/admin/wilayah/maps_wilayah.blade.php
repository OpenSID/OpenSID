@extends('admin.layouts.index')

@include('admin.layouts.components.asset_datatables')
@section('title')
    <h1>
        <h1>Peta Wilayah {{ $nama_wilayah }}</h1>
    </h1>
@endsection

@section('breadcrumb')
    @foreach ($breadcrumb as $tautan)
        <li><a href="{{ $tautan['link'] }}"> {{ $tautan['judul'] }}</a></li>
    @endforeach
    <li class="active">Peta Wilayah {{ $wilayah }}</li>
@endsection

@section('content')
    @include('admin.layouts.components.notifikasi')

    <div class="box box-info">
        <form action="{{ $form_action }}" method="POST" enctype="multipart/form-data" class="form-horizontal">
            <div class="box-body">
                <div id="tampil-map">
                    <input type="hidden" id="path" name="path" value="{{ $wil_ini['path'] }}">
                    <input type="hidden" name="id" id="id" value="{{ $wil_ini['id'] }}" />
                    <input type="hidden" name="zoom" id="zoom" value="{{ $wil_ini['zoom'] }}" />
                </div>
            </div>
            @if (can('u'))
                <div class="box-footer">
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="lat">Warna Area</label>
                        <div class="col-sm-4">
                            <div class="input-group my-colorpicker2">
                                <input type="text" id="warna" name="warna" class="form-control input-sm warna required" placeholder="#FFFFFF" value="{{ $wil_ini['warna'] ?? '#FFFFFF' }}">
                                <div class="input-group-addon input-sm">
                                    <i></i>
                                </div>
                            </div>
                        </div>
                        <label class="col-sm-2 control-label" for="lat">Warna Pinggiran</label>
                        <div class="col-sm-4">
                            <div class="input-group my-colorpicker2">
                                <input type="text" id="border" name="border" class="form-control input-sm warna required" placeholder="#FFFFFF" value="{{ $wil_ini['border'] ?? '#FFFFFF' }}">
                                <div class="input-group-addon input-sm">
                                    <i></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <a href="{{ $tautan['link'] }}" class="btn btn-social bg-purple btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Kembali"><i class="fa fa-arrow-circle-o-left"></i> Kembali</a>
                    <a
                        href="#"
                        data-href="{{ $route_kosongkan }}"
                        class="btn btn-social bg-maroon btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"
                        title="Kosongkan Wilayah"
                        data-toggle="modal"
                        data-target="#confirm-status"
                        data-body="Apakah yakin akan mengosongkan peta wilayah ini?"
                    ><i class="fa fa fa-trash-o"></i>Kosongkan</a>
                    <a href="#" class="btn btn-social btn-success btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" download="OpenSID.gpx" id="exportGPX"><i class='fa fa-download'></i> Export ke GPX</a>
                    <button type='reset' class='btn btn-social btn-danger btn-sm' id="resetme"><i class='fa fa-times'></i> Reset</button>
                    <button type='submit' class='btn btn-social btn-info btn-sm pull-right'><i class='fa fa-check'></i> Simpan</button>
                </div>
            @endif
        </form>
    </div>
@endsection
@include('admin.layouts.components.asset_peta')
@include('admin.layouts.components.konfirmasi', ['periksa_data' => true])
@push('css')
    <link rel="stylesheet" href="{{ asset('bootstrap/css/bootstrap-colorpicker.min.css') }}">
@endpush
@push('scripts')
    <script src="{{ asset('bootstrap/js/bootstrap-colorpicker.min.js') }}"></script>
    <script>
        window.onload = function() {
            $(".my-colorpicker2").colorpicker()

            @if (!empty($wil_ini['lat']) && !empty($wil_ini['lng']))
                var posisi = [{{ $wil_ini['lat'] . ', ' . $wil_ini['lng'] }}];
                var zoom = {{ $wil_ini['zoom'] }};
            @elseif (!empty($wil_atas['lat']) && !empty($wil_atas['lng']))
                // Jika posisi saat ini belum ada, maka posisi peta akan menampilkan peta desa
                var posisi = [{{ $wil_atas['lat'] . ', ' . $wil_atas['lng'] }}];
                var zoom = {{ $wil_atas['zoom'] }};
            @else
                // Kondisi ini hanya untuk lokasi/wilayah desa yg belum ada
                var posisi = [-1.0546279422758742, 116.71875000000001];
                var zoom = 10;
            @endif

            // Inisialisasi tampilan peta
            var peta_wilayah = L.map('tampil-map', pengaturan_peta).setView(posisi, zoom);

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
            var baseLayers = getBaseLayers(peta_wilayah, MAPBOX_KEY, JENIS_PETA);

            // Menampilkan Peta wilayah yg sudah ada
            @if (!empty($wil_ini['path']))
                var wilayah = {{ $wil_ini['path'] }};
                var warna = '{{ $wil_ini['warna'] }}';
                @if (isset($poly) && $poly == 'multi')
                    // MultiPolygon
                    showCurrentMultiPolygon(wilayah, peta_wilayah, warna, TAMPIL_LUAS);
                @else
                    // Polygon
                    showCurrentPolygon(wilayah, peta_wilayah, warna, TAMPIL_LUAS);
                @endif
            @endif

            // Menambahkan zoom scale ke peta
            L.control.scale().addTo(peta_wilayah);

            // Menambahkan toolbar ke peta
            peta_wilayah.pm.addControls(editToolbarPoly());

            @if (isset($poly) && $poly == 'multi')
                // Menambahkan Peta wilayah
                addPetaMultipoly(peta_wilayah);
                var multi = true;
            @else
                // Menambahkan peta poly
                addPetaPoly(peta_wilayah);
                var multi = false;
            @endif

            // Update value zoom ketika ganti zoom
            updateZoom(peta_wilayah);

            @if (can('u'))
                // Export/Import Peta dari file GPX
                eximGpxRegion(peta_wilayah, multi);

                // Import Peta dari file SHP
                eximShp(peta_wilayah, multi);
            @endif

            // Geolocation IP Route/GPS
            geoLocation(peta_wilayah);

            // Menghapus Peta wilayah
            hapuslayer(peta_wilayah);

            // Mencetak peta ke PNG
            cetakPeta(peta_wilayah);

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

            peta_wilayah.on('overlayadd', function(eventLayer) {
                if (eventLayer.name === 'Peta Wilayah Desa') {
                    setlegendPetaDesa(legenda_desa, peta_wilayah, {!! json_encode($desa, JSON_THROW_ON_ERROR) !!}, '{{ ucwords(setting('sebutan_desa')) }}', '{{ $desa['nama_desa'] }}');
                }

                if (eventLayer.name === 'Peta Wilayah Dusun') {
                    setlegendPeta(legenda_dusun, peta_wilayah, '{!! addslashes(json_encode($dusun_gis, JSON_THROW_ON_ERROR)) !!}', '{{ ucwords(setting('sebutan_dusun')) }}', 'dusun', '', '');
                }

                if (eventLayer.name === 'Peta Wilayah RW') {
                    setlegendPeta(legenda_rw, peta_wilayah, '{!! addslashes(json_encode($rw_gis, JSON_THROW_ON_ERROR)) !!}', 'RW', 'rw', '{{ ucwords(setting('sebutan_dusun')) }}');
                }

                if (eventLayer.name === 'Peta Wilayah RT') {
                    setlegendPeta(legenda_rt, peta_wilayah, '{!! addslashes(json_encode($rt_gis, JSON_THROW_ON_ERROR)) !!}', 'RT', 'rt', 'RW');
                }
            });

            peta_wilayah.on('overlayremove', function(eventLayer) {
                if (eventLayer.name === 'Peta Wilayah Desa') {
                    peta_wilayah.removeControl(legenda_desa);
                }

                if (eventLayer.name === 'Peta Wilayah Dusun') {
                    peta_wilayah.removeControl(legenda_dusun);
                }

                if (eventLayer.name === 'Peta Wilayah RW') {
                    peta_wilayah.removeControl(legenda_rw);
                }

                if (eventLayer.name === 'Peta Wilayah RT') {
                    peta_wilayah.removeControl(legenda_rt);
                }
            });

            // Menampilkan baseLayers dan overlayLayers
            L.control.layers(baseLayers, overlayLayers, {
                position: 'topleft',
                collapsed: true
            }).addTo(peta_wilayah);

            // Menampilkan notif error path
            view_error_path();
        }; //EOF window.onload
    </script>
    <script src="{{ asset('js/leaflet.filelayer.js') }}"></script>
    <script src="{{ asset('js/togeojson.js') }}"></script>
@endpush
