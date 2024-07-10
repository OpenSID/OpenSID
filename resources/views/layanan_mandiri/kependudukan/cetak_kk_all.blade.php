@extends('layanan_mandiri.layouts.print.headjs')

@section('content')
    <style type="text/css">
        #body {
            page-break-after: always;
        }
    </style>
    <div id="container">
        <link href="{{ asset('css/report.css') }}" rel="stylesheet" type="text/css">
        @foreach ($all_kk as $kk)
            @include('layanan_mandiri.kependudukan.cetak_kk', ['kepala_kk' => $kk['kepala_kk'], 'desa' => $kk['desa'], 'main' => $kk['main']])
        @endforeach
        <div id="aside"></div>
    </div>
@endsection
