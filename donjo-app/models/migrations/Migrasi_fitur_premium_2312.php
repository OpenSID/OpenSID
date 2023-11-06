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

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

defined('BASEPATH') || exit('No direct script access allowed');

class Migrasi_fitur_premium_2312 extends MY_model
{
    public function up()
    {
        $hasil = true;

        // Jalankan migrasi sebelumnya
        $hasil = $hasil && $this->jalankan_migrasi('migrasi_fitur_premium_2311', false);
        $hasil = $hasil && $this->migrasi_tabel($hasil);

        return $hasil && $this->migrasi_data($hasil);
    }

    protected function migrasi_tabel($hasil)
    {
        // Uncomment pada rilis rev terakhir
        // return $hasil && $this->buat_tabel_migrations($hasil);

        $hasil = $hasil && $this->migrasi_2023102571($hasil);

        return $hasil && $this->migrasi_2023110672($hasil);
    }

    // Migrasi perubahan data
    protected function migrasi_data($hasil)
    {
        // Migrasi berdasarkan config_id
        $config_id = DB::table('config')->pluck('id')->toArray();

        foreach ($config_id as $id) {
            $hasil = $hasil && $this->migrasi_2023110671($hasil, $id);
        }

        // Migrasi tanpa config_id
        $hasil = $hasil && $this->migrasi_2023110251($hasil);

        return $hasil && $this->migrasi_2023110252($hasil);
    }

    protected function migrasi_xxxxxxxxxx($hasil)
    {
        return $hasil;
    }

    protected function migrasi_2023110251($hasil)
    {
        return $hasil && $this->ubah_modul(
            ['slug' => 'home'],
            ['modul' => 'Beranda', 'slug' => 'beranda', 'url' => 'beranda']
        );
    }

    protected function migrasi_2023110252($hasil)
    {
        DB::table('tweb_penduduk')->where('id_kk', 0)->orWhere('id_kk', '')->update(['id_kk' => null]);

        return $hasil && $this->dbforge->modify_column('tweb_penduduk', [
            'id_kk' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => false,
                'default'    => null,
            ],
        ]);
    }

    protected function buat_tabel_migrations($hasil)
    {
        log_message('notice', 'Membuat tabel migrations');
        if (! Schema::hasTable('migrations')) {
            Schema::create('migrations', static function (Blueprint $table) {
                $table->increments('id');
                $table->string('migration');
                $table->integer('batch');
            });
        }

        return $hasil;
    }

    protected function migrasi_2023102571($hasil)
    {
        return $hasil && $this->dbforge->add_field([
            'id'            => ['type' => 'INT', 'constraint' => 11, 'auto_increment' => true],
            'config_id'     => ['type' => 'INT', 'constraint' => 11],
            'slug'          => ['type' => 'VARCHAR', 'constraint' => 200, 'null' => true],
            'nama'          => ['type' => 'VARCHAR', 'constraint' => 100],
            'jenis'         => ['type' => 'TINYINT', 'default' => '2'],
            'template'      => ['type' => 'LONGTEXT', 'null' => true],
            'template_desa' => ['type' => 'LONGTEXT', 'null' => true],
            'status'        => ['type' => 'TINYINT', 'default' => '1'],
            'created_at timestamp NULL DEFAULT CURRENT_TIMESTAMP',
            'created_by int(11) DEFAULT NULL',
            'updated_at timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP',
            'updated_by int(11) DEFAULT NULL',
            'PRIMARY KEY (`id`)',
            'UNIQUE KEY `slug_config` (`config_id`,`slug`)',
            'CONSTRAINT `lampiran_surat_config_fk` FOREIGN KEY (`config_id`) REFERENCES `config` (`id`) ON DELETE CASCADE ON UPDATE CASCADE',
        ])
            ->create_table('lampiran_surat', true);
    }

    protected function migrasi_2023110671($hasil, $id)
    {
        return $hasil && $this->tambah_setting([
            'judul'      => 'Margin Lampiran Global',
            'key'        => 'lampiran_margin',
            'value'      => json_encode(LampiranSurat::MARGINS),
            'keterangan' => 'Margin global untuk lampiran surat',
            'jenis'      => null,
            'option'     => null,
            'attribute'  => null,
            'kategori'   => 'format_lampiran',
        ], $id);
    }

    protected function migrasi_2023110672($hasil)
    {
        if (! $this->db->field_exists('margin', 'lampiran_surat')) {
            $hasil = $hasil && $this->dbforge->add_column('lampiran_surat', [
                'margin' => [
                    'type'  => 'text',
                    'null'  => true,
                    'after' => 'status',
                ],
            ]);
        }

        if (! $this->db->field_exists('margin_global', 'lampiran_surat')) {
            $hasil = $hasil && $this->dbforge->add_column('lampiran_surat', [
                'margin_global' => [
                    'type'       => 'tinyint',
                    'constraint' => 1,
                    'null'       => true,
                    'default'    => 1,
                    'after'      => 'margin',
                ],
            ]);
        }

        return $hasil;
    }
}
