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
                        <td>
                            @if ($aksi != 'unduh')
                                <img class="logo" src="{{ gambar_desa($desa['logo']) }}" alt="logo-desa">
                            @endif
                            <h1 class="judul">
                                PEMERINTAH {!! strtoupper(
                                    setting('sebutan_kabupaten') .
                                        ' ' .
                                        $desa['nama_kabupaten'] .
                                        ' <br>' .
                                        setting('sebutan_kecamatan') .
                                        ' ' .
                                        $desa['nama_kecamatan'] .
                                        ' <br>' .
                                        setting('sebutan_desa') .
                                        ' ' .
                                        $desa['nama_desa'],
                                ) !!}
                            </h1>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <hr class="garis">
                        </td>
                    </tr>
                    <tr>
                        <td class="text-center">
                            <h4>Klasifikasi Surat</h4>
                        </td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                    </tr>
                    <tr>
                        <td>
                            <table class="border thick">
                                <thead>
                                    <tr class="border thick">
                                        <th rowspan="2">KODE</th>
                                        <th rowspan="2">NAMA</th>
                                        <th rowspan="2">KETERANGAN</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($main as $key => $data)
                                        <tr>
                                            <td>{{ $data->kode }}</td>
                                            <td>{{ $data->nama }}</td>
                                            <td>{{ $data->uraian }}</td>
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
