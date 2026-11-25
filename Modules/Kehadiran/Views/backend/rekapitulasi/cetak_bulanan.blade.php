@extends('admin.layouts.print_layout')

@section('title', 'Rekapitulasi Bulanan Kehadiran')

@section('styles')
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            margin: 10px;
            padding: 10px;
            background: #fff;
            color: #000;
            font-size: 11px;
            font-family: Arial, "Times New Roman", Cambria, sans-serif !important;
        }

        h1, h2, h3 {
            text-align: center;
            text-transform: uppercase;
            font-weight: bold;
            line-height: 1.4;
        }

        h2 {
            font-size: 14px;
            margin-bottom: 5px;
        }

        h3 {
            font-size: 12px;
            margin-bottom: 15px;
            font-weight: normal;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 10px;
        }

        table.border th, table.border td {
            border: 1px solid #000 !important;
            padding: 5px 4px;
            font-size: 10px;
        }

        table.border th {
            text-align: center;
            vertical-align: middle;
            font-weight: bold;
            background-color: #f2f2f2 !important;
            text-transform: uppercase;
        }

        .text-center {
            text-align: center !important;
        }

        .text-right {
            text-align: right !important;
        }

        .text-left {
            text-align: left !important;
        }

        .padat {
            width: 1%;
            white-space: nowrap;
        }

        .font-bold {
            font-weight: bold;
        }

        .signature-section {
            margin-top: 30px;
            width: 100%;
        }

        .signature-table td {
            border: none !important;
            text-align: center;
            vertical-align: top;
            padding: 10px 0;
            font-size: 11px;
        }

        .legend-section {
            margin-top: 20px;
            font-size: 10px;
            line-height: 1.5;
            float: left;
        }

        .legend-title {
            font-weight: bold;
            text-decoration: underline;
            margin-bottom: 5px;
        }

        .page-break {
            page-break-after: always;
        }

        @media print {
            body {
                margin: 0;
                padding: 0;
            }
            .no-print {
                display: none;
            }
        }
    </style>
@endsection

