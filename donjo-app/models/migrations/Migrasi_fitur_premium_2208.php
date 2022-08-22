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
use Illuminate\Support\Facades\Schema;

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
        $hasil = $hasil && $this->migrasi_2022070751($hasil);
        $hasil = $hasil && $this->migrasi_2022071851($hasil);
        $hasil = $hasil && $this->migrasi_2022070751($hasil);
        $hasil = $hasil && $this->migrasi_2022072751($hasil);
        $hasil = $hasil && $this->migrasi_2022073151($hasil);

        return $hasil && $this->migrasi_2022080471($hasil);
    }

    protected function migrasi_2022070551($hasil)
    {
        // Hanya jalankan sebelum migrasi perubahan fungsi a.n dan u.b
        if (! Schema::hasColumn('tweb_desa_pamong', 'jabatan_id')) {
            $config = Config::first();

            if ($config->pamong_id && Pamong::where('pamong_ttd', 1)->count() > 1) {
                return $hasil && Pamong::whereNotIn('pamong_id', [$config->pamong_id])->update(['pamong_ttd' => 0]);
            }
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

    protected function migrasi_2022071851($hasil)
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

    protected function migrasi_2022070751($hasil)
    {
        // Buat tabel ref font Surat
        if (! $this->db->table_exists('ref_font_surat')) {
            $fields = [
                'id' => [
                    'type'           => 'INT',
                    'constraint'     => 11,
                    'auto_increment' => true,
                    'unsigned'       => true,
                ],
                'font_family' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 50,
                    'unique'     => true,
                    'null'       => false,
                ],
            ];

            $this->dbforge->add_key('id', true);
            $this->dbforge->add_field($fields);
            $hasil = $hasil && $this->dbforge->create_table('ref_font_surat', true);

            // isi data font surat
            $fonts = [
                ['font_family' => 'Andale Mono'],
                ['font_family' => 'Arial'],
                ['font_family' => 'Arial Black'],
                ['font_family' => 'Book Antiqua'],
                ['font_family' => 'Comic Sans MS'],
                ['font_family' => 'Courier New'],
                ['font_family' => 'Georgia'],
                ['font_family' => 'Helvetica'],
                ['font_family' => 'Impact'],
                ['font_family' => 'Symbol'],
                ['font_family' => 'Tahoma'],
                ['font_family' => 'Terminal'],
                ['font_family' => 'Times New Roman'],
                ['font_family' => 'Trebuchet MS'],
                ['font_family' => 'Verdana'],
                ['font_family' => 'Webdings'],
                ['font_family' => 'Wingdings'],
            ];
            $hasil = $this->db->insert_batch('ref_font_surat', $fonts);
        }

        // tambahkan pengaturan
        return $hasil && $this->tambah_setting([
            'key'        => 'font_surat',
            'value'      => 'Arial',
            'keterangan' => 'Font Surat Utama',
            'kategori'   => 'format_surat',
        ]);
    }

    protected function migrasi_2022072751($hasil)
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

    protected function migrasi_2022073151($hasil)
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

    protected function migrasi_2022080471($hasil)
    {
        $hasil = $hasil && $this->telegram_user($hasil);

        return $hasil && $this->setting_tte($hasil);
    }

    protected function telegram_user($hasil)
    {
        if (! $this->db->field_exists('notif_telegram', 'user')) {
            $fields = [
                'notif_telegram' => [
                    'type'       => 'TINYINT',
                    'constraint' => 1,
                    'null'       => false,
                    'default'    => 0,
                    'after'      => 'nama',
                ],
            ];
            $hasil = $hasil && $this->dbforge->add_column('user', $fields);
        }

        if (! $this->db->field_exists('id_telegram', 'user')) {
            $fields = [
                'id_telegram' => [
                    'type'       => 'INT',
                    'constraint' => 10,
                    'null'       => false,
                    'default'    => 0,
                    'after'      => 'nama',
                ],
            ];
            $hasil = $hasil && $this->dbforge->add_column('user', $fields);
        }

        return $hasil;
    }

    protected function setting_tte($hasil)
    {
        $hasil && $this->tambah_setting([
            'key'        => 'verifikasi_kades',
            'value'      => '0',
            'keterangan' => 'Verifikasi Surat Oleh Kepala Desa',
            'kategori'   => 'alur_surat',
            'jenis'      => 'boolean',
        ]);

        $hasil && $this->tambah_setting([
            'key'        => 'verifikasi_sekdes',
            'value'      => '0',
            'keterangan' => 'Verifikasi Surat Oleh Sekretaris daerah',
            'kategori'   => 'alur_surat',
            'jenis'      => 'boolean',
        ]);

        $hasil && $this->tambah_setting([
            'key'        => 'verifikasi_operator',
            'value'      => '0',
            'keterangan' => 'Verifikasi Surat Oleh Operator (Layanan Mandiri)',
            'kategori'   => 'alur_surat',
            'jenis'      => 'boolean',
        ]);

        if (! $this->db->field_exists('verifikasi_operator', 'log_surat')) {
            $fields = [
                'verifikasi_operator' => [
                    'type'       => 'TINYINT',
                    'constraint' => 1,
                    'null'       => true,
                    'after'      => 'status',
                ],
            ];
            $hasil = $hasil && $this->dbforge->add_column('log_surat', $fields);
        }

        if (! $this->db->field_exists('verifikasi_sekdes', 'log_surat')) {
            $fields = [
                'verifikasi_sekdes' => [
                    'type'       => 'TINYINT',
                    'constraint' => 1,
                    'null'       => true,
                    'after'      => 'status',
                ],
            ];
            $hasil = $hasil && $this->dbforge->add_column('log_surat', $fields);
        }

        if (! $this->db->field_exists('verifikasi_kades', 'log_surat')) {
            $fields = [
                'verifikasi_kades' => [
                    'type'       => 'TINYINT',
                    'constraint' => 1,
                    'null'       => true,
                    'after'      => 'status',
                ],
            ];
            $hasil = $hasil && $this->dbforge->add_column('log_surat', $fields);
        }

        if (! $this->db->field_exists('tte', 'log_surat')) {
            $fields = [
                'tte' => [
                    'type'       => 'TINYINT',
                    'constraint' => 1,
                    'null'       => true,
                    'after'      => 'status',
                ],
            ];
            $hasil = $hasil && $this->dbforge->add_column('log_surat', $fields);
        }

        if (! $this->db->field_exists('log_verifikasi', 'log_surat')) {
            $fields = [
                'log_verifikasi' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 100,
                    'null'       => true,
                    'after'      => 'status',
                ],
            ];
            $hasil = $hasil && $this->dbforge->add_column('log_surat', $fields);
        }

        return $hasil && $this->ubah_modul(32, ['url' => 'keluar/clear/masuk']);
    }
}
