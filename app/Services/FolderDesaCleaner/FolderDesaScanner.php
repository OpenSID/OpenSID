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

use App\Services\FolderDesaCleaner\Rules\DesaCacheRule;
use App\Services\FolderDesaCleaner\Rules\OrphanArsipRule;
use App\Services\FolderDesaCleaner\Rules\OrphanBukuTamuRule;
use App\Services\FolderDesaCleaner\Rules\OrphanDokumenRule;
use App\Services\FolderDesaCleaner\Rules\OrphanPendaftaranRule;
use App\Services\FolderDesaCleaner\Rules\OrphanPengaduanRule;
use App\Services\FolderDesaCleaner\Rules\OrphanPengesahanRule;
use App\Services\FolderDesaCleaner\Rules\OrphanThumbsRule;
use App\Services\FolderDesaCleaner\Rules\OrphanVaksinRule;
use App\Services\FolderDesaCleaner\Rules\ProdeskelOutputRule;
use App\Services\FolderDesaCleaner\Rules\StrayContentRule;
use FilesystemIterator;
use Illuminate\Support\Facades\File;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

/**
 * Orchestrates all CleanupRule instances to scan and delete candidate files.
 *
 * Usage (UI flow):
 *   $groups = (new FolderDesaScanner())->scan();
 *   // present $groups to user …
 *   $result = (new FolderDesaScanner())->delete($bulkKeys, $filePaths);
 *
 * Usage (Acak integration — arsip/thumbs only):
 *   $scanner = new FolderDesaScanner([new OrphanArsipRule(), new OrphanThumbsRule()]);
 */
class FolderDesaScanner
{
    /**
     * @var CleanupRule[]
     */
    private array $rules;

    /**
     * @param CleanupRule[] $rules    Pass a subset to limit scanning to specific rules.
     *                                Empty array uses all default rules.
     * @param string        $basePath Override for testing; defaults to base_path().
     */
    public function __construct(array $rules = [], private string $basePath = '')
    {
        if ($this->basePath === '') {
            $this->basePath = base_path();
        }
        // Resolve symlinks so path comparisons work on macOS (/tmp → /private/tmp)
        $this->basePath = realpath($this->basePath) ?: $this->basePath;

        $this->rules = $rules ?: static::defaultRules($this->basePath);
    }

    /**
     * @return CleanupRule[]
     */
    public static function defaultRules(string $basePath = ''): array
    {
        $bp = $basePath ?: base_path();

        return [
            new StrayContentRule($bp),
            new OrphanArsipRule($bp),
            new OrphanThumbsRule($bp),
            new ProdeskelOutputRule($bp),
            new DesaCacheRule($bp),
            new OrphanDokumenRule($bp),
            new OrphanPendaftaranRule($bp),
            new OrphanBukuTamuRule($bp),
            new OrphanVaksinRule($bp),
            new OrphanPengaduanRule($bp),
            new OrphanPengesahanRule($bp),
        ];
    }

    /**
     * Run all rules and return non-empty ScanGroups.
     *
     * @return ScanGroup[]
     */
    public function scan(): array
    {
        $groups = [];

        foreach ($this->rules as $rule) {
            foreach ($rule->scanGroups() as $group) {
                if ($group->fileCount > 0) {
                    $groups[] = $group;
                }
            }
        }

        return $groups;
    }

    /**
     * Bagi hasil scan menjadi argumen delete() dengan mengecualikan file yang
     * DIPERTAHANKAN ($keep, path relatif). Grup direktori (bulk) hanya dihapus
     * penuh bila tidak ada file yang dipertahankan di bawahnya.
     *
     * @param ScanGroup[]  $groups
     * @param list<string> $keep
     *
     * @return array{bulkKeys: list<string>, filePaths: list<string>}
     */
    public static function partition(array $groups, array $keep): array
    {
        $keepSet   = array_flip($keep);
        $bulkKeys  = [];
        $filePaths = [];

        foreach ($groups as $group) {
            if ($group->directoryPath !== null) {
                $adaDipertahankan = false;
                foreach (array_keys($group->files) as $path) {
                    if (isset($keepSet[$path])) {
                        $adaDipertahankan = true;
                        break;
                    }
                }
                if (! $adaDipertahankan) {
                    $bulkKeys[] = $group->key;
                }
            } else {
                foreach (array_keys($group->files) as $path) {
                    if (! isset($keepSet[$path])) {
                        $filePaths[] = $path;
                    }
                }
            }
        }

        return ['bulkKeys' => $bulkKeys, 'filePaths' => $filePaths];
    }

