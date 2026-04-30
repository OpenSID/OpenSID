<script defer src="{{ theme_asset('vendor/alpine/alpine.min.js') }}"></script>
<script src="{{ theme_asset('vendor/owl-carousel/owl.carousel.min.js') }}"></script>
<script src="{{ theme_asset('vendor/fancybox/jquery.fancybox.min.js') }}"></script>
<script src="{{ theme_asset('vendor/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ theme_asset('vendor/leaflet/leaflet.js') }}"></script>
<script src="{{ theme_asset('vendor/leaflet/leaflet-providers.min.js') }}"></script>
<script src="https://api.mapbox.com/mapbox-gl-js/v2.15.0/mapbox-gl.js"></script>
<script src="{{ theme_asset('vendor/leaflet/leaflet-mapbox-gl.min.js') }}"></script>
<script src="{{ theme_asset('vendor/cycle2/jquery.cycle2.min.js') }}"></script>
<script src="{{ theme_asset('vendor/cycle2/jquery.cycle2.carousel.js') }}"></script>
<script src="{{ theme_asset('vendor/tornpaper/tornpaper.min.js') }}"></script>
<script src="{{ theme_asset('vendor/marked/marked.min.js') }}"></script>
@include('core::admin.layouts.components.token')
<script src="{{ asset('js/peta.js') }}"></script>
<script>
    var BASE_URL = '{{ base_url() }}';
    $.extend($.fn.dataTable.defaults, {
        lengthMenu: [
            [10, 25, 50, 100, -1],
            [10, 25, 50, 100, "Semua"]
        ],
        pageLength: 10,
        language: {
            url: "{{ asset('bootstrap/js/dataTables.indonesian.lang') }}",
        }
    });
</script>

@if (!setting('inspect_element'))
    <script src="{{ asset('js/disabled.min.js') }}"></script>
@endif