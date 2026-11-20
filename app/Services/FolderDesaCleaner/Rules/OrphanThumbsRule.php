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
 * Hak Cipta 2016 - 2026 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright Hak Cipta 2016 - 2026 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

namespace App\Services\FolderDesaCleaner\Rules;

use App\Services\FolderDesaCleaner\AbstractCleanupRule;
use Illuminate\Support\Facades\Schema;

/**
 * Finds thumbnail files in desa/upload/thumbs/ whose corresponding letter
 * (nama_surat) is no longer in the database.
 *
 * Thumbnails are named after the stem of nama_surat: surat_396.pdf → surat_396.png.
 * A thumbnail is orphaned when its stem does not match any current log_surat or
 * log_surat_dinas nama_surat stem.
 */
class OrphanThumbsRule extends AbstractCleanupRule
{
    public function key(): string
    {
        return 'orphan_thumbs';
    }

    protected function folderLabel(): string
    {
        return 'desa/upload/thumbs/';
    }

    protected function description(): string
    {
        return 'Thumbnail surat tidak dirujuk oleh database';
    }

    protected function scan(): array
    {
        $thumbsDir = $this->absPath('desa/upload/thumbs');

        if (! is_dir($thumbsDir)) {
            return [];
        }

        // Collect stems of all currently-referenced nama_surat values.
        $referencedStems = [];

        foreach (['log_surat', 'log_surat_dinas'] as $table) {
            if (Schema::hasTable($table)) {
                \Illuminate\Support\Facades\DB::table($table)
                    ->whereNotNull('nama_surat')
                    ->pluck('nama_surat')
                    ->each(static function ($name) use (&$referencedStems): void {
                        $referencedStems[pathinfo($name, PATHINFO_FILENAME)] = true;
                    });
            }
        }

        $candidates = [];

        foreach (glob($thumbsDir . DIRECTORY_SEPARATOR . '*.png') ?: [] as $path) {
            $stem = pathinfo($path, PATHINFO_FILENAME);

            if (! isset($referencedStems[$stem])) {
                $candidates += $this->fileEntry($path);
            }
        }

        return $candidates;
    }
}