    /**
     * Delete candidate files as selected by the caller.
     *
     * @param string[] $bulkKeys  Rule keys whose entire candidate set should be deleted.
     *                            For groups with directoryPath, the whole tree is removed.
     * @param string[] $filePaths Individual relative file paths to delete (per-file groups).
     *
     * @return array{deleted: int, freed_bytes: int, errors: string[]}
     */
    public function delete(array $bulkKeys, array $filePaths): array
    {
        $deleted    = 0;
        $freedBytes = 0;
        $errors     = [];

        $bulkKeySet = array_flip($bulkKeys);

        // ── Bulk groups: re-scan each rule and delete groups whose key matches ──
        // Matches on group key (for multi-group rules like StrayContentRule) OR rule key
        // (for single-group rules where rule key == group key).
        foreach ($this->rules as $rule) {
            $ruleMatchesAll = isset($bulkKeySet[$rule->key()]);

            foreach ($rule->scanGroups() as $group) {
                if (! $ruleMatchesAll && ! isset($bulkKeySet[$group->key])) {
                    continue;
                }

                if ($group->directoryPath !== null) {
                    // Delete entire directory tree; count actual files, not just the dir itself
                    if (is_dir($group->directoryPath)) {
                        [$dirFiles, $dirBytes] = $this->countDirectory($group->directoryPath);
                        File::deleteDirectory($group->directoryPath);
                        $deleted += $dirFiles;
                        $freedBytes += $dirBytes;
                    }
                } else {
                    foreach ($group->files as $rel => $meta) {
                        [$ok, $bytes, $err] = $this->unlinkFile($rel, (int) ($meta['size'] ?? 0));
                        if ($ok) {
                        $deleted++; $freedBytes += $bytes;
                        } elseif ($err !== '') {
                        $errors[] = $err;
                        }
                    }
                }
            }
        }

        // ── Per-file paths submitted from the review form ────────────────────
        foreach ($filePaths as $rel) {
            $rel = (string) $rel;

            if (! $this->isWithinDesaFolder($rel)) {
                $errors[] = "Path ditolak (di luar folder desa): {$rel}";

                continue;
            }

            $abs                = $this->absPath($rel);
            $size               = is_file($abs) ? (int) @filesize($abs) : 0;
            [$ok, $bytes, $err] = $this->unlinkFile($rel, $size);

            if ($ok) {
            $deleted++; $freedBytes += $bytes;
            } elseif ($err !== '') {
            $errors[] = $err;
            }
        }

        return ['deleted' => $deleted, 'freed_bytes' => $freedBytes, 'errors' => $errors];
    }

    // ── Private helpers ───────────────────────────────────────────────────────

    /**
     * Path traversal guard.
     * Accepts relative paths that resolve to within base_path('desa').
     */
    private function isWithinDesaFolder(string $rel): bool
    {
        if ($rel === '' || str_contains($rel, "\0")) {
            return false;
        }

        $abs      = realpath($this->absPath($rel)) ?: $this->absPath($rel);
        $desaRoot = realpath($this->absPath('desa')) ?: $this->absPath('desa');

        return str_starts_with($abs, $desaRoot . DIRECTORY_SEPARATOR)
            || $abs === $desaRoot;
    }

    /**
     * Unlink a file by relative path. Returns [success, bytes_freed, error_message].
     */
    private function unlinkFile(string $rel, int $knownSize): array
    {
        $abs = $this->absPath($rel);

        if (! is_file($abs)) {
            return [false, 0, ''];
        }

        $size = $knownSize > 0 ? $knownSize : (int) @filesize($abs);

        if (@unlink($abs)) {
            return [true, $size, ''];
        }

        return [false, 0, "Gagal hapus: {$rel}"];
    }

    private function absPath(string $relative): string
    {
        return rtrim($this->basePath, '/\\') . DIRECTORY_SEPARATOR
            . ltrim(str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $relative), '/\\');
    }

    /**
     * Count files and total bytes inside a directory tree. Returns [fileCount, totalBytes].
     */
    private function countDirectory(string $dir): array
    {
        $count = 0;
        $bytes = 0;
        $iter  = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($dir, FilesystemIterator::SKIP_DOTS)
        );

        foreach ($iter as $file) {
            if ($file->isFile()) {
                $count++;
                $bytes += $file->getSize();
            }
        }

        return [$count, $bytes];
    }
}
