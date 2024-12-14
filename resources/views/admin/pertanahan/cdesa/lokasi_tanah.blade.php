<div id="map-modal" class="modal fade" role="dialog" style="padding-top:30px;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Lokasi Tanah</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12">
                        <input type="hidden" name="path" id="path" value="">
                        <input type="hidden" name="zoom" id="zoom" value="8">
                        <div id="map" style="width: 100%;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        var peta_area;
        @if (!empty($desa['lat']) && !empty($desa['lng']))
            var posisi = [{{ $desa['lat'] }}, {{ $desa['lng'] }}];
            var zoom = {{ $desa['zoom'] ?: 18 }};
        @else
            var posisi = [-1.0546279422758742, 116.71875000000001];
            var zoom = 4;
        @endif

        $(document).on('shown.bs.modal', '#map-modal', function(event) {
            if (L.DomUtil.get('map')._leaflet_id == undefined) {
                peta_area = L.map('map', pengaturan_peta).setView(posisi, zoom);

                //Menampilkan BaseLayers Peta
                var baseLayers = getBaseLayers(peta_area, MAPBOX_KEY, JENIS_PETA);

                //Import Peta dari file SHP
                //eximShp(peta_area);

                //Geolocation IP Route/GPS
                geoLocation(peta_area);

                //Menambahkan Peta wilayah
                addPetaPoly(peta_area);
                // end tampilkan map
            }

            var wilayah = $(event.relatedTarget).data('path');
            clearMap(peta_area);
            showCurrentArea(wilayah, peta_area, TAMPIL_LUAS)
        });
    </script>
@endpush
