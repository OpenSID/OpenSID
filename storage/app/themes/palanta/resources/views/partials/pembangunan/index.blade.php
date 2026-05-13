@extends('theme::layouts.full-content')
@include('theme::commons.asset_peta')

@section('content')
<div class="heading-module l-flex">
	<div class="heading-module-inner l-flex">
		<i class="fa fa-gavel"></i><h1>Pembangunan</h1>
	</div>
</div>
<div class="row-custom mlr-min5 pembangunan" id="pembangunan-list">
</div>
@include('theme::commons.pagination')
@endsection


<div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto"
    id="modalLokasi" tabindex="-1" aria-modal="true" role="dialog">
    <div class="modal-dialog relative w-auto pointer-events-none">
        <div
            class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
            <div
                class="modal-header flex flex-shrink-0 items-center justify-between p-4 border-b border-gray-200 rounded-t-md">
                <h5 class="text-h5">Lokasi Pembangunan</h5>
            </div>
            <div class="modal-body p-4">
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script type="text/javascript">
    $(document).ready(function () {
        function loadPembangunan(params = {}) {
            
            var apiPembangunan = '{{ route("api.pembangunan") }}';

            $('#pagination-container').hide();

            $.get(apiPembangunan, params, function (data) {
                var pembangunan = data.data;
                var pembangunanList = $('#pembangunan-list');

                pembangunanList.empty();

                if (!pembangunan.length) {
                    pembangunanList.html(`<div class="box-def hoverstyle">
                                            <div class="emptydata c-flex">
                                                <div>
                                                <svg viewBox="0 0 24 24"><path d="M13 13H11V7H13M11 15H13V17H11M15.73 3H8.27L3 8.27V15.73L8.27 21H15.73L21 15.73V8.27L15.73 3Z" /></svg>
                                                <p>Mohon maaf, untuk saat ini data belum tersedia...!</p>
                                                </div>
                                            </div>
                                        </div>`);
                    return;
                }

                pembangunan.forEach(function (item) {
                    var url = SITE_URL + 'pembangunan/' + item.attributes.slug;
                    var fotoHTML = `<img src="${item.attributes.foto}" alt="Foto Pembangunan" />`
                    var pembangunanHTML = `
                            <div class="column-4 box-def">
                                <a href="${url}">
                                    <div class="box-def-inner2">
                                        <div class="image-slider">
                                            ${fotoHTML}
                                        </div>
                                        <div class="l-flex" style="margin-top:10px;">
                                            <div>
                                            <h3>${item.attributes.judul}</h3>
                                            <table width="100%" class="tableagenda">
                                                <tr>
                                                    <td>Lokasi</td><td style="width:30px;text-align:center;">:</td><td>${item.attributes.alamat}</td>
                                                </tr>
                                                <tr>
                                                    <td>Tahun</td><td style="width:30px;text-align:center;">:</td><td>${item.attributes.tahun_anggaran}</td>
                                                </tr>
                                            </table>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>`;

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
        
        $('#modalLokasi').on('shown.bs.modal', function (event) {
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

            L.marker(posisi, { icon: markerIcon }).addTo(window.pelapak).bindPopup(`
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