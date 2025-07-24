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

            <div class="header" align="center">
                <label align="left"><?= get_identitas() ?></label>
                <h3>
                    <span>AGENDA SURAT MASUK</span>
                    @if ($tahun)
                        TAHUN {{ $tahun }}
                    @endif
                </h3>
                <br>
            </div>
            <table class="border thick">
                <thead>
                    <tr class="border thick">
                        <th>Nomor Urut</th>
                        <th>Tanggal Penerimaan</th>
                        <th>Nomor Surat</th>
                        <th>Tanggal Surat</th>
                        <th>Pengirim</th>
                        <th>Isi Singkat</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($main as $data)
                        <tr>
                            <td><?= $data['nomor_urut'] ?></td>
                            <td><?= tgl_indo($data['tanggal_penerimaan']) ?></td>
                            <td><?= $data['nomor_surat'] ?></td>
                            <td><?= tgl_indo($data['tanggal_surat']) ?></td>
                            <td><?= $data['pengirim'] ?></td>
                            <td><?= $data['isi_singkat'] ?></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>
