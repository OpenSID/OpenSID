@extends('theme::layouts.full-content')
@include('theme::commons.asset_peta')

@section('content')
    <nav role="navigation" aria-label="navigation" class="breadcrumb">
        <ol>
            <li><a href="{{ site_url('/') }}">Beranda</a></li>
            <li aria-current="page">Pembangunan</li>
        </ol>
    </nav>
    <h1 class="text-h2">Pembangunan</h1>
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-5 py-1" id="pembangunan-list">
    </div>
@endsection

@include('theme::commons.pagination')

<div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto" id="modalLokasi" tabindex="-1" aria-modal="true" role="dialog">
    <div class="modal-dialog relative w-auto pointer-events-none">
        <div class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
            <div class="modal-header flex flex-shrink-0 items-center justify-between p-4 border-b border-gray-200 rounded-t-md">
                <h5 class="text-h5">Lokasi Pembangunan</h5>
            </div>
            <div class="modal-body p-4">
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            function loadPembangunan(params = {}) {

                var apiPembangunan = '{{ route('api.pembangunan') }}';

                $('#pagination-container').hide();

                $.get(apiPembangunan, params, function(data) {
                    var pembangunan = data.data;
                    var pembangunanList = $('#pembangunan-list');

                    pembangunanList.empty();

                    if (!pembangunan.length) {
                        pembangunanList.html('<p class="py-2">Tidak ada pembangunan yang tersedia</p>');
                        return;
                    }

                    pembangunan.forEach(function(item) {
                        var url = SITE_URL + 'pembangunan/' + item.attributes.slug;
                        var fotoHTML = `<div class="space-y-3">
                        <img class="h-44 w-full object-cover object-center bg-gray-300 dark:bg-gray-600"
                            src="${item.attributes.foto}" alt="Foto Pembangunan" />`

                        var buttonMap = '';
                        if (item.attributes.lat && item.attributes.lng) {
                            buttonMap = `<button type="button" class="btn btn-secondary text-xs text-center rounded-0" data-bs-toggle="modal"
                            data-bs-target="#modalLokasi" data-bs-remote="false" title="Lokasi Pembangunan" data-lat="${item.attributes.lat}"
                            data-lng="${item.attributes.lng}" data-title="Lokasi Pembangunan"><i class="fas fa-map-marker-alt mr-2"></i>
                            Lokasi</button>`;
                        }

                        var pembangunanHTML = `
                        <div class="flex flex-col justify-between space-y-4 this-product">
                            <div class="space-y-3">
                                ${fotoHTML}
                                <div class="space-y-1/2 text-sm flex flex-col detail">
                                    <h3 class="text-h5">${item.attributes.judul}</h3>
                                    <div class="inline-flex"><i class="fas fa-calendar-alt mr-2"></i>
                                        ${item.attributes.tahun_anggaran}
                                    </div>
                                    <div class="font-thin">
                                        <i class="fas fa-map-marker-alt mr-1"></i>
                                        ${item.attributes.lokasi}
                                    </div>
                                    <p class="text-sm pt-1">
                                        ${item.attributes.keterangan.length > 100 ? item.attributes.keterangan.substring(0, 100) + '...' : item.attributes.keterangan}
                                    </p>
                                </div>
                            </div>
                            <div class="group flex items-center space-x-1">
                                <a href="${url}"
                                    class="btn btn-primary text-xs text-center rounded-0">Selengkapnya <i class="fas fa-chevron-right ml-1"></i>
                                </a>
                                ${buttonMap}
                            </div>
                        </div>
                    `;

                        pembangunanList.append(pembangunanHTML);
                    });

                    initPagination(data);
                });
            }

            $('.pagination').on('click', '.btn-page', function() {
                var params = {};
                var page = $(this).data('page');

                params['page[number]'] = page;

                loadPembangunan(params);
            });

            loadPembangunan();

            $('#modalLokasi').on('shown.bs.modal', function(event) {
                const link = $(event.relatedTarget);
                const modal = $(this);

                modal.find('.modal-title').text(link.data('title'));
                modal.find('.modal-body').html("<div id='map' style='width: 100%; height:350px'></div>");

                const posisi = [link.data('lat'), link.data('lng')];
                const zoom = link.data('zoom') || 10;
                const popupContent = link.closest('.this-product').find('.detail').html();

                const mapOptions = {
                    maxZoom: setting.max_zoom_peta,
                    minZoom: setting.min_zoom_peta
                };

                $('#lat').val(posisi[0]);
                $('#lng').val(posisi[1]);

                if (window.pelapak) {
                    window.pelapak.remove();
                }

                window.pelapak = L.map('map', mapOptions).setView(posisi, zoom);
                getBaseLayers(window.pelapak, setting.mapbox_key, setting.jenis_peta);

                const markerIcon = L.icon({
                    iconUrl: setting.icon_pembangunan_peta
                });

                L.marker(posisi, {
                    icon: markerIcon
                }).addTo(window.pelapak).bindPopup(`
                <div class="card">
                    <div class="text-xs">
                        <div class="py-1 space-y-1/2 text-sm flex flex-col">
                            ${popupContent}
                        </div>
                    </div>
                </div>
            `);

                L.control.scale().addTo(window.pelapak);

                window.pelapak.invalidateSize();
            });
        });
    </script>
@endpush
