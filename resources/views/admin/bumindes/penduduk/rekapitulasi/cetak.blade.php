@extends('admin.layouts.print_layout')

@section('title', $file)

@if(isset($is_landscape) && $is_landscape)
@push('css')
<style>
    /* Mendukung landscape orientation untuk print preview */
    body.landscape #print-modal {
        width: 1122px;
        margin: 0 0 0 -589px;
    }

    /* Override overflow hidden untuk enable scrolling */
    body.landscape #print-modal-content {
        overflow: auto !important;
    }

    @media print {
        @page {
            margin: 0.5cm;
        }

        body {
            margin: 0;
            padding: 0;
        }
    }
</style>
@endpush
@endif

@push('css')
<style>
    body {
        orientation: landscape;
    }

    .textx {
        mso-number-format: "\@";
    }

    td,
    th {
        font-size: 8pt;
        mso-number-format: "\@";
    }

    table#ttd td {
        text-align: center;
        white-space: nowrap;
    }

    .underline {
        text-decoration: underline;
    }

    @page {
        size: landscape;
        margin: 1cm;
    }
</style>
@endpush

@section('content')
    @include('admin.bumindes.penduduk.rekapitulasi._content')
@endsection