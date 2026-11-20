@php
    if (empty($ekstensi)) {
        $ekstensi = 'xls';
    }

    if ($aksi == 'unduh') {
        header('Content-type: application/' . $ekstensi);
        header('Content-Disposition: attachment; filename=' . namafile($file) . '.' . $ekstensi);
        header('Pragma: no-cache');
        header('Expires: 0');
    }
@endphp

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
@if ($aksi == 'cetak' && !isset($headjs))
    @include('admin.layouts.components.headjs')
@elseif ($aksi == 'cetak' && $headjs)
    @include('admin.layouts.components.headjs')
@endif

<head>
    <title>@yield('title', 'Dokumen Cetak')</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <link rel="shortcut icon" href="{{ favico_desa() }}" />
    <link href="{{ asset('css/report.css') }}" rel="stylesheet" type="text/css" media="all">
    
    @hasSection('styles')
        @yield('styles')
    @else
        <!-- Default styles -->
        <style>
            td,
            th {
                mso-number-format: "\@";
            }

            td.bold {
                font-weight: bold;
            }

            td.underline {
                border-bottom: solid 1px;
            }
        </style>
    @endif
    
    @stack('css')
    @stack('scripts')
</head>

<body @if (isset($is_landscape) && $is_landscape) class="landscape" @endif>
    <div id="container">
        <!-- Print Body -->
        <div id="body">
            @hasSection('header')
                @yield('header')
            @endif

            @hasSection('document_info')
                @yield('document_info')
            @endif

            @yield('content')

            @hasSection('signature')
                @yield('signature')
            @else
                @if(isset($letak_ttd) && $letak_ttd && count($letak_ttd) > 0)
                    @include('admin.layouts.components.blok_ttd_pamong', [
                        'total_col' => $total_col ?? 13, 
                        'spasi_kiri' => $spasi_kiri ?? 3, 
                        'spasi_tengah' => $spasi_tengah ?? 6
                    ])
                @endif
            @endif
        </div>
    </div>
</body>

</html>