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
 * Hak Cipta 2016 - 2025 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright Hak Cipta 2016 - 2025 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

defined('BASEPATH') || exit('No direct script access allowed');

class Theme_model extends CI_Model
{
    /**
     * @var mixed[]|string
     */
    public $tema;

    /**
     * @var 'desa/themes'|'storage/app/themes'
     */
    public $folder;

    private $templateFile = 'resources/views/template.blade.php';

    public function __construct()
    {
        parent::__construct();
    }

    // TODO:: KONVERSI TEME, AMBIL DARI DATABASE
    public function list_all()
    {
        $tema_sistem = glob('storage/app/themes/*', GLOB_ONLYDIR);
        $tema_desa   = glob('desa/themes/*', GLOB_ONLYDIR);
        $tema_semua  = array_merge($tema_sistem, $tema_desa);
        $list_tema   = [];

        foreach ($tema_semua as $tema) {
            if (is_file(FCPATH . $tema . '/' . $this->templateFile)) {
                $list_tema[] = str_replace(['vendor/', 'themes/'], '', $tema);
            }
        }

        return $list_tema;
    }

    // Mengambil latar belakang website ubahan
    public function latar_website()
    {
        $ubahan_tema   = "desa/pengaturan/{$this->tema}/images/";
        $bawaan_tema   = "{$this->folder}/{$this->tema}/assets/css/images/latar_website.jpg";
        $latar_website = is_file($ubahan_tema) ? $ubahan_tema : $bawaan_tema;

        return is_file($latar_website) ? $latar_website : null;
    }

    public function lokasi_latar_website()
    {
        $folder = "desa/pengaturan/{$this->tema}/images/";
        if (! file_exists($folder)) {
            mkdir($folder, 0755, true);
        }

        return $folder;
    }

    // Mengambil latar belakang login mandiri ubahan
    public function latar_login_mandiri()
    {
        return file_exists(FCPATH . LATAR_KEHADIRAN) ? LATAR_KEHADIRAN : DEFAULT_LATAR_KEHADIRAN;
    }
}
