@extends('admin.layouts.print_layout')

@section('title', 'Laporan Suplemen ' . set_ucwords($suplemen['nama']))

@section('header')
    <div style="text-align: center; margin-bottom: 10px;">
        @if ($aksi != 'unduh')
            <img class="logo" src="{{ gambar_desa($desa['logo']) }}" alt="logo-desa" style="display: block; margin: 0 auto;">
        @endif
        <h1 class="judul">
            PEMERINTAH {!! strtoupper(setting('sebutan_kabupaten') . ' ' . $desa['nama_kabupaten'] . '<br>' . setting('sebutan_kecamatan') . ' ' . $desa['nama_kecamatan'] . '<br>' . setting('sebutan_desa') . ' ' . $desa['nama_desa']) !!}
        </h1>
    </div>
    <hr class="garis">
    <h4 align="center" style="margin-bottom: 10px;"><u>Daftar Terdata Suplemen {{ set_ucwords($suplemen['nama']) }}</u></h4>
    <br>
@endsection

@section('content')
<table>
    <tbody>
        <tr>
            <td>
                <strong>Sasaran Suplemen : </strong>{{ $sasaran[$suplemen['sasaran']] }}<br>
                <strong>Keterangan : </strong>{{ $suplemen['keterangan'] }}
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
                            <th class="padat">No</th>
                            <th>{{ $suplemen['sasaran'] == 1 ? 'No.' : 'NIK' }} KK</th>
                            <th>{{ $suplemen['sasaran'] == 1 ? 'NIK Penduduk' : 'No. KK' }}</th>
                            <th>{{ $suplemen['sasaran'] == 1 ? 'Nama Penduduk' : 'Kepala Keluarga' }}</th>
                            <th>Tempat Lahir</th>
                            <th>Tanggal Lahir</th>
                            <th>Jenis Kelamin</th>
                            <th>Alamat</th>
                            <th>Keterangan</th>

                            @foreach ($terdata as $item)
                                @php
                                    // Memastikan data_form_isian ada dan berbentuk array
                                    $dataForm = is_array($item['data_form_isian']) ? $item['data_form_isian'] : json_decode($item['data_form_isian'], true);
                                @endphp

                                @if (is_array($dataForm) && !empty($dataForm) && $dataForm !== 'null')
                                    @foreach ($dataForm as $key => $value)
                                        <th>{{ str_replace('_', ' ', ucfirst($key)) }}</th> <!-- Menampilkan key sebagai header -->
                                    @endforeach
                                    @break
                                @endif
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($terdata as $key => $item)
                            <tr>
                                <td class="padat">{{ $key + 1 }}</td>
                                <td class="textx">{{ $item['terdata_info'] }}</td>
                                <td class="textx">{{ $item['terdata_plus'] }}</td>
                                <td>{{ $item['terdata_nama'] }}</td>
                                <td>{{ $item['tempatlahir'] }}</td>
                                <td class="textx">{{ tgl_indo($item['tanggallahir']) }}</td>
                                <td>{{ App\Enums\JenisKelaminEnum::valueOf($item['sex']) }}</td>
                                <td>{{ 'RT/RW ' . $item['rt'] . '/' . $item['rw'] . ' - ' . strtoupper($item['dusun']) }}</td>
                                <td>{{ $item['keterangan'] }}</td>

                                @php
                                    // Cek jika data_form_isian sudah berupa array
                                    $dataForm = is_array($item['data_form_isian']) ? $item['data_form_isian'] : json_decode($item['data_form_isian'], true);
                                @endphp

                                @if (is_array($dataForm) && !empty($dataForm) && $dataForm !== 'null')
                                    @foreach ($dataForm as $value)
                                        <td>{{ $value }}</td> <!-- Menampilkan value berdasarkan header yang sudah ada -->
                                    @endforeach
                                @else
                                    <td colspan="1"></td> <!-- Kolom kosong jika dataForm kosong atau error -->
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </td>
        </tr>
    </tbody>
</table>
@endsection

