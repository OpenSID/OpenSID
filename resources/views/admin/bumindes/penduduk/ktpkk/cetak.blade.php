@extends('admin.layouts.print_layout')

@section('title', $file)

@if(isset($is_landscape) && $is_landscape)
@push('css')
<style>
    /* Mendukung landscape orientation untuk print preview */
    body.landscape #print-modal {
        width: 1122px;
        margin: 0 0 0 -589px;
    }

    /* Override overflow hidden untuk enable scrolling */
    body.landscape #print-modal-content {
        overflow: auto !important;
    }

    @media print {
        @page {
            margin: 0.5cm;
        }

        body {
            margin: 0;
            padding: 0;
        }
    }
</style>
@endpush
@endif

@push('css')
<style>
    body {
        orientation: landscape;
    }

    .textx {
        mso-number-format: "\@";
    }

    td,
    th {
        font-size: 8pt;
        mso-number-format: "\@";
    }

    table#ttd td {
        text-align: center;
        white-space: nowrap;
    }

    .underline {
        text-decoration: underline;
    }

    @page {
        size: landscape;
        margin: 1cm;
    }
</style>
@endpush

@section('content')
<table>
    <tbody>
        <tr>
            <td>
                @if ($aksi != 'unduh')
                <img class="logo" src="{{ gambar_desa($desa['logo']) }}" alt="logo-desa">
                @endif
                <h1 class="judul">
                    PEMERINTAH {!! strtoupper(setting('sebutan_kabupaten') . ' ' . $desa['nama_kabupaten'] . ' <br>' .
                    setting('sebutan_kecamatan') . ' ' . $desa['nama_kecamatan'] . ' <br>' . setting('sebutan_desa') . '
                    ' . $desa['nama_desa']) !!}
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
                <h4>B.5 BUKU KARTU TANDA PENDUDUK DAN BUKU KARTU KELUARGA</h4>
            </td>
        </tr>
        @if(!empty($filters['bulan']) || !empty($filters['tahun']))
        <tr>
            <td class="text-center">
                <h4>BUKU KARTU TANDA PENDUDUK DAN BUKU KARTU KELUARGA
                    @if(!empty($filters['bulan']))
                    BULAN {{ strtoupper(getBulan($filters['bulan'])) }}
                    @endif
                    @if(!empty($filters['tahun']))
                    TAHUN {{ $filters['tahun'] }}
                    @endif
                </h4>
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
                            <th rowspan="2" align="center">NOMOR URUT</th>
                            <th rowspan="2">NO. KK</th>
                            <th rowspan="2">NAMA LENGKAP</th>
                            <th rowspan="2">NIK</th>
                            <th rowspan="2">JENIS KELAMIN</th>
                            <th rowspan="2">TEMPAT / TANGGAL LAHIR</th>
                            <th rowspan="2">GOL. DARAH</th>
                            <th rowspan="2">AGAMA</th>
                            <th rowspan="2">PENDIDIKAN</th>
                            <th rowspan="2">PEKERJAAN</th>
                            <th rowspan="2">ALAMAT</th>
                            <th rowspan="2">STATUS PERKAWINAN</th>
                            <th rowspan="2">TEMPAT DAN TANGGAL DIKELUARKAN</th>
                            <th rowspan="2">STATUS HUB. KELUARGA</th>
                            <th rowspan="2">KEWARGANEGARAAN</th>
                            <th colspan="2">ORANG TUA</th>
                            <th rowspan="2">TGL MULAI DI {{ strtoupper(setting('sebutan_desa')) }}</th>
                            <th rowspan="2">KET</th>
                        </tr>
                        <tr class="border thick">
                            <th>AYAH</th>
                            <th>IBU</th>
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
                            <th>11</th>
                            <th>12</th>
                            <th>13</th>
                            <th>14</th>
                            <th>15</th>
                            <th>16</th>
                            <th>17</th>
                            <th>18</th>
                            <th>19</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($main)
                        @foreach ($main as $key => $data)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td class="textx">{{ $privasi_nik ? sensor_nik_kk($data['kk']) : $data['kk'] }}</td>
                            <td>{{ strtoupper($data['nama']) }}</td>
                            <td class="textx">{{ $privasi_nik ? sensor_nik_kk($data['nik']) : $data['nik'] }}</td>
                            <td class="padat">{{ $data['jenis_kelamin_inisial'] }}</td>
                            <td>{{ strtoupper($data['tempatlahir']) . ', ' . $data['tanggallahir'] }}</td>
                            <td class="padat">{{ $data['golongan_darah'] }}</td>
                            <td>{{ $data['agama'] }}</td>
                            <td>{{ $data['pendidikan'] }}</td>
                            <td>{{ $data['pekerjaan'] }}</td>
                            <td>{{ $data['alamat_wilayah'] }}</td>
                            <td>{{ $data['status_perkawinan'] }}</td>
                            <td>{{ empty($data['tempat_cetak_ktp']) ? '-' : strtoupper($data['tempat_cetak_ktp']) . ', '
                                . tgl_indo_out($data['tanggal_cetak_ktp']) }}</td>
                            <td>{{ $data['penduduk_hubungan'] }}</td>
                            <td>{{ $data['warganegara'] }}</td>
                            <td>{{ strtoupper($data['nama_ayah']) }}</td>
                            <td>{{ strtoupper($data['nama_ibu']) }}</td>
                            <td>{{ $data['tgl_datang'] }}</td>
                            <td>{{ $data['log']['ket'] }}</td>
                        </tr>
                        @endforeach
                        @endif
                    </tbody>
                </table>
            </td>
        </tr>
    </tbody>
</table>
@endsection