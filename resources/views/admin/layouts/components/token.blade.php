@if (config_item('csrf_protection'))
    <!-- CSRF Token -->
    <script type="text/javascript">
        var csrfParam = "{{ $token_name }}";
        var csrfVal = "{{ $token_value }}";

        var getCsrfToken = (csrfVal) => {
            const match = document.cookie.match(new RegExp(`${csrfParam}=([^;]+)`));
            return match ? match[1] : csrfVal;
        };
    </script>
    <script src="{{ asset('js/anti-csrf.js') }}"></script>
@endif
