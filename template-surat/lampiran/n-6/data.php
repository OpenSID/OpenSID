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
use App\Libraries\TinyMCE;
use App\Models\Penduduk;

defined('BASEPATH') || exit('No direct script access allowed');

$ayah = $this->surat_model->get_data_ayah($data['id_pend']);

$formatSuratN6 = (new TinyMCE())->substitusiNomorSurat($data['input']['nomor'], setting('format_nomor_surat'));
$dataIndividu = [
    'nama_pasangan_terdahulu' => $input['nama_pasangan_terdahulu'],
    'nik_pasangan_terdahulu' => $input['no_ktp_pasangan_terdahulu'],
    'binti_pasangan_terdahulu' => $input['bin/binti_pasangan_terdahulu'],
    'tempat_lahir_pasangan_terdahulu' => $input['tempat_tinggal_pasangan_terdahulu'],
    'tanggal_lahir_pasangan_terdahulu' => $input['tanggal_lahir_pasangan_terdahulu'],
    'warga_negara_pasangan_terdahulu' => $input['warga_negara_pasangan_terdahulu'],
    'agama_pasangan_terdahulu' => $input['agama_pasangan_terdahulu'],
    'pekerjaan_pasangan_terdahulu' => $input['pekerjaan_pasangan_terdahulu'],
    'tempat_tinggal_pasangan_terdahulu' => $input['tempat_tinggal_pasangan_terdahulu'],
    'tempat_meninggal_pasangan_terdahulu' => $input['tempat_meninggal_pasangan_terdahulu'],
    'tanggal_meninggal_pasangan_terdahulu' => $input['tanggal_meninggal_pasangan_terdahulu'],
    'bin' => $individu['nama_ayah'] ?? $ayah['nama'],
];

$dataIndividuN6 = array_merge($individu, $dataIndividu);

$dataCalonPasanganN6 = [
    'nama_pasangan_terdahulu' => $input['nama_pasangan_terdahulu_calon_pasangan'],
    'nik_pasangan_terdahulu' => $input['no_ktp_pasangan_terdahulu_calon_pasangan'],
    'binti_pasangan_terdahulu' => $input['bin/binti_pasangan_terdahulu_calon_pasangan'],
    'tempat_lahir_pasangan_terdahulu' => $input['tempat_lahir_pasangan_terdahulu_calon_pasangan'],
    'tanggal_lahir_pasangan_terdahulu' => $input['tanggal_lahir_pasangan_terdahulu_calon_pasangan'],
    'warga_negara_pasangan_terdahulu' => $input['warga_negara_pasangan_terdahulu_calon_pasangan'],
    'agama_pasangan_terdahulu' => $input['agama_pasangan_terdahulu_calon_pasangan'],
    'pekerjaan_pasangan_terdahulu' => $input['pekerjaan_pasangan_terdahulu_calon_pasangan'],
    'tempat_tinggal_pasangan_terdahulu' => $input['tempat_tinggal_pasangan_terdahulu_calon_pasangan'],
    'tempat_meninggal_pasangan_terdahulu' => $input['tempat_meninggal_pasangan_terdahulu_calon_pasangan'],
    'tanggal_meninggal_pasangan_terdahulu' => $input['tanggal_meninggal_pasangan_terdahulu_calon_pasangan'],
    'bin' => $individu['nama_ayah_calon_pasangan'],
    'nik' => $individu['no_ktp_calon_pasangan'],
    'nama' => $individu['nama_calon_pasangan'],
    'tempatlahir' => $individu['tempat_lahir_calon_pasangan'],
    'tanggallahir' => $individu['tanggal_lahir_calon_pasangan'],
    'warganegara' => $individu['warga_negara_calon_pasangan'],
    'agama' => $individu['agama_calon_pasangan'],
    'pekerjaan' => $individu['pekerjaan_calon_pasangan'],
    'alamat' => $individu['tempat_tinggal_calon_pasangan'],
];

 
