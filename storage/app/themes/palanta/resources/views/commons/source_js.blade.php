<script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ if (window.scrollY == 0) window.scrollTo(0,1); } </script>
<script language='javascript' src="{{ asset('front/js/jquery.min.js') }}"></script>
<script language='javascript' src="{{ asset('front/js/jquery.cycle2.min.js') }}"></script>
<script language='javascript' src="{{ asset('front/js/jquery.cycle2.carousel.js') }}"></script>
<script language='javascript' src="{{ asset('bootstrap/js/bootstrap.min.js') }}"></script>
@include('theme::commons.asset_highcharts')
<script src="{{ theme_asset('js/flickity.js') }}"></script>
<script src="{{ asset('js/leaflet.js') }}"></script>
<script src="{{ asset('front/js/layout.js') }}"></script>
<script src="{{ asset('front/js/jquery.colorbox.js') }}"></script>
<script src="{{ asset('js/leaflet-providers.js') }}"></script>
<script src="{{ asset('js/mapbox-gl.js') }}"></script>
<script src="{{ asset('js/leaflet-mapbox-gl.js') }}"></script>
<script src="{{ asset('js/peta.js') }}"></script>
<script src="{{ asset('bootstrap/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('bootstrap/js/dataTables.bootstrap.min.js') }}"></script>
<script src="{{ theme_asset('js/jquery.fancybox.min.js') }}"></script>
<div id="fb-root"></div>
<script async defer crossorigin="anonymous" src="https://connect.facebook.net/id_ID/sdk.js#xfbml=1&version=v3.2&appId=731690645328652&autoLogAppEvents=1"></script>
@include('core::admin.layouts.components.token')
<script>
    var BASE_URL = '{{ base_url() }}';
    var SITE_URL = '{{ ci_route("") }}';
    var setting  = @json(setting());
    var config   = @json(identitas());
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
<script>
function printDiv(divId) {
    // Ambil konten yang ingin dicetak
    var printContents = document.getElementById(divId).innerHTML;
    // Buka jendela baru
    var printWindow = window.print();
}
</script>