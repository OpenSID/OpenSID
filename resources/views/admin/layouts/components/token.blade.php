@if (config_item('csrf_protection'))
    <!-- CSRF Token -->
    <script type="text/javascript">
        var csrfParam = "{{ $token }}";
        var getCsrfToken = () => document.cookie.match(new RegExp(csrfParam + '=(\\w+)'))[1];
    </script>
    <script src="{{ asset('js/anti-csrf.js') }}"></script>
@endif
