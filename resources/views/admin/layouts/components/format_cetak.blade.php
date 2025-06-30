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
    <title>{{ ucwords($file) }}</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <link href="{{ base_url('assets/css/report.css') }}" rel="stylesheet">
    @stack('css')
    @stack('scripts')
</head>

<body>
    <div id="container">
        <div id="body"> @include($isi) </div>
        @if ($letak_ttd && count($letak_ttd) > 0)
            <br />
            <table width="10%" style="text-align: center; @isset($ispdf) margin: auto; @endisset">
                <tr>
                    <td colspan="{{ $letak_ttd[0] }}" width={{ $width ? '"' . 0.1 * $width . 'mm;"' : '5%' }}>&nbsp;</td>
                    @if (!empty($pamong_ketahui))
                        <td colspan="{{ $letak_ttd[1] }}" width={{ $width ? '"' . 0.2 * $width . 'mm;"' : '40%' }}>
                            MENGETAHUI
                            <br>{{ strtoupper($pamong_ketahui['pamong_jabatan'] . ' ' . $desa['nama_desa']) }}
                            <br><br><br><br>
                            <br><u>{{ strtoupper($pamong_ketahui['nama'] ?? $pamong_ketahui['pamong_nama']) }}</u>
                            <br>{{ setting('sebutan_nip_desa') }}/NIP : {{ $pamong_ketahui['pamong_nip'] }}
                        </td>
                    @endif
                    <td colspan="{{ $letak_ttd[2] }}" @isset($ispdf) style="width: 700px" @else width={{ $width ? '"' . 0.4 * $width . 'mm;"' : '10%' }} @endisset>&nbsp;</td>
                    <td width={{ $width ? '"' . 0.2 * $width . 'mm;"' : '40%' }} nowrap>
                        {{ strtoupper($desa['nama_desa'] . ', ' . tgl_indo($tgl_cetak ? date('Y m d', strtotime($tgl_cetak)) : date('Y m d'))) }}
                        <br>{{ strtoupper($pamong_ttd['pamong_jabatan'] . ' ' . $desa['nama_desa']) }}
                        <br><br><br><br>
                        <br><u>{{ strtoupper($pamong_ttd['nama'] ?? $pamong_ttd['pamong_nama']) }}</u>
                        <br>{{ setting('sebutan_nip_desa') }}/NIP : {{ $pamong_ttd['pamong_nip'] }}
                    </td>
                    <td width={{ $width ? '"' . 0.1 * $width . 'mm;"' : '5%' }}>&nbsp;</td>
                </tr>
            </table>
        @endif
    </div>
</body>

</html>
