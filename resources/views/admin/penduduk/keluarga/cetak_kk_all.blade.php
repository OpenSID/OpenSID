<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <title>{{ ucwords($file) }}</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <link href="{{ base_url('assets/css/report.css') }}" rel="stylesheet">
    @include('admin.layouts.components.headjs')
    @stack('css')
    @stack('scripts')
</head>

<body>
    <div id="container">
        <link href="{{ asset('css/report.css') }}" rel="stylesheet" type="text/css">
        @foreach ($all_kk as $kk)
            @include('admin.penduduk.keluarga.cetak_kk', $kk)
        @endforeach
    </div>
</body>
