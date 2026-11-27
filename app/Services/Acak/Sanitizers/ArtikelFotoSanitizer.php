<?php

declare(strict_types=1);

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

namespace App\Services\Acak\Sanitizers;

use App\Services\Acak\AcakContext;
use App\Services\Acak\AcakResult;
use App\Services\Acak\Support\ContentImageProcessor;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Watermark/ganti gambar sampul artikel (kolom gambar, gambar1, gambar2, gambar3).
 * Varian kecil_/sedang_ ditangani otomatis oleh ContentImageProcessor.
 */
final class ArtikelFotoSanitizer extends AbstractContentFotoSanitizer
{
    public function label(): string
    {
        return 'Memproses foto artikel';
    }

    protected function process(ContentImageProcessor $processor, string $dir, AcakContext $context, AcakResult $result): int
    {
        if (! Schema::hasTable('artikel')) {
            return 0;
        }

        $filenames = [];
        foreach (['gambar', 'gambar1', 'gambar2', 'gambar3'] as $col) {
            if (! Schema::hasColumn('artikel', $col)) {
                continue;
            }
            $filenames = array_merge($filenames, DB::table('artikel')
                ->whereNotNull($col)->where($col, '!=', '')
                ->distinct()->pluck($col)->all());
        }

        $count = 0;
        foreach (array_unique(array_filter(array_map('strval', $filenames))) as $filename) {
            if ($processor->processContentImage($dir, $filename, $result)) {
                $count++;
            }
        }

        if ($count > 0) {
            $result->markTable('artikel (foto)');
        }
        $context->progress($this->label());

        return $count;
    }

    protected function defaultDirName(): string
    {
        return 'artikel';
    }

    protected function warnLabel(): string
    {
        return 'foto_artikel';
    }

    protected function recordCount(AcakResult $result, int $count): void
    {
        $result->fotosArtikelProcessed += $count;
    }
}
