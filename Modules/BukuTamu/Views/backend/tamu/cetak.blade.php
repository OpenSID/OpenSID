<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <title>Buku Tamu</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="robots" content="noindex">
    <link rel="shortcut icon" href="{{ favico_desa() }}" />
    <link href="{{ asset('css/report.css') }}" rel="stylesheet">
</head>

<body onload="window.print()">
    <table>
        <tbody>
            <tr>
                <td>
                    <img class="logo" src="{{ gambar_desa($desa->logo) }}" alt="logo-desa">
                    <h1 class="judul">
                        PEMERINTAH
                        {!! strtoupper(setting('sebutan_kabupaten') . ' ' . $desa->nama_kabupaten . ' <br>' . setting('sebutan_kecamatan') . ' ' . $desa->nama_kecamatan . ' <br>' . setting('sebutan_desa') . ' ' . $desa->nama_desa) !!}
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
                    <h4><u>BUKU TAMU</u></h4>
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
                                <th nowrap>NO</th>
                                <th nowrap>HARI / TANGGAL </th>
                                <th nowrap>NAMA</th>
                                <th nowrap>TELEPON</th>
                                <th nowrap>INSTANSI</th>
                                <th nowrap>JENIS KELAMIN</th>
                                <th nowrap>ALAMAT</th>
                                <th nowrap>KEPERLUAN</th>
                                <th nowrap>FOTO</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data_tamu as $no => $tamu)
                                <tr>
                                    <td width="1%"class="text-center">{{ $no + 1 }}</td>
                                    <td width="15%">
                                        {{ \Carbon\Carbon::parse($tamu->created_at)->dayName . ' / ' . tgl_indo($tamu->created_at) . ' - ' . \Carbon\Carbon::parse($tamu->created_at)->format('H:i:s') }}
                                    </td>
                                    <td width="20%">{{ $tamu->nama }}</td>
                                    <td width="15%">{{ $tamu->telepon }}</td>
                                    <td>{{ $tamu->instansi }}</td>
                                    <td width="5%">
                                        {{ \App\Enums\JenisKelaminEnum::all()[$tamu->jenis_kelamin] }}</td>
                                    <td>{{ $tamu->alamat }}</td>
                                    <td>{{ $tamu->keperluan }}</td>
                                    <td width="1%"class="text-center"><img src="{{ $tamu->url_foto }}" alt="foto" width="50px"></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
</body>

</html>
