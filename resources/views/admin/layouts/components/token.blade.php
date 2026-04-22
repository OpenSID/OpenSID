@if (config_item('csrf_protection'))
    <!-- jQuery Cookie (dimuat sebelum inline script supaya $.cookie tersedia saat getCsrfToken dipanggil) -->
    <script src="{{ asset('bootstrap/js/jquery.cookie.min.js') }}"></script>
    <!-- CSRF Token -->
    <script type="text/javascript">
        var csrfParam = "{{ $token_name }}";
        var csrfVal = "{{ $token_value }}";

        // Selalu baca dari cookie karena csrf_regenerate=true membuat token rotasi
        // setiap request sukses. csrfVal hanya dipakai sebagai fallback pada
        // request pertama sebelum cookie tersedia.
        function getCsrfToken() {
            return (typeof $.cookie === 'function' && $.cookie(csrfParam)) || csrfVal;
        }
    </script>
    <script src="{{ asset('js/anti-csrf.js') }}"></script>
@endif