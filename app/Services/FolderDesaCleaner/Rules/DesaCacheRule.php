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
 * Flags installation-specific cache files in desa/cache/ that should not
 * be distributed with a demo dataset.
 *
 * Currently targets:
 *   - status_langganan  (serialised PHP object containing the JWT subscription
 *     token, domain name, and desa code — specific to the originating installation)
 *
 * IDM cache files (idm_YEAR_KODE.json) live in desa/cache/ and are safe to
 * delete (they are re-fetched from the API on next access), but they carry no
 * PII so they are not included here.
 */
class DesaCacheRule extends AbstractCleanupRule
{
    private const SENSITIVE_CACHE_FILES = [
        'status_langganan',
    ];

    public function key(): string
    {
        return 'desa_cache';
    }

    protected function folderLabel(): string
    {
        return 'desa/cache/';
    }

    protected function description(): string
    {
        return 'Cache spesifik instalasi (status_langganan berisi token JWT dan domain desa asal)';
    }

    protected function scan(): array
    {
        $cacheDir = $this->absPath('desa/cache');

        if (! is_dir($cacheDir)) {
            return [];
        }

        $candidates = [];

        foreach (self::SENSITIVE_CACHE_FILES as $name) {
            $path = $cacheDir . DIRECTORY_SEPARATOR . $name;

            if (is_file($path)) {
                $candidates += $this->fileEntry($path);
            }
        }

        return $candidates;
    }
}
