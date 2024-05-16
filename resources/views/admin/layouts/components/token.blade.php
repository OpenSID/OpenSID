@if (config_item('csrf_protection'))
    <!-- CSRF Token -->
    <script type="text/javascript">
        var csrfParam = "{{ $token }}";
        var csrfVal = '{{ $ci->security->get_csrf_hash() }}';
        function getCsrfToken() {
            return csrfVal;
        }
    </script>
    <script src="{{ asset('js/anti-csrf.js') }}"></script>
@endif
