@push('css')
<!-- bootstrap datepicker -->
<link rel="stylesheet" href="{{ asset('bootstrap/css/bootstrap-datepicker.min.css') }}">
<?php if (cek_koneksi_internet()): ?>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<?php endif ?>
@endpush

@push('scripts')
<!-- moment js -->
<script src="{{ asset('bootstrap/js/moment.min.js') }}"></script>
<script src="{{ asset('bootstrap/js/moment-timezone.js') }}"></script>
<script src="{{ asset('bootstrap/js/moment-timezone-with-data.js') }}"></script>
<!-- bootstrap Date time picker -->
<script src="{{ asset('bootstrap/js/bootstrap-datetimepicker.min.js') }}"></script>
<script src="{{ asset('bootstrap/js/id.js') }}"></script>
<!-- bootstrap Date picker -->
<script src="{{ asset('bootstrap/js/bootstrap-datepicker.min.js') }}"></script>
<script src="{{ asset('bootstrap/js/bootstrap-datepicker.id.min.js') }}"></script>
<?php if (cek_koneksi_internet()): ?>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<?php endif ?>
<!-- Script-->
<script src="{{ asset('js/custom-datetimepicker.js') }}"></script>
<script>
    $.extend($.fn.datetimepicker.defaults, {
        timeZone: `<?= date_default_timezone_get() ?>`
    });

    moment.tz.setDefault(`<?= date_default_timezone_get() ?>`);
</script>
@endpush