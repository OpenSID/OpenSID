<?php

/*
 *
 * File ini bagian dari:
 *
 * OpenSID
 *
 * Sistem informasi desa sumber terbuka untuk memajukan desa
 *
 * Aplikasi dan source code ini dirilis berdasarkan lisensi GPL V3
 *
 * Hak Cipta 2009 - 2015 Combine Resource Institution (http://lumbungkomunitas.net/)
 * Hak Cipta 2016 - 2023 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @package   OpenSID
 * @author    Tim Pengembang OpenDesa
 * @copyright Hak Cipta 2009 - 2015 Combine Resource Institution (http://lumbungkomunitas.net/)
 * @copyright Hak Cipta 2016 - 2023 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

use App\Enums\JenisKelaminEnum;

defined('BASEPATH') || exit('No direct script access allowed');

if ($individu['sex_id'] == JenisKelaminEnum::LAKI_LAKI) {
    $dataCalonSWN = [
        // calon pria
        'nama_pria' => $individu['nama'],

        // calon wanita
        'nama_wanita' => $input['nama_calon_pasangan'],
    ];
} else {
    $dataCalonSWN = [
        // calon pria
        'nama_pria' => $input['nama_calon_pasangan'],

        // calon wanita
        'nama_wanita' => $individu['nama'],
    ];
}

$dataWaliNikah = [
    'nama_wali' => $input['nama_wali_nikah'],
    'bin_wali' => $input['bin_wali_nikah'],
    'nik_wali' => $input['no_ktp_wali_nikah'],
    'tempatlahir_wali' => $input['tempatlahir_wali_nikah'],
    'tanggallahir_wali' => $input['tanggallahir_wali_nikah'],
    'agama_wali' => $input['agama_wali_nikah'],
    'pekerjaan_wali' => $input['pekerjaan_wali_nikah'],
    'alamat_wali' => $input['tempat_tinggal_wali_nikah'],
    'hubungan_wali' => $input['hubungan_dengan_wali'],
];
