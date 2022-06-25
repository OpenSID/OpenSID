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
 * Hak Cipta 2016 - 2021 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright Hak Cipta 2016 - 2021 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

defined('BASEPATH') || exit('No direct script access allowed');

class Theme_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    /*
     * Tema sistem ada di subfolder themes/
     * Tema buatan sistem ada di subfolder desa/themes/
    */
    public function list_all()
    {
        $tema_sistem = glob('themes/*', GLOB_ONLYDIR);
        $tema_desa   = glob('desa/themes/*', GLOB_ONLYDIR);
        $tema_semua  = array_merge($tema_sistem, $tema_desa);
        $list_tema   = [];

        foreach ($tema_semua as $tema) {
            $list_tema[] = str_replace('themes/', '', $tema);
        }

        return $list_tema;
    }

    // Mengambil latar belakang website ubahan
    public function latar_website()
    {
        $ubahan_tema   = "desa/pengaturan/{$this->theme}/images/latar_website.jpg";
        $bawaan_tema   = "{$this->theme_folder}/{$this->theme}/assets/css/images/latar_website.jpg";
        $latar_website = is_file($ubahan_tema) ? $ubahan_tema : $bawaan_tema;

        return is_file($latar_website) ? $latar_website : null;
    }

    public function lokasi_latar_website()
    {
        $folder = "desa/pengaturan/{$this->theme}/images/";
        mkdir($folder, 0775, true);

        return $folder;
    }

    // Mengambil latar belakang login ubahan
    public function latar_login()
    {
        $ubahan = LATAR_LOGIN . 'latar_login.jpg';

        return is_file($ubahan) ? $ubahan : null;
    }

    // Mengambil latar belakang login mandiri ubahan
    public function latar_login_mandiri()
    {
        $ubahan = LATAR_LOGIN . 'latar_login_mandiri.jpg';

        return is_file($ubahan) ? $ubahan : null;
    }
}
