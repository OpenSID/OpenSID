@extends('theme::template')

@push('styles')
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
        <h2 class="text-3xl font-bold text-bold my-0 pt-6 pb-2">Peta {{ ucwords(setting('sebutan_desa')) }} {{
            ucwords(identitas('nama_desa')) }}</h2>
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
                            <div id="bodyContent"></div>
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

<div class="modal fade" id="modalKecil" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false">
    <div class="modal-dialog modal-sm">
        <div class='modal-content'>
            <div class='modal-header'>
                <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                    <span aria-hidden='true'>&times;</span>
                </button>
                <h4 class='modal-title' id='myModalLabel'></h4>
            </div>
            <div class="fetched-data"></div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalSedang" role="dialog" aria-labelledby="myModalLabel2" data-backdrop="false">
    <div class="modal-dialog">
        <div class='modal-content'>
            <div class='modal-header'>
                <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                    <span aria-hidden='true'>&times;</span>
                </button>
                <h4 class='modal-title' id='myModalLabel2'></h4>
            </div>
            <div class="fetched-data"></div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalBesar" role="dialog" aria-labelledby="myModalLabel3" data-backdrop="false">
    <div class="modal-dialog modal-lg">
        <div class='modal-content'>
            <div class='modal-header'>
                <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                    <span aria-hidden='true'>&times;</span>
                </button>
                <h4 class='modal-title' id='myModalLabel3'><i class='fa fa-exclamation-triangle text-red'></i></h4>
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
        'use strict';

        window.onload = function() {
            const _url = `{{ ci_route('internal_api.peta') }}`;

            $.get(_url, {}, function(json) {
                if (!json || !json.data || !json.data[0]) {
                    console.error('Invalid API response');
                    $('.spinner-grow').parent().remove();
                    return;
                }

                const data = json.data[0].attributes;

                generatePopupDesa(data);
                generatePopupDusun(data);
                generatePopupRw(data);
                generatePopupRt(data);
                generatePeta(data);

                $('#isi_popup_dusun').remove();
                $('#isi_popup_rw').remove();
                $('#isi_popup_rt').remove();
                $('#isi_popup').remove();
                $('.spinner-grow').parent().remove();

                // Force resize after load
                setTimeout(() => {
                    if (window.mymap) {
                        window.mymap.invalidateSize();
                    }
                    window.dispatchEvent(new Event('resize'));
                }, 500);
            }).fail(function() {
                console.error('Failed to fetch map data');
                $('.spinner-grow').parent().remove();
            });

            const generatePopupDesa = function(data) {
                let _listLink = [],
                    _elmPopup;
                const _link = '{{ ci_route('statistik_web.chart_gis_desa') }}';
                _elmPopup = document.getElementById('isi_popup');
                _elmPopup.querySelector('#content').querySelector('#firstHeading').innerHTML = `Wilayah {{ ucwords(setting('sebutan_desa')) }} ${data.desa.nama_desa}`;

                let _pengaturan = typeof data.pengaturan === 'string' ? JSON.parse(data.pengaturan) : data.pengaturan;
                let _html = '';

                if (_pengaturan.includes('Statistik Penduduk')) {
                    for (let id in data.list_ref) {
                        _listLink.push({
                            link: `${_link}/${id}/${underscore(data.desa.nama_desa)}`,
                            judul: `${data.list_ref[id]} Wilayah {{ ucwords(setting('sebutan_desa')) }} ${data.desa.nama_desa}`
                        });
                    }
                    _html += generateStatistik('collapseStatPenduduk', 'bar-chart', 'Statistik Penduduk', _listLink);
                }

                if (_pengaturan.includes('Statistik Bantuan')) {
                    let _listBantuan = [];
                    for (let id in data.list_bantuan) {
                        _listBantuan.push({
                            link: `${_link}/${id}/${underscore(data.desa.nama_desa)}`,
                            judul: `${data.list_bantuan[id]} Wilayah {{ ucwords(setting('sebutan_desa')) }} ${data.desa.nama_desa}`
                        });
                    }
                    _html += generateStatistik('collapseStatBantuan', 'heart', 'Statistik Bantuan', _listBantuan);
                }

                if (_pengaturan.includes('Aparatur Desa')) {
                    _html += `
                        <p>
                            <a href="{{ ci_route('load_aparatur_desa') }}" class="btn btn-social bg-navy btn-sm btn-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" data-title="{{ ucwords(setting('sebutan_pemerintah_desa')) }}" data-remote="false" data-toggle="modal" data-target="#modalKecil">
                                <i class="fa fa-user"></i>&nbsp;&nbsp;{{ ucwords(setting('sebutan_pemerintah_desa')) }}&nbsp;&nbsp;
                            </a>
                        </p>
                    `;
                }

                _elmPopup.querySelector('#content').querySelector('#bodyContent').innerHTML = _html;
            };

            const generatePopupDusun = function(data) {
                let _elmPopup = document.getElementById('isi_popup_dusun');
                const _link = '{{ ci_route('statistik_web.chart_gis_dusun') }}';
                const _linkAparatur = '{{ ci_route('load_aparatur_wilayah') }}';
                const _sebutan_dusun = '{{ ucwords(setting('sebutan_dusun')) }}';

                let _pengaturan = typeof data.pengaturan === 'string' ? JSON.parse(data.pengaturan) : data.pengaturan;
                let _html = '';

                if (data.dusun_gis) {
                    data.dusun_gis.forEach((item, index) => {
                        let _listLink = [];
                        let _subHtml = '';

                        if (_pengaturan.includes('Statistik Penduduk')) {
                            for (let id in data.list_ref) {
                                _listLink.push({
                                    link: `${_link}/${id}/${underscore(item.dusun)}`,
                                    judul: `${data.list_ref[id]} ${_sebutan_dusun} ${capitalizeFirstCharacterOfEachWord(item.dusun)}`
                                });
                            }
                            _subHtml += generateStatistik('collapseStatPenduduk', 'bar-chart', 'Statistik Penduduk', _listLink);
                        }

                        if (_pengaturan.includes('Statistik Bantuan')) {
                            let _listBantuan = [];
                            for (let id in data.list_bantuan) {
                                _listBantuan.push({
                                    link: `${_link}/${id}/${underscore(item.dusun)}`,
                                    judul: `${data.list_bantuan[id]} ${_sebutan_dusun} ${capitalizeFirstCharacterOfEachWord(item.dusun)}`
                                });
                            }
                            _subHtml += generateStatistik('collapseStatBantuan', 'heart', 'Statistik Bantuan', _listBantuan);
                        }

                        if (_pengaturan.includes('Aparatur Desa') && item.id_kepala) {
                            _subHtml += `
                                <p>
                                    <a href="${_linkAparatur}/${item.id_kepala}/1" class="btn btn-social bg-navy btn-sm btn-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" data-title="{{ ucwords(setting('sebutan_kepala_dusun')) }}" data-remote="false" data-toggle="modal" data-target="#modalKecil">
                                        <i class="fa fa-user"></i>&nbsp;&nbsp;{{ ucwords(setting('sebutan_kepala_dusun')) }}&nbsp;&nbsp;
                                    </a>
                                </p>
                            `;
                        }

                        _html += `
                            <div id="isi_popup_dusun_${index}" style="visibility: hidden;">
                                <div id="content">
                                    <h5 id="firstHeading" class="firstHeading">Wilayah {{ ucwords(setting('sebutan_desa')) }} ${capitalizeFirstCharacterOfEachWord(item.dusun)}</h5>
                                    <div id="bodyContent">${_subHtml}</div>
                                </div>
                            </div>
                        `;
                    });
                }
                _elmPopup.innerHTML = _html;
            };

            const generatePopupRw = function(data) {
                let _elmPopup = document.getElementById('isi_popup_rw');
                const _link = '{{ ci_route('statistik_web.chart_gis_rw') }}';
                const _linkAparatur = '{{ ci_route('load_aparatur_wilayah') }}';
                const _sebutan_dusun = '{{ ucwords(setting('sebutan_dusun')) }}';

                let _pengaturan = typeof data.pengaturan === 'string' ? JSON.parse(data.pengaturan) : data.pengaturan;
                let _html = '';

                if (data.rw_gis) {
                    data.rw_gis.forEach((item, index) => {
                        let _listLink = [];
                        let _subHtml = '';

                        if (_pengaturan.includes('Statistik Penduduk')) {
                            for (let id in data.list_ref) {
                                _listLink.push({
                                    link: `${_link}/${id}/${underscore(item.dusun)}/${underscore(item.rw)}`,
                                    judul: `${data.list_ref[id]} RW ${capitalizeFirstCharacterOfEachWord(item.rw)} ${_sebutan_dusun} ${capitalizeFirstCharacterOfEachWord(item.dusun)}`
                                });
                            }
                            _subHtml += generateStatistik('collapseStatPenduduk', 'bar-chart', 'Statistik Penduduk', _listLink);
                        }

                        if (_pengaturan.includes('Statistik Bantuan')) {
                            let _listBantuan = [];
                            for (let id in data.list_bantuan) {
                                _listBantuan.push({
                                    link: `${_link}/${id}/${underscore(item.dusun)}/${underscore(item.rw)}`,
                                    judul: `${data.list_bantuan[id]} RW ${capitalizeFirstCharacterOfEachWord(item.rw)} ${_sebutan_dusun} ${capitalizeFirstCharacterOfEachWord(item.dusun)}`
                                });
                            }
                            _subHtml += generateStatistik('collapseStatBantuan', 'heart', 'Statistik Bantuan', _listBantuan);
                        }

                        if (_pengaturan.includes('Aparatur Desa') && item.id_kepala) {
                            _subHtml += `
                                <p>
                                    <a href="${_linkAparatur}/${item.id_kepala}/2" class="btn btn-social bg-navy btn-sm btn-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" data-title="Ketua RW" data-remote="false" data-toggle="modal" data-target="#modalKecil">
                                        <i class="fa fa-user"></i>&nbsp;&nbsp;Ketua RW&nbsp;&nbsp;
                                    </a>
                                </p>
                            `;
                        }

                        _html += `
                            <div id="isi_popup_rw_${index}" style="visibility: hidden;">
                                <div id="content">
                                    <h5 id="firstHeading" class="firstHeading">RW ${capitalizeFirstCharacterOfEachWord(item.rw)} ${_sebutan_dusun} ${capitalizeFirstCharacterOfEachWord(item.dusun)}</h5>
                                    <div id="bodyContent">${_subHtml}</div>
                                </div>
                            </div>
                        `;
                    });
                }
                _elmPopup.innerHTML = _html;
            };

            const generatePopupRt = function(data) {
                let _elmPopup = document.getElementById('isi_popup_rt');
                const _link = '{{ ci_route('statistik_web.chart_gis_rt') }}';
                const _linkAparatur = '{{ ci_route('load_aparatur_wilayah') }}';
                const _sebutan_dusun = '{{ ucwords(setting('sebutan_dusun')) }}';

                let _pengaturan = typeof data.pengaturan === 'string' ? JSON.parse(data.pengaturan) : data.pengaturan;
                let _html = '';

                if (data.rt_gis) {
                    data.rt_gis.forEach((item, index) => {
                        let _listLink = [];
                        let _subHtml = '';

                        if (_pengaturan.includes('Statistik Penduduk')) {
                            for (let id in data.list_ref) {
                                _listLink.push({
                                    link: `${_link}/${id}/${underscore(item.dusun)}/${underscore(item.rw)}/${underscore(item.rt)}`,
                                    judul: `${data.list_ref[id]} RT ${capitalizeFirstCharacterOfEachWord(item.rt)} RW ${capitalizeFirstCharacterOfEachWord(item.rw)} ${_sebutan_dusun} ${capitalizeFirstCharacterOfEachWord(item.dusun)}`
                                });
                            }
                            _subHtml += generateStatistik('collapseStatPenduduk', 'bar-chart', 'Statistik Penduduk', _listLink);
                        }

                        if (_pengaturan.includes('Statistik Bantuan')) {
                            let _listBantuan = [];
                            for (let id in data.list_bantuan) {
                                _listBantuan.push({
                                    link: `${_link}/${id}/${underscore(item.dusun)}/${underscore(item.rw)}/${underscore(item.rt)}`,
                                    judul: `${data.list_bantuan[id]} RT ${capitalizeFirstCharacterOfEachWord(item.rt)} RW ${capitalizeFirstCharacterOfEachWord(item.rw)} ${_sebutan_dusun} ${capitalizeFirstCharacterOfEachWord(item.dusun)}`
                                });
                            }
                            _subHtml += generateStatistik('collapseStatBantuan', 'heart', 'Statistik Bantuan', _listBantuan);
                        }

                        if (_pengaturan.includes('Aparatur Desa') && item.id_kepala) {
                            _subHtml += `
                                <p>
                                    <a href="${_linkAparatur}/${item.id_kepala}/3" class="btn btn-social bg-navy btn-sm btn-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" data-title="Ketua RT" data-remote="false" data-toggle="modal" data-target="#modalKecil">
                                        <i class="fa fa-user"></i>&nbsp;&nbsp;Ketua RT&nbsp;&nbsp;
                                    </a>
                                </p>
                            `;
                        }

                        _html += `
                            <div id="isi_popup_rt_${index}" style="visibility: hidden;">
                                <div id="content">
                                    <h5 id="firstHeading" class="firstHeading">RT ${capitalizeFirstCharacterOfEachWord(item.rt)} RW ${capitalizeFirstCharacterOfEachWord(item.rw)} ${_sebutan_dusun} ${capitalizeFirstCharacterOfEachWord(item.dusun)}</h5>
                                    <div id="bodyContent">${_subHtml}</div>
                                </div>
                            </div>
                        `;
                    });
                }
                _elmPopup.innerHTML = _html;
            };

            const generatePeta = function(data) {
                let posisi = [-1.0546279422758742, 116.71875000000001];
                let zoom = 10;

                if (data.desa['lat'] != null && data.desa['lng'] != null) {
                    posisi = [data.desa['lat'], data.desa['lng']];
                    zoom = parseInt(data.desa['zoom']);
                } else {
                    if (data.desa['path'] != null) {
                        let _path = typeof data.desa.path === 'string' ? JSON.parse(data.desa.path) : data.desa.path;
                        posisi = _path[0][0];
                        zoom = parseInt(data.desa['zoom']);
                    }
                }

                let options = {
                    maxZoom: {{ setting('max_zoom_peta') }},
                    minZoom: {{ setting('min_zoom_peta') }},
                    fullscreenControl: {
                        position: 'topright'
                    }
                };

                const mymap = L.map('map', options).setView(posisi, zoom);
                window.mymap = mymap;

                if (data.desa['path'] != null) {
                    let _path = typeof data.desa.path === 'string' ? JSON.parse(data.desa.path) : data.desa.path;
                    mymap.fitBounds(_path);
                }

                let marker_desa = [];
                let marker_dusun = [];
                let marker_rw = [];
                let marker_rt = [];

                if (data.desa['path']) {
                    set_marker_desa_content(marker_desa, data.desa, "{{ ucwords(setting('sebutan_desa')) }} ${data.desa['nama_desa']}", "{{ favico_desa() }}", '#isi_popup');
                }

                if (data.dusun_gis) {
                    set_marker_multi_content(marker_dusun, JSON.stringify(data.dusun_gis), '{{ ucwords(setting('sebutan_dusun')) }}', 'dusun', '#isi_popup_dusun_', '{{ favico_desa() }}');
                }

                if (data.rw_gis) {
                    set_marker_multi_content(marker_rw, JSON.stringify(data.rw_gis), 'RW', 'rw', '#isi_popup_rw_', '{{ favico_desa() }}');
                }

                if (data.rt_gis) {
                    set_marker_content(marker_rt, JSON.stringify(data.rt_gis), 'RT', 'rt', '#isi_popup_rt_', '{{ favico_desa() }}');
                }

                let overlayLayers = overlayWil(marker_desa, marker_dusun, marker_rw, marker_rt, "{{ ucwords(setting('sebutan_desa')) }}", "{{ ucwords(setting('sebutan_dusun')) }}", true, "{{ setting('tampil_luas_peta') }}");

                let baseLayers;
                const mapbox_key = "{{ setting('mapbox_key') }}";
                if (!mapbox_key || mapbox_key === '') {
                    const osmLayer = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        maxZoom: 19,
                        attribution: '&copy; OpenStreetMap contributors'
                    }).addTo(mymap);
                    baseLayers = { "OpenStreetMap": osmLayer };
                } else {
                    baseLayers = getBaseLayers(mymap, mapbox_key, "{{ setting('jenis_peta') }}");
                }

                geoLocation(mymap);
                L.control.scale().addTo(mymap);
                cetakPeta(mymap);

                let layerCustom = tampilkan_layer_area_garis_lokasi_plus(
                    mymap,
                    JSON.stringify(data.area || []),
                    JSON.stringify(data.garis || []),
                    JSON.stringify(data.lokasi || []),
                    JSON.stringify(data.lokasi_pembangunan || []),
                    "{{ base_url(LOKASI_SIMBOL_LOKASI) }}",
                    "{{ favico_desa() }}",
                    "{{ base_url(LOKASI_FOTO_AREA) }}",
                    "{{ base_url(LOKASI_FOTO_GARIS) }}",
                    "{{ base_url(LOKASI_FOTO_LOKASI) }}",
                    "{{ base_url(LOKASI_GALERI) }}",
                    "{{ ci_route('pembangunan') }}",
                    JSON.stringify(data.persil || []),
                    "{{ setting('tampil_luas_peta') }}"
                );

                L.control.layers(baseLayers, overlayLayers, { position: 'topleft', collapsed: true }).addTo(mymap);
                L.control.groupedLayers('', layerCustom, { groupCheckboxes: true, position: 'topleft', collapsed: true }).addTo(mymap);
            };

            const generateStatistik = function(id, icon, judul, listLink) {
                let _html = `
                    <div class="panel box box-primary">
                        <div class="box-header with-border">
                            <h4 class="box-title">
                                <a data-toggle="collapse" data-parent="#accordion" href="#${id}" aria-expanded="false" class="collapsed">
                                    <i class="fa fa-${icon}"></i> ${judul}
                                </a>
                            </h4>
                        </div>
                        <div id="${id}" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">
                            <div class="box-body">
                                <ul style="padding-left: 20px;">
                `;
                listLink.forEach(item => {
                    _html += `<li><a href="${item.link}" data-title="${item.judul}" data-remote="false" data-toggle="modal" data-target="#modalBesar">${item.judul}</a></li>`;
                });
                _html += `
                                </ul>
                            </div>
                        </div>
                    </div>
                `;
                return _html;
            };
        };
    })();
</script>
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