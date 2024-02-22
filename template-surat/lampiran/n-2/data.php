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

$dataCalonSuamiN2 = [
    'nama' => $input['nama_calon_pasangan'],
    'nama_ayah' => $input['nama_ayah_calon_pasangan'],
    'tempatlahir' => $input['tempat_lahir_calon_pasangan'],
    'tanggallahir' => $input['tanggal_lahir_calon_pasangan'],
    'warganegara'  => $input['warga_negara_calon_pasangan'],
    'agama'  => $input['agama_calon_pasangan'],
    'pekerjaan'  => $input['pekerjaan_calon_pasangan'],
    'pendidikan'  => $input['pendidikan_calon_pasangan'],
    'bin'         => $input['nama_ayah_calon_pasangan'],
    'alamat_wilayah' => $input['tempat_tinggal_calon_pasangan'],
    'ayah_nik' => $input['no_ktp_ayah_calon_pasangan'],
    'ibu_nik'  => $input['no_ktp_ibu_calon_pasangan'],
    'warganegara_ayah' => $input['warga_negara_ayah_calon_pasangan'],
    'warganegara_ibu' => $input['warga_negara_ibu_calon_pasangan'],
    'agama_ayah' => $input['agama_ayah_calon_pasangan'],
    'agama_ibu' => $input['agama_ibu_calon_pasangan'],
    'pekerjaan_ayah' => $input['pekerjaan_ayah_calon_pasangan'],
    'pekerjaan_ibu' => $input['pekerjaan_ibu_calon_pasangan'],
    'alamat_ayah' => $input['tempat_tinggal_ayah_calon_pasangan'],
    'alamat_ibu' => $input['tempat_tinggal_ibu_calon_pasangan'],
    'tempat_lahir_ayah'   => $input['tempat_lahir_ayah_calon_pasangan'],
    'tempat_lahir_ibu'   => $input['tempat_lahir_ibu_calon_pasangan'],
    'tanggal_lahir_ayah'   => $input['tanggal_lahir_ayah_calon_pasangan'],
    'tanggal_lahir_ibu'   => $input['tanggal_lahir_ibu_calon_pasangan'],
    'status_kawin'        => $individu['sex_id'] == JenisKelaminEnum::PEREMPUAN ? $input['status_kawin_calon_pasangan'] : $input['status_kawin'],
    'jumlah_pasangan_terdahulu' => $individu['sex_id'] == JenisKelaminEnum::PEREMPUAN ? $input['jumlah_pasangan_terdahulu_calon_pasangan'] : $input['jumlah_pasangan_terdahulu'],
];
$dataCalonIstriN2 = [
    'nama' => $input['nama_calon_pasangan'],
    'nama_ayah' => $input['nama_ayah_calon_pasangan'],
    'tempatlahir' => $input['tempat_lahir_calon_pasangan'],
    'tanggallahir' => $input['tanggal_lahir_calon_pasangan'],
    'warganegara'  => $input['warga_negara_calon_pasangan'],
    'agama'  => $input['agama_calon_pasangan'],
    'pekerjaan'  => $input['pekerjaan_calon_pasangan'],
    'pendidikan'  => $input['pendidikan_calon_pasangan'],
    'bin'         => $input['nama_ayah_calon_pasangan'],
    'alamat_wilayah' => $input['tempat_tinggal_calon_pasangan'],
    'ayah_nik' => $input['no_ktp_ayah_calon_pasangan'],
    'ibu_nik'  => $input['no_ktp_ibu_calon_pasangan'],
    'warganegara_ayah' => $input['warga_negara_ayah_calon_pasangan'],
    'warganegara_ibu' => $input['warga_negara_ibu_calon_pasangan'],
    'agama_ayah' => $input['agama_ayah_calon_pasangan'],
    'agama_ibu' => $input['agama_ibu_calon_pasangan'],
    'pekerjaan_ayah' => $input['pekerjaan_ayah_calon_pasangan'],
    'pekerjaan_ibu' => $input['pekerjaan_ibu_calon_pasangan'],
    'alamat_ayah' => $input['tempat_tinggal_ayah_calon_pasangan'],
    'alamat_ibu' => $input['tempat_tinggal_ibu_calon_pasangan'],
    'tempat_lahir_ayah'   => $input['tempat_lahir_ayah_calon_pasangan'],
    'tempat_lahir_ibu'   => $input['tempat_lahir_ibu_calon_pasangan'],
    'tanggal_lahir_ayah'   => $input['tanggal_lahir_ayah_calon_pasangan'],
    'tanggal_lahir_ibu'   => $input['tanggal_lahir_ibu_calon_pasangan'],
    'status_kawin'        => $individu['sex_id'] == JenisKelaminEnum::PEREMPUAN ? $input['status_kawin_calon_pasangan'] : $input['status_kawin'],
    'jumlah_pasangan_terdahulu' => $individu['sex_id'] == JenisKelaminEnum::PEREMPUAN ? $input['jumlah_pasangan_terdahulu_calon_pasangan'] : $input['jumlah_pasangan_terdahulu'],
];
if ($individu['sex_id'] == JenisKelaminEnum::LAKI_LAKI) {
    $dataCalonSuamiN2 = $individu;
    $dataCalonSuamiN2['bin'] = $individu['nama_ayah'];
} else {
    $dataCalonIstriN2 = $individu;
}

