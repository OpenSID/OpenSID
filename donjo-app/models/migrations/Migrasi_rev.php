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
 * Hak Cipta 2016 - 2025 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright Hak Cipta 2016 - 2025 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

use App\Traits\Migrator;
use Illuminate\Support\Facades\DB;
use App\Enums\AnalisisRefSubjekEnum;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

defined('BASEPATH') || exit('No direct script access allowed');

class Migrasi_rev
{
    use Migrator;

    public function up()
    {
        $this->tabelLogNotifikasiMandiri();
        $this->updatePinPendudukMandiri();
        $this->updateAnalisis();
    }

    public function tabelLogNotifikasiMandiri()
    {
        if (! Schema::hasIndex('log_notifikasi_mandiri', 'log_notifikasi_mandiri_device_unique')) {
            return;
        }

        Schema::table('log_notifikasi_mandiri', function (Blueprint $table) {
            $table->dropUnique('log_notifikasi_mandiri_device_unique');
        });
    }

    public function updateAnalisis()
    {
        $this->hapusForeignKey('analisis_respon_bukti_subjek_fk', 'analisis_respon_bukti', 'analisis_ref_subjek');

        $columnMappings = [
            'penduduk_id' => 'tweb_penduduk',
            'keluarga_id' => 'tweb_keluarga',
            'kelompok_id' => 'kelompok',
            'rtm_id'      => 'tweb_rtm',
            'desa_id'     => 'config',
            'dusun_id'    => 'tweb_wil_clusterdesa',
            'rw_id'       => 'tweb_wil_clusterdesa',
            'rt_id'       => 'tweb_wil_clusterdesa',
        ];

        $subjekMappings = [
            AnalisisRefSubjekEnum::PENDUDUK     => 'penduduk_id',
            AnalisisRefSubjekEnum::KELUARGA     => 'keluarga_id',
            AnalisisRefSubjekEnum::RUMAH_TANGGA => 'rtm_id',
            AnalisisRefSubjekEnum::KELOMPOK     => 'kelompok_id',
            AnalisisRefSubjekEnum::DESA         => 'desa_id',
            AnalisisRefSubjekEnum::DUSUN        => 'dusun_id',
            AnalisisRefSubjekEnum::RW           => 'rw_id',
            AnalisisRefSubjekEnum::RT           => 'rt_id',
        ];

        $targetTables = [
            'analisis_respon',
            'analisis_respon_bukti',
            'analisis_respon_hasil',
        ];

        foreach ($targetTables as $tableName) {
            Schema::table($tableName, function (Blueprint $table) use ($tableName, $columnMappings) {
                if (Schema::hasIndex($tableName, "{$tableName}_subjek_fk")) {
                    $table->dropIndex("{$tableName}_subjek_fk");
                }

                foreach ($columnMappings as $columnName => $referenceTable) {
                    if (! Schema::hasColumn($tableName, $columnName)) {
                        $table->integer($columnName)->nullable();
                        $table->foreign($columnName)
                              ->references('id')
                              ->on($referenceTable)
                              ->onUpdate('cascade')
                              ->onDelete('cascade');
                    }
                }
            });
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        foreach ($targetTables as $table) {
            $caseUpdate = [];

            foreach ($subjekMappings as $subjekTipe => $columnName) {
                $caseUpdate[$columnName] = DB::raw("
                    CASE
                        WHEN analisis_master.subjek_tipe = {$subjekTipe} AND {$table}.{$columnName} IS NULL
                        THEN {$table}.id_subjek
                        ELSE {$table}.{$columnName}
                    END
                ");
            }

            DB::table($table)
                ->when(
                    $table === 'analisis_respon',
                    function ($query) {
                        $query->join('analisis_periode', 'analisis_periode.id', '=', 'analisis_respon.id_periode')
                            ->join('analisis_master', 'analisis_master.id', '=', 'analisis_periode.id_master');
                    },
                    function ($query) use ($table) {
                        $query->join('analisis_master', 'analisis_master.id', '=', "{$table}.id_master");
                    }
                )
                ->whereNotNull("{$table}.id_subjek")
                ->where("{$table}.config_id", identitas('id'))
                ->update($caseUpdate);
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
  
    public function updatePinPendudukMandiri()
    {
        Schema::table('tweb_penduduk_mandiri', function (Blueprint $table) {
            $table->string('pin')->change();
        });
    }
}
