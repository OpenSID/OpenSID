@push('css')
<!-- Jquery UI -->
  <link rel="stylesheet" href="{{ asset('bootstrap/css/jquery-ui.min.css') }}"/>
@endpush

@push('scripts')
<script src="{{ asset('bootstrap/js/jquery.dataTables.min.js') }}"></script>
<!-- Validasi -->
<script src="{{ asset('js/validasi.js') }}"></script>
<script src="{{ asset('js/localization/messages_id.js') }}"></script>
@endpush