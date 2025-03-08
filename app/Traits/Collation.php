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

use Exception;
use Illuminate\Support\Facades\DB;

trait Collation
{
    public function updateCollation(string $database, string $dbCollate): void
    {
        $charSet = explode('_', $dbCollate)[0] ?? 'utf8mb4';

        // Ambil semua tabel dengan info foreign key (kalau ada)
        $tables = DB::table('INFORMATION_SCHEMA.TABLES as t')
            ->select('t.TABLE_NAME', 't.TABLE_COLLATION')
            ->selectRaw("
                EXISTS (
                    SELECT 1
                    FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE AS kcu
                    JOIN INFORMATION_SCHEMA.TABLE_CONSTRAINTS AS tc
                        ON kcu.CONSTRAINT_NAME = tc.CONSTRAINT_NAME
                        AND kcu.TABLE_SCHEMA = tc.TABLE_SCHEMA
                    WHERE kcu.TABLE_NAME = t.TABLE_NAME
                    AND kcu.TABLE_SCHEMA = '{$database}'
                    AND tc.CONSTRAINT_TYPE = 'FOREIGN KEY'
                ) AS has_fk
            ")
            ->where('t.TABLE_SCHEMA', $database)
            ->where('t.TABLE_TYPE', 'BASE TABLE')
            ->where('t.TABLE_COLLATION', '!=', $dbCollate)
            ->orderByDesc('has_fk')
            ->get();

        if ($tables->isEmpty()) {
            return;
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        foreach ($tables as $tbl) {
            try {
                DB::statement("ALTER TABLE `{$tbl->TABLE_NAME}` CONVERT TO CHARACTER SET {$charSet} COLLATE {$dbCollate}");
                logger()->info("Tabel {$tbl->TABLE_NAME} collation diubah dari {$tbl->TABLE_COLLATION} menjadi {$dbCollate}.");
            } catch (Exception $e) {
                logger()->error($e);
            }
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
}
