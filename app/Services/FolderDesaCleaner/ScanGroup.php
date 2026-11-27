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

namespace App\Services\FolderDesaCleaner;

/**
 * Result of one CleanupRule scan — a group of candidate files/directories.
 *
 * $files format: ['relative/path/to/file' => ['size' => int, 'mtime' => int]]
 * Paths are relative to base_path() for portability.
 *
 * When $directoryPath is set, FolderDesaScanner::delete() removes the entire
 * directory tree instead of unlinking individual files.
 */
class ScanGroup
{
    public int $totalSize;
    public int $fileCount;

    /**
     * @param string      $key           Matches CleanupRule::key() — used as form field name
     * @param string      $folderLabel   Display path shown in UI, e.g. 'desa/OSX/'
     * @param string      $description   Short human description of why these are candidates
     * @param bool        $isBulk        true → show count/size only; false → list individual files
     * @param array       $files         ['rel/path' => ['size' => int, 'mtime' => int]]
     * @param string|null $directoryPath Absolute path to delete as a tree (stray dirs only)
     */
    public function __construct(
        public readonly string $key,
        public readonly string $folderLabel,
        public readonly string $description,
        public readonly bool $isBulk,
        public readonly array $files,
        public readonly ?string $directoryPath = null,
    ) {
        $this->fileCount = count($files);
        $this->totalSize = (int) array_sum(array_column($files, 'size'));
    }

    public static function formatBytes(int $bytes): string
    {
        if ($bytes >= 1_073_741_824) {
            return round($bytes / 1_073_741_824, 1) . ' GB';
        }

        if ($bytes >= 1_048_576) {
            return round($bytes / 1_048_576, 1) . ' MB';
        }

        if ($bytes >= 1_024) {
            return round($bytes / 1_024, 1) . ' KB';
        }

        return $bytes . ' B';
    }

    public function formattedSize(): string
    {
        return static::formatBytes($this->totalSize);
    }
}
