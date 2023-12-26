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
use Illuminate\Support\Str;

defined('BASEPATH') || exit('No direct script access allowed');

class Migrasi_dev extends MY_model
{
    public function up()
    {
        $hasil = true;

        $hasil = $hasil && $this->migrasi_tabel($hasil);

        return $hasil && $this->migrasi_data($hasil);
    }

    protected function migrasi_tabel($hasil)
    {
        return $hasil;

        // return $hasil && $this->migrasi_2023122252($hasil);
    }

    // Migrasi perubahan data
    protected function migrasi_data($hasil)
    {
        // Migrasi berdasarkan config_id
        // $config_id = DB::table('config')->pluck('id')->toArray();

        // foreach ($config_id as $id) {
        //     $hasil = $hasil && $this->migrasi_xxxxxxxxxx($hasil, $id);
        // }

        // Migrasi tanpa config_id

        return $hasil && $this->migrasi_xxxxxxxxxx($hasil);
    }

    protected function migrasi_xxxxxxxxxx($hasil)
    {
        return $hasil;
    }

    // TODO:: Migrasi untuk tabel yang berubah struktur
    protected function migrasi_2023122252($hasil)
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        if (! Schema::hasColumn('setting_modul', 'uuid')) {
            Schema::table('setting_modul', static function (Blueprint $table) {
                $table->uuid('uuid')->after('id');
                $table->uuid('parent_uuid')->after('parent')->nullable();
            });

            Schema::table('grup_akses', static function (Blueprint $table) {
                $table->uuid('modul_uuid')->after('id_grup')->nullable();
            });

            DB::table('setting_modul')->orderBy('id')->chunk(100, static function ($items) {
                foreach ($items as $item) {
                    $uuid = Str::uuid();
                    DB::table('setting_modul')->where('id', $item->id)->update(['uuid' => $uuid]);
                    DB::table('setting_modul')->where('parent', $item->id)->update(['parent_uuid' => $uuid]);
                    DB::table('grup_akses')->where('id_modul', $item->id)->update(['modul_uuid' => $uuid]);
                }
            });

            Schema::table('setting_modul', static function (Blueprint $table) {
                $table->unique(['uuid', 'config_id']);
            });

            Schema::table('grup_akses', static function (Blueprint $table) {
                $table->foreign('modul_uuid')->references('uuid')->on('setting_modul')->onDelete('cascade');
            });

            $this->hapus_foreign_key('setting_modul', 'fk_id_modul', 'grup_akses');

            Schema::table('grup_akses', static function (Blueprint $table) {
                $table->dropIndex('id_modul');
                $table->dropColumn('id_modul');
            });

            DB::statement('ALTER TABLE `setting_modul` CHANGE COLUMN `id` `id` INT(11) NULL DEFAULT NULL FIRST, DROP PRIMARY KEY');
            Schema::table('setting_modul', static function (Blueprint $table) {
                $table->dropColumn('id');
                $table->primary('uuid');
            });
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        return $hasil;
    }
}
