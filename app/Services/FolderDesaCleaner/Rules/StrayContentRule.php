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
use App\Services\FolderDesaCleaner\ScanGroup;
use FilesystemIterator;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use Throwable;

/**
 * Detects content in desa/ that is not part of the known OpenSID structure.
 *
 * Uses a whitelist derived from LOKASI_* constants in donjo-app/config/constants.php
 * plus confirmed bootstrap requirements. Anything not on the list — whether a
 * directory (e.g. desa/OSX/) or a loose file (e.g. desa/idm_0008.json) — is a
 * stray candidate.
 *
 * Returns one ScanGroup per stray directory found (with directoryPath set so
 * FolderDesaScanner can delete the whole tree), plus one combined group for any
 * stray loose files at desa/ root.
 */
class StrayContentRule extends AbstractCleanupRule
{
    // ── Whitelists ────────────────────────────────────────────────────────────

    /**
     * Known subdirectory names at desa/ root.
     * Derived from LOKASI_* constants and application bootstrap requirements.
     */
    private const KNOWN_ROOT_DIRS = [
        'logo',
        'arsip',
        'cache',
        'config',
        'template-surat',
        'themes',
        'upload',
        'anjungan',
        'widgets',
        'pengaturan',
        'prodeskel',
        'backup',
        'modules',
        'mandiri',
        'mandiri_video',
    ];

    /**
     * Known file names at desa/ root.
     * ALWAYS_PROTECTED (index.html, .htaccess, etc.) are also implicitly protected.
     */
    private const KNOWN_ROOT_FILES = [
        'app_key',
        '.env',
        'offline_mode.php',
        'acak_info.json',
    ];

    /**
     * Known subdirectory names inside desa/upload/.
     * Derived from LOKASI_* constants.
     */
    private const KNOWN_UPLOAD_DIRS = [
        'user_pict',
        'kelompok',
        'lembaga',
        'galeri',
        'artikel',
        'buku_tamu',
        'gis',
        'dokumen',
        'pengesahan',
        'widgets',
        'keuangan',
        'media',
        'produk',
        'pengaduan',
        'vaksin',
        'pendaftaran',
        'dtks',
        'dtsen',
        'fonts',
        'sosmed',
        'pengajuan_izin',
        'catatan_harian',
        'themes',
        'sinkronisasi',
        'thumbs',
    ];

    public function key(): string
    {
        return 'stray_content';
    }

    // ── CleanupRule implementation ────────────────────────────────────────────

    public function scanGroups(): array
    {
        $groups = [];

        $desaRoot   = $this->absPath('desa');
        $uploadRoot = $this->absPath('desa/upload');

        // ── Scan desa/ root ───────────────────────────────────────────────────
        $knownDirs  = array_flip(self::KNOWN_ROOT_DIRS);
        $knownFiles = array_flip(array_merge(
            self::KNOWN_ROOT_FILES,
            static::ALWAYS_PROTECTED
        ));
        $strayFiles = [];

        foreach (glob($desaRoot . DIRECTORY_SEPARATOR . '*') ?: [] as $path) {
            $name = basename($path);

            if (is_dir($path)) {
                if (! isset($knownDirs[$name])) {
                    $groups[] = $this->directoryGroup($name, $path);
                }
            } else {
                if (! isset($knownFiles[$name])) {
                    $strayFiles += $this->fileEntry($path);
                }
            }
        }

        // Also catch hidden files at root (e.g. .DS_Store) not in protected list
        foreach (glob($desaRoot . DIRECTORY_SEPARATOR . '.*') ?: [] as $path) {
            $name = basename($path);

            if ($name === '.' || $name === '..') {
                continue;
            }

            if (is_file($path) && ! isset($knownFiles[$name])) {
                $strayFiles += $this->fileEntry($path);
            }
        }

        if (! empty($strayFiles)) {
            $strayFiles = $this->applySafetyFilter($strayFiles);
        }

        if (! empty($strayFiles)) {
            $groups[] = new ScanGroup(
                key: 'stray_root_files',
                folderLabel: 'desa/',
                description: 'File tidak dikenal di root folder desa',
                isBulk: false,
                files: $strayFiles,
            );
        }

        // ── Scan desa/upload/ for unknown subdirectories ──────────────────────
        if (is_dir($uploadRoot)) {
            $knownUpload = array_flip(self::KNOWN_UPLOAD_DIRS);

            foreach (glob($uploadRoot . DIRECTORY_SEPARATOR . '*') ?: [] as $path) {
                if (is_dir($path) && ! isset($knownUpload[basename($path)])) {
                    $groups[] = $this->directoryGroup(
                        'desa/upload/' . basename($path),
                        $path,
                        'desa/upload/'
                    );
                }
            }
        }

        return array_filter($groups, static fn ($g) => $g->fileCount > 0 || $g->directoryPath !== null);
    }

    protected function folderLabel(): string
    {
        return 'desa/';
    }

    protected function description(): string
    {
        return 'Konten tidak dikenal di root folder desa';
    }

    protected function scan(): array
    {
        return [];
    }

    // ── Private helpers ───────────────────────────────────────────────────────

    private function directoryGroup(string $name, string $absDir, string $parentLabel = 'desa/'): ScanGroup
    {
        $files = $this->buildDirFileList($absDir);

        return new ScanGroup(
            key: 'stray_dir_' . preg_replace('/[^a-z0-9]+/', '_', strtolower($name)),
            folderLabel: $parentLabel . $name . '/',
            description: 'Folder tidak dikenal — bukan bagian dari struktur OpenSID',
            isBulk: true,
            files: $files,
            directoryPath: $absDir,
        );
    }

    /**
     * Recursively list all files in a directory for size/count reporting.
     */
    private function buildDirFileList(string $absDir): array
    {
        $files = [];
        $protected = array_flip(static::ALWAYS_PROTECTED);

        try {
            $iter = new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator($absDir, FilesystemIterator::SKIP_DOTS)
            );

            foreach ($iter as $file) {
                if ($file->isFile()) {
                    $abs = $file->getPathname();
                    if (isset($protected[basename($abs)])) {
                        continue;
                    }
                    $rel = ltrim(
                        str_replace(
                            rtrim($this->basePath, '/\\') . DIRECTORY_SEPARATOR,
                            '',
                            $abs
                        ),
                        '/\\'
                    );
                    $files[$rel] = ['size' => $file->getSize(), 'mtime' => $file->getMTime()];
                }
            }
        } catch (Throwable) {
            // Unreadable directory — return empty; fileCount will be 0 but directoryPath still set.
        }

        return $files;
    }
}