@section('content')
    <h2>REKAPITULASI DAFTAR HADIR KERJA KEPALA DESA DAN PERANGKAT DESA {{ strtoupper(identitas('nama_desa')) }}</h2>
    <h3>BULAN {{ strtoupper($bulanCarbon->getTranslatedMonthName()) }} TAHUN {{ $tahun }}</h3>

    <table class="border">
        <thead>
            <tr>
                <th rowspan="2" class="text-center padat" style="width: 30px;">NO</th>
                <th rowspan="2" class="text-center" style="width: 180px;">NAMA</th>
                <th rowspan="2" class="text-center" style="width: 150px;">JABATAN</th>
                <th rowspan="2" class="text-center" style="width: 70px;">JUMLAH HARI KERJA</th>
                <th rowspan="2" class="text-center" style="width: 70px;">JUMLAH HARI KEHADIRAN</th>
                <th colspan="6" class="text-center">JUMLAH HARI KETIDAKHADIRAN</th>
                <th rowspan="2" class="text-center">KETERANGAN</th>
            </tr>
            <tr>
                <th class="text-center" style="width: 35px;" title="Izin">I</th>
                <th class="text-center" style="width: 35px;" title="Sakit">S</th>
                <th class="text-center" style="width: 35px;" title="Cuti">CT</th>
                <th class="text-center" style="width: 35px;" title="Dinas Luar">DL</th>
                <th class="text-center" style="width: 35px;" title="Tanpa Keterangan">TK</th>
                <th class="text-center" style="width: 45px;">JML</th>
            </tr>
            <tr style="background-color: #e6e6e6;">
                <td class="text-center padat font-bold">1</td>
                <td class="text-center font-bold">2</td>
                <td class="text-center font-bold">3</td>
                <td class="text-center font-bold">4</td>
                <td class="text-center font-bold">5</td>
                <td class="text-center font-bold">6</td>
                <td class="text-center font-bold">7</td>
                <td class="text-center font-bold">8</td>
                <td class="text-center font-bold">9</td>
                <td class="text-center font-bold">10</td>
                <td class="text-center font-bold">11</td>
                <td class="text-center font-bold">12</td>
            </tr>
        </thead>
        <tbody>
            @forelse ($rekap as $index => $row)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $row['nama'] }}</td>
                    <td>{{ $row['jabatan'] }}</td>
                    <td class="text-center">{{ $row['hari_kerja'] }}</td>
                    <td class="text-center">{{ $row['hadir'] }}</td>
                    <td class="text-center">{{ $row['izin'] }}</td>
                    <td class="text-center">{{ $row['sakit'] }}</td>
                    <td class="text-center">{{ $row['cuti'] }}</td>
                    <td class="text-center">{{ $row['dinas_luar'] }}</td>
                    <td class="text-center">{{ $row['tanpa_keterangan'] }}</td>
                    <td class="text-center font-bold">{{ $row['jumlah_absen'] }}</td>
                    <td>{{ $row['keterangan'] }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="12" class="text-center">Tidak ada data untuk periode ini</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="signature-section" style="clear: both; padding-top: 20px;">
        <table class="signature-table" style="width: 100%; border: none !important;">
            <tr style="border: none !important; vertical-align: top;">
                <!-- Kolom 1: Keterangan (Legend) -->
                <td style="width: 30%; border: none !important; text-align: left; padding: 10px 0;">
                    <div style="font-weight: bold; text-decoration: underline; margin-bottom: 5px; font-size: 11px;">Keterangan :</div>
                    <table style="width: 100%; border: none !important; font-size: 10px; line-height: 1.4;">
                        <tr style="border: none !important;">
                            <td style="border: none !important; padding: 1px 5px 1px 0; width: 35px; text-align: left;">a. I</td>
                            <td style="border: none !important; padding: 1px 5px; width: 10px;">:</td>
                            <td style="border: none !important; padding: 1px 5px; text-align: left;">Izin</td>
                        </tr>
                        <tr style="border: none !important;">
                            <td style="border: none !important; padding: 1px 5px 1px 0; text-align: left;">b. S</td>
                            <td style="border: none !important; padding: 1px 5px;">:</td>
                            <td style="border: none !important; padding: 1px 5px; text-align: left;">Sakit</td>
                        </tr>
                        <tr style="border: none !important;">
                            <td style="border: none !important; padding: 1px 5px 1px 0; text-align: left;">c. CT</td>
                            <td style="border: none !important; padding: 1px 5px;">:</td>
                            <td style="border: none !important; padding: 1px 5px; text-align: left;">Cuti</td>
                        </tr>
                        <tr style="border: none !important;">
                            <td style="border: none !important; padding: 1px 5px 1px 0; text-align: left;">d. DL</td>
                            <td style="border: none !important; padding: 1px 5px;">:</td>
                            <td style="border: none !important; padding: 1px 5px; text-align: left;">Dinas Luar</td>
                        </tr>
                        <tr style="border: none !important;">
                            <td style="border: none !important; padding: 1px 5px 1px 0; text-align: left;">e. TK</td>
                            <td style="border: none !important; padding: 1px 5px;">:</td>
                            <td style="border: none !important; padding: 1px 5px; text-align: left;">Tanpa Keterangan</td>
                        </tr>
                    </table>
                </td>

                <!-- Kolom 2: CAMAT (Left Signature) -->
                <td style="width: 35%; border: none !important; text-align: center; padding: 10px 0;">
                    MENGETAHUI:<br>
                    CAMAT {{ strtoupper(identitas('nama_kecamatan')) }}<br><br><br><br><br>
                    <strong><u>....................................................</u></strong><br>
                    NIP. ............................................
                </td>

                <!-- Kolom 3: KEPALA DESA (Right Signature) -->
                <td style="width: 35%; border: none !important; text-align: center; padding: 10px 0;">
                    @if (!empty($pamong_ketahui))
                        {{ strtoupper(identitas('nama_desa')) }}, {{ tgl_indo(date('Y-m-d')) }}<br>
                        MENGETAHUI:<br>
                        {{ strtoupper($pamong_ketahui['pamong_jabatan'] ?? '') }}<br><br><br><br><br>
                        <strong><u>{{ strtoupper($pamong_ketahui['pamong_nama'] ?? $pamong_ketahui['nama'] ?? '') }}</u></strong><br>
                        @if (!empty($pamong_ketahui['pamong_nip']))
                            NIP. {{ $pamong_ketahui['pamong_nip'] }}
                        @elseif (!empty($pamong_ketahui['pamong_niap']))
                            NIAP. {{ $pamong_ketahui['pamong_niap'] }}
                        @else
                            NIP/NIAP. -
                        @endif
                    @else
                        {{ strtoupper(identitas('nama_desa')) }}, {{ tgl_indo(date('Y-m-d')) }}<br>
                        MENGETAHUI:<br>
                        KEPALA DESA {{ strtoupper(identitas('nama_desa')) }}<br><br><br><br><br>
                        <strong><u>{{ strtoupper(identitas('nama_kepala_desa')) }}</u></strong><br>
                        {{ identitas('nip_kepala_desa') ? 'NIP. ' . identitas('nip_kepala_desa') : '' }}
                    @endif
                </td>
            </tr>

            <!-- Baris Kedua untuk BUPATI (di bawah KEPALA DESA) -->
            <tr style="border: none !important;">
                <td style="border: none !important; padding: 10px 0;">&nbsp;</td>
                <td style="border: none !important; padding: 10px 0;">&nbsp;</td>
                <td style="border: none !important; text-align: center; padding-top: 30px; padding-bottom: 10px; vertical-align: top;">
                    BUPATI {{ strtoupper(identitas('nama_kabupaten')) }},<br><br><br><br><br>
                    <strong><u>....................................................</u></strong>
                </td>
            </tr>
        </table>
    </div>
@endsection

