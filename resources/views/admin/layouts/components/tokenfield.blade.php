@push('css')
    <link rel="stylesheet" href="{{ asset('bootstrap/css/bootstrap-tokenfield.min.css') }}">
    <style>
        .tokenfield .token {
            background-color: #3c8dbc;
            border-color: #367fa9;
            border-radius: 5px;
            padding: 1px 5px;
            font-size: 12px;
            color: #fff;
        }

        .tokenfield .token .close {
            font-size: 12px;
        }

        .ui-autocomplete {
            max-height: 200px;
            overflow-y: auto;
            overflow-x: hidden;
        }
    </style>
@endpush

@push('scripts')
    <script src="{{ asset('bootstrap/js/bootstrap-tokenfield.min.js') }}"></script>
@endpush
