<!DOCTYPE html
    PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <title>Data Peraturan {{ ucwords(setting('sebutan_desa')) }}</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <link href="{{ asset('css/report.css') }}" rel="stylesheet" type="text/css">
    <link rel="shortcut icon" href="{{ favico_desa() }}" />
    <!-- TODO: Pindahkan ke external css -->
    <style>
        .textx {
            mso-number-format: "\@";
        }

        td,
        th {
            font-size: 9pt;
        }

        table#ttd td {
            text-align: center;
            white-space: nowrap;
        }

        .underline {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <div id="container">
        <div id="body">
            <table>
                <tbody>
                    <tr>
                        <td align="center">
                            @if ($aksi != 'unduh')
                                <img class="logo" src="{{ gambar_desa($desa['logo']) }}" alt="logo-desa">
                            @endif
                            <h1 class="judul">
                                BUKU EKSPEDISI
                                {{-- {!! $tahun ? '<h3>' . $tahun . '</h3>' : '' !!} --}}
                                {{ strtoupper(setting('sebutan_desa')) . ' ' . strtoupper($desa['nama_desa']) }}
                            </h1>
                        </td>
                    </tr>
                    <tr>
                        <td align="center">
                            <h2>
                                {{ strtoupper(setting('sebutan_kecamatan') . ' ' . $desa['nama_kecamatan'] . ' ' . setting('sebutan_kabupaten') . ' ' . $desa['nama_kabupaten']) }}
                            </h2>
                        </td>
                    </tr>
                    <tr>
                        <td align="center">
                            <h2>
                                {{ empty($tahun) ? '' : 'TAHUN ' . $tahun }}
                            </h2>
                        </td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                    </tr>
                    <tr>
                        <td>
                            <table class="border thick" width="100%">
                                <thead>
                                    <tr class="border thick">
                                        <th>NOMOR URUT</th>
                                        <th>TANGGAL PENGIRIMAN</th>
                                        <th>TANGGAL DAN NOMOR SURAT</th>
                                        <th>ISI SINGKAT SURAT YANG DIKIRIM</th>
                                        <th>DITUJUKAN KEPADA</th>
                                        <th>KETERANGAN</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($main as $key => $data)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ tgl_indo($data['tanggal_pengiriman']) }}</td>
                                            <td>{{ tgl_indo($data['tanggal_surat']) . ' / ' . $data['nomor_surat'] }}</td>
                                            <td>{{ $data['isi_singkat'] }}</td>
                                            <td>{{ $data['tujuan'] }}</td>
                                            <td>{{ $data['keterangan'] }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>