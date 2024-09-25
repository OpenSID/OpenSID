<?php

defined('BASEPATH') || exit('No direct script access allowed');

/*
 * File ini:
 *
 * View Log Penduduk untuk modul Kependudukan > Penduduk
 *
 * resources/views/admin/penduduk_log/cetak.blade.php
 */

/*
 * File ini bagian dari:
 *
 * OpenSID
 *
 * Sistem informasi desa sumber terbuka untuk memajukan desa
 *
 * Aplikasi dan source code ini dirilis berdasarkan lisensi GPL V3
 *
 * Hak Cipta 2009 - 2015 Combine Resource Institution (http://lumbungkomunitas.net/)
 * Hak Cipta 2016 - 2020 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 *
 * Dengan ini diberikan izin, secara gratis, kepada siapa pun yang mendapatkan salinan
 * dari perangkat lunak ini dan file dokumentasi terkait ("Aplikasi Ini"), untuk diperlakukan
 * tanpa batasan, termasuk hak untuk menggunakan, menyalin, mengubah dan/atau mendistribusikan,
 * asal tunduk pada syarat berikut:
 *
 * Pemberitahuan hak cipta di atas dan pemberitahuan izin ini harus disertakan dalam
 * setiap salinan atau bagian penting Aplikasi Ini. Barang siapa yang menghapus atau menghilangkan
 * pemberitahuan ini melanggar ketentuan lisensi Aplikasi Ini.
 *
 * PERANGKAT LUNAK INI DISEDIAKAN "SEBAGAIMANA ADANYA", TANPA JAMINAN APA PUN, BAIK TERSURAT MAUPUN
 * TERSIRAT. PENULIS ATAU PEMEGANG HAK CIPTA SAMA SEKALI TIDAK BERTANGGUNG JAWAB ATAS KLAIM, KERUSAKAN ATAU
 * KEWAJIBAN APAPUN ATAS PENGGUNAAN ATAU LAINNYA TERKAIT APLIKASI INI.
 *
 * @copyright	  Hak Cipta 2009 - 2015 Combine Resource Institution (http://lumbungkomunitas.net/)
 * @copyright	  Hak Cipta 2016 - 2020 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license	http://www.gnu.org/licenses/gpl.html	GPL V3
 *
 * @see 	https://github.com/OpenSID/OpenSID
 */

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <title>Data Log Penduduk</title>
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
            font-size: 6.5pt;
            mso-number-format: "\@";
        }
    </style>
</head>

<body>
    <div id="container">
        <!-- Print Body -->
        <div id="body">
            <div class="header" align="center">
                <label align="left">{{ get_identitas() }}</label>
                <h3> DAFTAR PENDUDUK YANG STATUS DASARNYA MATI, HILANG ATAU PINDAH</h3>
                <br>
            </div>
            <table class="border thick">
                <thead>
                    <tr class="border thick">
                        <th>No</th>
                        <th>NIK</th>
                        <th>Nama</th>
                        <th>No. KK / Nama KK</th>
                        <th>{{ ucwords(setting('sebutan_dusun')) }}</th>
                        <th>RW</th>
                        <th>RT</th>
                        <th>Umur</th>
                        <th>Status Menjadi</th>
                        <th>Tanggal Peristiwa</th>
                        <th>Tanggal Rekam</th>
                        <th>Catatan Peristiwa</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($main as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $privasi_nik ? sensor_nik_kk($item->penduduk->nik) : $item->penduduk->nik }}</td>
                            <td>{{ strtoupper($item->penduduk->nama) }}</td>
                            <td>
                                {{ $privasi_nik ? sensor_nik_kk($item->keluarga->no_kk) : $item->keluarga->no_kk }}
                                {{ ' / ' . strtoupper($item?->penduduk?->keluarga?->kepalaKeluarga?->nama) }}
                            </td>
                            <td>{{ strtoupper($item->penduduk->wilayah->dusun) }}</td>
                            <td>{{ $item->penduduk->wilayah->rw }}</td>
                            <td>{{ $item->penduduk->wilayah->rt }}</td>
                            <td align="right">{{ $item?->penduduk?->umur }}</td>
                            <td>{{ \App\Models\LogPenduduk::kodePeristiwaAll($item->kode_peristiwa) }}</td>
                            <td>{{ tgl_indo($item->tgl_peristiwa) }}</td>
                            <td>{{ tgl_indo($item->tgl_lapor) }}</td>
                            <td>{{ $item->catatan }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <br>
        <label>Tanggal cetak : &nbsp; </label>{{ tgl_indo(date('Y m d')) }}
    </div>

</body>

</html>
