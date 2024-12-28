@push('scripts')
    @if (cek_koneksi_internet())
        <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.1.0/leaflet.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet-providers/1.6.0/leaflet-providers.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/mapbox-gl/1.11.1/mapbox-gl.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/mapbox-gl-leaflet/0.0.14/leaflet-mapbox-gl.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.cycle2/2.1.6/jquery.cycle2.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.cycle2/2.1.6/jquery.cycle2.carousel.js"></script>
    @endif
@endpush
