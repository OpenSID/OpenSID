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

$ayah = $this->surat_model->get_data_ayah($data['id_pend']);

if ($individu['sex_id'] == JenisKelaminEnum::LAKI_LAKI) {
    $dataCalonN4 = [
        // calon pria
        'nama_pria' => $individu['nama'],
        'bin_pria'  => $individu['nama_ayah'] ?? $ayah['nama'],
        'nik_pria' => $individu['nik'],
        'tempatlahir_pria' => $individu['tempatlahir'],
        'tanggallahir_pria' => $individu['tanggallahir'],
        'warganegara_pria' => $individu['warganegara'],
        'agama_pria' => $individu['agama'],
        'pekerjaan_pria' => $individu['pekerjaan'],
        'alamat_pria' => $individu['alamat_wilayah'],

        // calon wanita
        'nama_wanita' => $input['nama_calon_pasangan'],
        'binti_wanita'  => $input['nama_ayah_calon_pasangan'],
        'nik_wanita' => $input['no_ktp_calon_pasangan'],
        'tempatlahir_wanita' => $input['tempat_lahir_calon_pasangan'],
        'tanggallahir_wanita' => $input['tanggal_lahir_calon_pasangan'],
        'warganegara_wanita' => $input['warga_negara_calon_pasangan'],
        'agama_wanita' => $input['agama_calon_pasangan'],
        'pekerjaan_wanita' => $input['pekerjaan_calon_pasangan'],
        'alamat_wanita' => $input['tempat_tinggal_calon_pasangan'],
    ];
} else {
    $dataCalonN4 = [
        // calon pria
        'nama_pria' => $input['nama_calon_pasangan'],
        'bin_pria'  => $input['nama_ayah_calon_pasangan'],
        'nik_pria' => $input['no_ktp_calon_pasangan'],
        'tempatlahir_pria' => $input['tempat_lahir_calon_pasangan'],
        'tanggallahir_pria' => $input['tanggal_lahir_calon_pasangan'],
        'warganegara_pria' => $input['warga_negara_calon_pasangan'],
        'agama_pria' => $input['agama_calon_pasangan'],
        'pekerjaan_pria' => $input['pekerjaan_calon_pasangan'],
        'alamat_pria' => $input['tempat_tinggal_calon_pasangan'],

        // calon wanita
        'nama_wanita' => $individu['nama'],
        'binti_wanita'  => $individu['nama_ayah'] ?? $ayah['nama'],
        'nik_wanita' => $individu['nik'],
        'tempatlahir_wanita' => $individu['tempatlahir'],
        'tanggallahir_wanita' => $individu['tanggallahir'],
        'warganegara_wanita' => $individu['warganegara'],
        'agama_wanita' => $individu['agama'],
        'pekerjaan_wanita' => $individu['pekerjaan'],
        'alamat_wanita' => $individu['alamat_wilayah'],
    ];
}
