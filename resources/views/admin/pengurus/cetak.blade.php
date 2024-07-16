<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <title>BUKU {{ strtoupper(setting('sebutan_pemerintah_desa')) }}</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <link rel="shortcut icon" href="{{ favico_desa() }}" />
    <link href="{{ asset('css/report.css') }}" rel="stylesheet" type="text/css">
    <!-- TODO: Pindahkan ke external css -->
    <style>
        .textx {
            mso-number-format: "\@";
        }

        td.bold {
            font-weight: bold;
        }

        td.underline {
            border-bottom: solid 1px;
        }
    </style>
</head>

<body>
    <div id="container">
        <!-- Print Body -->
        <div id="body">
            <div align="center">
                <h3> BUKU {{ strtoupper(setting('sebutan_pemerintah_desa')) }} </h3>
            </div>
            <table>
                <col span="12" style="width: 7.75%;">
                <col style="width: 7%;">
                <tr>
                    <td colspan="12">&nbsp;</td>
                    <td style="border: solid 1px black; font-size: 14px; text-align: center; padding: 5px 0px;">Model A.4</td>
                </tr>
            </table>
            <table>
                <tr>
                    <td colspan="2" class="bold" style="width: 13%;">{{ ucwords(setting('sebutan_desa')) }}</td>
                    <td colspan="1" style="width: 10%; white-space: nowrap;"> : {{ $desa['nama_desa'] }}</td>
                    <td colspan="10">&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="2" class="bold">{{ ucwords(setting('sebutan_kecamatan')) }}</td>
                    <td colspan="1"> : {{ $desa['nama_kecamatan'] }}</td>
                    <td colspan="10">&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="2" class="bold">{{ ucwords(setting('sebutan_kabupaten')) }}</td>
                    <td colspan="1"> : {{ $desa['nama_kabupaten'] }}</td>
                    <td colspan="10">&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="2" class="bold">Bulan / Tahun</td>
                    <td colspan="1"> : {{ getBulan(date('m')) . ' / ' . date('Y') }}</td>
                    <td colspan="10">&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="13">&nbsp;</td>
                </tr>
            </table>
            <table class="border thick">
                <thead>
                    <tr class="border thick">
                        <th width="3%">NO</th>
                        <th width="10%">NAMA</th>
                        <th>{{ setting('sebutan_nip_desa') }}</th>
                        <th>NIP</th>
                        <th>JENIS KELAMIN</th>
                        <th>TEMPAT TANGGAL LAHIR</th>
                        <th>AGAMA</th>
                        <th width="5%">PANGKAT/ GOLONGAN</th>
                        <th>JABATAN</th>
                        <th>PENDIDIKAN TERAKHIR</th>
                        <th width="10%">NOMOR DAN TANGGAL KEPUTUSAN PENGANGKATAN</th>
                        <th width="10%">NOMOR DAN TANGGAL KEPUTUSAN PEMBERHENTIAN</th>
                        <th width="7%">KETERANGAN (Periode/Masa Jabatan)</th>
                    </tr>
                    <tr>
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
                        <th>11</th>
                        <th>12</th>
                        <th>13</th>
                </thead>
                <tbody>
                    @foreach ($main as $key => $data)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $data->pamong_nama }}</td>
                            <td class="textx">{{ $data->pamong_niap }}</td>
                            <td class="textx">{{ $data->pamong_nip }}</td>
                            <td>{{ App\Enums\JenisKelaminEnum::valueOf($data->pamong_sex) }}</td>
                            <td>{{ $data->pamong_tempatlahir . ', ' . tgl_indo_out($data->pamong_tanggallahir) }}</td>
                            <td>{{ App\Enums\AgamaEnum::valueOf($data->pamong_agama) }}</td>
                            <td>{{ $data->pamong_pangkat }}</td>
                            <td>{{ $data->jabatan->nama }}</td>
                            <td>{{ App\Enums\PendidikanKKEnum::valueOf($data->pamong_pendidikan) }}</td>
                            <td>{{ $data->pamong_nosk . ', ' . tgl_indo_out($data->pamong_tglsk) }}</td>
                            <td>{{ $data->pamong_nohenti . ', ' . tgl_indo_out($data->pamong_tglhenti) }}</td>
                            <td>{{ $data->pamong_masajab }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            @include('admin.layouts.components.blok_ttd_pamong', ['total_col' => 13, 'spasi_kiri' => 3, 'spasi_tengah' => 6])
        </div>
    </div>
</body>

</html>
