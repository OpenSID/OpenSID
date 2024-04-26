<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <title>Dokumentasi Pembangunan</title>

    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <link rel="shortcut icon" href="{{ favico_desa() }}" />
    <link href="{{ asset('css/report.css') }}" rel="stylesheet" type="text/css">
    <style>
        img.gambar-pembangunan {
            width: 600px;
            height: 300px;
            display: block;
            margin-left: auto;
            margin-right: auto;
        }
    </style>
</head>

<body>
    <table>
        <tbody>
            <tr>
                <td class="text-center">
                    <img class="logo " src="{{ gambar_desa($config['logo']) }}" alt="logo-desa">
                    <h1 class="judul">DOKUMENTASI BIDANG PELAKSANAAN PEMBANGUNAN</h1>
                    <h1 class="judul">
                        PEMERINTAH {!! strtoupper(setting('sebutan_kabupaten') . ' ' . $config['nama_kabupaten'] . ' <br>' . setting('sebutan_kecamatan') . ' ' . $config['nama_kecamatan'] . ' <br>' . setting('sebutan_desa') . ' ' . $config['nama_desa']) !!}
                    </h1>
                </td>
            </tr>
            <tr>
                <td>
                    <hr class="garis">
                </td>
            </tr>
            <table>
                <tbody>
                    <tr>
                        <td width="20%"><strong>Nama Kegiatan</strong></td>
                        <td width="1%">:</td>
                        <td><b>{{ $pembangunan->judul }}</b></td>
                    </tr>
                    <tr>
                        <td><strong>Anggaran</strong></td>
                        <td> : </td>
                        <td><b>{{ rupiah($pembangunan->anggaran) }}</b></td>
                    </tr>
                    <tr>
                        <td><strong>Tahun</strong></td>
                        <td> : </td>
                        <td><b>{{ $pembangunan->tahun_anggaran }}</b></td>
                    </tr>
                    <tr>
                        <td><strong>Lokasi Pembangunan</strong></td>
                        <td> : </td>
                        <td> {{ $pembangunan->wilayah->dusun }}</td>
                    </tr>
                    <tr>
                        <td><strong>Sumber Anggaran</strong></td>
                        <td> : </td>
                        <td> {{ $pembangunan->sumber_dana }}</td>
                    </tr>
                    <tr>
                        <td><strong>Volume</strong></td>
                        <td> : </td>
                        <td> {{ $pembangunan->volume }}</td>
                    </tr>
                    <tr>
                        <td><strong>Pelaksana</strong></td>
                        <td> : </td>
                        <td> {{ $pembangunan->pelaksana_kegiatan }}</td>
                    </tr>
                    <tr>
                        <td><strong>Manfaat</strong></td>
                        <td> : </td>
                        <td> {{ $pembangunan->manfaat }}</td>
                    </tr>
                    <tr>
                        <td><strong>Keterangan</strong></td>
                        <td> : </td>
                        <td> {{ $pembangunan->keterangan }}</td>
                    </tr>
                </tbody>
            </table>
            <tr>
                <td>&nbsp;</td>
            </tr>
            <table>
                <tbody>
                    @foreach ($dokumentasi as $value)
                        <tr>
                            <td class="text-center">
                                <h4>{{ $value->keterangan . ' ' . $value->persentase }}</h4>
                                <img class="gambar-pembangunan" src="{{ to_base64(LOKASI_GALERI . $value->gambar) }}" width="400" height="200" alt="{{ $pembangunan->judul }}">
                            </td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            @include('admin.layouts.components.blok_ttd_pamong', ['total_col' => 12, 'spasi_kiri' => 2, 'spasi_tengah' => 6])
        </tbody>
    </table>
</body>

</html>
