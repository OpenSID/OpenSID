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

defined('BASEPATH') || exit('No direct script access allowed');

class Migrasi_2402_ke_2403 extends MY_Model
{
    public function up()
    {
        $hasil = true;

        // Migrasi fitur premium
        // $hasil = $hasil && $this->jalankan_migrasi('migrasi_fitur_premium_2308', false);
        $hasil = $hasil && $this->jalankan_migrasi('migrasi_fitur_premium_2309', false);

        $this->shortcut($hasil);

        status_sukses($hasil);

        return $hasil;
    }

    public function shortcut($hasil)
    {
        if (! $this->db->table_exists('shortcut')) {
            $this->db->query("
                CREATE TABLE `shortcut` (
                    `id` BIGINT(20) NOT NULL AUTO_INCREMENT,
                    `judul` VARCHAR(50) NOT NULL COLLATE 'utf8_general_ci',
                    `link` VARCHAR(50) NULL DEFAULT NULL COLLATE 'utf8_general_ci',
                    `query` VARCHAR(150) NULL DEFAULT NULL COLLATE 'utf8_general_ci',
                    `icon` VARCHAR(50) NULL DEFAULT NULL COLLATE 'utf8_general_ci',
                    `attribute` TEXT NULL DEFAULT NULL COLLATE 'utf8_general_ci',
                    `urutan` INT(11) NOT NULL DEFAULT '0',
                    `status` TINYINT(4) NOT NULL DEFAULT '0',
                    PRIMARY KEY (`id`) USING BTREE
                )
                COLLATE='utf8_general_ci'
                ENGINE=InnoDB
                AUTO_INCREMENT=3;
            ");

            // jalankan query insert
            $this->db->query("
                INSERT INTO `shortcut` (`id`, `judul`, `akses`, `link`, `jenis_query`, `raw_query`, `icon`, `warna`, `urutan`, `status`, `sistem`) VALUES
                (1, 'Wilayah [desa]', 'wilayah-administratif', 'wilayah', 0, 'Dusun', 'fa-map-marker', '#605ca8', 0, 1, 0),
                (2, 'Penduduk', 'penduduk', 'penduduk', 0, 'Penduduk', 'fa-user', '#00c0ef', 0, 1, 0),
                (3, 'Keluarga', 'keluarga', 'keluarga', 0, 'Keluarga', 'fa-users', '#00a65a', 0, 1, 0),
                (4, 'Surat Tercetak', 'arsip-layanan', 'keluar', 0, 'Surat Tercetak', 'fa-file-text-o', '#0073b7', 0, 1, 0),
                (5, 'Kelompok', 'kelompok', 'kelompok', 0, 'Kelompok', 'fa-user-plus', '#dd4b39', 0, 1, 0),
                (6, 'Rumah Tangga', 'rumah-tangga', 'rtm', 0, 'RTM', 'fa-home', '#d2d6de', 0, 1, 0),
                (7, 'Bantuan', 'bantuan', 'program_bantuan', 0, 'Bantuan', 'fa-handshake-o', '#f39c12', 0, 1, 0),
                (8, 'Verifikasi Layanan Mandiri', 'pendaftar-layanan-mandiri', 'mandiri', 0, 'Verifikasi Layanan Mandiri', 'fa-drivers-license', '#39cccc', 0, 1, 0);
            ");
        }
        
        $this->tambah_modul([
            'id'         => 370,
            'config_id'  => 1,
            'modul'      => 'Shortcut',
            'slug'       => 'shortcut',
            'url'        => 'shortcut',
            'aktif'      => '1',
            'ikon'       => 'fa-chain',
            'urut'       => '20',
            'level'      => '1',
            'parent'     => '11',
            'hidden'     => '0',
            'ikon_kecil' => 'fa-chain',
        ]);

        return $hasil;
    }
}
