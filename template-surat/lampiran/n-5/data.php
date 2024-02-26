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


$dataCalonN5 = [
    'nama' => $input['nama_calon_pasangan'],
    'nik' => $input['no_ktp_calon_pasangan'],
    'tempatlahir' => $input['tempat_lahir_calon_pasangan'],
    'tanggallahir' => $input['tanggal_lahir_calon_pasangan'],
    'warganegara'  => $input['warga_negara_calon_pasangan'],
    'agama'  => $input['agama_calon_pasangan'],
    'pekerjaan'  => $input['pekerjaan_calon_pasangan'],
    'pendidikan'  => $input['pendidikan_calon_pasangan'],
    'bin'         => $input['nama_ayah_calon_pasangan'],
    'alamat_wilayah' => $input['tempat_tinggal_calon_pasangan'],
    'status_kawin'        => $individu['sex_id'] == JenisKelaminEnum::PEREMPUAN ? $input['status_kawin_calon_pasangan'] : $input['status_kawin'],
    'jumlah_pasangan_terdahulu' => $individu['sex_id'] == JenisKelaminEnum::PEREMPUAN ? $input['jumlah_pasangan_terdahulu_calon_pasangan'] : $input['jumlah_pasangan_terdahulu'],
];

$ayah = $this->surat_model->get_data_ayah($data['id_pend']);
$ayah = $this->surat_model->get_data_surat($ayah['id']);
$ibu = $this->surat_model->get_data_ibu($data['id_pend']);
$ibu = $this->surat_model->get_data_surat($ibu['id']);

$data_individu = [
    'nama_ayah' => $ayah['nama'],
    'bin_ayah' => $ayah['nama_ayah'],
    'nik_ayah' => $ayah['nik'],
    'tempat_lahir_ayah' => $ayah['tempatlahir'],
    'tanggal_lahir_ayah' => $ayah['tanggallahir'],
    'warga_negara_ayah' => $ayah['warganegara'],
    'agama_ayah' => $ayah['agama'],
    'pekerjaan_ayah' => $ayah['pekerjaan'],
    'alamat_ayah' => $ayah['alamat_wilayah'],
    
    'nama_ibu' => $ibu['nama'],
    'binti_ibu' => $ibu['nama_ayah'],
    'nik_ibu' => $ibu['nik'],
    'tempat_lahir_ibu' => $ibu['tempatlahir'],
    'tanggal_lahir_ibu' => $ibu['tanggallahir'],
    'warga_negara_ibu' => $ibu['warganegara'],
    'agama_ibu' => $ibu['agama'],
    'pekerjaan_ibu' => $ibu['pekerjaan'],
    'alamat_ibu' => $ibu['alamat_wilayah'],
];
 
$dataIndividuN5 = array_merge($individu, $data_individu);;
$dataCalonPasanganN5 = $dataCalonN5;
 