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

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

defined('BASEPATH') || exit('No direct script access allowed');

class Migrasi_rev extends MY_model
{
    public function up()
    {
        $hasil = true;

        // Migrasi berdasarkan config_id
        // $config_id = DB::table('config')->pluck('id')->toArray();

        foreach ($config_id as $id) {
            $hasil = $hasil && $this->migrasi_2024082651($hasil, $id);
            $hasil = $hasil && $this->migrasi_2024082751($hasil, $id);
        }

        $hasil = $hasil && $this->migrasi_2024082951($hasil);
        $hasil = $hasil && $this->migrasi_2024083051($hasil);

        return $this->migrasi_2024083052($hasil);
    }

    protected function migrasi_2024082651($hasil, $config_id)
    {
        if (! $this->db->field_exists('penduduk_id', 'suplemen_terdata')) {
            $hasil = $hasil && $this->dbforge->add_column('suplemen_terdata', [
                'penduduk_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true, 'after' => 'id_terdata'],
                'keluarga_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true, 'after' => 'id_terdata'],
            ]);

            $hasil = $hasil && $this->tambahForeignKey('suplemen_terdata_penduduk_fk', 'suplemen_terdata', 'penduduk_id', 'tweb_penduduk', 'id', true);
            $hasil = $hasil && $this->tambahForeignKey('suplemen_terdata_keluarga_fk', 'suplemen_terdata', 'keluarga_id', 'tweb_keluarga', 'id', true);
        }

        DB::table('suplemen_terdata')
            ->where('config_id', $config_id)
            ->update([
                'penduduk_id' => DB::raw("
                    case
                        when sasaran = 1 then (select id from tweb_penduduk where config_id = {$config_id} and tweb_penduduk.id = suplemen_terdata.id_terdata)
                    end
                "),
                'keluarga_id' => DB::raw("
                    case
                        when sasaran = 2 then (select id from tweb_keluarga where config_id = {$config_id} and tweb_keluarga.id = suplemen_terdata.id_terdata)
                    end
                "),
            ]);

        return $hasil;
    }

    protected function migrasi_2024082751($hasil, $config_id)
    {
        DB::table('kelompok_anggota')
            ->join('kelompok', 'kelompok_anggota.id_kelompok', '=', 'kelompok.id')
            ->where('kelompok_anggota.config_id', $config_id)
            ->whereColumn('kelompok_anggota.tipe', '!=', 'kelompok.tipe')
            ->update([
                'kelompok_anggota.tipe' => DB::raw('kelompok.tipe'),
            ]);

        return $hasil;
    }

    protected function migrasi_2024082951($hasil)
    {
        if (! Schema::hasTable('log_login')) {
            $directoryTable = 'donjo-app/models/migrations/struktur_tabel';
            $migrationFiles = [
                '2023_12_22_015242_create_log_login_table.php',
                '2023_12_22_015245_add_foreign_keys_to_log_login_table.php',
            ];

            foreach ($migrationFiles as $file) {
                $migrateFile = require $directoryTable . DIRECTORY_SEPARATOR . $file;
                $migrateFile->up();
            }
        }

        return $hasil;
    }

    protected function migrasi_2024083051($hasil)
    {
        (new Filesystem())->copyDirectory('vendor/tecnickcom/tcpdf/fonts', LOKASI_FONT_DESA);

        return $hasil;
    }

    protected function migrasi_2024083052($hasil)
    {
        if (! $this->db->field_exists('foto', 'kelompok_anggota')) {
            $hasil = $hasil && $this->dbforge->add_column('kelompok_anggota', [
                'foto' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            ]);
        }

        return $hasil;
    }
}
