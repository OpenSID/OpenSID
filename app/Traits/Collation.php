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

namespace App\Traits;

use Illuminate\Support\Facades\DB;

trait Collation
{
    public function updateCollation(string $database, string $dbCollate): void
    {
        $charSet                    = explode('_', $dbCollate)[0] ?? 'utf8mb4';
        $referencedTable            = $this->referenceTable($database);
        $inReferencedTableString    = '';
        $notInReferencedTableString = '';
        if ($referencedTable) {
            $listTableString            = "('" . implode("','", array_column($referencedTable, 'referenced_table')) . "')";
            $inReferencedTableString    = ' and TABLE_NAME in ' . $listTableString;
            $notInReferencedTableString = ' and TABLE_NAME not in ' . $listTableString;
        }
        $list = DB::select("
            select TABLE_NAME, TABLE_COLLATION
            from INFORMATION_SCHEMA.TABLES
            where TABLE_SCHEMA = '{$database}'
            and TABLE_TYPE   = 'BASE TABLE'
            and TABLE_COLLATION != '{$dbCollate}'
            {$inReferencedTableString}
            union all
            select TABLE_NAME, TABLE_COLLATION
            from INFORMATION_SCHEMA.TABLES
            where TABLE_SCHEMA = '{$database}'
            and TABLE_TYPE   = 'BASE TABLE'
            and TABLE_COLLATION != '{$dbCollate}'
            {$notInReferencedTableString}
        ");

        if ($list) {
            DB::statement('SET FOREIGN_KEY_CHECKS=0');

            foreach ($list as $tbl) {
                DB::statement("ALTER TABLE {$tbl->TABLE_NAME} CONVERT TO CHARACTER SET {$charSet} COLLATE {$dbCollate}");
                log_message('notice', 'Tabel ' . $tbl->TABLE_NAME . ' collation diubah dari ' . $tbl->TABLE_COLLATION . " menjadi {$dbCollate}.");
            }
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
        }
    }

    private function referenceTable(string $database)
    {
        $sql = "
        SELECT
            kcu.referenced_table_name AS referenced_table
        FROM
            information_schema.key_column_usage kcu
        JOIN
            information_schema.table_constraints tc
            ON kcu.constraint_name = tc.constraint_name
        WHERE
            tc.constraint_type = 'FOREIGN KEY'
            AND kcu.table_schema = '{$database}'
        group by kcu.referenced_table_name
        HAVING count(kcu.referenced_table_name) > 1
        order by count(kcu.referenced_table_name) desc
        ";

        return DB::select($sql) ?? [];
    }
}
