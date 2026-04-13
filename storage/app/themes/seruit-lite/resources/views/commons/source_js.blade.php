<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
@if (cek_koneksi_internet())
<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha384-1H217gwSVyLSIfaLxHbE7dRb3v4mYCKbpQvzx0cegeju1MVsGrX5xXxAvs/HgeFs" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@3.4.1/dist/js/bootstrap.min.js" integrity="sha384-aJ21OjlMXNL5UyIl/XNwTMqvzeRMZH2w8c5cRVpzpU8Y5bApTppSuUkhZXN0VxHd" crossorigin="anonymous"></script>
@else
<script src="{{ base_url('assets/bootstrap/js/jquery.min.js') }}"></script>
<script src="{{ base_url('assets/bootstrap/js/bootstrap.min.js') }}"></script>
@endif
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js" integrity="sha384-Udt767MMeKelGRBxaCfxX88YDLbViYdQ7T/gkRoB197Jf+OviZ+lsaRAOpS/MIjf" crossorigin="anonymous"></script>
<script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap.min.js" integrity="sha384-xX2rLG/IDoD8nMCCawO1tSmnmivygPR0hHih92wcA9NqItz/WQBRYL3LcGloEQnU" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.full.min.js" integrity="sha384-HCjW0//bc6Gu9bS3OISjenLhzVqjRipLVVj9LZtzKu+FYXXOZVCN7WDv2TYxCfmo" crossorigin="anonymous"></script>
@if (cek_koneksi_internet())
<script src="https://code.highcharts.com/11.4.1/highcharts.js" crossorigin="anonymous"></script>
<script src="https://code.highcharts.com/11.4.1/highcharts-3d.js" crossorigin="anonymous"></script>
<script src="https://code.highcharts.com/11.4.1/modules/exporting.js" crossorigin="anonymous"></script>
<script src="https://code.highcharts.com/11.4.1/modules/accessibility.js" crossorigin="anonymous"></script>
<script src="https://code.highcharts.com/11.4.1/modules/sankey.js" crossorigin="anonymous"></script>
<script src="https://code.highcharts.com/11.4.1/modules/organization.js" crossorigin="anonymous"></script>
@endif
<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet-providers/1.13.0/leaflet-providers.min.js" integrity="sha384-AVZcE5fEOTer3LhRjZOvOvtaQCbajsrYb0uT1PP7K0JMPUqCWpZSm5FzyxDEI3+b" crossorigin="anonymous"></script>
<script src="{{ asset('js/leaflet.markercluster.js') }}"></script> 
<script src="{{ asset('js/leaflet.groupedlayercontrol.min.js') }}"></script>
<script src="{{ asset('js/turf.min.js') }}"></script>
<script src="{{ asset('js/Leaflet.fullscreen.min.js') }}"></script>
<script src="{{ asset('js/peta.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.umd.js" integrity="sha384-BodKYo5iRmFaqEaP1o8AAu9hCHqLvNhSWEg12QF1IjPnl1SgsrwQMSMKUB4POJ18" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js" integrity="sha384-2UI1PfnXFjVMQ7/ZDEF70CR943oH3v6uZrFQGGqJYlvhh4g6z6uVktxYbOlAczav" crossorigin="anonymous"></script>
@if (cek_koneksi_internet())
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js" integrity="sha384-Rpe/8orFUm5Q1GplYBHxbuA8Az8O8C5sAoOsdbRWkqPjKFaxPgGZipj4zeHL7lxX" crossorigin="anonymous"></script>
@endif
<script>
const BASE_URL = '{{ base_url() }}';
const SITE_URL = '{{ site_url() }}';
const setting = @json(setting());
const config = @json(identitas());
</script>