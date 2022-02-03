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

class Migrasi_fitur_premium_2203 extends MY_model
{
    public function up()
    {
        $hasil = true;

        // Jalankan migrasi sebelumnya
        $hasil = $hasil && $this->jalankan_migrasi('migrasi_fitur_premium_2202');

        return $hasil && $this->migrasi_2022020151($hasil);
    }

    protected function migrasi_2022020151($hasil)
    {
        if ($this->db->field_exists('anggaran', 'pembangunan')) {
            $fields = [
                'anggaran' => [
                    'type'    => 'bigint',
                    'default' => 0,
                ],
            ];

            $hasil = $hasil && $this->dbforge->modify_column('pembangunan', $fields);
        }

        if ($this->db->field_exists('sumber_biaya_pemerintah', 'pembangunan')) {
            $fields = [
                'sumber_biaya_pemerintah' => [
                    'type'    => 'bigint',
                    'default' => 0,
                ],
            ];

            $hasil = $hasil && $this->dbforge->modify_column('pembangunan', $fields);
        }

        if ($this->db->field_exists('sumber_biaya_provinsi', 'pembangunan')) {
            $fields = [
                'sumber_biaya_provinsi' => [
                    'type'    => 'bigint',
                    'default' => 0,
                ],
            ];

            $hasil = $hasil && $this->dbforge->modify_column('pembangunan', $fields);
        }

        if ($this->db->field_exists('sumber_biaya_kab_kota', 'pembangunan')) {
            $fields = [
                'sumber_biaya_kab_kota' => [
                    'type'    => 'bigint',
                    'default' => 0,
                ],
            ];

            $hasil = $hasil && $this->dbforge->modify_column('pembangunan', $fields);
        }

        if ($this->db->field_exists('sumber_biaya_swadaya', 'pembangunan')) {
            $fields = [
                'sumber_biaya_swadaya' => [
                    'type'    => 'bigint',
                    'default' => 0,
                ],
            ];

            $hasil = $hasil && $this->dbforge->modify_column('pembangunan', $fields);
        }

        if ($this->db->field_exists('sumber_biaya_jumlah', 'pembangunan')) {
            $fields = [
                'sumber_biaya_jumlah' => [
                    'type'    => 'bigint',
                    'default' => 0,
                ],
            ];

            $hasil = $hasil && $this->dbforge->modify_column('pembangunan', $fields);
        }

        return $hasil;
    }
}
