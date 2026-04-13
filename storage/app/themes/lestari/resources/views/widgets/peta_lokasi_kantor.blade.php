@php defined('BASEPATH') || exit('No direct script access allowed'); @endphp

<div id="map_canvas" class="map-front"></div>

<script>
    //Jika posisi kantor desa belum ada, maka posisi peta akan menampilkan seluruh Indonesia
    @if (!empty($data_config['lat']) && !empty($data_config['lng']))
        var posisi = [{{ $data_config['lat'] }}, {{ $data_config['lng'] }}];
        var zoom = {{ $data_config['zoom'] ?: 10 }};
    @else
        var posisi = [-1.0546279422758742, 116.71875000000001];
        var zoom = 10;
    @endif

    var options = {
        maxZoom: {{ setting('max_zoom_peta') }},
        minZoom: {{ setting('min_zoom_peta') }},
    };

    var lokasi_kantor = L.map('map_canvas', options).setView(posisi, zoom);

    //Menampilkan BaseLayers Peta
    var baseLayers = getBaseLayers(lokasi_kantor, "{{ setting('mapbox_key') }}", "{{ setting('jenis_peta') }}");

    L.control.layers(baseLayers, null, {
        position: 'topright',
        collapsed: true
    }).addTo(lokasi_kantor);

    //Jika posisi kantor desa belum ada, maka posisi peta akan menampilkan seluruh Indonesia
    @if (!empty($data_config['lat']) && !empty($data_config['lng']))
        var kantor_desa = L.marker(posisi).addTo(lokasi_kantor);
    @endif
</script>
