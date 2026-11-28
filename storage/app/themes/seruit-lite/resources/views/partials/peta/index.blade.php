@extends('theme::layouts.full-content')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/leaflet-measure-path.css') }}">
    <link rel="stylesheet" href="{{ asset('css/MarkerCluster.css') }}">
    <link rel="stylesheet" href="{{ asset('css/MarkerCluster.Default.css') }}">
    <link rel="stylesheet" href="{{ asset('css/leaflet.groupedlayercontrol.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/leaflet.fullscreen.css') }}" />
    
    <style>
        #map {
            width: 100%;
            height: 85vh;
            border-radius: 0;
            border: 1px solid var(--border-color);
        }

        #map .leaflet-popup-content {
            height: auto;
            max-height: 300px;
            overflow-y: auto;
        }

        .wilayah-info-popup .list-group-item {
            padding: 0.5rem 0.75rem;
            font-size: 0.875rem;
            background-color: transparent;
            border: none;
            border-bottom: 1px solid var(--border-color);
        }
        .wilayah-info-popup .list-group-item:last-child {
            border-bottom: none;
        }

        .dark .leaflet-control-layers-base label,
        .dark .leaflet-control-layers-overlays label,
        .dark .leaflet-control-scale-line {
            color: var(--text-color-base);
        }
        .dark .leaflet-control-layers,
        .dark .leaflet-bar,
        .dark .leaflet-popup-content-wrapper,
        .dark .leaflet-popup-tip {
            background: var(--bg-color-card);
            color: var(--text-color-base);
            border-color: var(--border-color);
            box-shadow: 0 1px 5px rgba(255, 255, 255, 0.2);
        }
    </style>
@endpush

