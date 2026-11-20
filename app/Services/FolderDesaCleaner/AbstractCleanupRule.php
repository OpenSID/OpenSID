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
 * Base class for all single-group cleanup rules.
 *
 * Provides:
 *   1. ALWAYS_PROTECTED — filenames that must never be deletion candidates.
 *      Applied as a final safety filter regardless of what scan() returns.
 *   2. Default scanGroups() — wraps scan() into a single ScanGroup; subclasses
 *      only need to implement key(), folderLabel(), description(), isBulk(), scan().
 *
 * Rules that produce multiple groups (e.g. StrayContentRule) override scanGroups() directly.
 */
abstract class AbstractCleanupRule implements CleanupRule
{
    /**
     * Filenames that must never appear as deletion candidates in any folder.
     * Belt-and-suspenders: even if a buggy rule returns these, they are stripped.
     */
    protected const ALWAYS_PROTECTED = [
        'index.html',
        'index.php',
        '.htaccess',
        '.htpasswd',
        'baca-ini.txt',
        'web.config',
        '.gitkeep',
        '.gitignore',
    ];

    public function __construct(protected string $basePath = '')
    {
        if ($this->basePath === '') {
            $this->basePath = base_path();
        }
        // Resolve symlinks so path comparisons work on macOS (/tmp → /private/tmp)
        $this->basePath = realpath($this->basePath) ?: $this->basePath;
    }

    /**
     * Strips ALWAYS_PROTECTED (and extraProtected) filenames from a candidate map.
     * Always called by scanGroups() — never pass raw scan() output to the scanner.
     *
     * @param array $candidates ['rel/path' => ['size' => int, 'mtime' => int]]
     */
    final public function applySafetyFilter(array $candidates): array
    {
        $protected = array_flip(
            array_merge(static::ALWAYS_PROTECTED, $this->extraProtected())
        );

        return array_filter(
            $candidates,
            static fn ($path) => ! isset($protected[basename($path)]),
            ARRAY_FILTER_USE_KEY
        );
    }

    // ── CleanupRule implementation ────────────────────────────────────────────

    public function scanGroups(): array
    {
        $files = $this->applySafetyFilter($this->scan());

        if (empty($files)) {
            return [];
        }

        return [new ScanGroup(
            key: $this->key(),
            folderLabel: $this->folderLabel(),
            description: $this->description(),
            isBulk: $this->isBulk(),
            files: $files,
        )];
    }

    /**
     * Subclasses may add folder-specific protected filenames.
     */
    protected function extraProtected(): array
    {
        return [];
    }

    // ── Subclass contract for single-group rules ─────────────────────────────

    abstract protected function folderLabel(): string;

    abstract protected function description(): string;

    protected function isBulk(): bool
    {
        return false;
    }

    /**
     * Return candidate files.
     * Format: ['relative/path/to/file' => ['size' => int, 'mtime' => int]]
     * Paths must be relative to base_path().
     */
    abstract protected function scan(): array;

    // ── Shared filesystem helpers ─────────────────────────────────────────────

    /**
     * Absolute path for a path relative to basePath.
     */
    protected function absPath(string $relative): string
    {
        return rtrim($this->basePath, '/\\') . DIRECTORY_SEPARATOR
            . ltrim(str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $relative), '/\\');
    }

    /**
     * Build a candidate entry from an absolute file path.
     * Returns ['rel/path' => ['size' => int, 'mtime' => int]].
     */
    protected function fileEntry(string $absPath): array
    {
        $rel = ltrim(
            str_replace(
                rtrim($this->basePath, '/\\') . DIRECTORY_SEPARATOR,
                '',
                $absPath
            ),
            '/\\'
        );

        return [$rel => [
            'size'  => (int) @filesize($absPath),
            'mtime' => (int) @filemtime($absPath),
        ]];
    }

    /**
     * Glob a single directory (non-recursive) and return all files as candidate entries,
     * excluding ALWAYS_PROTECTED names (applySafetyFilter is still called by scanGroups,
     * but this saves work for large dirs).
     */
    protected function globDir(string $absDir): array
    {
        $protected = array_flip(static::ALWAYS_PROTECTED);
        $result    = [];

        foreach (glob(rtrim($absDir, '/\\') . DIRECTORY_SEPARATOR . '*') ?: [] as $path) {
            if (is_file($path) && ! isset($protected[basename($path)])) {
                $result += $this->fileEntry($path);
            }
        }

        return $result;
    }

    /**
     * Pluck a set of non-null basenames from a DB table column.
     */
    protected function referencedBasenames(string $table, string $column): array
    {
        if (! \Illuminate\Support\Facades\Schema::hasTable($table)) {
            return [];
        }

        return \Illuminate\Support\Facades\DB::table($table)
            ->whereNotNull($column)
            ->pluck($column)
            ->map(static fn ($v) => basename((string) $v))
            ->filter()
            ->flip()
            ->all();
    }
}
