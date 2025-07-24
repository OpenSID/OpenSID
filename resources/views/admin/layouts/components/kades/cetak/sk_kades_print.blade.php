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
                                BUKU KEPUTUSAN KEPALA
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
                                        <th>NOMOR DAN TANGGAL KEPUTUSAN KEPALA DESA</th>
                                        <th>TENTANG</th>
                                        <th>URAIAN SINGKAT</th>
                                        <th>NOMOR DAN TANGGAL DILAPORKAN</th>
                                        <th>KET.</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($main as $no => $data)
                                        <tr>
                                            <td>
                                                {{ $no + 1 }}
                                            </td>
                                            <td>
                                                {{ 'Nomor ' . strip_kosong($data['attr']['no_kep_kades']) . ', Tanggal ' . tgl_indo_dari_str($data['attr']['tgl_kep_kades']) }}
                                            </td>
                                            <td>
                                                {{ $data['nama'] }}
                                            </td>
                                            <td>
                                                {{ $data['attr']['uraian'] }}
                                            </td>
                                            <td>
                                                {{ 'Nomor ' . strip_kosong($data['attr']['no_lapor']) . ', Tanggal ' . tgl_indo_dari_str($data['attr']['tgl_lapor']) }}
                                            </td>
                                            <td>
                                                {{ $data['attr']['keterangan'] }}
                                            </td>
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
