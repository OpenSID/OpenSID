<html>

<head>
    <title>KIB C</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <link href="{{ asset('css/report.css') }}" rel="stylesheet" type="text/css">
    <link rel="shortcut icon" href="{{ favico_desa() }}" />
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

        /* Style berikut untuk unduh excel.
    Cetak mengabaikan dan menggunakan style dari report.css
   */
        table#inventaris {
            border: solid 2px black;
        }

        td.border {
            border: dotted 0.5px gray;
        }

        th.border {
            border: solid 0.5pt gray;
        }

        .pull-left {
            position: relative;
            width: 50%;
            float: left;
        }

        .pull-right {
            position: relative;
            width: 50%;
            float: right;
            text-align: right;
            /* padding-right:20px; */
        }
    </style>
</head>

<body>
    <div id="container">

        <!-- Print Body -->
        <div id="body">
            <div class="" align="center">
                <h3> {{ $title }}
                    <br>{{ $tahun }}
                </h3>
            </div>
            <div style="padding-bottom: 35px;">
                <div class="pull-left" style="width: auto">
                    <table>
                        <tr>
                            <td>{{ strtoupper(setting('sebutan_desa')) }}</td>
                            <td style="padding-left: 10px">{{ strtoupper(' : ' . $desa['nama_desa']) }}</td>
                        </tr>
                        <tr>
                            <td>{{ strtoupper(setting('sebutan_kecamatan')) }}</td>
                            <td style="padding-left: 10px">{{ strtoupper(' : ' . $desa['nama_kecamatan']) }}</td>
                        </tr>
                        <tr>
                            <td>{{ strtoupper(setting('sebutan_kabupaten')) }}</td>
                            <td style="padding-left: 10px">{{ strtoupper(' : ' . $desa['nama_kabupaten']) }}</td>
                        </tr>
                    </table>
                </div>
                <div class="pull-right">
                    KODE LOKASI : _ _ . _ _ . _ _ . _ _ . _ _ . _ _ . _ _ _
                </div>

            </div>
            <br>
            <table id="inventaris" class="list border thick">
                <thead style="background-color:#f9f9f9;">
                    <tr>
                        <th align="center" rowspan="3">No</th>
                        <th align="center" rowspan="3">Jenis Barang</th>
                        <th align="center" colspan="5">Asal barang</th>
                        <th align="center" width="40%" rowspan="3">Keterangan</th>

                    </tr>
                    <tr>
                        <th align="center" rowspan="2">Dibeli Sendiri</th>
                        <th align="center" colspan="3">Bantuan</th>
                        <th align="center" rowspan="2">Sumbangan</th>
                    </tr>
                    <tr>
                        <th align="center">Pemerintah</th>
                        <th align="center">Provinsi</th>
                        <th align="center">Kabupaten</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($main as $i => $data)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td>{{ $data['jenis'] }}</td>
                            <td style="text-align:right">{{ $data['pribadi'] }}</td>
                            <td style="text-align:right">{{ $data['pemerintah'] }}</td>
                            <td style="text-align:right">{{ $data['provinsi'] }}</td>
                            <td style="text-align:right">{{ $data['kabupaten'] }}</td>
                            <td style="text-align:right">{{ $data['sumbangan'] }}</td>
                            <td>{{ $data['ket'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

        </div>
    </div> <!-- Container -->

</body>

</html>
