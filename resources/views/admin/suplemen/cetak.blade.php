<?php

defined('BASEPATH') || exit('No direct script access allowed');

/*
 * File ini:
 *
 * View untuk cetak/unduh laporan modul suplemen
 *
 * donjo-app/views/suplemen/cetak.php,
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
<table>
    <tbody>
        <tr>
            <td>
                @if ($aksi != 'unduh')
                    <img class="logo" src="{{ gambar_desa($desa['logo']) }}" alt="logo-desa">
                @endif
                <h1 class="judul">
                    PEMERINTAH {!! strtoupper(setting('sebutan_kabupaten') . ' ' . $desa['nama_kabupaten'] . ' <br>' . setting('sebutan_kecamatan') . ' ' . $desa['nama_kecamatan'] . ' <br>' . setting('sebutan_desa') . ' ' . $desa['nama_desa']) !!}
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
                <h4><u>Daftar Terdata Suplemen {{ set_ucwords($suplemen['nama']) }}</u></h4>
            </td>
        </tr>
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
