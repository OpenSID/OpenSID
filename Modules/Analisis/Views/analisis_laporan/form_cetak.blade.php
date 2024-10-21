<?php
if ($aksi == 'unduh') {
    $judul = namafile('Laporan Hasil Analisis ' . $asubjek . ' ' . $subjek['nama']);

    header('Content-type: application/xls');
    header('Content-Disposition: attachment; filename=' . $judul . '.xls');
    header('Pragma: no-cache');
    header('Expires: 0');
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <title>Laporan Hasil Analisis {{ $asubjek }}</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <link href="{{ asset('css/report.css') }}" rel="stylesheet" type="text/css">
</head>

<body>
    <div id="container">
        <!-- Print Body -->
        <div id="body">
            <table>
                <tbody>
                    <tr>
                        <td colspan='6'>
                            @if ($aksi != 'unduh')
                                <img class="logo" src="{{ gambar_desa($config['logo']) }}" alt="logo-desa">
                            @endif
                            <h1 class="judul">
                                PEMERINTAH {!! strtoupper(setting('sebutan_kabupaten') . ' ' . $config['nama_kabupaten'] . ' <br>' . setting('sebutan_kecamatan') . ' ' . $config['nama_kecamatan'] . ' <br>' . setting('sebutan_desa') . ' ' . $config['nama_desa']) !!}
                                <h1>
                        </td>
                    </tr>
                    <tr>
                        <td colspan='6'>
                            <hr class="garis">
                        </td>
                    </tr>
                    <tr>
                        <td colspan='6' class="text-center">
                            <h4><u>Laporan Hasil Analisis {{ $asubjek }}</u></h4>
                        </td>
                    </tr>
                    <tr>
                        <td colspan='6'>
                            <strong>Nomor Identitas : </strong>{{ $subjek['nid'] }}<br>
                            <strong>Nama Subjek : </strong>{{ $subjek['nama'] }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <br>
            <strong>
                <p>DAFTAR ANGGOTA</p>
            </strong>
            <table class="border thick">
                <thead>
                    <tr class="border thick">
                        <th width="10">NO</th>
                        <th align="left">NIK</th>
                        <th align="left">NAMA</th>
                        <th align="left">TANGGAL LAHIR</th>
                        <th align="left">JENIS KELAMIN</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($list_anggota as $ang)
                        <tr>
                            <td align="center" width="2">{{ $loop->iteration }}</td>
                            <td class="textx">{{ $ang['nik'] }}</td>
                            <td width="45%">{{ $ang['nama'] }}</td>
                            <td nowrap>{{ tgl_indo(implode('-', array_reverse(explode('-', $ang['tanggallahir'])))) }}</td>
                            <td>{{ strtoupper(App\Enums\JenisKelaminEnum::valueOf($ang['sex'])) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <br />
            <br />
            <table class="border thick">
                <thead>
                    <tr class="border thick">
                        <th width="10">NO</th>
                        <th align="left">PERTANYAAN / INDIKATOR</th>
                        <th align="left">BOBOT</th>
                        <th align="left">JAWABAN</th>
                        <th align="left">NILAI</th>
                        <th align="left">POIN</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($list_jawab as $data)
                        <tr>
                            <td>{{ $data['no'] }}</td>
                            <td>{{ $data['pertanyaan'] }}</td>
                            <td>{{ $data['bobot'] }}</td>
                            <td>{{ $data['jawaban'] }}</td>
                            <td>{{ $data['nilai'] }}</td>
                            <td>{{ $data['poin'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan='5'><strong>TOTAL</strong></th>
                        <th>{{ $total }}</th>
                    </tr>
                </tfoot>
            </table>
        </div>
        <table>
            <tr>
                <td width="10%">&nbsp;</td>
                <td width="20%">
                    Mengetahui
                    <br>{{ $pamong_ketahui['jabatan'] . ' ' . $config['nama_desa'] }}
                    <br><br><br><br>
                    <br><u>( {{ $pamong_ketahui['pamong_nama'] }} )</u>
                    <br>{{ setting('sebutan_nip_desa') }}/NIP : {{ $pamong_ketahui['pamong_nip'] }}
                </td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td width="20%" nowrap>
                    {{ ucwords(setting('sebutan_desa')) . ' ' . $config['nama_desa'] }}, {{ tgl_indo(date('Y m d')) }}
                    <br>{{ $pamong_ttd['jabatan'] . ' ' . $config['nama_desa'] }}
                    <br><br><br><br>
                    <br><u>( {{ $pamong_ttd['pamong_nama'] }} )</u>
                    <br>{{ setting('sebutan_nip_desa') }}/NIP : {{ $pamong_ketahui['pamong_nip'] }}
                </td>
                <td width="10%">&nbsp;</td>
            </tr>
        </table>

    </div>
</body>

</html>
