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
 * Hak Cipta 2016 - 2022 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright Hak Cipta 2016 - 2022 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

defined('BASEPATH') || exit('No direct script access allowed');

class Folder_desa_model extends CI_Model
{
    /*
        Dimasukkan di autoload. Supaya folder desa dibuat secara otomatis menggunakan
        desa-contoh apabila belum ada. Yaitu pada pertama kali menginstall OpenSID.

        Perubahan folder desa pada setiap rilis dilakukan sebagai bagian dari
        Database > Migrasi DB.
    */
    public function __construct()
    {
        parent::__construct();
        $this->periksa_folder_desa();
    }

    public function periksa_folder_desa()
    {
        $this->salin_contoh();
    }

    private function salin_contoh()
    {
        if (! file_exists('desa')) {
            mkdir('desa');
            xcopy('desa-contoh', 'desa');
        }
    }

    public function amankan_folder_desa()
    {
        $this->salin_file('desa', 'index.html', 'desa-contoh/index.html');
        $this->salin_file('desa/arsip', '.htaccess', 'desa-contoh/arsip/.htaccess', true);
        $this->salin_file('desa/upload', '.htaccess', 'desa-contoh/upload/media/.htaccess', true);
        $this->salin_file('desa/upload/dokumen', '.htaccess', 'desa-contoh/upload/dokumen/.htaccess', true);
    }

    public function salin_file($cek, $cari, $contoh, $ganti = false)
    {
        if ($ganti || ! file_exists("{$cek}/{$cari}")) {
            copy($contoh, "{$cek}/{$cari}");
        }

        foreach (glob("{$cek}/*", GLOB_ONLYDIR) as $folder) {
            $file = "{$folder}/{$cari}";

            if ($ganti || ! file_exists($file)) {
                copy($contoh, $file);
            }

            $this->salin_file($folder, $cari, $contoh);
        }
    }
}
