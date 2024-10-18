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
    <div id="content" class="container_12 clearfix">
        <div id="content-main" class="grid_7">
            <link href="{{ asset('css/surat.css') }}" rel="stylesheet" type="text/css" />
            <table width="100%" style="border: solid 0px black; text-align: left; margin-bottom: -15px;">
                <tr>
                    <td width="8%">NIK</td>
                    <td width="2%">:</td>
                    <td width="90%">{{ get_nik($penduduk->nik) }}</td>
                </tr>
                <tr>
                    <td width="8%">No.KK</td>
                    <td width="2%">:</td>
                    <td width="90%">{{ $penduduk->keluarga->no_kk }}</td>
                    </td>
                </tr>
            </table>
            <table width="100%" style="border: solid 0px black; text-align: center;">
                <tr>
                    <td align="center"><img src="{{ gambar_desa($desa['logo']) }}" alt="{{ $desa['nama_desa'] }}" class="logo_mandiri">
                </tr>
                <tr>
                    </td>
                    <td>
                        <h3>BIODATA PENDUDUK WARGA NEGARA INDONESIA</h3>
                        <h5>{{ ucwords(setting('sebutan_kabupaten_singkat')) }} {{ $desa['nama_kabupaten'] }}, {{ ucwords(setting('sebutan_kecamatan_singkat')) }} {{ $desa['nama_kecamatan'] }}, {{ ucwords(setting('sebutan_desa')) }} {{ $desa['nama_desa'] }}</h5>
                    </td>
                </tr>
            </table>
            <table width="100%" style="border: solid 0px black; padding: 10px;">
                <tr>
                    <td><b>DATA PERSONAL</b></td>
                </tr>
                <tr>
                    <td width="220">Nama Lengkap</td>
                    <td width="2%">:</td>
                    <td>{{ strtoupper($penduduk->nama) }}</td>
                    <td rowspan="18" style="vertical-align: top;">
                        @if ($penduduk->foto)
                            <img class="pas_foto" src="{{ AmbilFoto($penduduk->foto, '', $penduduk->id_sex) }}" alt="{{ $penduduk->foto }}" style="width: 100%; max-width: 150px; height: auto; border: solid 2px black;" />
                        @endif
                    </td>
                </tr>
                <tr>
                    <td>Tempat Lahir</td>
                    <td>:</td>
                    <td>{{ strtoupper($penduduk->tempatlahir) }}</td>
                </tr>
                <tr>
                    <td>Tanggal Lahir</td>
                    <td>:</td>
                    <td>{{ strtoupper($penduduk->tanggallahir->format('d-m-Y')) }}</td>
                </tr>
                <tr>
                    <td>Jenis Kelamin</td>
                    <td>:</td>
                    <td>{{ strtoupper(App\Enums\JenisKelaminEnum::valueOf($penduduk->sex)) }}</td>
                </tr>
                <tr>
                    <td>Akta lahir</td>
                    <td>:</td>
                    <td>{{ strtoupper($penduduk->akta_lahir) }}</td>
                </tr>
                <tr>
                    <td>Agama</td>
                    <td>:</td>
                    <td>{{ strtoupper($penduduk->agama->nama) }}</td>
                </tr>
                <tr>
                    <td>Pendidikan Terakhir</td>
                    <td>:</td>
                    <td>{{ strtoupper($penduduk->pendidikanKK->nama) }}</td>
                </tr>
                <tr>
                    <td>Pekerjaan</td>
                    <td>:</td>
                    <td>{{ strtoupper($penduduk->pekerjaan->nama) }}</td>
                </tr>
                <tr>
                    <td>Golongan Darah</td>
                    <td>:</td>
                    <td>{{ strtoupper($penduduk->golonganDarah->nama) }}</td>
                </tr>
                <tr>
                    <td>Cacat</td>
                    <td>:</td>
                    <td>{{ strtoupper($penduduk->cacat->nama) }}</td>
                </tr>
                <tr>
                    <td>Status Kawin</td>
                    <td>:</td>
                    <td>{{ strtoupper($penduduk->statusPerkawinan) }}</td>
                </tr>
                <tr>
                    <td>Hubungan dalam Keluarga</td>
                    <td>:</td>
                    <td>{{ strtoupper(App\Enums\SHDKEnum::valueOf($penduduk->kk_level)) }}</td>
                </tr>
                <tr>
                    <td>Warga Negara</td>
                    <td>:</td>
                    <td>{{ strtoupper($penduduk->warganegara->nama) }}</td>
                </tr>
                <tr>
                    <td>Suku/Etnis</td>
                    <td>:</td>
                    <td>{{ strtoupper($penduduk->suku) }}</td>
                </tr>
                <tr>
                    <td>NIK Ayah</td>
                    <td>:</td>
                    <td>{{ strtoupper($penduduk->ayah_nik) }}</td>
                </tr>
                <tr>
                    <td>Nama Ayah</td>
                    <td>:</td>
                    <td>{{ strtoupper($penduduk->nama_ayah) }}</td>
                </tr>
                <tr>
                    <td>NIK Ibu</td>
                    <td>:</td>
                    <td>{{ strtoupper($penduduk->ibu_nik) }}</td>
                </tr>
                <tr>
                    <td>Nama Ibu</td>
                    <td>:</td>
                    <td>{{ strtoupper($penduduk->nama_ibu) }}</td>
                </tr>
                <tr>
                    <td>Status Kependudukan</td>
                    <td>:</td>
                    <td>{{ strtoupper(App\Enums\StatusPendudukEnum::valueOf($penduduk->status)) }}</td>
                </tr>
                <tr>
                    <td>Nomor Telepon/HP</td>
                    <td>:</td>
                    <td>{{ strtoupper($penduduk->telepon) }}</td>
                </tr>
                <tr>
                    <td>Alamat Email</td>
                    <td>:</td>
                    <td>{{ strtoupper($penduduk->email) }}</td>
                </tr>
                <tr>
                    <td>Alamat</td>
                    <td>:</td>
                    <td>{{ strtoupper($penduduk->keluarga->alamat ?? $penduduk->alamat) }}<br>
                        RT. {{ strtoupper($penduduk->wilayah->rt) }} RW. {{ $penduduk->wilayah->rw }}
                        {{ ucwords(setting('sebutan_dusun')) }} {{ strtoupper($penduduk->wilayah->dusun) }}
                    </td>
                </tr>
                <tr>
                    <td colspan="4" style="padding-top: 15px;"><strong>DATA KEPEMILIKAN DOKUMEN</strong></td>
                </tr>
                <tr>
                    <td>Nomor Kartu Keluarga (No.KK)</td>
                    <td>:</td>
                    <td>{{ $penduduk->keluarga->no_kk }}</td>
                </tr>
                <tr>
                    <td>Dokumen Paspor</td>
                    <td>:</td>
                    <td>{{ strtoupper($penduduk->dokumen_pasport) }}</td>
                </tr>
                <tr>
                    <td>Dokumen Kitas</td>
                    <td>:</td>
                    <td>{{ strtoupper($penduduk->dokumen_kitas) }}</td>
                </tr>
                <tr>
                    <td>Akta Perkawinan</td>
                    <td>:</td>
                    <td>{{ strtoupper($penduduk->akta_perkawinan) }}</td>
                </tr>
                <tr>
                    <td>Tanggal Perkawinan</td>
                    <td>:</td>
                    <td>{{ strtoupper($penduduk->tanggalperkawinan) }}</td>
                </tr>
                <tr>
                    <td>Akta Perceraian</td>
                    <td>:</td>
                    <td>{{ strtoupper($penduduk->akta_perceraian) }}</td>
                </tr>
                <tr>
                    <td>Tanggal Perceraian</td>
                    <td>:</td>
                    <td>{{ strtoupper($penduduk->tanggalperceraian) }}</td>
                </tr>
                <tr>
                    <td>Nomor BPJS Ketenagakerjaan</td>
                    <td>:</td>
                    <td>{{ strtoupper($penduduk->bpjs_ketenagakerjaan) }}</td>
                </tr>
            </table>
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td align="center" scope="col" width="40%">Yang Bersangkutan</td>
                    <td align="center" scope="col" width="10%">&nbsp;</td>
                    <td align="center" scope="col" width="50%">{{ ucwords(setting('sebutan_desa') . ' ' . $desa['nama_desa']) . ', ' . tgl_indo(date('Y m d')) }}</td>
                </tr>
                <tr>
                    <td align="center">&nbsp;</td>
                    <td align="center">&nbsp;</td>
                    <td align="center">{{ ucwords(setting('sebutan_kepala_desa') . ' ' . $desa['nama_desa']) }}</td>
                </tr>
                <tr>
                    <td align="center" colspan="3" height="90px">&nbsp;</td>
                </tr>
                <tr>
                    <td align="center"><b>( {{ strtoupper($penduduk->nama) }} )</b></td>
                    <td align="center">&nbsp;</td>
                    <td align="center"><b>( {{ $desa['nama_kepala_desa'] }} )</b></td>
                </tr>
            </table>
        </div>
        <div id="aside">
        </div>
    </div>
</body>
