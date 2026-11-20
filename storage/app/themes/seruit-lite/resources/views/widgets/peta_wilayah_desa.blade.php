@php
    $active_gradient = 'from-green-500 to-teal-500';
    $active_hover = 'hover:from-green-600 hover:to-teal-600';
@endphp

<div class="bg-white dark:bg-gray-800 shadow-md border border-gray-100 dark:border-gray-700 overflow-hidden flex flex-col">
    <div class="p-4 border-b border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50">
        <h3 class="font-bold text-sm uppercase tracking-wider text-gray-700 dark:text-gray-200">
            <i class="fas fa-map mr-3 text-teal-500"></i>{{ $judul_widget }}
        </h3>
    </div>
    <div class="p-4 space-y-4">
        <div id="map_wilayah_{{ $widget['id'] }}" class="h-48 w-full border border-gray-200 dark:border-gray-700 z-0"></div>
        <a href="{{ site_url('peta') }}" class="block w-full py-2 px-3 bg-gradient-to-r {{ $active_gradient }} {{ $active_hover }} text-white text-center text-[10px] font-bold uppercase tracking-wider shadow-sm transition-all">
            Lihat Peta Wilayah Detail
        </a>
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        if (typeof L === 'undefined') return;

        function getBaseLayers(map, apikey, jenis_peta) {
            const openstreetmap = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { maxZoom: 18, attribution: 'OSM' });
            const esri = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', { attribution: 'Esri' });
            let baseLayers = { "OpenStreetMap": openstreetmap, "Esri World Imagery": esri };
            openstreetmap.addTo(map);
            return baseLayers;
        }

        const posisi = [{{ $data_config['lat'] ?: -1.05 }}, {{ $data_config['lng'] ?: 116.71 }}];
        const zoom = {{ $data_config['zoom'] ?: 12 }};
        const map_id = 'map_wilayah_{{ $widget['id'] }}';
        
        const map_wilayah = L.map(map_id, { 
            maxZoom: {{ setting('max_zoom_peta') }}, 
            minZoom: {{ setting('min_zoom_peta') }},
            scrollWheelZoom: false 
        }).setView(posisi, zoom);

        const baseLayers = getBaseLayers(map_wilayah, "{{ setting('mapbox_key') }}", "{{ setting('jenis_peta') }}");
        L.control.layers(baseLayers, null, { position: 'topright', collapsed: true }).addTo(map_wilayah);

        const style_polygon = { 
            stroke: true, 
            color: '#14b8a6', 
            opacity: 1, 
            weight: 2, 
            fillColor: '#14b8a6', 
            fillOpacity: 0.3 
        };

        @if (!empty($data_config['path']))
            const polygon_desa = {!! $data_config['path'] !!};
            const layer_desa = L.polygon(polygon_desa, style_polygon).bindTooltip("Wilayah Desa").addTo(map_wilayah);
            map_wilayah.fitBounds(layer_desa.getBounds());
        @endif
    });
</script>
@endpush