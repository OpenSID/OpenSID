<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <title>{{ ucwords($file) }}</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <link href="{{ base_url('assets/css/report.css') }}" rel="stylesheet">
    @include('admin.layouts.components.headjs')
    @stack('css')
    @stack('scripts')
</head>

<body>
    <div id="container">
        <link href="{{ asset('css/report.css') }}" rel="stylesheet" type="text/css">
        <!-- Print Body -->
        <div id="body">
            <div align="center">
                <h3>KARTU RUMAH TANGGA</h3>
                <h4>SALINAN</h4>
                <h5>No. {{ $main['no_kk'] }} </h5>
            </div>
            <br>
            <table width="100%" cellpadding="3" cellspacing="4">
                <tr>
                    <td width="100">Nama KK</td>
                    <td width="600">: {{ strtoupper($kepala_kk['nama']) }}</td>
                    <td width="160">Kecamatan</td>
                    <td width="150">: {{ strtoupper($desa['nama_kecamatan']) }}</td>
                </tr>
                <tr>
                    <td>Alamat</td>
                    <td>: {{ strtoupper($kepala_kk['keluarga']['wilayah']['dusun']) }} </td>
                    <td>Kabupaten/Kota</td>
                    <td>: {{ $desa['nama_kabupaten'] }}</td>
                </tr>
                <tr>
                    <td>RT / RW</td>
                    <td>: {{ $kepala_kk['keluarga']['wilayah']['rt'] }} / {{ $kepala_kk['keluarga']['wilayah']['rw'] }}
                    </td>
                    <td>Kode Pos</td>
                    <td>: {{ strtoupper($desa['kode_pos']) }}</td>
                </tr>
                <tr>
                    <td>Kelurahan/Desa</td>
                    <td>: {{ strtoupper($desa['nama_desa']) }}</td>
                    <td>Provinsi</td>
                    <td>: {{ strtoupper($desa['nama_propinsi']) }}</td>
                </tr>
            </table>
            <br>
            <table class="border thick ">
                <thead>
                    <tr class="border thick">
                        <th width="7">No</th>
                        <th width='180'>Nama</th>
                        <th width='100'>NIK</th>
                        <th width='100'>NOMOR KK</th>
                        <th width='100'>Jenis Kelamin</th>
                        <th width='100'>Tempat Lahir</th>
                        <th width='120'>Tanggal Lahir</th>
                        <th width='100'>Agama</th>
                        <th width='100'>Pendidikan</th>
                        <th width='100'>Pekerjaan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($main as $key => $data)
                        <tr>
                            <td align="center" width="2">{{ $key + 1 }}</td>
                            <td>{{ strtoupper($data['nama']) }}</td>
                            <td>{{ $data['nik'] }}</td>
                            <td>{{ $data['keluarga']['no_kk'] ?? '' }}</td>
                            <td>{{ strtoupper(App\Enums\JenisKelaminEnum::valueOf($data['sex'])) }}</td>
                            <td>{{ $data['tempatlahir'] }}</td>
                            <td>{{ $data['tanggallahir'] }}</td>
                            <td>{{ $data['agama']['nama'] ?? '' }}</td>
                            <td>{{ $data['pendidikan_k_k']['nama'] ?? '' }}</td>
                            <td>{{ $data['pekerjaan']['nama'] ?? '' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <br>
            <table class="border thick ">
                <thead>
                    <tr class="border thick">
                        <th width="7">No</th>
                        <th width='150'>Status Perkawinan</th>
                        <th width='240'>Status Hubungan dalam Keluarga</th>
                        <th width='140'>Kewarganegaraan</th>
                        <th width='170'>Nama Ayah</th>
                        <th width='170'>Nama Ibu</th>
                        <th width='70'>Golongan darah</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($main as $key => $data)
                        <tr class="data">
                            <td align="center" width="2">{{ $key + 1 }}</td>
                            <td>{{ $data['status_kawin']['nama'] ?? '' }}</td>
                            <td>{{ App\Enums\HubunganRTMEnum::valueOf($data['rtm_level']) }}</td>
                            <td>{{ $data['warga_negara']['nama'] ?? '' }}</td>
                            <td>{{ strtoupper($data['nama_ayah']) }}</td>
                            <td>{{ strtoupper($data['nama_ibu']) }}</td>
                            <td align="center">{{ $data['golongan_darah']['nama'] ?? '' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <br>
            <table width="100%" cellpadding="3" cellspacing="4">
                <tr>
                    <td width="25%"></td>
                    <td width="50%"></td>
                    <td width="25%" align="center">{{ $desa['nama_desa'] }}, {{ tgl_indo(date('Y m d')) }}</td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td width="25%" align="center">KEPALA RUMAH TANGGA</td>
                    <td width="50%"></td>
                    <td align="center" width="150">
                        {{ strtoupper(setting('sebutan_kepala_desa') . ' ' . $desa['nama_desa']) }}</td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td width="25%" align="center">{{ strtoupper($kepala_kk['nama']) }}</td>
                    <td width="50%"></td>
                    <td width="25%" align="center" width="150">{{ strtoupper($desa['nama_kepala_desa']) }}</td>
                </tr>
            </table>
        </div>
        <label>Tanggal cetak : &nbsp; </label>{{ tgl_indo(date('Y m d')) }}
    </div>
    <div id="aside"></div>
</body>
