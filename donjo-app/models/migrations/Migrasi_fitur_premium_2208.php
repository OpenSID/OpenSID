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

use App\Models\Config;
use App\Models\LogKeluarga;
use App\Models\Pamong;

defined('BASEPATH') || exit('No direct script access allowed');

class Migrasi_fitur_premium_2208 extends MY_model
{
    public function up()
    {
        $hasil = true;

        // Jalankan migrasi sebelumnya
        $hasil = $hasil && $this->jalankan_migrasi('migrasi_fitur_premium_2207');
        $hasil = $hasil && $this->migrasi_2022070551($hasil);
        $hasil = $hasil && $this->migrasi_2022070451($hasil);
        $hasil = $hasil && $this->migrasi_2022071851($hasil);
        $hasil = $hasil && $this->migrasi_2022072751($hasil);

        return $hasil && $this->migrasi_2022073151($hasil);
    }

    protected function migrasi_2022070551($hasil)
    {
        $config = Config::first();

        if ($config->pamong_id && Pamong::where('pamong_ttd', 1)->count() > 1) {
            return $hasil && Pamong::whereNotIn('pamong_id', [$config->pamong_id])->update(['pamong_ttd' => 0]);
        }

        return $hasil;
    }

    protected function migrasi_2022070451($hasil)
    {
        $hasil = $hasil && $this->db
            ->where('id', 7)
            ->update('setting_modul', ['url' => '']);

        $hasil = $hasil && $this->db
            ->where('parent', 7)
            ->update('setting_modul', ['hidden' => 0]);

        $hasil = $hasil && $this->db
            ->where([
                'id'    => 213,
                'modul' => 'data_persil',
            ])
            ->update('setting_modul', [
                'modul' => 'Daftar Persil',
                'ikon'  => 'fa-list',
            ]);

        $this->cache->hapus_cache_untuk_semua('_cache_modul');

        return $hasil;
    }

    public function migrasi_2022071851($hasil)
    {
        if (! $this->db->field_exists('permanen', 'log_backup')) {
            $fields = [
                'permanen' => [
                    'type'       => 'TINYINT',
                    'constraint' => 1,
                    'null'       => false,
                    'default'    => 0,
                    'after'      => 'path',
                ],
            ];
            $hasil = $hasil && $this->dbforge->add_column('log_backup', $fields);
        }

        return $hasil;
    }

    public function migrasi_2022072751($hasil)
    {
        if ($this->db->field_exists('updated_at', 'tweb_penduduk_mandiri')) {
            $hasil = $hasil && $this->dbforge->modify_column('tweb_penduduk_mandiri', 'updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP');
        }

        if ($this->db->field_exists('id', 'ibu_hamil')) {
            $hasil = $hasil && $this->dbforge->modify_column('ibu_hamil', [
                'id' => [
                    'name'           => 'id_ibu_hamil',
                    'type'           => 'INT',
                    'constraint'     => 11,
                    'auto_increment' => true,
                    'unsigned'       => true,
                ],
            ]);
        }

        if ($this->db->field_exists('id', 'bulanan_anak')) {
            $hasil = $hasil && $this->dbforge->modify_column('bulanan_anak', [
                'id' => [
                    'name'           => 'id_bulanan_anak',
                    'type'           => 'INT',
                    'constraint'     => 11,
                    'auto_increment' => true,
                    'unsigned'       => true,
                ],
            ]);
        }

        if ($this->db->field_exists('id', 'sasaran_paud')) {
            $hasil = $hasil && $this->dbforge->modify_column('sasaran_paud', [
                'id' => [
                    'name'           => 'id_sasaran_paud',
                    'type'           => 'INT',
                    'constraint'     => 11,
                    'auto_increment' => true,
                    'unsigned'       => true,
                ],
            ]);
        }

        return $hasil;
    }

    public function migrasi_2022073151($hasil)
    {
        // Cek duplikasi log_keluarga dengan id_peristiwa kematian (2) yang sama dalam 1 kk
        $cek_log = LogKeluarga::where('id_peristiwa', 2)
            ->groupBy('id_kk')
            ->havingRaw('COUNT(id_kk) > 1')
            ->pluck('id_kk');

        foreach ($cek_log as $key => $value) {
            $log_keluarga = LogKeluarga::where('id_kk', $value)->where('id_peristiwa', 2)->orderBy('tgl_peristiwa', 'asc')->pluck('id')->toArray();
            unset($log_keluarga[0]);

            // Hapus log mati ganda
            if ($log_keluarga) {
                $hasil = $hasil && LogKeluarga::destroy($log_keluarga);
            }
        }

        return $hasil;
    }
}
