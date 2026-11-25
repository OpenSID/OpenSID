@php
    $active_gradient = 'from-green-500 to-teal-500';
    $active_hover = 'hover:from-green-600 hover:to-teal-600';
@endphp

<div x-data="{ modalOpen: false }">
    <div class="bg-white dark:bg-gray-800 shadow-md border border-gray-100 dark:border-gray-700 overflow-hidden flex flex-col">
        <div class="p-4 border-b border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50">
            <h3 class="font-bold text-sm uppercase tracking-wider text-gray-700 dark:text-gray-200">
                <i class="fas fa-map-marker-alt mr-3 text-teal-500"></i>{{ $judul_widget }}
            </h3>
        </div>
        <div class="p-4 space-y-4">
            <div id="map_canvas_{{ $widget['id'] }}" class="h-48 w-full border border-gray-200 dark:border-gray-700 z-0"></div>
            <div class="grid grid-cols-2 gap-2">
                <a href="https://www.openstreetmap.org/#map=15/{{ $data_config['lat'] }}/{{ $data_config['lng'] }}" target="_blank" rel="noopener noreferrer" class="py-2 px-3 bg-gradient-to-r {{ $active_gradient }} {{ $active_hover }} text-white text-center text-[10px] font-bold uppercase tracking-wider shadow-sm transition-all">
                    Buka Peta
                </a>
                <button type="button" @click="modalOpen = true" class="py-2 px-3 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-white hover:bg-gray-200 dark:hover:bg-gray-600 text-[10px] font-bold uppercase tracking-wider border border-gray-200 dark:border-gray-600 shadow-sm transition-all">
                    Detail Kantor
                </button>
            </div>
        </div>
    </div>

    <div x-show="modalOpen" 
         x-cloak 
         class="fixed inset-0 z-[1100] overflow-y-auto flex items-center justify-center p-4" 
         role="dialog" 
         aria-modal="true">
        
        <div x-show="modalOpen" 
             x-transition:enter="ease-out duration-300" 
             x-transition:enter-start="opacity-0" 
             x-transition:enter-end="opacity-100" 
             x-transition:leave="ease-in duration-200" 
             x-transition:leave-start="opacity-100" 
             x-transition:leave-end="opacity-0" 
             class="fixed inset-0 bg-black/60 backdrop-blur-sm transition-opacity" 
             @click="modalOpen = false"></div>

        <div x-show="modalOpen" 
             x-transition:enter="ease-out duration-300" 
             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" 
             x-transition:leave="ease-in duration-200" 
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" 
             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
             class="relative bg-white dark:bg-gray-800 w-full max-w-lg shadow-2xl border border-gray-300 dark:border-gray-700 overflow-hidden">
            
            <div class="flex items-center justify-between p-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900">
                <h5 class="text-base font-bold text-gray-800 dark:text-gray-100 uppercase tracking-tight">Detail Kantor {{ ucwords(setting('sebutan_desa')) }}</h5>
                <button type="button" @click="modalOpen = false" class="text-gray-400 hover:text-gray-600 dark:hover:text-white transition-colors">
                    <i class="fas fa-times text-lg"></i>
                </button>
            </div>
            
            <div class="p-6">
                <table class="w-full text-sm">
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        <tr><td class="py-2 font-bold text-gray-500 w-1/3">Alamat</td><td class="py-2 text-gray-800 dark:text-gray-200">: {{ $desa['alamat_kantor'] }}</td></tr>
                        <tr><td class="py-2 font-bold text-gray-500">{{ ucwords(setting('sebutan_desa')) }}</td><td class="py-2 text-gray-800 dark:text-gray-200">: {{ $desa['nama_desa'] }}</td></tr>
                        <tr><td class="py-2 font-bold text-gray-500">{{ ucwords(setting('sebutan_kecamatan')) }}</td><td class="py-2 text-gray-800 dark:text-gray-200">: {{ $desa['nama_kecamatan'] }}</td></tr>
                        <tr><td class="py-2 font-bold text-gray-500">{{ ucwords(setting('sebutan_kabupaten')) }}</td><td class="py-2 text-gray-800 dark:text-gray-200">: {{ $desa['nama_kabupaten'] }}</td></tr>
                        <tr><td class="py-2 font-bold text-gray-500">Kodepos</td><td class="py-2 text-gray-800 dark:text-gray-200">: {{ $desa['kode_pos'] }}</td></tr>
                        <tr><td class="py-2 font-bold text-gray-500">Telepon</td><td class="py-2 text-gray-800 dark:text-gray-200">: {{ $desa['telepon'] }}</td></tr>
                        <tr><td class="py-2 font-bold text-gray-500">Email</td><td class="py-2 text-gray-800 dark:text-gray-200">: {{ $desa['email_desa'] }}</td></tr>
                    </tbody>
                </table>
                <div class="mt-8 flex justify-end">
                    <button type="button" @click="modalOpen = false" class="px-6 py-2 bg-gray-800 text-white text-xs font-bold uppercase tracking-widest hover:bg-black transition-colors">Tutup</button>
                </div>
            </div>
        </div>
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
        const map_id = 'map_canvas_{{ $widget['id'] }}';
        
        const map_lokasi = L.map(map_id, { 
            maxZoom: {{ setting('max_zoom_peta') }}, 
            minZoom: {{ setting('min_zoom_peta') }},
            scrollWheelZoom: false 
        }).setView(posisi, zoom);

        const baseLayers = getBaseLayers(map_lokasi, "{{ setting('mapbox_key') }}", "{{ setting('jenis_peta') }}");
        L.control.layers(baseLayers, null, { position: 'topright', collapsed: true }).addTo(map_lokasi);

        @if (!empty($data_config['lat']) && !empty($data_config['lng']))
            L.marker(posisi).addTo(map_lokasi);
        @endif
    });
</script>
@endpush