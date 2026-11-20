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
 * Finds orphaned files in desa/upload/pendaftaran/.
 *
 * These are scan uploads from the mandiri (self-service) registration flow
 * (scan KTP, scan KK, foto selfie). They are actively displayed to the admin
 * in the warga-verification modal. Only files with no matching DB row are candidates.
 *
 * Referenced by: tweb_penduduk_mandiri.(scan_ktp, scan_kk, foto_selfie)
 */
class OrphanPendaftaranRule extends AbstractCleanupRule
{
    public function key(): string
    {
        return 'orphan_pendaftaran';
    }

    protected function folderLabel(): string
    {
        return 'desa/upload/pendaftaran/';
    }

    protected function description(): string
    {
        return 'Scan pendaftaran mandiri (KTP/KK/selfie) tidak dirujuk oleh database — aktif ditampilkan di verifikasi warga';
    }

    protected function scan(): array
    {
        $dir = $this->absPath('desa/upload/pendaftaran');

        if (! is_dir($dir)) {
            return [];
        }

        $referenced = array_merge(
            $this->referencedBasenames('tweb_penduduk_mandiri', 'scan_ktp'),
            $this->referencedBasenames('tweb_penduduk_mandiri', 'scan_kk'),
            $this->referencedBasenames('tweb_penduduk_mandiri', 'foto_selfie'),
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
