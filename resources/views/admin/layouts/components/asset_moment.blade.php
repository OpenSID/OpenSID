@push('scripts')
    <script src="{{ asset('bootstrap/js/moment.min.js') }}"></script>
    <script src="{{ asset('bootstrap/js/moment-timezone.js') }}"></script>
    <script src="{{ asset('bootstrap/js/moment-timezone-with-data.js') }}"></script>

    <script>
        moment.tz.setDefault(`Asia/Jakarta`);
    </script>
@endpush