@section('content')
    <div class="bg-[var(--bg-color-card)] p-6 shadow-xl border border-[var(--border-color)] -mt-16 relative z-10">
        <div class="flex items-center mt-6 mb-8">
            <div class="flex-grow border-t border-gray-300 dark:border-gray-700"></div>
            <h1 class="flex-shrink px-4 text-2xl lg:text-3xl font-bold uppercase">Peta Wilayah Desa</h1>
            <div class="flex-grow border-t border-gray-300 dark:border-gray-700"></div>
        </div>

        <div id="map-container" class="relative">
            <div id="map-loading" class="absolute inset-0 z-10 flex items-center justify-center bg-gray-100/50 dark:bg-gray-900/50">
                <div class="text-center">
                    <svg class="animate-spin h-8 w-8 text-blue-600 mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <p class="mt-2 text-sm font-semibold">Memuat Peta...</p>
                </div>
            </div>

            <div id="map">
                <div class="leaflet-top leaflet-left">
                    <div id="isi_popup" class="hidden"></div>
                    <div id="isi_popup_dusun" class="hidden"></div>
                    <div id="isi_popup_rw" class="hidden"></div>
                    <div id="isi_popup_rt" class="hidden"></div>
                </div>
            </div>
        </div>
    </div>

    <div x-data="{ modalOpen: false, modalTitle: '', modalContent: '' }"
         @open-modal.window="modalTitle = $event.detail.title; modalContent = $event.detail.content; modalOpen = true"
         x-show="modalOpen"
         x-cloak
         class="fixed inset-0 z-[999] overflow-y-auto"
         aria-labelledby="modal-title" role="dialog" aria-modal="true">
        
        <div class="flex items-center justify-center min-h-screen px-4 text-center">
            <div x-show="modalOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-black/60 backdrop-blur-sm" @click="modalOpen = false"></div>
            
            <div x-show="modalOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="inline-block w-full max-w-2xl my-8 overflow-hidden text-left align-middle transition-all transform bg-white dark:bg-gray-800 shadow-xl">
                <div class="flex items-center justify-between p-4 border-b dark:border-gray-700">
                    <h3 class="text-lg font-bold" x-text="modalTitle"></h3>
                    <button @click="modalOpen = false" class="p-2 -m-2 rounded-full hover:bg-gray-100 dark:hover:bg-gray-700">&times;</button>
                </div>
                <div class="p-6 max-h-[70vh] overflow-y-auto" x-html="modalContent"></div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ theme_asset('js/helper.js') }}"></script>
    
    <script>
    document.addEventListener("DOMContentLoaded", function () {
        window.openModal = function(title, url) {
            fetch(url)
                .then(response => response.text())
                .then(html => {
                    window.dispatchEvent(new CustomEvent('open-modal', { detail: { title: title, content: html } }));
                })
                .catch(err => console.error('Gagal memuat konten modal:', err));
        };

        (function() {
            const apiUrl = `{{ ci_route('internal_api.peta') }}`;
            const mapLoading = document.getElementById('map-loading');
            const mapContainer = document.getElementById('map');
            
            fetch(apiUrl)
                .then(response => response.json())
                .then(json => {
                    if (json.data && json.data.length > 0) {
                        const data = json.data[0].attributes;
                        
                        generatePopupDesa(data);
                        generatePopupWilayah(data, 'dusun');
                        generatePopupWilayah(data, 'rw');
                        generatePopupWilayah(data, 'rt');

                        generatePeta(data);

                        $('.leaflet-top.leaflet-left > div.hidden').remove();
                        mapLoading.style.display = 'none';

                    } else {
                       mapLoading.innerHTML = '<p class="text-red-500 font-semibold">Gagal memuat data peta.</p>';
                    }
                })
                .catch(error => {
                    console.error("Error fetching map data:", error);
                    mapLoading.innerHTML = '<p class="text-red-500 font-semibold">Terjadi kesalahan saat mengambil data peta.</p>';
                });

            const generatePopupDesa = function(data) {
                let content = '';
                const linkStatPenduduk = `{{ ci_route('statistik_web.chart_gis_desa') }}`;
                const title = `Statistik Penduduk {{ ucwords(setting('sebutan_desa')) }} ${capitalizeFirstCharacterOfEachWord(data.desa.nama_desa)}`;

                if (data.pengaturan.includes('Statistik Penduduk')) {
                    let listLink = '';
                    for (let key in data.list_ref) {
                        listLink += `<li><a href="javascript:void(0);" onclick="openModal('Statistik Penduduk ${title}', '${linkStatPenduduk}/${key}/${underscore(data.desa.nama_desa)}')">${data.list_ref[key]}</a></li>`;
                    }
                    content += `<div class="wilayah-info-popup"><strong>Statistik Penduduk:</strong><ul class="list-disc list-inside pl-4 mt-1">${listLink}</ul></div>`;
                }

                if (data.pengaturan.includes('Aparatur Desa')) {
                    const linkAparatur = `{{ ci_route('load_aparatur_desa') }}`;
                    content += `<div class="mt-2"><a href="javascript:void(0);" onclick="openModal('{{ ucwords(setting('sebutan_pemerintah_desa')) }}', '${linkAparatur}')" class="btn btn-primary btn-sm w-full">Lihat Aparatur Desa</a></div>`;
                }
                
                $('#isi_popup').html(`<div id="content"><div id="bodyContent">${content}</div></div>`);
            };

            const generatePopupWilayah = function(data, level) {
                const gisData = data[level + '_gis'];
                if (!gisData) return;
                
                const sebutan = (level === 'dusun') ? `{{ ucwords(setting('sebutan_dusun')) }}` : level.toUpperCase();
                const linkStat = `{{ ci_route('statistik_web.chart_gis_${level}') }}`;

                let allPopups = '';

                for (let key in gisData) {
                    const wilayah = gisData[key];
                    let content = '';
                    let params, titleWilayah;

                    if (level === 'dusun') {
                        params = underscore(wilayah.dusun);
                        titleWilayah = `${sebutan} ${capitalizeFirstCharacterOfEachWord(wilayah.dusun)}`;
                    } else if (level === 'rw') {
                        params = `${underscore(wilayah.dusun)}/${underscore(wilayah.rw)}`;
                        titleWilayah = `${sebutan} ${capitalizeFirstCharacterOfEachWord(wilayah.rw)} Dusun ${capitalizeFirstCharacterOfEachWord(wilayah.dusun)}`;
                    } else {
                        params = `${underscore(wilayah.dusun)}/${underscore(wilayah.rw)}/${underscore(wilayah.rt)}`;
                        titleWilayah = `${sebutan} ${capitalizeFirstCharacterOfEachWord(wilayah.rt)} RW ${capitalizeFirstCharacterOfEachWord(wilayah.rw)} Dusun ${capitalizeFirstCharacterOfEachWord(wilayah.dusun)}`;
                    }

                    if (data.pengaturan.includes('Statistik Penduduk')) {
                        let listLink = '';
                        for (let refKey in data.list_ref) {
                             listLink += `<li><a href="javascript:void(0);" onclick="openModal('Statistik Penduduk ${titleWilayah}', '${linkStat}/${refKey}/${params}')">${data.list_ref[refKey]}</a></li>`;
                        }
                        content += `<div class="wilayah-info-popup"><strong>Statistik Penduduk:</strong><ul class="list-disc list-inside pl-4 mt-1">${listLink}</ul></div>`;
                    }
                     
                    allPopups += `<div id="isi_popup_${level}_${key}" class="hidden"><div id="content"><div id="bodyContent">${content}</div></div></div>`;
                }
                 $('body').append(allPopups);
            };

            const generatePeta = function(data) {
                let posisi = [-1.054, 116.718];
                let zoom = 10;
                
                if (data.desa.lat && data.desa.lng) {
                    posisi = [data.desa.lat, data.desa.lng];
                    zoom = data.desa.zoom || 10;
                } else if (data.desa.path) {
                    const path = JSON.parse(data.desa.path);
                    if(path.length > 0 && path[0].length > 0) {
                        posisi = path[0][0];
                    }
                    zoom = data.desa.zoom || 10;
                }
                
                const mymap = L.map('map', {
                    maxZoom: setting.max_zoom_peta,
                    minZoom: setting.min_zoom_peta,
                    fullscreenControl: { position: 'topright' }
                }).setView(posisi, zoom);

                if (data.desa.path) {
                    mymap.fitBounds(JSON.parse(data.desa.path));
                }

                const baseLayers = getBaseLayers(mymap, setting.mapbox_key, setting.jenis_peta);
                
                let marker_desa = [], marker_dusun = [], marker_rw = [], marker_rt = [];
                if (data.desa.path) set_marker_desa_content(marker_desa, data.desa, `Wilayah {{ ucwords(setting('sebutan_desa')) }} ${data.desa.nama_desa}`, `{{ favico_desa() }}`, '#isi_popup');
                if (data.dusun_gis) set_marker_multi_content(marker_dusun, JSON.stringify(data.dusun_gis), `{{ ucwords(setting('sebutan_dusun')) }}`, 'dusun', '#isi_popup_dusun_', `{{ favico_desa() }}`);
                if (data.rw_gis) set_marker_content(marker_rw, JSON.stringify(data.rw_gis), 'RW', 'rw', '#isi_popup_rw_', `{{ favico_desa() }}`);
                if (data.rt_gis) set_marker_content(marker_rt, JSON.stringify(data.rt_gis), 'RT', 'rt', '#isi_popup_rt_', `{{ favico_desa() }}`);
                
                let overlayLayers = overlayWil(marker_desa, marker_dusun, marker_rw, marker_rt, `{{ ucwords(setting('sebutan_desa')) }}`, `{{ ucwords(setting('sebutan_dusun')) }}`, true, (setting.tampil_luas_peta || '').toString());

                let layerCustom = tampilkan_layer_area_garis_lokasi_plus(
                    mymap, JSON.stringify(data.area), JSON.stringify(data.garis), JSON.stringify(data.lokasi),
                    JSON.stringify(data.lokasi_pembangunan), `{{ base_url(LOKASI_SIMBOL_LOKASI) }}`, `{{ favico_desa() }}`,
                    `{{ base_url(LOKASI_FOTO_AREA) }}`, `{{ base_url(LOKASI_FOTO_GARIS) }}`, `{{ base_url(LOKASI_FOTO_LOKASI) }}`,
                    `{{ base_url(LOKASI_GALERI) }}`, `{{ ci_route('pembangunan') }}`, JSON.stringify(data.persil), (setting.tampil_luas_peta || '').toString()
                );
                
                L.control.layers(baseLayers, overlayLayers, { position: 'topleft', collapsed: true }).addTo(mymap);
                L.control.groupedLayers('', layerCustom, { groupCheckboxes: true, position: 'topleft', collapsed: true }).addTo(mymap);
                L.control.scale().addTo(mymap);

                const defaultLayers = (setting.default_tampil_peta_wilayah || []).concat(setting.default_tampil_peta_infrastruktur || []);
                $('input[type=checkbox]').each(function() {
                    if (defaultLayers.includes($(this).next().text().trim())) {
                        $(this).click();
                    }
                });
            };
        })();
    });
    </script>
@endpush