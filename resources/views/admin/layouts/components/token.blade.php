@if (config_item('csrf_protection'))
    <!-- CSRF Token -->
    <script type="text/javascript">
        var csrfParam = "{{ $token_name }}";
        var csrfVal = "{{ $token_value }}";

        function getCsrfToken() {
            return csrfVal;
        }
    </script>
    <!-- jQuery Cookie -->
    <script src="{{ asset('bootstrap/js/jquery.cookie.min.js') }}"></script>
    <script src="{{ asset('js/anti-csrf.js') }}"></script>
@endif
