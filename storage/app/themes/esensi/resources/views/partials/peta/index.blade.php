@extends('theme::template')

@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.4.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('bootstrap/css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/leaflet-measure-path.css') }}">
    <link rel="stylesheet" href="{{ asset('css/MarkerCluster.css') }}">
    <link rel="stylesheet" href="{{ asset('css/MarkerCluster.Default.css') }}">
    <link rel="stylesheet" href="{{ asset('css/leaflet.groupedlayercontrol.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/leaflet.fullscreen.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/peta.css') }}">
    <style>
        #map {
            width: 100%;
            height: 88vh !important;
        }

        #map .leaflet-popup-content {
            height: auto;
            overflow-y: auto;
        }

        table {
            table-layout: fixed;
            white-space: normal !important;
        }

        td {
            word-wrap: break-word;
        }

        .persil {
            min-width: 350px;
        }

        .persil td {
            padding-right: 1rem;
        }
    </style>
@endpush

@section('layout')
    <main id="main-peta" class="container w-full space-y-1 text-gray-600">
        <div class="page-title text-center">
            <h2 class="text-3xl font-bold text-bold my-0 pt-6 pb-2">Peta {{ ucwords(setting('sebutan_desa')) }} {{ ucwords(identitas('nama_desa')) }}</h2>
            <a href="{{ ci_route('') }}" class="inline-block">Kembali ke Beranda</a>
        </div>
        <div class="row">
            <div class="col-md-12">
                @include('theme::commons.loading')
                <div id="map">
                    <div class="leaflet-top leaflet-left">
                        <div id="isi_popup" style="visibility: hidden;">
                            <div id="content">
                                <h5 id="firstHeading" class="firstHeading"></h5>
                                <div id="bodyContent">

                                </div>
                            </div>
                        </div>
                        <div id="isi_popup_dusun"></div>
                        <div id="isi_popup_rw"></div>
                        <div id="isi_popup_rt"></div>
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
    </main>

    <div class="modal fade" id="modalKecil" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="false">
        <div class="modal-dialog modal-sm">
            <div class='modal-content'>
                <div class='modal-header'>
                    <button type='button' class='close' data-dismiss='modal' aria-hidden='true'>&times;</button>
                    <h4 class='modal-title' id='myModalLabel'></h4>
                </div>
                <div class="fetched-data"></div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalSedang" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="false">
        <div class="modal-dialog">
            <div class='modal-content'>
                <div class='modal-header'>
                    <button type='button' class='close' data-dismiss='modal' aria-hidden='true'>&times;</button>
                    <h4 class='modal-title' id='myModalLabel'></h4>
                </div>
                <div class="fetched-data"></div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalBesar" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="false">
        <div class="modal-dialog modal-lg">
            <div class='modal-content'>
                <div class='modal-header'>
                    <button type='button' class='close' data-dismiss='modal' aria-hidden='true'>&times;</button>
                    <h4 class='modal-title' id='myModalLabel'><i class='fa fa-exclamation-triangle text-red'></i></h4>
                </div>
                <div class="fetched-data"></div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    @include('theme::commons.asset_highcharts')
    <script src="{{ theme_asset('js/helper.js') }}"></script>
    <script>
        (function() {
            let infoWindow;
            window.onload = function() {
                const _url = `{{ ci_route('internal_api.peta') }}`
                // remove header dan footer
                $('#main-peta').siblings('.container').remove()
                $.get(_url, {}, function(json) {
                    generatePopupDesa(json.data[0].attributes)
                    generatePopupDusun(json.data[0].attributes)
                    generatePopupRw(json.data[0].attributes)
                    generatePopupRt(json.data[0].attributes)
                    generatePeta(json.data[0].attributes)

                    $('#isi_popup_dusun').remove();
                    $('#isi_popup_rw').remove();
                    $('#isi_popup_rt').remove();
                    $('#isi_popup').remove();
                    $('.spinner-grow').parent().remove()
                })

                const generatePopupDesa = function(data) {
                    let _listLink = [],
                        _elmPopup
                    const _link = '{{ ci_route('statistik_web.chart_gis_desa') }}'
                    _elmPopup = document.getElementById('isi_popup')
                    _elmPopup.querySelector('#content').querySelector('#firstHeading').innerHTML = `Wilayah {{ ucwords(setting('sebutan_desa')) }} ${data.desa.nama_desa}`
                    const _title = `Statistik Penduduk {{ ucwords(setting('sebutan_desa')) }} ${capitalizeFirstCharacterOfEachWord(data.desa.nama_desa)}`
                    // statistik penduduk
                    if (data.pengaturan.includes('Statistik Penduduk')) {
                        _listLink = []
                        for (let key in data.list_ref) {
                            _listLink.push(`<li><a href="${_link}/${key}/${data.desa.nama_desa.replace(/\s+/g, '_')}" data-remote="false" data-toggle="modal" data-target="#modalSedang" data-title="Statistik Penduduk ${_title}" >${data.list_ref[key]}</a></li>`)
                        }
                        const _listStatistikPenduduk = `<p><a href="#collapseStatPenduduk" class="btn btn-social bg-navy btn-sm btn-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Statistik Penduduk" data-toggle="collapse" data-target="#collapseStatPenduduk" aria-expanded="false" aria-controls="collapseStatPenduduk"><i class="fa fa-bar-chart"></i>&nbsp;&nbsp;Statistik Penduduk&nbsp;&nbsp;</a></p>
          <div class="collapse box-body no-padding" id="collapseStatPenduduk">
            <div class="card card-body">
              <ul>
              ${_listLink.join('')}
              </ul>
            </div>
          </div>`
                        _elmPopup.querySelector('#content').querySelector('#bodyContent').innerHTML += _listStatistikPenduduk
                    }
                    // statistik bantuan
                    if (data.pengaturan.includes('Statistik Bantuan')) {
                        _listLink = []
                        for (let key in data.list_bantuan) {
                            _listLink.push(`<li><a href="${_link}/${key}/${data.desa.nama_desa.replace(/\s+/g, '_')}" data-remote="false" data-toggle="modal" data-target="#modalSedang" data-title="Statistik Bantuan ${_title}">${data.list_bantuan[key]}</a></li>`)
                        }
                        const _listStatistikBantuan = `<p><a href="#collapseStatBantuan" class="btn btn-social bg-navy btn-sm btn-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Statistik Bantuan" data-toggle="collapse" data-target="#collapseStatBantuan" aria-expanded="false" aria-controls="collapseStatBantuan"><i class="fa fa-heart"></i>&nbsp;&nbsp;Statistik Bantuan&nbsp;&nbsp;</a></p>
          <div class="collapse box-body no-padding" id="collapseStatBantuan">
            <div class="card card-body">
              <ul>
              ${_listLink.join('')}
              </ul>
            </div>
          </div>`
                        _elmPopup.querySelector('#content').querySelector('#bodyContent').innerHTML += _listStatistikBantuan
                    }
                    // statistik aparatur
                    if (data.pengaturan.includes('Aparatur Desa')) {
                        _elmPopup.querySelector('#content').querySelector('#bodyContent').innerHTML +=
                            `<p><a href="{{ ci_route('load_aparatur_desa') }}" class="btn btn-social bg-navy btn-sm btn-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" data-title="{{ ucwords(setting('sebutan_pemerintah_desa')) }}" data-remote="false" data-toggle="modal" data-target="#modalKecil"><i class="fa fa-user"></i>&nbsp;&nbsp;{{ ucwords(setting('sebutan_pemerintah_desa')) }}&nbsp;&nbsp;</a></p>`
                    }
                }
                const generatePopupDusun = function(data) {
                    const _elmPopup = document.getElementById('isi_popup_dusun')
                    const _link = '{{ ci_route('statistik_web.chart_gis_dusun') }}'
                    const _title = `{{ ucwords(setting('sebutan_desa')) }} ${capitalizeFirstCharacterOfEachWord(data.desa.nama_desa)}`
                    const _wilayah = {
                        level: 1,
                        key: 'dusun',
                        sebutan: "{{ ucwords(setting('sebutan_kepala_dusun')) }}",
                        div_parent: 'isi_popup_dusun'
                    }
                    _elmPopup.innerHTML = generatePopupElement(data, data.pengaturan, data.dusun_gis, _link, _title, _wilayah)
                }
                const generatePopupRw = function(data) {
                    const _elmPopup = document.getElementById('isi_popup_rw')
                    const _link = '{{ ci_route('statistik_web.chart_gis_rw') }}'
                    const _title = `{{ ucwords(setting('sebutan_dusun')) }}`
                    const _wilayah = {
                        level: 2,
                        key: 'rw',
                        sebutan: "RW",
                        div_parent: 'isi_popup_rw'
                    }
                    _elmPopup.innerHTML = generatePopupElement(data, data.pengaturan, data.rw_gis, _link, _title, _wilayah)
                }
                const generatePopupRt = function(data) {
                    const _elmPopup = document.getElementById('isi_popup_rt')
                    const _link = '{{ ci_route('statistik_web.chart_gis_rt') }}'
                    const _title = `{{ ucwords(setting('sebutan_dusun')) }}`
                    const _wilayah = {
                        level: 3,
                        key: 'rt',
                        sebutan: "RT",
                        div_parent: 'isi_popup_rt'
                    }
                    _elmPopup.innerHTML = generatePopupElement(data, data.pengaturan, data.rt_gis, _link, _title, _wilayah)
                }
                const generatePopupElement = function(data, pengaturan, gis, _link, _title, _wilayah) {
                    let _listLink = [],
                        _params, _newTitle
                    let _parentElementHTML = ``,
                        _elemenHTML, _contentHTML = ``,
                        _listStatistikPenduduk, _listStatistikBantuan

                    for (let _key in gis) {
                        _elemenHTML = ``
                        _contentHTML = ``
                        switch (_wilayah['key']) {
                            case 'dusun':
                                _params = underscore(gis[_key]['dusun'])
                                _newTitle = `${_title} ${capitalizeFirstCharacterOfEachWord(gis[_key]['dusun'])}`
                                break;
                            case 'rw':
                                _params = `${underscore(gis[_key]['dusun'])}/${underscore(gis[_key]['rw'])}`
                                _newTitle = `RW ${capitalizeFirstCharacterOfEachWord(gis[_key]['rw'])} ${_title} ${capitalizeFirstCharacterOfEachWord(gis[_key]['dusun'])}`
                                break;
                            case 'rt':
                                _params = `${underscore(gis[_key]['dusun'])}/${underscore(gis[_key]['rw'])}/${underscore(gis[_key]['rt'])}`
                                _newTitle = `RT ${capitalizeFirstCharacterOfEachWord(gis[_key]['rt'])} RW ${capitalizeFirstCharacterOfEachWord(gis[_key]['rw'])} ${_title} ${capitalizeFirstCharacterOfEachWord(gis[_key]['dusun'])}`
                                break;
                        }

                        // statistik penduduk
                        if (pengaturan.includes('Statistik Penduduk')) {
                            _listLink = []
                            for (let key in data.list_ref) {
                                _listLink.push(`<li><a href="${_link}/${key}/${_params}" data-remote="false" data-toggle="modal" data-target="#modalSedang" data-title="Statistik Penduduk ${_newTitle}" >${data.list_ref[key]}</a></li>`)
                            }
                            _listStatistikPenduduk = `<p><a href="#collapseStatPenduduk" class="btn btn-social bg-navy btn-sm btn-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Statistik Penduduk" data-toggle="collapse" data-target="#collapseStatPenduduk" aria-expanded="false" aria-controls="collapseStatPenduduk"><i class="fa fa-bar-chart"></i>&nbsp;&nbsp;Statistik Penduduk&nbsp;&nbsp;</a></p>
            <div class="collapse box-body no-padding" id="collapseStatPenduduk">
              <div class="card card-body">
                <ul>
                ${_listLink.join('')}
                </ul>
              </div>
            </div>`
                            _contentHTML += _listStatistikPenduduk
                        }
                        // statistik bantuan
                        if (pengaturan.includes('Statistik Bantuan')) {
                            _listLink = []
                            for (let key in data.list_bantuan) {
                                _listLink.push(`<li><a href="${_link}/${key}/${_params}" data-remote="false" data-toggle="modal" data-target="#modalSedang" data-title="Statistik Bantuan ${_newTitle}">${data.list_bantuan[key]}</a></li>`)
                            }
                            _listStatistikBantuan = `<p><a href="#collapseStatBantuan" class="btn btn-social bg-navy btn-sm btn-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Statistik Bantuan" data-toggle="collapse" data-target="#collapseStatBantuan" aria-expanded="false" aria-controls="collapseStatBantuan"><i class="fa fa-heart"></i>&nbsp;&nbsp;Statistik Bantuan&nbsp;&nbsp;</a></p>
            <div class="collapse box-body no-padding" id="collapseStatBantuan">
              <div class="card card-body">
                <ul>
                ${_listLink.join('')}
                </ul>
              </div>
            </div>`
                            _contentHTML += _listStatistikBantuan
                        }
                        // statistik aparatur
                        if (pengaturan.includes('Aparatur Desa')) {
                            _contentHTML +=
                                `<p><a href="{{ ci_route('load_aparatur_wilayah') }}/${gis[_key]['id_kepala']}/${_wilayah['level']}" class="btn btn-social bg-navy btn-sm btn-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" data-title="{{ ucwords(setting('sebutan_kepala_dusun')) . ' ' . $dusun['dusun'] }}" data-remote="false" data-toggle="modal" data-target="#modalKecil"><i class="fa fa-user"></i>&nbsp;&nbsp;${_wilayah['sebutan']}&nbsp;&nbsp;</a></p>`
                        }
                        _elemenHTML = `
          <div id="${_wilayah['div_parent']}_${_key}" style="visibility: hidden;">
            <div id="content">
              <h5 id="firstHeading" class="firstHeading">Wilayah ${_newTitle}</h5>
                <div id="bodyContent">
                  ${_contentHTML}
                </div>
            </div>
          </div>`

                        _parentElementHTML += _elemenHTML
                    }
                    return _parentElementHTML
                }
                const generatePeta = function(data) {
                    let posisi = [-1.0546279422758742, 116.71875000000001];
                    let zoom = 10;
                    let wilayah_desa;
                    let options = {
                        maxZoom: {{ setting('max_zoom_peta') }},
                        minZoom: {{ setting('min_zoom_peta') }},
                        fullscreenControl: {
                            position: 'topright' // Menentukan posisi tombol fullscreen
                        }
                    }
                    if (data.desa['lat'] && data.desa['lng']) {
                        posisi = [data.desa['lat'], data.desa['lng']]
                        zoom = data.desa['zoom'] ?? 10
                    } else if (data.desa['path']) {
                        wilayah_desa = data.desa['path'];
                        posisi = wilayah_desa[0][0];
                        zoom = data.desa['zoom'] ?? 10
                    }
                    //Inisialisasi tampilan peta
                    const mymap = L.map('map', options).setView(posisi, zoom);
                    if (data.desa['path']) {
                        mymap.fitBounds(JSON.parse(data.desa.path));
                    }

                    //Menampilkan overlayLayers Peta Semua Wilayah
                    let marker_desa = [];
                    let marker_dusun = [];
                    let marker_rw = [];
                    let marker_rt = [];
                    let marker_area = [];
                    let marker_garis = [];
                    let marker_lokasi = [];
                    let markers = new L.MarkerClusterGroup();
                    let markersList = [];
                    let marker_legend = [];
                    let mark_desa = [];
                    let mark_covid = [];

                    // deklrasi variabel agar mudah di baca
                    let all_area = JSON.stringify(data.area)
                    let all_garis = JSON.stringify(data.garis)
                    let all_lokasi = JSON.stringify(data.lokasi)
                    let all_lokasi_pembangunan = JSON.stringify(data.lokasi_pembangunan)
                    let LOKASI_SIMBOL_LOKASI = '{{ base_url(LOKASI_SIMBOL_LOKASI) }}';
                    let favico_desa = '{{ favico_desa() }}';
                    let LOKASI_FOTO_AREA = '{{ base_url(LOKASI_FOTO_AREA) }}';
                    let LOKASI_FOTO_GARIS = '{{ base_url(LOKASI_FOTO_GARIS) }}';
                    let LOKASI_FOTO_LOKASI = '{{ base_url(LOKASI_FOTO_LOKASI) }}';
                    let LOKASI_GALERI = '{{ base_url(LOKASI_GALERI) }}';
                    let info_pembangunan = '{{ ci_route('pembangunan') }}';
                    let all_persil = JSON.stringify(data.persil)
                    let TAMPIL_LUAS = '{{ setting('tampil_luas_peta') }}';
                    let PENGATURAN_WILAYAH = '{!! SebutanDesa(setting('default_tampil_peta_wilayah')) ?: [] !!}';
                    let PENGATURAN_INFRASTRUKTUR = '{!! SebutanDesa(setting('default_tampil_peta_infrastruktur')) ?: [] !!}';
                    let WILAYAH_INFRASTRUKTUR = PENGATURAN_WILAYAH.concat(PENGATURAN_INFRASTRUKTUR);

                    //OVERLAY WILAYAH DESA
                    if (data.desa['path']) {
                        set_marker_desa_content(marker_desa, data.desa, "{{ ucwords(setting('sebutan_desa')) }} ${data.desa['nama_desa']}", "{{ favico_desa() }}", '#isi_popup');
                    }

                    //OVERLAY WILAYAH DUSUN
                    if (data.dusun_gis) {
                        set_marker_multi_content(marker_dusun, JSON.stringify(data.dusun_gis), '{{ ucwords(setting('sebutan_dusun')) }}', 'dusun', '#isi_popup_dusun_', '{{ favico_desa() }}');
                    }

                    //OVERLAY WILAYAH RW
                    if (data.rw_gis) {
                        set_marker_content(marker_rw, JSON.stringify(data.rw_gis), 'RW', 'rw', '#isi_popup_rw_', '{{ favico_desa() }}');
                    }

                    //OVERLAY WILAYAH RT
                    if (data.rt_gis) {
                        set_marker_content(marker_rt, JSON.stringify(data.rt_gis), 'RT', 'rt', '#isi_popup_rt_', '{{ favico_desa() }}');
                    }

                    //Menampilkan overlayLayers Peta Semua Wilayah
                    let overlayLayers = overlayWil(
                        marker_desa,
                        marker_dusun,
                        marker_rw,
                        marker_rt,
                        "{{ ucwords(setting('sebutan_desa')) }}",
                        "{{ ucwords(setting('sebutan_dusun')) }}",
                        true,
                        TAMPIL_LUAS.toString()
                    );

                    //Menampilkan BaseLayers Peta
                    let baseLayers = getBaseLayers(mymap, "{{ setting('mapbox_key') }}", "{{ setting('jenis_peta') }}");

                    //Geolocation IP Route/GPS
                    geoLocation(mymap);

                    //Menambahkan zoom scale ke peta
                    L.control.scale().addTo(mymap);

                    //Mencetak peta ke PNG
                    cetakPeta(mymap);

                    //Menambahkan Legenda Ke Peta
                    let legenda_desa = L.control({
                        position: 'bottomright'
                    });
                    let legenda_dusun = L.control({
                        position: 'bottomright'
                    });
                    let legenda_rw = L.control({
                        position: 'bottomright'
                    });
                    let legenda_rt = L.control({
                        position: 'bottomright'
                    });

                    mymap.on('overlayadd', function(eventLayer) {
                        if (eventLayer.name === 'Peta Wilayah Desa') {
                            setlegendPetaDesa(legenda_desa, mymap, data.desa, '{{ ucwords(setting('sebutan_desa')) }}', data.desa['nama_desa']);
                        }
                        if (eventLayer.name === 'Peta Wilayah Dusun') {
                            setlegendPeta(legenda_dusun, mymap, JSON.stringify(data.dusun_gis), '{{ ucwords(setting('sebutan_dusun')) }}', 'dusun', '', '');
                        }
                        if (eventLayer.name === 'Peta Wilayah RW') {
                            setlegendPeta(legenda_rw, mymap, JSON.stringify(data.rw_gis), 'RW', 'rw', '{{ ucwords(setting('sebutan_dusun')) }}');
                        }
                        if (eventLayer.name === 'Peta Wilayah RT') {
                            setlegendPeta(legenda_rt, mymap, JSON.stringify(data.rt_gis), 'RT', 'rt', 'RW');
                        }
                    });

                    mymap.on('overlayremove', function(eventLayer) {
                        if (eventLayer.name === 'Peta Wilayah Desa') {
                            mymap.removeControl(legenda_desa);
                        }
                        if (eventLayer.name === 'Peta Wilayah Dusun') {
                            mymap.removeControl(legenda_dusun);
                        }
                        if (eventLayer.name === 'Peta Wilayah RW') {
                            mymap.removeControl(legenda_rw);
                        }
                        if (eventLayer.name === 'Peta Wilayah RT') {
                            mymap.removeControl(legenda_rt);
                        }
                    });

                    // Menampilkan OverLayer Area, Garis, Lokasi plus Lokasi Pembangunan
                    let layerCustom = tampilkan_layer_area_garis_lokasi_plus(
                        mymap,
                        all_area,
                        all_garis,
                        all_lokasi,
                        all_lokasi_pembangunan,
                        LOKASI_SIMBOL_LOKASI,
                        favico_desa,
                        LOKASI_FOTO_AREA,
                        LOKASI_FOTO_GARIS,
                        LOKASI_FOTO_LOKASI,
                        LOKASI_GALERI,
                        info_pembangunan,
                        all_persil,
                        TAMPIL_LUAS.toString()
                    );

                    L.control.layers(baseLayers, overlayLayers, {
                        position: 'topleft',
                        collapsed: true
                    }).addTo(mymap);
                    L.control.groupedLayers('', layerCustom, {
                        groupCheckboxes: true,
                        position: 'topleft',
                        collapsed: true
                    }).addTo(mymap);
                    let labelCheckbox
                    $('input[type=checkbox]').each(function() {
                        labelCheckbox = $(this).next().text().trim()
                        if (WILAYAH_INFRASTRUKTUR.includes(labelCheckbox)) {
                            $(this).click();
                        }
                        if (labelCheckbox == 'Letter C-Desa') {
                            if (data.tampilkan_cdesa != 1) {
                                $(this).parent().remove()
                            }
                        }
                    });
                }

            }; //EOF window.onload

        })();
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.js"></script>
    <script src="{{ asset('js/Leaflet.fullscreen.min.js') }}"></script>
    <script src="{{ asset('js/turf.min.js') }}"></script>
    <script src="{{ asset('js/leaflet-providers.js') }}"></script>
    <script src="{{ asset('js/L.Control.Locate.min.js') }}"></script>
    <script src="{{ asset('js/leaflet-measure-path.js') }}"></script>
    <script src="{{ asset('js/leaflet.markercluster.js') }}"></script>
    <script src="{{ asset('js/leaflet.groupedlayercontrol.min.js') }}"></script>
    <script src="{{ asset('js/leaflet.browser.print.js') }}"></script>
    <script src="{{ asset('js/leaflet.browser.print.utils.js') }}"></script>
    <script src="{{ asset('js/leaflet.browser.print.sizes.js') }}"></script>
    <script src="{{ asset('js/dom-to-image.min.js') }}"></script>
    <script src="{{ asset('js/script.js') }}"></script>
@endpush
