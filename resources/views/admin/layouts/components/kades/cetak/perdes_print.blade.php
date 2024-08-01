<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
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
            <div class="header" align="center">
                <h3>BUKU PERATURAN DI {{ strtoupper(setting('sebutan_desa') . ' ' . $desa['nama_desa']) }}</h3>
                <h3>{{ strtoupper(setting('sebutan_kecamatan') . ' ' . $desa['nama_kecamatan'] . ' ' . setting('sebutan_kabupaten') . ' ' . $desa['nama_kabupaten']) }}</h3>
                <h3>{{ empty($tahun) ? '' : 'TAHUN ' . $tahun }}</h3>
                <br>
            </div>
            <table class="border thick">
                <thead>
                    <tr class="border thick">
                        <th>NOMOR URUT</th>
                        <th>JENIS PERATURAN DI {{ strtoupper(setting('sebutan_desa')) }}</th>
                        <th>NOMOR DAN TANGGAL DITETAPKAN</th>
                        <th>TENTANG</th>
                        <th>URAIAN SINGKAT</th>
                        <th>TANGGAL KESEPAKATAN PERATURAN {{ strtoupper(setting('sebutan_desa')) }}</th>
                        <th>NOMOR DAN TANGGAL DILAPORKAN</th>
                        <th>NOMOR DAN TANGGAL DIUNDANGKAN DALAM LEMBARAN {{ strtoupper(setting('sebutan_desa')) }}</th>
                        <th>NOMOR DAN TANGGAL DIUNDANGKAN DALAM BERITA {{ strtoupper(setting('sebutan_desa')) }}</th>
                        <th>KET.</th>
                    </tr>
                    <tr class="border thick">
                        <th>1</th>
                        <th>2</th>
                        <th>3</th>
                        <th>4</th>
                        <th>5</th>
                        <th>6</th>
                        <th>7</th>
                        <th>8</th>
                        <th>9</th>
                        <th>10</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($main as $no => $data)
                        <tr>
                            <td>{{ $no + 1 }}</td>
                            <td>{{ $data['attr']['jenis_peraturan'] }}</td>
                            <td>{{ 'Nomor ' . strip_kosong($data['attr']['no_ditetapkan']) . ', Tanggal ' . tgl_indo_dari_str($data['attr']['tgl_ditetapkan']) }}</td>
                            <td>{{ $data['nama'] }}</td>
                            <td>{{ $data['attr']['uraian'] }}</td>
                            <td>{{ tgl_indo_dari_str($data['attr']['tgl_kesepakatan']) }}</td>
                            <td>{{ 'Nomor ' . strip_kosong($data['attr']['no_lapor']) . ', Tanggal ' . tgl_indo_dari_str($data['attr']['tgl_lapor']) }}</td>
                            <td>{{ 'Nomor ' . strip_kosong($data['attr']['no_lembaran_desa']) . ', Tanggal ' . tgl_indo_dari_str($data['attr']['tgl_lembaran_desa']) }}</td>
                            <td>{{ 'Nomor ' . strip_kosong($data['attr']['no_berita_desa']) . ', Tanggal ' . tgl_indo_dari_str($data['attr']['tgl_berita_desa']) }}</td>
                            <td>{{ $data['attr']['keterangan'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>
