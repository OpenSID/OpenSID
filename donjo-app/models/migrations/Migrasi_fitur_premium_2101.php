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

class Migrasi_fitur_premium_2101 extends MY_model
{
    public function up()
    {
        $hasil = true;

        // Jalankan migrasi sebelumnya
        $hasil = $hasil && $this->jalankan_migrasi('migrasi_fitur_premium_2012');

        $this->log_hapus_penduduk();

        // Tambahkan key sebutan_nip_desa
        $hasil = $hasil && $this->db->query("INSERT INTO setting_aplikasi (`key`, value, keterangan) VALUES ('sebutan_nip_desa', 'NIPD', 'Pengganti sebutan label niap/nipd')
            ON DUPLICATE KEY UPDATE value = VALUES(value), keterangan = VALUES(keterangan)");

        $list_setting = [
            [
                'key'        => 'api_opendk_server',
                'value'      => '',
                'keterangan' => 'Alamat Server OpenDK (contoh: https://demodk.opendesa.id)',
            ],
            [
                'key'        => 'api_opendk_key',
                'value'      => '',
                'keterangan' => 'OpenDK API Key untuk Sinkronisasi Data',
            ],
            [
                'key'        => 'api_opendk_user',
                'value'      => '',
                'keterangan' => 'Email Login Pengguna OpenDK',
            ],
            [
                'key'        => 'api_opendk_password',
                'value'      => '',
                'keterangan' => 'Password Login Pengguna OpenDK',
            ],
        ];

        foreach ($list_setting as $setting) {
            $hasil = $hasil && $this->tambah_setting($setting);
        }

        // setting_aplikasi.valud diperpanjang
        $field = [
            'value' => [
                'type'    => 'TEXT',
                'null'    => true,
                'default' => null,
            ],
        ];

        return $hasil && $this->dbforge->modify_column('setting_aplikasi', $field);
    }

    private function log_hapus_penduduk()
    {
        //insert log_penduduk_hapus
        if (! $this->db->table_exists('log_hapus_penduduk')) {
            $query = '
            CREATE TABLE IF NOT EXISTS `log_hapus_penduduk` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `id_pend` int NOT NULL,
                `nik` decimal(16,0) NOT NULL,
                `foto` varchar(100) DEFAULT NULL,
                `deleted_by` varchar(100) DEFAULT NULL,
                `deleted_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (`id`)
                )';
            $this->db->query($query);
        }
    }
}
