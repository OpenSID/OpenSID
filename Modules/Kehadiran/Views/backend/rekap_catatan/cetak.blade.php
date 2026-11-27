@extends('admin.layouts.print_layout')

@php
    if (empty($ekstensi)) {
        $ekstensi = 'xls';
    }

    [$tahunParse, $bulanParse] = explode('-', $bulan);
    $bulanCarbon = \Carbon\Carbon::createFromDate((int) $tahunParse, (int) $bulanParse, 1)->locale('id');
@endphp

@section('title', 'Rekap Catatan Harian Kerja')

@section('styles')
    <style>
        * {
            margin: 0;
            padding: 0;
        }

        body {
            margin: 2px;
            padding: 2px;
            background: #fff;
            color: #000;
            font-size: 12px;
            font-family: arial, "times new roman", cambria !important;
        }

        h1 {
            font-size: 18px;
        }

        h4 {
            font-size: 14px;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        table.border {
            border: 1px solid #000;
        }

        table.border.thick {
            border: 2px solid #000;
        }

        table.noborder * {
            border: none !important;
        }

        table.border tr {
            border-bottom: 1px solid #aaa;
        }

        table.border td {
            padding: 2px 5px;
            border: 1px solid #aaa !important;
        }

        th {
            text-transform: uppercase;
            padding: 2px;
            border: 1px solid #000 !important;
            background: #eee;
            font-weight: bold;
        }

        tr.footer {
            text-transform: uppercase;
            font-weight: bold;
            padding: 2px;
            border-top: 1px solid #000 !important;
            background: #eee;
        }

        tr.thick,
        thead tr.thick {
            border-bottom: 2px solid #000 !important;
        }

        td.bilangan,
        th.bilangan,
        td.no_urut {
            text-align: center;
        }

        img.logo {
            width: 100px;
            display: block;
            margin-left: auto;
            margin-right: auto;
        }

        .judul {
            text-transform: uppercase;
            text-align: center;
        }

        th.padat,
        td.padat {
            width: 1px;
            white-space: nowrap;
            text-align: center;
        }

        .text-center,
        th {
            text-align: center;
        }

        hr.garis {
            border-bottom: 2px solid #000000;
            height: 0px;
            margin-top: 5px;
            margin-bottom: 10px;
        }

        .underline {
            text-decoration: underline;
        }

        td,
        th {
            font-size: 9pt;
        }

        table#ttd td {
            text-align: center;
            white-space: nowrap;
        }

        .page-break {
            page-break-after: always;
        }
    </style>
@endsection

@section('header')
    <div style="text-align: center; margin-bottom: 10px;">
        <img class="logo" src="{{ gambar_desa(identitas('logo')) }}" alt="logo-desa"
            onerror="this.style.display='none'">
        <h1 class="judul">
            PEMERINTAH {!! strtoupper(
                setting('sebutan_kabupaten') .
                    ' ' .
                    identitas('nama_kabupaten') .
                    '<br>' .
                    setting('sebutan_kecamatan') .
                    ' ' .
                    identitas('nama_kecamatan') .
                    '<br>' .
                    setting('sebutan_desa') .
                    ' ' .
                    identitas('nama_desa'),
            ) !!}
        </h1>
    </div>
    <hr class="garis">
    <h4 align="center" style="margin-bottom: 10px;"><u>CATATAN HARIAN KERJA</u></h4>
    <p style="text-align: center; font-size: 10pt; margin-bottom: 20px;">
        <strong>{{ $bulanCarbon->getTranslatedMonthName() }} {{ $tahunParse }}</strong>
    </p>
@endsection

@section('content')

    <table>
        <tbody>
            <tr>
                <td style="padding: 5px 20px;">
                    <table border=1 class="border thick">
                        <thead>
                            <tr class="border thick">
                                <th>NO</th>
                                <th>TANGGAL</th>
                                <th>NAMA PERANGKAT</th>
                                <th>JABATAN</th>
                                <th>URAIAN KEGIATAN</th>
                                <th>HASIL YANG DIHARAPKAN</th>
                                <th>KETERANGAN</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($catatans as $no => $catatan)
                                <tr>
                                    <td align="center" width="3%">{{ $no + 1 }}</td>
                                    <td align="center" width="8%">
                                        {{ $catatan->hari . ', ' . $catatan->tanggal?->format('d/m/Y') ?? '-' }}
                                    </td>
                                    <td width="15%">{{ $catatan->pamong?->penduduk?->nama ?? ($catatan->pamong?->pamong_nama ?? '-') }}</td>
                                    <td width="15%">{{ $catatan->pamong?->jabatan?->nama ?? '-' }}</td>
                                    <td width="20%">{{ $catatan->uraian_kegiatan ?? '-' }}</td>
                                    <td width="20%">{{ $catatan->hasil_diharapkan ?? '-' }}</td>
                                    <td width="19%"></td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" align="center" style="padding: 20px;">Tidak ada data</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
@endsection

@section('signature')
    <table style="width: 100%; margin-top: 30px;">
        <tr>
            <td colspan="13">&nbsp;</td>
        </tr>
        <tr class="text-center">
            <td colspan="5">&nbsp;</td>
            <td colspan="3" class="nowrap">
                {{ strtoupper(identitas('nama_kecamatan') . ', ' . tgl_indo(date('Y-m-d'))) }}</td>
            <td colspan="5">&nbsp;</td>
        </tr>
        <tr class="text-center">
            <td colspan="5">&nbsp;</td>
            <td colspan="3" class="nowrap">
                {{ strtoupper(setting('sebutan_kepala_desa') . ' ' . identitas('nama_desa')) }}</td>
            <td colspan="5">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="13">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="13">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="13">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="13">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="13">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="13">&nbsp;</td>
        </tr>
        <tr class="text-center">
            <td colspan="5">&nbsp;</td>
            <td colspan="3" class="nowrap"><u>{{ identitas('nama_kepala_desa') }}</u></td>
            <td colspan="5">&nbsp;</td>
        </tr>
        <tr class="text-center">
            <td colspan="5">&nbsp;</td>
            <td colspan="3">{{ identitas('nip_kepala_desa') ? 'NIP : ' . identitas('nip_kepala_desa') : '' }}</td>
            <td colspan="5">&nbsp;</td>
        </tr>
    </table>
@endsection

@push('scripts')
    <script>
        window.addEventListener('load', function() {
            window.print();
        });
    </script>
@endpush
