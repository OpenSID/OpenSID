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
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

/**
 * Watermark/ganti seluruh berkas gambar di folder media (rekursif). Gambar media
 * adalah embed TinyMCE ukuran penuh — tanpa konvensi thumbnail kecil_/sedang_, jadi
 * setiap berkas diproses in-place tanpa membuat varian.
 */
final class MediaFotoSanitizer extends AbstractContentFotoSanitizer
{
    private const IMAGE_EXTS = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

    public function label(): string
    {
        return 'Memproses foto media';
    }

    protected function process(ContentImageProcessor $processor, string $dir, AcakContext $context, AcakResult $result): int
    {
        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS),
            RecursiveIteratorIterator::LEAVES_ONLY
        );

        $count = 0;
        foreach ($iterator as $file) {
            $path = $file->getPathname();
            $ext  = strtolower(pathinfo($path, PATHINFO_EXTENSION));
            if (! in_array($ext, self::IMAGE_EXTS, true)) {
                continue;
            }

            if ($processor->processMediaImage($path, $result)) {
                $count++;
            }
        }

        $context->progress($this->label());

        return $count;
    }

    protected function defaultDirName(): string
    {
        return 'media';
    }

    protected function warnLabel(): string
    {
        return 'foto_media';
    }

    protected function recordCount(AcakResult $result, int $count): void
    {
        $result->fotosMediaProcessed += $count;
    }
}
