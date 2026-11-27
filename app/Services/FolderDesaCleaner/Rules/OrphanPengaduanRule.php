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
 * Finds orphaned complaint photos in desa/upload/pengaduan/.
 *
 * Complaint photos are actively displayed in the detail timeline view
 * (resources/views/admin/pengaduan_warga/detail.blade.php). The Pengaduan
 * model auto-deletes the file on record deletion, so orphan rate is low.
 * Only files with no matching DB row are candidates.
 *
 * Referenced by: pengaduan.foto
 */
class OrphanPengaduanRule extends AbstractCleanupRule
{
    public function key(): string
    {
        return 'orphan_pengaduan';
    }

    protected function folderLabel(): string
    {
        return 'desa/upload/pengaduan/';
    }

    protected function description(): string
    {
        return 'Foto pengaduan tidak dirujuk oleh database — aktif ditampilkan di detail pengaduan';
    }

    protected function scan(): array
    {
        $dir = $this->absPath('desa/upload/pengaduan');

        if (! is_dir($dir)) {
            return [];
        }

        $referenced = $this->referencedBasenames('pengaduan', 'foto');

        $candidates = [];

        foreach (glob($dir . DIRECTORY_SEPARATOR . '*') ?: [] as $path) {
            if (is_file($path) && ! isset($referenced[basename($path)])) {
                $candidates += $this->fileEntry($path);
            }
        }

        return $candidates;
    }
}
