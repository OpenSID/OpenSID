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
 * Hak Cipta 2016 - 2024 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright Hak Cipta 2016 - 2024 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

defined('BASEPATH') || exit('No direct script access allowed');

class Migrasi_2402_ke_2403 extends MY_Model
{
    public function up()
    {
        $hasil = true;

        // Migrasi fitur premium
        // $hasil = $hasil && $this->jalankan_migrasi('migrasi_fitur_premium_2308', false);
        $hasil = $hasil && $this->jalankan_migrasi('migrasi_fitur_premium_2309', false);

        $this->modultheme($hasil);

        status_sukses($hasil);

        return $hasil;
    }

    public function modultheme($hasil)
    {
        $this->tambah_modul([
            'modul'      => 'Tema',
            'slug'       => 'themes',
            'url'        => 'theme',
            'aktif'      => 1,
            'ikon'       => 'fa-object-group',
            'urut'       => 5,
            'level'      => 1,
            'hidden'     => 0,
            'ikon_kecil' => 'fa-object-group',
            'parent'     => $this->db->get_where('setting_modul', ['slug' => 'admin-web'])->row()->id,
        ]);

        if (!$this->db->table_exists('theme')) {
            $this->db->query("
                CREATE TABLE `theme` (
                    `id` INT NOT NULL AUTO_INCREMENT,
                    `nama` VARCHAR(50) NOT NULL DEFAULT '0',
                    `slug` VARCHAR(60) NULL,
                    `versi` VARCHAR(10) NULL DEFAULT NULL,
                    `sistem` TINYINT NOT NULL DEFAULT '0',
                    `path` VARCHAR(100) NOT NULL DEFAULT '',
                    `status` TINYINT NOT NULL DEFAULT '0',
                    `keterangan` TEXT NULL,
                    `opsi` TEXT NULL,
                    
                    PRIMARY KEY (`id`)
                    UNIQUE INDEX `slug` (`slug`)
                )
                COLLATE='utf8_general_ci'
            ");

            $this->sesuaikanTemaAktif($hasil);
        }

        return $hasil;
    }

    protected function sesuaikanTemaAktif($hasil) {
        if (DB::table('setting_aplikasi')->where('key', 'web_theme')->exists()) {
            $temaSetting = DB::table('setting_aplikasi')->where('key', 'web_theme')->first()->value;
            $temaSetting = Str::slug($temaSetting);

            DB::table('theme')->where('slug', $temaSetting)->update(['status' => 1]);
            DB::table('theme')->where('slug', '!=', $temaSetting)->update(['status' => 0]);

            $this->db->query("DELETE FROM `setting_aplikasi` WHERE `key` = 'web_theme'");
        }

        return $hasil;
    }
}
