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

        // Configuration constants
        const CONFIG = {
            apiUrl: '{{ ci_route('internal_api.peta') }}',
            routes: {
                statistikDesa: '{{ ci_route('statistik_web.chart_gis_desa') }}',
                statistikDusun: '{{ ci_route('statistik_web.chart_gis_dusun') }}',
                statistikRw: '{{ ci_route('statistik_web.chart_gis_rw') }}',
                statistikRt: '{{ ci_route('statistik_web.chart_gis_rt') }}',
                aparaturDesa: '{{ ci_route('load_aparatur_desa') }}',
                aparaturWilayah: '{{ ci_route('load_aparatur_wilayah') }}',
                pembangunan: '{{ ci_route('pembangunan') }}'
            },
            assets: {
                symbolLokasi: '{{ base_url(LOKASI_SIMBOL_LOKASI) }}',
                favicoDesa: '{{ favico_desa() }}',
                fotoArea: '{{ base_url(LOKASI_FOTO_AREA) }}',
                fotoGaris: '{{ base_url(LOKASI_FOTO_GARIS) }}',
                fotoLokasi: '{{ base_url(LOKASI_FOTO_LOKASI) }}',
                galeri: '{{ base_url(LOKASI_GALERI) }}'
            },
            settings: {
                maxZoom: {{ setting('max_zoom_peta') }},
                minZoom: {{ setting('min_zoom_peta') }},
                tampilLuas: '{{ setting('tampil_luas_peta') }}',
                mapboxKey: '{{ setting('mapbox_key') }}',
                jenisPeta: '{{ setting('jenis_peta') }}',
                defaultTampilWilayah: @json(SebutanDesa(setting('default_tampil_peta_wilayah')) ?: []),
                defaultTampilInfrastruktur: @json(SebutanDesa(setting('default_tampil_peta_infrastruktur')) ?: [])
            },
            labels: {
                desa: '{{ ucwords(setting('sebutan_desa')) }}',
                dusun: '{{ ucwords(setting('sebutan_dusun')) }}',
                kepalaDusun: '{{ ucwords(setting('sebutan_kepala_dusun')) }}',
                pemerintahDesa: '{{ ucwords(setting('sebutan_pemerintah_desa')) }}'
            }
        };

        // Utility Functions
        const Utils = {
            /**
             * Safely get nested object property
             */
            safeGet(obj, path, defaultValue = null) {
                return path.split('.').reduce((acc, part) => acc && acc[part], obj) || defaultValue;
            },

            /**
             * Capitalize first character of each word
             */
            capitalizeWords(str) {
                if (!str) return '';
                return str.replace(/\w\S*/g, txt => txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase());
            },

            /**
             * Convert string to underscore format
             */
            underscore(str) {
                if (!str) return '';
                return str.toString().toLowerCase().replace(/\s+/g, '_');
            },

            /**
             * Remove loading spinner
             */
            removeLoadingSpinner() {
                $('.spinner-grow').parent().remove();
            },

            /**
             * Show error message
             */
            showError(message) {
                console.error(message);
                this.removeLoadingSpinner();
                alert(message);
            },

            /**
             * Validate required libraries
             */
            validateLibraries() {
                if (typeof L === 'undefined') {
                    throw new Error('Leaflet library not loaded');
                }
                if (typeof $ === 'undefined') {
                    throw new Error('jQuery library not loaded');
                }
                if (!document.getElementById('map')) {
                    throw new Error('Map container not found');
                }
            }
        };

        // Popup Generator Class
        class PopupGenerator {
            constructor(config) {
                this.config = config;
            }

            /**
             * Parse pengaturan - handle both string and array formats
             */
            parsePengaturan(pengaturan) {
                if (!pengaturan) return [];
                
                if (typeof pengaturan === 'string') {
                    try {
                        return JSON.parse(pengaturan);
                    } catch (e) {
                        console.error('Error parsing pengaturan:', e);
                        return [];
                    }
                }
                
                return Array.isArray(pengaturan) ? pengaturan : [];
            }

            /**
             * Generate statistics links
             */
            generateStatistikLinks(listRef, baseUrl, params, title) {
                const links = [];
                for (let key in listRef) {
                    links.push(
                        `<li><a href="${baseUrl}/${key}/${params}" 
                            data-remote="false" 
                            data-toggle="modal" 
                            data-target="#modalSedang" 
                            data-title="Statistik Penduduk ${title}">
                            ${listRef[key]}
                        </a></li>`
                    );
                }
                return links.join('');
            }

            /**
             * Generate statistics section
             */
            generateStatistikSection(id, icon, title, links) {
                return `
                    <p>
                        <a href="#${id}" 
                           class="btn btn-social bg-navy btn-sm btn-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" 
                           title="${title}" 
                           data-toggle="collapse" 
                           data-target="#${id}" 
                           aria-expanded="false" 
                           aria-controls="${id}">
                            <i class="fa fa-${icon}"></i>&nbsp;&nbsp;${title}&nbsp;&nbsp;
                        </a>
                    </p>
                    <div class="collapse box-body no-padding" id="${id}">
                        <div class="card card-body">
                            <ul>${links}</ul>
                        </div>
                    </div>
                `;
            }

            /**
             * Generate popup for Desa
             */
            generatePopupDesa(data) {
                const elmPopup = document.getElementById('isi_popup');
                if (!elmPopup) return;

                const content = elmPopup.querySelector('#content');
                if (!content) return;

                const firstHeading = content.querySelector('#firstHeading');
                const bodyContent = content.querySelector('#bodyContent');
                
                if (!firstHeading || !bodyContent) return;

                const desaName = Utils.safeGet(data, 'desa.nama_desa', '');
                firstHeading.innerHTML = `Wilayah ${this.config.labels.desa} ${desaName}`;
                
                let html = '';
                const title = `Statistik Penduduk ${this.config.labels.desa} ${Utils.capitalizeWords(desaName)}`;

                // Parse pengaturan (handle JSON string or array)
                const pengaturan = this.parsePengaturan(data.pengaturan);

                // Statistik Penduduk
                if (pengaturan.includes('Statistik Penduduk')) {
                    const params = Utils.underscore(desaName);
                    const links = this.generateStatistikLinks(
                        data.list_ref,
                        this.config.routes.statistikDesa,
                        params,
                        title
                    );
                    html += this.generateStatistikSection('collapseStatPenduduk', 'bar-chart', 'Statistik Penduduk', links);
                }

                // Statistik Bantuan
                if (pengaturan.includes('Statistik Bantuan')) {
                    const params = Utils.underscore(desaName);
                    const links = this.generateStatistikLinks(
                        data.list_bantuan,
                        this.config.routes.statistikDesa,
                        params,
                        title
                    );
                    html += this.generateStatistikSection('collapseStatBantuan', 'heart', 'Statistik Bantuan', links);
                }

                // Aparatur Desa
                if (pengaturan.includes('Aparatur Desa')) {
                    html += `
                        <p>
                            <a href="${this.config.routes.aparaturDesa}" 
                               class="btn btn-social bg-navy btn-sm btn-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" 
                               data-title="${this.config.labels.pemerintahDesa}" 
                               data-remote="false" 
                               data-toggle="modal" 
                               data-target="#modalKecil">
                                <i class="fa fa-user"></i>&nbsp;&nbsp;${this.config.labels.pemerintahDesa}&nbsp;&nbsp;
                            </a>
                        </p>
                    `;
                }

                bodyContent.innerHTML = html;
            }

            /**
             * Generate popup element for wilayah (Dusun/RW/RT)
             */
            generatePopupElement(data, pengaturanRaw, gisData, wilayah) {
                let parentElementHTML = '';

                if (!gisData || gisData.length === 0) return parentElementHTML;

                // Parse pengaturan (handle JSON string or array)
                const pengaturan = this.parsePengaturan(pengaturanRaw);

                gisData.forEach((item, index) => {
                    let params, newTitle;

                    // Generate params and title based on wilayah type
                    switch (wilayah.key) {
                        case 'dusun':
                            params = Utils.underscore(item.dusun);
                            newTitle = `${this.config.labels.desa} ${Utils.capitalizeWords(item.dusun)}`;
                            break;
                        case 'rw':
                            params = `${Utils.underscore(item.dusun)}/${Utils.underscore(item.rw)}`;
                            newTitle = `RW ${Utils.capitalizeWords(item.rw)} ${this.config.labels.dusun} ${Utils.capitalizeWords(item.dusun)}`;
                            break;
                        case 'rt':
                            params = `${Utils.underscore(item.dusun)}/${Utils.underscore(item.rw)}/${Utils.underscore(item.rt)}`;
                            newTitle = `RT ${Utils.capitalizeWords(item.rt)} RW ${Utils.capitalizeWords(item.rw)} ${this.config.labels.dusun} ${Utils.capitalizeWords(item.dusun)}`;
                            break;
                    }

                    let contentHTML = '';

                    // Statistik Penduduk
                    if (pengaturan.includes('Statistik Penduduk')) {
                        const links = this.generateStatistikLinks(
                            data.list_ref,
                            wilayah.link,
                            params,
                            newTitle
                        );
                        contentHTML += this.generateStatistikSection('collapseStatPenduduk', 'bar-chart', 'Statistik Penduduk', links);
                    }

                    // Statistik Bantuan
                    if (pengaturan.includes('Statistik Bantuan')) {
                        const links = this.generateStatistikLinks(
                            data.list_bantuan,
                            wilayah.link,
                            params,
                            newTitle
                        );
                        contentHTML += this.generateStatistikSection('collapseStatBantuan', 'heart', 'Statistik Bantuan', links);
                    }

                    // Aparatur Wilayah
                    if (pengaturan.includes('Aparatur Desa') && item.id_kepala) {
                        contentHTML += `
                            <p>
                                <a href="${this.config.routes.aparaturWilayah}/${item.id_kepala}/${wilayah.level}" 
                                   class="btn btn-social bg-navy btn-sm btn-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" 
                                   data-title="${wilayah.sebutan}" 
                                   data-remote="false" 
                                   data-toggle="modal" 
                                   data-target="#modalKecil">
                                    <i class="fa fa-user"></i>&nbsp;&nbsp;${wilayah.sebutan}&nbsp;&nbsp;
                                </a>
                            </p>
                        `;
                    }

                    const elementHTML = `
                        <div id="${wilayah.div_parent}_${index}" style="visibility: hidden;">
                            <div id="content">
                                <h5 id="firstHeading" class="firstHeading">Wilayah ${newTitle}</h5>
                                <div id="bodyContent">${contentHTML}</div>
                            </div>
                        </div>
                    `;

                    parentElementHTML += elementHTML;
                });

                return parentElementHTML;
            }

            /**
             * Generate popup for Dusun
             */
            generatePopupDusun(data) {
                const elmPopup = document.getElementById('isi_popup_dusun');
                if (!elmPopup) return;

                const wilayah = {
                    level: 1,
                    key: 'dusun',
                    sebutan: this.config.labels.kepalaDusun,
                    div_parent: 'isi_popup_dusun',
                    link: this.config.routes.statistikDusun
                };

                elmPopup.innerHTML = this.generatePopupElement(
                    data,
                    data.pengaturan,
                    data.dusun_gis,
                    wilayah
                );
            }

            /**
             * Generate popup for RW
             */
            generatePopupRw(data) {
                const elmPopup = document.getElementById('isi_popup_rw');
                if (!elmPopup) return;

                const wilayah = {
                    level: 2,
                    key: 'rw',
                    sebutan: 'RW',
                    div_parent: 'isi_popup_rw',
                    link: this.config.routes.statistikRw
                };

                elmPopup.innerHTML = this.generatePopupElement(
                    data,
                    data.pengaturan,
                    data.rw_gis,
                    wilayah
                );
            }

            /**
             * Generate popup for RT
             */
            generatePopupRt(data) {
                const elmPopup = document.getElementById('isi_popup_rt');
                if (!elmPopup) return;

                const wilayah = {
                    level: 3,
                    key: 'rt',
                    sebutan: 'RT',
                    div_parent: 'isi_popup_rt',
                    link: this.config.routes.statistikRt
                };

                elmPopup.innerHTML = this.generatePopupElement(
                    data,
                    data.pengaturan,
                    data.rt_gis,
                    wilayah
                );
            }
        }

        // Map Generator Class
        class MapGenerator {
            constructor(config) {
                this.config = config;
                this.map = null;
            }

            /**
             * Initialize map position and zoom
             */
            initializeMapView(data) {
                let posisi = [-1.0546279422758742, 116.71875000000001];
                let zoom = 10;

                const lat = Utils.safeGet(data, 'desa.lat');
                const lng = Utils.safeGet(data, 'desa.lng');
                const path = Utils.safeGet(data, 'desa.path');
                const zoomLevel = Utils.safeGet(data, 'desa.zoom', 10);

                if (lat && lng) {
                    posisi = [parseFloat(lat), parseFloat(lng)];
                    zoom = parseInt(zoomLevel);
                } else if (path) {
                    try {
                        const parsedPath = typeof path === 'string' ? JSON.parse(path) : path;
                        if (Array.isArray(parsedPath) && parsedPath.length > 0 && parsedPath[0].length > 0) {
                            posisi = parsedPath[0][0];
                            zoom = parseInt(zoomLevel);
                        }
                    } catch (e) {
                        console.error('Error parsing path:', e);
                    }
                }

                return { posisi, zoom };
            }

            /**
             * Generate map
             */
            generate(data) {
                try {
                    // Initialize map view
                    const { posisi, zoom } = this.initializeMapView(data);

                    // Map options
                    const options = {
                        maxZoom: this.config.settings.maxZoom,
                        minZoom: this.config.settings.minZoom,
                        fullscreenControl: {
                            position: 'topright'
                        }
                    };

                    // Create map
                    this.map = L.map('map', options).setView(posisi, zoom);

                    // Fit bounds if path exists
                    const path = Utils.safeGet(data, 'desa.path');
                    if (path) {
                        try {
                            const parsedPath = typeof path === 'string' ? JSON.parse(path) : path;
                            this.map.fitBounds(parsedPath);
                        } catch (e) {
                            console.error('Error fitting bounds:', e);
                        }
                    }

                    // Initialize marker arrays
                    const markers = {
                        desa: [],
                        dusun: [],
                        rw: [],
                        rt: [],
                        area: [],
                        garis: [],
                        lokasi: []
                    };

                    // Setup overlay layers for wilayah
                    if (Utils.safeGet(data, 'desa.path')) {
                        set_marker_desa_content(
                            markers.desa,
                            data.desa,
                            `${this.config.labels.desa} ${data.desa.nama_desa}`,
                            this.config.assets.favicoDesa,
                            '#isi_popup'
                        );
                    }

                    if (data.dusun_gis) {
                        set_marker_multi_content(
                            markers.dusun,
                            JSON.stringify(data.dusun_gis),
                            this.config.labels.dusun,
                            'dusun',
                            '#isi_popup_dusun_',
                            this.config.assets.favicoDesa
                        );
                    }

                    if (data.rw_gis) {
                        set_marker_content(
                            markers.rw,
                            JSON.stringify(data.rw_gis),
                            'RW',
                            'rw',
                            '#isi_popup_rw_',
                            this.config.assets.favicoDesa
                        );
                    }

                    if (data.rt_gis) {
                        set_marker_content(
                            markers.rt,
                            JSON.stringify(data.rt_gis),
                            'RT',
                            'rt',
                            '#isi_popup_rt_',
                            this.config.assets.favicoDesa
                        );
                    }

                    // Create overlay layers
                    const overlayLayers = overlayWil(
                        markers.desa,
                        markers.dusun,
                        markers.rw,
                        markers.rt,
                        this.config.labels.desa,
                        this.config.labels.dusun,
                        true,
                        this.config.settings.tampilLuas
                    );

                    // Create base layers with fallback
                    let baseLayers;
                    
                    // Check if Mapbox key is available
                    if (!this.config.settings.mapboxKey || this.config.settings.mapboxKey === '') {
                        console.warn('No Mapbox token found, using OpenStreetMap as fallback');
                        
                        // Create OpenStreetMap layers as fallback
                        const osmLayer = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                            maxZoom: 19,
                            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                        }).addTo(this.map);
                        
                        const osmHotLayer = L.tileLayer('https://{s}.tile.openstreetmap.fr/hot/{z}/{x}/{y}.png', {
                            maxZoom: 19,
                            attribution: '&copy; OpenStreetMap contributors, Tiles courtesy of Humanitarian OSM Team'
                        });
                        
                        const topoLayer = L.tileLayer('https://{s}.tile.opentopomap.org/{z}/{x}/{y}.png', {
                            maxZoom: 17,
                            attribution: 'Map data: &copy; OpenStreetMap contributors, SRTM | Map style: &copy; OpenTopoMap'
                        });
                        
                        baseLayers = {
                            "OpenStreetMap": osmLayer,
                            "OpenStreetMap HOT": osmHotLayer,
                            "OpenTopoMap": topoLayer
                        };
                    } else {
                        // Use Mapbox layers
                        try {
                            baseLayers = getBaseLayers(
                                this.map,
                                this.config.settings.mapboxKey,
                                this.config.settings.jenisPeta
                            );
                        } catch (error) {
                            console.error('Error loading Mapbox layers:', error);
                            
                            // Fallback to OSM if Mapbox fails
                            const osmLayer = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                                maxZoom: 19,
                                attribution: '&copy; OpenStreetMap contributors'
                            }).addTo(this.map);
                            
                            baseLayers = {
                                "OpenStreetMap": osmLayer
                            };
                        }
                    }

                    // Add geolocation
                    geoLocation(this.map);

                    // Add scale control
                    L.control.scale().addTo(this.map);

                    // Add print control
                    cetakPeta(this.map);

                    // Setup legends
                    this.setupLegends(data);

                    // Add custom layers (area, garis, lokasi, etc.)
                    const layerCustom = tampilkan_layer_area_garis_lokasi_plus(
                        this.map,
                        JSON.stringify(data.area || []),
                        JSON.stringify(data.garis || []),
                        JSON.stringify(data.lokasi || []),
                        JSON.stringify(data.lokasi_pembangunan || []),
                        this.config.assets.symbolLokasi,
                        this.config.assets.favicoDesa,
                        this.config.assets.fotoArea,
                        this.config.assets.fotoGaris,
                        this.config.assets.fotoLokasi,
                        this.config.assets.galeri,
                        this.config.routes.pembangunan,
                        JSON.stringify(data.persil || []),
                        this.config.settings.tampilLuas
                    );

                    // Add layer controls
                    L.control.layers(baseLayers, overlayLayers, {
                        position: 'topleft',
                        collapsed: true
                    }).addTo(this.map);

                    L.control.groupedLayers('', layerCustom, {
                        groupCheckboxes: true,
                        position: 'topleft',
                        collapsed: true
                    }).addTo(this.map);

                    // Auto-check configured layers
                    this.autoCheckLayers(data);

                } catch (error) {
                    console.error('Error generating map:', error);
                    throw error;
                }
            }

            /**
             * Setup map legends
             */
            setupLegends(data) {
                const legends = {
                    desa: L.control({ position: 'bottomright' }),
                    dusun: L.control({ position: 'bottomright' }),
                    rw: L.control({ position: 'bottomright' }),
                    rt: L.control({ position: 'bottomright' })
                };

                this.map.on('overlayadd', (eventLayer) => {
                    if (eventLayer.name === 'Peta Wilayah Desa') {
                        setlegendPetaDesa(
                            legends.desa,
                            this.map,
                            data.desa,
                            this.config.labels.desa,
                            data.desa.nama_desa
                        );
                    }
                    if (eventLayer.name === 'Peta Wilayah Dusun') {
                        setlegendPeta(
                            legends.dusun,
                            this.map,
                            JSON.stringify(data.dusun_gis),
                            this.config.labels.dusun,
                            'dusun',
                            '',
                            ''
                        );
                    }
                    if (eventLayer.name === 'Peta Wilayah RW') {
                        setlegendPeta(
                            legends.rw,
                            this.map,
                            JSON.stringify(data.rw_gis),
                            'RW',
                            'rw',
                            this.config.labels.dusun
                        );
                    }
                    if (eventLayer.name === 'Peta Wilayah RT') {
                        setlegendPeta(
                            legends.rt,
                            this.map,
                            JSON.stringify(data.rt_gis),
                            'RT',
                            'rt',
                            'RW'
                        );
                    }
                });

                this.map.on('overlayremove', (eventLayer) => {
                    if (eventLayer.name === 'Peta Wilayah Desa') {
                        this.map.removeControl(legends.desa);
                    }
                    if (eventLayer.name === 'Peta Wilayah Dusun') {
                        this.map.removeControl(legends.dusun);
                    }
                    if (eventLayer.name === 'Peta Wilayah RW') {
                        this.map.removeControl(legends.rw);
                    }
                    if (eventLayer.name === 'Peta Wilayah RT') {
                        this.map.removeControl(legends.rt);
                    }
                });
            }

            /**
             * Auto-check configured layers
             */
            autoCheckLayers(data) {
                const wilayahInfrastruktur = this.config.settings.defaultTampilWilayah.concat(
                    this.config.settings.defaultTampilInfrastruktur
                );

                $('input[type=checkbox]').each(function() {
                    const labelCheckbox = $(this).next().text().trim();
                    
                    if (wilayahInfrastruktur.includes(labelCheckbox)) {
                        $(this).click();
                    }
                    
                    if (labelCheckbox === 'Letter C-Desa') {
                        if (data.tampilkan_cdesa != 1) {
                            $(this).parent().remove();
                        }
                    }
                });
            }
        }

        // Main Application
        class MapApplication {
            constructor() {
                this.config = CONFIG;
                this.popupGenerator = new PopupGenerator(this.config);
                this.mapGenerator = new MapGenerator(this.config);
            }

            /**
             * Initialize application
             */
            async initialize() {
                try {
                    // Validate libraries
                    Utils.validateLibraries();

                    // Remove header and footer
                    $('#main-peta').siblings('.container').remove();

                    // Fetch map data
                    const data = await this.fetchMapData();

                    // Validate response
                    if (!data || !data.data || !data.data[0] || !data.data[0].attributes) {
                        throw new Error('Invalid API response structure');
                    }

                    const attributes = data.data[0].attributes;

                    // Generate popups
                    this.popupGenerator.generatePopupDesa(attributes);
                    this.popupGenerator.generatePopupDusun(attributes);
                    this.popupGenerator.generatePopupRw(attributes);
                    this.popupGenerator.generatePopupRt(attributes);

                    // Generate map
                    this.mapGenerator.generate(attributes);

                    // Cleanup popup elements
                    this.cleanupPopupElements();

                    // Remove loading spinner
                    Utils.removeLoadingSpinner();

                } catch (error) {
                    console.error('Map initialization error:', error);
                    Utils.showError('Failed to load map. Please refresh the page.');
                }
            }

            /**
             * Fetch map data from API
             */
            fetchMapData() {
                return new Promise((resolve, reject) => {
                    $.ajax({
                        url: this.config.apiUrl,
                        type: 'POST',
                        dataType: 'json',
                        timeout: 30000,
                        success: function(response) {
                            resolve(response);
                        },
                        error: function(xhr, status, error) {
                            reject(new Error(`API request failed: ${status} - ${error}`));
                        }
                    });
                });
            }

            /**
             * Cleanup temporary popup elements
             */
            cleanupPopupElements() {
                $('#isi_popup_dusun').remove();
                $('#isi_popup_rw').remove();
                $('#isi_popup_rt').remove();
                $('#isi_popup').remove();
            }
        }

        // Initialize application on window load
        window.onload = function() {
            const app = new MapApplication();
            app.initialize();
        };

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