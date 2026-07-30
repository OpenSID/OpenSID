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

namespace Modules\BukuTamu\FolderDesa;

use App\Services\FolderDesaCleaner\AbstractCleanupRule;

/**
 * Finds orphaned photos in desa/upload/buku_tamu/.
 *
 * Guest book photos are actively displayed in the admin interface with a
 * gender-based avatar fallback (Modules/BukuTamu TamuModel::getUrlFotoAttribute).
 * Only files with no matching DB row are candidates.
 *
 * Referenced by: buku_tamu.foto
 */
class OrphanBukuTamuRule extends AbstractCleanupRule
{
    public function key(): string
    {
        return 'orphan_buku_tamu';
    }

    protected function folderLabel(): string
    {
        return 'desa/upload/buku_tamu/';
    }

    protected function description(): string
    {
        return 'Foto buku tamu tidak dirujuk oleh database — aktif ditampilkan di admin buku tamu';
    }

    protected function scan(): array
    {
        $dir = $this->absPath('desa/upload/buku_tamu');

        if (! is_dir($dir)) {
            return [];
        }

        $referenced = $this->referencedBasenames('buku_tamu', 'foto');

        $candidates = [];

        foreach (glob($dir . DIRECTORY_SEPARATOR . '*') ?: [] as $path) {
            if (is_file($path) && ! isset($referenced[basename($path)])) {
                $candidates += $this->fileEntry($path);
            }
        }

        return $candidates;
    }
}
