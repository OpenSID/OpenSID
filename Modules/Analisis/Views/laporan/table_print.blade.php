<?php defined('BASEPATH') || exit('No direct script access allowed');

/*
 * File ini:
 *
 * View untuk modul Analisis > Analisis Laporan
 *
 * donjo-app/views/analisis_laporan/table_print.php
 *
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
 * @package	OpenSID
 * @author	Tim Pengembang OpenDesa
 * @copyright	  Hak Cipta 2009 - 2015 Combine Resource Institution (http://lumbungkomunitas.net/)
 * @copyright	  Hak Cipta 2016 - 2020 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license	http://www.gnu.org/licenses/gpl.html	GPL V3
 * @link 	https://github.com/OpenSID/OpenSID
 */

?>

<table>
    <tbody>
        <tr>
            <td colspan="8">
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
                <h4><u>Laporan Hasil Analisis {{ $judul['asubjek'] }}</u></h4>
            </td>
        </tr>
    </tbody>
</table>
<br>
<table class="border thick">
    <thead>
        <tr class="border thick">
            <th width="10">NO</th>
            <th align="left">{{ strtoupper($judul['nomor']) }}</th>
            @if (in_array($analisis_master['subjek_tipe'], [App\Enums\AnalisisRefSubjekEnum::PENDUDUK, App\Enums\AnalisisRefSubjekEnum::KELUARGA, App\Enums\AnalisisRefSubjekEnum::RUMAH_TANGGA]))
                <th>{{ $analisis_master['subjek_tipe'] == App\Enums\AnalisisRefSubjekEnum::PENDUDUK ? 'No. KK' : 'NIK KK' }}
                </th>
            @endif
            <th align="left">{{ strtoupper($judul['nama']) }}</th>
            @if (in_array($analisis_master['subjek_tipe'], [1, 2, 3, 4]))
                <th align="left">JENIS KELAMIN</th>
                <th align="left">ALAMAT</th>
            @endif
            <th align="left">NILAI</th>
            <th align="left">KLASIFIKASI</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($main as $data)
            <tr>
                <td align="center" width="2">{{ $loop->iteration }}</td>
                <td class="textx">{{ $data[$judul['kolom'][0]] }}</td>
                @if (in_array($analisis_master['subjek_tipe'], [1, 2, 3]))
                    <td class="textx">{{ $data['kk'] }}</td>
                @endif
                <td>{{ $data[$judul['kolom'][1]] }}</td>
                @if (in_array($analisis_master['subjek_tipe'], [1, 2, 3, 4]))
                    <td align="center">{{ App\Enums\JenisKelaminEnum::valueOf($data['sex']) }}</td>
                    <td>{{ strtoupper($data['alamat'] . ' ' . 'RT/RW ' . $data['rt'] . '/' . $data['rw'] . ' - ' . setting('sebutan_dusun') . ' ' . $data['dusun']) }}</td>
                @endif
                <td align="right">{{ $data['nilai'] ? number_format($data['nilai'], 2, ',', '.') : '-' }}</td>
                <td align="right">{{ $data['klasifikasi'] }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
