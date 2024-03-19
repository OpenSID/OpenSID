@extends('admin.layouts.index')

@section('content')
    @include('admin.layouts.components.notifikasi')
    <form id="mainform_map" name="mainform_map" method="post">
        <div class="row">
            <div class="col-md-12">
                <div id="map">
                    @include ('admin.gis.cetak_peta')
                    <div class="leaflet-top leaflet-right">
                        <div class="leaflet-control-layers leaflet-bar leaflet-control" style="margin-top: 50px;">
                            <a class="leaflet-bar-part leaflet-bar-part-single" href="#" title="Control Panel" role="button" aria-label="Control Panel" onclick="$('#target1').toggle();$('#target1').removeClass('hidden');$('#target2').hide();"><i class="fa fa-gears"></i></a>
                            <a class="leaflet-bar-part leaflet-bar-part-single" href="#" title="Legenda" role="button" aria-label="Legenda" onclick="$('#target2').toggle();$('#target2').removeClass('hidden');$('#target1').hide();"><i class="fa fa-list"></i></a>
                        </div>
                        @include('admin.gis.content_desa', [
                            'desa' => $desa,
                            'list_ref' => $list_ref,
                            'wilayah' => ucwords(setting('sebutan_desa') . ' ' . $desa['nama_desa']),
                        ])
                        @include('admin.gis.content_dusun', [
                            'dusun_gis' => $dusun_gis,
                            'list_ref' => $list_ref,
                            'wilayah' => ucwords(setting('sebutan_dusun') . ' '),
                        ])
                        @include('admin.gis.content_rw', [
                            'rw_gis' => $rw_gis,
                            'list_ref' => $list_ref,
                            'wilayah' => ucwords(setting('sebutan_dusun') . ' '),
                        ])
                        @include('admin.gis.content_rt', [
                            'rt_gis' => $rt_gis,
                            'list_ref' => $list_ref,
                            'wilayah' => ucwords(setting('sebutan_dusun') . ' '),
                        ])
                        <div id="target1" class="leaflet-control-layers leaflet-control-layers-expanded leaflet-control hidden" aria-haspopup="true" style="max-width: 250px;">
                            <div class="leaflet-control-layers-overlays">
                                <div class="leaflet-control-layers-group" id="leaflet-control-layers-group-2">
                                    <span class="leaflet-control-layers-group-name">CARI PENDUDUK</span>
                                    <div class="leaflet-control-layers-separator"></div>
                                    <div class="form-group">
                                        <label>Status Penduduk</label>
                                        <select class="form-control input-sm " name="filter" onchange="formAction('mainform_map','{{ ci_route('gis.filter') }}')">
                                            <option value="">Pilih Status Penduduk </option>
                                            @foreach ($list_status_penduduk as $data)
                                                <option value="{{ $data['id'] }}" @selected($filter == $data['id'])>
                                                    {{ $data['nama'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>Jenis Kelamin</label>
                                        <select class="form-control input-sm " name="sex" onchange="formAction('mainform_map','{{ ci_route('gis.filter') }}')">
                                            <option value="">Pilih Jenis Kelamin </option>
                                            @foreach ($list_jenis_kelamin as $key => $item)
                                                <option value="{{ $key }}" @selected($key == $sex)>
                                                    {{ $item }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>{{ ucwords(setting('sebutan_dusun')) }}</label>
                                        <select class="form-control input-sm " name="dusun" onchange="formAction('mainform_map','{{ ci_route('gis.filter') }}')">
                                            <option value="">Pilih Dusun</option>
                                            @foreach ($list_dusun as $data)
                                                <option value="{{ $data['dusun'] }}" @selected($dusun == $data['dusun'])>{{ $data['dusun'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @if ($dusun)
                                        <div class="form-group">
                                            <label>RW</label>
                                            <select class="form-control input-sm " name="rw" onchange="formAction('mainform_map','{{ ci_route('gis.filter') }}')">
                                                <option value="">Pilih RW</option>
                                                @foreach ($list_rw as $data)
                                                    <option value="{{ $data['rw'] }}" @selected($rw == $data['rw'])>
                                                        {{ $data['rw'] }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        @if ($rw)
                                            <div class="form-group">
                                                <label>RT</label>
                                                <select class="form-control input-sm " name="rt" onchange="formAction('mainform_map','{{ ci_route('gis.filter') }}')">
                                                    <option value="">Pilih RT</option>
                                                    @foreach ($list_rt as $data)
                                                        <option value="{{ $data['rt'] }}" @selected($rt == $data['rt'])>{{ $data['rt'] }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        @endif
                                    @endif
                                    <div class="col-sm-12">
                                        <div class="form-group row">
                                            <label>Cari</label>
                                            <div class="box-tools">
                                                <div class="input-group input-group-sm pull-right">
                                                    <input
                                                        name="cari"
                                                        id="cari"
                                                        class="form-control"
                                                        placeholder="cari..."
                                                        type="text"
                                                        value="{{ html_escape($cari) }}"
                                                        onkeypress="if (event.keyCode == 13):$('#'+'mainform_map').attr('action', '{{ ci_route('gis.filter') }}');$('#'+'mainform_map').submit();endif"
                                                    >
                                                    <div class="input-group-btn">
                                                        <button type="submit" class="btn btn-default" onclick="$('#'+'mainform_map').attr('action', '{{ ci_route('gis.filter') }}');$('#'+'mainform_map').submit();"><i class="fa fa-search"></i></button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <a
                                            href="{{ ci_route('gis.ajax_adv_search') }}"
                                            class="btn btn-block btn-social bg-olive btn-sm"
                                            data-remote="false"
                                            data-toggle="modal"
                                            data-target="#modalBox"
                                            data-title="Pencarian Spesifik"
                                            title="Pencarian Spesifik"
                                        >
                                            <i class="fa fa-search"></i> Pencarian Spesifik
                                        </a>
                                        <a href="{{ ci_route('gis.clear') }}" class="btn btn-block btn-social bg-orange btn-sm">
                                            <i class="fa fa-refresh"></i> Bersihkan
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="target2" class="leaflet-control-layers leaflet-control-layers-expanded leaflet-control hidden" aria-haspopup="true" style="max-height: 315px;">
                            <div class="leaflet-control-layers-overlays">
                                <div class="leaflet-control-layers-group" id="leaflet-control-layers-group-3">
                                    <span class="leaflet-control-layers-group-name">LEGENDA</span>
                                    <div class="leaflet-control-layers-separator"></div>
                                    <label>
                                        <input class="leaflet-control-layers-selector layer-checkbox" type="checkbox" name="layer_penduduk" value="1" onchange="handle_layer(this);" @checked($layer_penduduk == 1)>
                                        <span> Penduduk </span>
                                    </label>
                                    <label>
                                        <input class="leaflet-control-layers-selector layer-checkbox" type="checkbox" name="layer_keluarga" value="1" onchange="handle_layer(this);" @checked($layer_keluarga == 1)>
                                        <span> Keluarga</span>
                                    </label>
                                    <label>
                                        <input class="leaflet-control-layers-selector layer-checkbox" type="checkbox" name="layer_rtm" value="1" onchange="handle_layer(this);" @checked($layer_rtm == 1)>
                                        <span> Rumah Tangga</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="leaflet-bottom leaflet-left">
                        <div id="qrcode">
                            <div class="panel-body-lg">
                                <a href="https://github.com/OpenSID/OpenSID">
                                    <img src="{{ to_base64(GAMBAR_QRCODE) }}" alt="OpenSID">
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

@endsection
@push('css')
    @include('admin.layouts.components.asset_peta')
    @include('admin.gis.local_gis_css')
@endpush
@push('scripts')
    <script>
        (function() {
            $('.content-wrapper').css({
                height: '93%'
            })
            $('.content-wrapper>section.content-header').remove()
            $('.content-wrapper>section.content').css({
                padding: 0
            })
            var infoWindow;
            window.onload = function() {
                @if (!empty($desa['lat']) && !empty($desa['lng']))
                    var posisi = [{{ $desa['lat'] . ',' . $desa['lng'] }}];
                    var zoom = {{ $desa['zoom'] ?: 10 }};
                @elseif (!empty($desa['path']))
                    var wilayah_desa = {{ $desa['path'] }};
                    var posisi = wilayah_desa[0][0];
                    var zoom = {{ $desa['zoom'] ?: 10 }};
                @else
                    var posisi = [-1.0546279422758742, 116.71875000000001];
                    var zoom = 10;
                @endif

                //Inisialisasi tampilan peta
                var peta = L.map('map', pengaturan_peta).setView(posisi, zoom);

                @if (!empty($desa['path']))
                    peta.fitBounds({{ $desa['path'] }});
                @endif

                //Menampilkan overlayLayers Peta Semua Wilayah
                var marker_desa = [];
                var marker_dusun = [];
                var marker_rw = [];
                var marker_rt = [];
                var semua_marker = [];
                var markers = new L.MarkerClusterGroup();
                var markersList = [];


                //OVERLAY WILAYAH DESA
                @if (!empty($desa['path']))
                    set_marker_desa_content(marker_desa, {!! json_encode($desa, JSON_THROW_ON_ERROR) !!},
                        "{{ ucwords(setting('sebutan_desa')) . ' ' . $desa['nama_desa'] }}",
                        "{{ favico_desa() }}", '#isi_popup');
                @endif

                //OVERLAY WILAYAH DUSUN
                @if (!empty($dusun_gis))
                    set_marker_multi_content(marker_dusun, '{!! addslashes(json_encode($dusun_gis, JSON_THROW_ON_ERROR)) !!}',
                        '{{ ucwords(setting('sebutan_dusun')) }}', 'dusun', '#isi_popup_dusun_',
                        '{{ favico_desa() }}');
                @endif

                //OVERLAY WILAYAH RW
                @if (!empty($rw_gis))
                    set_marker_content(marker_rw, '{!! addslashes(json_encode($rw_gis, JSON_THROW_ON_ERROR)) !!}', 'RW', 'rw', '#isi_popup_rw_',
                        '{{ favico_desa() }}');
                @endif

                //OVERLAY WILAYAH RT
                @if (!empty($rt_gis))
                    set_marker_content(marker_rt, '{!! addslashes(json_encode($rt_gis, JSON_THROW_ON_ERROR)) !!}', 'RT', 'rt', '#isi_popup_rt_',
                        '{{ favico_desa() }}');
                @endif

                //Menampilkan overlayLayers Peta Semua Wilayah
                var overlayLayers = overlayWil(marker_desa, marker_dusun, marker_rw, marker_rt,
                    "{{ ucwords(setting('sebutan_desa')) }}", "{{ ucwords(setting('sebutan_dusun')) }}", true,
                    TAMPIL_LUAS);
                //Menampilkan BaseLayers Peta
                var baseLayers = getBaseLayers(peta, MAPBOX_KEY, JENIS_PETA);

                //Geolocation IP Route/GPS
                geoLocation(peta);

                //Menambahkan zoom scale ke peta
                L.control.scale().addTo(peta);

                //Mencetak peta ke PNG
                cetakPeta(peta);

                //Menambahkan Legenda Ke Peta
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

                peta.on('overlayadd', function(eventLayer) {
                    if (eventLayer.name === 'Peta Wilayah Desa') {
                        setlegendPetaDesa(legenda_desa, peta, {!! json_encode($desa, JSON_THROW_ON_ERROR) !!},
                            '{{ ucwords(setting('sebutan_desa')) }}', '{{ $desa['nama_desa'] }}');
                    }
                    if (eventLayer.name === 'Peta Wilayah Dusun') {
                        setlegendPeta(legenda_dusun, peta, '{!! addslashes(json_encode($dusun_gis, JSON_THROW_ON_ERROR)) !!}',
                            '{{ ucwords(setting('sebutan_dusun')) }}', 'dusun', '', '');
                    }
                    if (eventLayer.name === 'Peta Wilayah RW') {
                        setlegendPeta(legenda_rw, peta, '{!! addslashes(json_encode($rw_gis, JSON_THROW_ON_ERROR)) !!}', 'RW', 'rw',
                            '{{ ucwords(setting('sebutan_dusun')) }}');
                    }
                    if (eventLayer.name === 'Peta Wilayah RT') {
                        setlegendPeta(legenda_rt, peta, '{!! addslashes(json_encode($rt_gis, JSON_THROW_ON_ERROR)) !!}', 'RT', 'rt', 'RW');
                    }
                });

                peta.on('overlayremove', function(eventLayer) {
                    if (eventLayer.name === 'Peta Wilayah Desa') {
                        peta.removeControl(legenda_desa);
                    }
                    if (eventLayer.name === 'Peta Wilayah Dusun') {
                        peta.removeControl(legenda_dusun);
                    }
                    if (eventLayer.name === 'Peta Wilayah RW') {
                        peta.removeControl(legenda_rw);
                    }
                    if (eventLayer.name === 'Peta Wilayah RT') {
                        peta.removeControl(legenda_rt);
                    }
                });

                // deklrasi variabel agar mudah di baca
                var all_area = '{!! addslashes(json_encode($area, JSON_THROW_ON_ERROR)) !!}';
                var all_garis = '{!! addslashes(json_encode($garis, JSON_THROW_ON_ERROR)) !!}';
                var all_lokasi = '{!! addslashes(json_encode($lokasi, JSON_THROW_ON_ERROR)) !!}';
                var all_lokasi_pembangunan = '{!! addslashes(json_encode($lokasi_pembangunan, JSON_THROW_ON_ERROR)) !!}';
                var LOKASI_SIMBOL_LOKASI = '{{ base_url() . LOKASI_SIMBOL_LOKASI }}';
                var favico_desa = '{{ favico_desa() }}';
                var LOKASI_FOTO_AREA = '{{ base_url(LOKASI_FOTO_AREA) }}';
                var LOKASI_FOTO_GARIS = '{{ base_url(LOKASI_FOTO_GARIS) }}';
                var LOKASI_FOTO_LOKASI = '{{ base_url(LOKASI_FOTO_LOKASI) }}';
                var LOKASI_GALERI = '{{ base_url(LOKASI_GALERI) }}';
                var info_pembangunan = '{{ ci_route('pembangunan') }}';
                var all_persil = '{!! addslashes(json_encode($persil, JSON_THROW_ON_ERROR)) !!}';

                // Menampilkan OverLayer Area, Garis, Lokasi plus Lokasi Pembangunan, persil
                var layerCustom = tampilkan_layer_area_garis_lokasi_plus(peta, all_area, all_garis, all_lokasi,
                    all_lokasi_pembangunan, LOKASI_SIMBOL_LOKASI, favico_desa, LOKASI_FOTO_AREA,
                    LOKASI_FOTO_GARIS, LOKASI_FOTO_LOKASI, LOKASI_GALERI, info_pembangunan, all_persil,
                    TAMPIL_LUAS);

                //PENDUDUK
                @if (!empty($penduduk))

                    var layer_penduduk = '{{ $layer_penduduk }}';
                    var layer_keluarga = '{{ $layer_keluarga }}';
                    var layer_rtm = '{{ $layer_rtm }}';

                    //Data penduduk
                    var penduduk = JSON.parse('{!! addslashes(json_encode($penduduk, JSON_THROW_ON_ERROR)) !!}');

                    var jml = penduduk.length;
                    var foto;
                    var content;
                    var point_style = L.icon({
                        iconUrl: '{{ base_url(LOKASI_SIMBOL_LOKASI . 'pend.png') }}',
                        iconSize: [22, 27],
                        iconAnchor: [11, 27],
                        popupAnchor: [0, -28],
                    });
                    for (var x = 0; x < jml; x++) {
                        if (penduduk[x].lat || penduduk[x].lng) {
                            foto =
                                `<td style="text-align: center;"><img class="foto_pend" src="{{ ci_route('penduduk.ambil_foto') }}?foto=${penduduk[x].foto}&sex=${penduduk[x].id_sex}" alt="Foto Penduduk"/></td>`;

                            if (layer_rtm == 1) {
                                info_lain = '<br/>Anggota Rumah Tangga : ' + penduduk[x].jumlah_anggota;
                                link_detail = '{{ ci_route('rtm.anggota') }}/' + penduduk[x].rtm.id;
                            } else if (layer_keluarga == 1) {
                                info_lain = '<br/>Anggota Keluarga : ' + penduduk[x].jumlah_anggota;
                                link_detail = '{{ ci_route('keluarga.anggota.1.0') }}/' + penduduk[x].id_kk;
                            } else {
                                info_lain = '';
                                link_detail = '{{ ci_route('penduduk.detail.1.0') }}/' + penduduk[x].id;
                            }

                            //Konten yang akan ditampilkan saat marker diklik
                            content =
                                '<table border=0 style="width:150px;max-width:200px"><tr>' + foto + '</tr>' +
                                '<tr><td style="text-align: center;">' +
                                '<p size="2.5" style="margin: 5px 0;"><b>' + penduduk[x].nama + '</b>' +
                                '<br/>' + penduduk[x].sex +
                                '<br/>' + penduduk[x].umur + ' Tahun ' +
                                '<br/>' + penduduk[x].agama +
                                '<br/>' + penduduk[x].alamat +
                                info_lain + '</p>' +
                                '<a class="btn btn-sm btn-primary" href="' + link_detail +
                                '" style="color:black;" target="ajax-modalx" rel="content" header="Rincian Data ' +
                                penduduk[x].nama + '" >Data Rincian</a></td>' +
                                '</tr></table>';
                            //Menambahkan point ke marker
                            semua_marker.push(turf.point([Number(penduduk[x].lng), Number(penduduk[x].lat)], {
                                content: content,
                                style: point_style
                            }));
                        }
                    }
                @endif
                if (semua_marker.length != 0) {
                    var geojson = L.geoJSON(turf.featureCollection(semua_marker), {
                        pmIgnore: true,
                        showMeasurements: true,
                        onEachFeature: function(feature, layer) {
                            layer.bindPopup(feature.properties.content);
                            layer.bindTooltip(feature.properties.content);
                        },
                        style: function(feature) {
                            if (feature.properties.style) {
                                return feature.properties.style;
                            }
                        },
                        pointToLayer: function(feature, latlng) {
                            if (feature.properties.style) {
                                return L.marker(latlng, {
                                    icon: feature.properties.style
                                });
                            } else
                                return L.marker(latlng);
                        }
                    });

                    markersList.push(geojson);
                    markers.addLayer(geojson);
                    peta.addLayer(markers);

                    //Mempusatkan tampilan map agar semua marker terlihat
                    peta.fitBounds(geojson.getBounds());
                }

                //Menampilkan Baselayer dan Overlayer
                var mainlayer = L.control.layers(baseLayers, overlayLayers, {
                    position: 'topleft',
                    collapsed: true
                }).addTo(peta);
                var customlayer = L.control.groupedLayers('', layerCustom, {
                    groupCheckboxes: true,
                    position: 'topleft',
                    collapsed: true
                }).addTo(peta);

                $('#isi_popup').remove();
                $('#isi_popup_dusun').remove();
                $('#isi_popup_rw').remove();
                $('#isi_popup_rt').remove();

            }; //EOF window.onload
        })();

        function handle_layer(cb) {
            $(':checkbox.layer-checkbox').not(cb).prop('checked', false)
            formAction('mainform_map', '{{ ci_route('gis.filter') }}');
        }

        function AmbilFotoLokasi(foto, ukuran = "kecil_") {
            ukuran_foto = ukuran || null
            file_foto = '{{ base_url(LOKASI_FOTO_LOKASI) }}' + ukuran_foto + foto;
            return file_foto;
        }
    </script>
    <script src="{{ asset('js/leaflet.filelayer.js') }}"></script>
    <script src="{{ asset('js/togeojson.js') }}"></script>
@endpush
