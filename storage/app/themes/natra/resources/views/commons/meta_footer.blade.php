@php defined('BASEPATH') || exit('No direct script access allowed'); @endphp

<script src="{{ theme_asset('js/wow.min.js') }}"></script>
<script src="{{ theme_asset('js/slick.min.js') }}"></script>
<script src="{{ theme_asset('js/custom.js') }}"></script>
<script>
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
