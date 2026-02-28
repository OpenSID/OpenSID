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
                            <h4>BUKU KADER PEMBERDAYAAN MASYARAKAT</h4>
                        </td>
                    </tr>
                    @if ($tahun)
                        <tr>
                            <td class="text-center">
                                <h4>TAHUN {{ $tahun }}</h4>
                            </td>
                        </tr>
                    @endif
                    <tr>
                        <td>&nbsp;</td>
                    </tr>
                    <tr>
                        <td>
                            <table class="border thick">
                                <thead>
                                    <tr class="border thick">
                                        <th>No. Urut</th>
                                        <th>Nama</th>
                                        <th>Umur</th>
                                        <th>Jenis Kelamin</th>
                                        <th>Pendidikan / Kursus</th>
                                        <th>Bidang</th>
                                        <th>Alamat</th>
                                        <th>Keterangan</th>
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
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($main as $key => $data)
                                        <tr>
                                            <td align="center">{{ $key + 1 }}</td>
                                            <td class="textx">{{ $data->penduduk->nama }}</td>
                                            <td align="center">{{ usia($data->penduduk->tanggallahir, null, '%y') }}
                                            </td>
                                            <td align="center">{{ $data->penduduk->sex == 1 ? 'L' : 'P' }}</td>
                                            <td>{!! str_replace(
                                                ',',
                                                ', ',
                                                App\Enums\PendidikanKKEnum::valueOf($data->penduduk->pendidikan_kk_id) .
                                                    '<br/>' .
                                                    preg_replace('/[^a-zA-Z, ]/', '', $data->kursus),
                                            ) !!}</td>
                                            <td>{{ str_replace(',', ', ', preg_replace('/[^a-zA-Z, ]/', '', $data->bidang)) }}
                                            </td>
                                            <td>{{ $data->penduduk->alamat_wilayah }}</td>
                                            <td>{{ $data->keterangan }}</td>
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
