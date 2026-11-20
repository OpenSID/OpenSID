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

/**
 * Finds orphaned vaccination documents in desa/upload/vaksin/.
 *
 * These files have a download route via ambilBerkas() in Vaksin_covid controller.
 * Only files with no matching DB row are candidates.
 *
 * Referenced by: covid19_vaksin.(dokumen_vaksin_1, dokumen_vaksin_2, dokumen_vaksin_3, surat_dokter)
 */
class OrphanVaksinRule extends AbstractCleanupRule
{
    public function key(): string
    {
        return 'orphan_vaksin';
    }

    protected function folderLabel(): string
    {
        return 'desa/upload/vaksin/';
    }

    protected function description(): string
    {
        return 'Dokumen vaksinasi tidak dirujuk oleh database';
    }

    protected function scan(): array
    {
        $dir = $this->absPath('desa/upload/vaksin');

        if (! is_dir($dir)) {
            return [];
        }

        $referenced = array_merge(
            $this->referencedBasenames('covid19_vaksin', 'dokumen_vaksin_1'),
            $this->referencedBasenames('covid19_vaksin', 'dokumen_vaksin_2'),
            $this->referencedBasenames('covid19_vaksin', 'dokumen_vaksin_3'),
            $this->referencedBasenames('covid19_vaksin', 'surat_dokter'),
        );

        $candidates = [];

        foreach (glob($dir . DIRECTORY_SEPARATOR . '*') ?: [] as $path) {
            if (is_file($path) && ! isset($referenced[basename($path)])) {
                $candidates += $this->fileEntry($path);
            }
        }

        return $candidates;
    }
}
