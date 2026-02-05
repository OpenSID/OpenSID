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

namespace App\Services\Security;

use App\Models\SecurityBaseline;
use App\Models\SecurityReport;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Finder\Finder;

class FileIntegrityDefaultService
{
    private HeuristicDetectorDefaultService $detector;

    /**
     * Excluded directories (relative to desa/)
     */
    private array $excludedDirs = [
        'themes',
        'upload/fonts',
    ];

    public function __construct()
    {
        $this->detector = new HeuristicDetectorDefaultService();

        // Ensure security storage disk exists
        Storage::disk('local')->makeDirectory('security');
    }

    /**
     * Generate baseline dari current state
     * Hanya menyimpan 1 baseline terbaru, replace yang lama
     *
     * @return array Result dengan jumlah file yang diproses
     */
    public function generateBaseline(): array
    {
        $desaPath = $this->getDesaPath();

        // Find all files using Symfony Finder
        $finder = new Finder();
        $finder->files()->in($desaPath);

        // Exclude directories
        foreach ($this->excludedDirs as $dir) {
            $finder->exclude($dir);
        }

        // Collect files with Laravel Collection
        $files = collect($finder)->map(function ($file) use ($desaPath) {
            $filepath     = $file->getRealPath();
            $relativePath = str_replace($desaPath, '', $filepath);

            $fileInfo = [
                'path'       => $relativePath,
                'size'       => $file->getSize(),
                'modified'   => $file->getMTime(),
                'hash'       => hash_file('sha256', $filepath),
                'suspicious' => false,
            ];

            // For PHP files and suspicious non-PHP files, do heuristic check
            if ($this->detector->shouldScanFile($filepath)) {
                $scanResult = $this->detector->scanFile($filepath);

                if (isset($scanResult['error'])) {
                    $fileInfo['scan_error'] = $scanResult['error'];
                } elseif ($scanResult['suspicious']) {
                    $fileInfo = array_merge($fileInfo, [
                        'suspicious'     => true,
                        'risk_score'     => $scanResult['risk_score'] ?? null,
                        'risk_level'     => $scanResult['risk_level'] ?? null,
                        'categories'     => $scanResult['categories'] ?? [],
                        'recommendation' => $scanResult['recommendation'] ?? null,
                    ]);
                }
            }

            return $fileInfo;
        });

        // Calculate statistics including total size
        $stats = [
            'total_files'      => $files->count(),
            'total_size'       => $files->sum('size'),
            'php_files'        => $files->filter(static fn ($f) => isset($f['risk_score']) || isset($f['scan_error']))->count(),
            'suspicious_files' => $files->where('suspicious', true)->count(),
            'errors'           => $files->filter(static fn ($f) => isset($f['scan_error']))->count(),
        ];

        // Update jika ada, create jika belum ada berdasarkan kondisi
        $newBaseline = SecurityBaseline::updateOrCreate(
            ['config_id' => identitas('id')], // Kondisi pencarian
            [
                'generated_at'     => Carbon::now(),
                'version'          => '1.0',
                'target_directory' => $desaPath,
                'excluded_dirs'    => $this->excludedDirs,
                'statistics'       => $stats,
                'files'            => $files->values()->toArray(),
            ]
        );

        return [
            'success'      => true,
            'baseline_id'  => $newBaseline->id,
            'statistics'   => $stats,
            'generated_at' => $newBaseline->generated_at->format('Y-m-d H:i:s'),
        ];
    }

    /**
     * Menjalankan proses scan keamanan lengkap.
     * 1. Cek jika baseline ada, jika tidak, buat otomatis.
     * 2. Bandingkan state file saat ini dengan baseline.
     * 3. Kelompokkan file ke dalam: MODIFIED, FOREIGN, SAFE.
     *
     * @return array Hasil scan
     */
    public function runScan(): array
    {
        // Sebelum proses panjang, pastikan koneksi DB aktif untuk menghindari timeout
        try {
            \Illuminate\Support\Facades\DB::connection()->getPdo();
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::reconnect();
        }

        // 1. Pastikan baseline ada
        $baselineRecord = SecurityBaseline::latest('generated_at')->first();
        if (! $baselineRecord) {
            return ['error' => 'Baseline tidak ditemukan. Harap generate baseline terlebih dahulu melalui menu Keamanan.'];
        }

        // 2. Lakukan perbandingan integritas
        $baseline      = $baselineRecord->toArray();
        $baselineIndex = collect($baseline['files'])->keyBy('path');
        $desaPath      = $this->getDesaPath();

        // Find current files
        $finder = new Finder();
        $finder->files()->in($desaPath);
        foreach ($this->excludedDirs as $dir) {
            $finder->notPath($dir);
        }

        $quarantinedFiles = [];
        $safeFilesCount   = 0;

        foreach ($finder as $file) {
            $filepath     = $file->getRealPath();
            $relativePath = str_replace($desaPath, '', $filepath);
            $currentHash  = hash_file('sha256', $filepath);

            $baselineFile = $baselineIndex->get($relativePath);

            if ($baselineFile) {
                // File ada di baseline, cek hash
                if ($baselineFile['hash'] !== $currentHash) {
                    // HASH BERBEDA -> MODIFIED
                    $quarantinedFiles[] = [
                        'path'     => $relativePath,
                        'status'   => 'MODIFIED',
                        'hash'     => $currentHash,
                        'old_hash' => $baselineFile['hash'],
                        'size'     => $file->getSize(),
                    ];
                } else {
                    // HASH SAMA -> SAFE
                    $safeFilesCount++;
                }
                // Hapus dari index agar nanti bisa deteksi file yang hilang (deleted)
                $baselineIndex->forget($relativePath);
            } else {
                // File tidak ada di baseline -> FOREIGN
                $quarantinedFiles[] = [
                    'path'     => $relativePath,
                    'status'   => 'FOREIGN',
                    'hash'     => $currentHash,
                    'size'     => $file->getSize(),
                    'modified' => date('Y-m-d H:i:s', $file->getMTime()),
                ];
            }
        }

        // File yang tersisa di baselineIndex adalah file yang "deleted"
        // Untuk saat ini, kita abaikan file yang deleted sesuai permintaan
        // 'deleted_files' => $baselineIndex->values()->toArray()

        $result = [
            'success'          => true,
            'checked_at'       => Carbon::now()->format('Y-m-d H:i:s'),
            'baseline_date'    => $baseline['generated_at'],
            'quarantined'      => $quarantinedFiles,
            'statistics'       => [
                'quarantined_count' => count($quarantinedFiles),
                'safe_count'        => $safeFilesCount,
                'total_scanned'     => $safeFilesCount + count($quarantinedFiles),
            ],
        ];

        $this->exportReport($result, 'scan');

        return $result;
    }

    /**
     * Menghapus file "asing" dari folder desa.
     *
     * @param string $relativePath Path relatif file yang akan dihapus
     * @return bool
     */
    public function deleteFile(string $relativePath): bool
    {
        $desaPath = $this->getDesaPath();
        $fullPath = realpath($relativePath);

        // Keamanan: Pastikan file ada di dalam folder 'desa'
        $realDesaPath = realpath($desaPath);
        if (! $fullPath || ! $realDesaPath || ! str_starts_with($fullPath, $realDesaPath)) {
            throw new \Exception('Path file tidak valid atau berada di luar folder yang diizinkan.');
        }

        if (file_exists($fullPath)) {
            return unlink($fullPath);
        }

        return false;
    }

    /**
     * Mengembalikan file yang dimodifikasi ke versi asli.
     *
     * @param string $relativePath Path relatif file yang akan dikembalikan
     * @return bool
     */
    public function restoreFile(string $fullPath): bool
    {
        // 1. Normalisasi: Ubah path absolut menjadi path relatif terhadap root aplikasi
        // Misal: 'C:\laragon\www\premium\desa\config.php' -> 'desa/config.php'
        $basePath = base_path();
        $relativePath = str_replace($basePath, '', $fullPath);
        $relativePath = ltrim($relativePath, '/\\'); 

        // 2. Tentukan Patokan Sumber (Source)
        // Kita asumsikan file asli ada di dalam folder vendor (clean source)
        $cleanSourcePath = base_path('vendor/opensid/premium/src/' . $relativePath);

        // 3. Tentukan Tujuan (Destination)
        $destinationPath = base_path($relativePath);
        $destinationDir  = dirname($destinationPath);

        // --- LOGIKA PEMULIHAN ---

        // Cek apakah file sumber di vendor ada
        if (file_exists($cleanSourcePath)) {
            if (!is_dir($destinationDir)) {
                mkdir($destinationDir, 0755, true);
            }
            return copy($cleanSourcePath, $destinationPath);
        }

        // Jika tidak ada di vendor, lempar error atau gunakan fallback lain
        throw new \Exception("File sumber asli tidak ditemukan di: " . $cleanSourcePath);
    }
    /**
     * Check integrity dengan membandingkan current state vs baseline
     *
     * @return array Report dengan new files, modified files, deleted files, suspicious files
     */
    public function checkIntegrity(): array
    {
        // Get latest baseline from database untuk config_id saat ini
        $baselineRecord = SecurityBaseline::latest('generated_at')->first();

        if (! $baselineRecord) {
            return [
                'error'           => 'Baseline not found. Please generate baseline first.',
                'baseline_exists' => false,
            ];
        }

        // Convert to array format for compatibility
        $baseline = [
            'generated_at'     => $baselineRecord->generated_at->format('Y-m-d H:i:s'),
            'version'          => $baselineRecord->version,
            'target_directory' => $baselineRecord->target_directory,
            'excluded_dirs'    => $baselineRecord->excluded_dirs,
            'files'            => $baselineRecord->files,
            'statistics'       => $baselineRecord->statistics,
        ];

        // Create index dari baseline untuk quick lookup using Collection
        $baselineIndex = collect($baseline['files'])->keyBy('path');

        $desaPath = $this->getDesaPath();

        // Find current files
        $finder = new Finder();
        $finder->files()->in($desaPath);

        // Exclude directories
        foreach ($this->excludedDirs as $dir) {
            $finder->notPath($dir);
        }

        // Process current files using Collection
        $currentFiles = collect($finder)->map(static function ($file) use ($desaPath) {
            $filepath     = $file->getRealPath();
            $relativePath = str_replace($desaPath, '', $filepath);

            return [
                'path'     => $relativePath,
                'filepath' => $filepath,
                'hash'     => hash_file('sha256', $filepath),
                'size'     => $file->getSize(),
                'modified' => $file->getMTime(),
            ];
        })->keyBy('path');

        // Find new files
        $newFiles = $currentFiles->diffKeys($baselineIndex)->map(function ($file) {
            $fileInfo = [
                'path'     => $file['path'],
                'size'     => $file['size'],
                'modified' => date('Y-m-d H:i:s', $file['modified']),
                'hash'     => $file['hash'],
            ];

            // Scan PHP files and suspicious non-PHP files for suspicious patterns
            if ($this->detector->shouldScanFile($file['filepath'])) {
                $scanResult = $this->detector->scanFile($file['filepath']);

                if ($scanResult['suspicious']) {
                    $fileInfo = array_merge($fileInfo, [
                        'suspicious'     => true,
                        'risk_score'     => $scanResult['risk_score'] ?? null,
                        'risk_level'     => $scanResult['risk_level'] ?? null,
                        'categories'     => $scanResult['categories'] ?? [],
                        'recommendation' => $scanResult['recommendation'] ?? null,
                    ]);
                }
            }

            return $fileInfo;
        });

        // Find modified files
        $modifiedFiles = $currentFiles->intersectByKeys($baselineIndex)
            ->filter(static function ($current) use ($baselineIndex) {
                $baseline = $baselineIndex[$current['path']];

                return $current['hash'] !== $baseline['hash'];
            })
            ->map(function ($file) use ($baselineIndex) {
                $baseline = $baselineIndex[$file['path']];

                $fileInfo = [
                    'path'     => $file['path'],
                    'old_hash' => $baseline['hash'],
                    'new_hash' => $file['hash'],
                    'old_size' => $baseline['size'],
                    'new_size' => $file['size'],
                    'modified' => date('Y-m-d H:i:s', $file['modified']),
                ];

                // Rescan PHP files and suspicious non-PHP files
                if ($this->detector->shouldScanFile($file['filepath'])) {
                    $scanResult = $this->detector->scanFile($file['filepath']);

                    if ($scanResult['suspicious']) {
                        $fileInfo = array_merge($fileInfo, [
                            'suspicious'     => true,
                            'risk_score'     => $scanResult['risk_score'] ?? null,
                            'risk_level'     => $scanResult['risk_level'] ?? null,
                            'categories'     => $scanResult['categories'] ?? [],
                            'recommendation' => $scanResult['recommendation'] ?? null,
                        ]);
                    }
                }

                return $fileInfo;
            });

        // Find deleted files
        $deletedFiles = $baselineIndex->diffKeys($currentFiles)
            ->map(static fn ($baseline) => [
                'path'          => $baseline['path'],
                'baseline_hash' => $baseline['hash'],
                'baseline_size' => $baseline['size'],
            ]);

        // Collect suspicious files
        $suspiciousFiles = $newFiles->concat($modifiedFiles)
            ->filter(static fn ($f) => isset($f['suspicious']) && $f['suspicious']);

        return [
            'checked_at'       => Carbon::now()->format('Y-m-d H:i:s'),
            'baseline_date'    => $baseline['generated_at'],
            'new_files'        => $newFiles->values()->toArray(),
            'modified_files'   => $modifiedFiles->values()->toArray(),
            'deleted_files'    => $deletedFiles->values()->toArray(),
            'suspicious_files' => $suspiciousFiles->values()->toArray(),
            'statistics'       => [
                'total_checked'    => $currentFiles->count(),
                'new_count'        => $newFiles->count(),
                'modified_count'   => $modifiedFiles->count(),
                'deleted_count'    => $deletedFiles->count(),
                'suspicious_count' => $suspiciousFiles->count(),
            ],
        ];
    }

    /**
     * Full scan desa/ folder dengan heuristic detector
     * Tidak membandingkan dengan baseline, hanya scan suspicious patterns
     *
     * @return array Scan results
     */
    public function fullScan(): array
    {
        $desaPath      = $this->getDesaPath();
        $excludedPaths = $this->getExcludedPaths()->toArray();

        return $this->detector->scanDirectory($desaPath, $excludedPaths);
    }

    /**
     * Save security report to database
     *
     * @param array  $report Report data
     * @param string $type   Type of report: 'integrity' or 'scan'
     *
     * @return string Generated filename (used as unique identifier)
     */
    public function exportReport(array $report, string $type = 'integrity'): string
    {
        $timestamp = Carbon::now()->format('Ymd_His');
        $filename  = "security_{$type}_{$timestamp}.json";

        // Save to database instead of file
        SecurityReport::create([
            'filename'  => $filename,
            'type'      => $type,
            'data'      => $report,
            'config_id' => identitas('id'),
        ]);

        return $filename; // Return filename as unique identifier for database lookup
    }

    /**
     * Get baseline info untuk config_id saat ini
     */
    public function getBaselineInfo(): ?array
    {
        $baseline = SecurityBaseline::latest('generated_at')->first();

        if (! $baseline) {
            return null;
        }

        return [
            'exists'       => true,
            'id'           => $baseline->id,
            'generated_at' => $baseline->generated_at->format('Y-m-d H:i:s'),
            'version'      => $baseline->version,
            'total_files'  => $baseline->statistics['total_files'] ?? 0,
            'total_size'   => $baseline->statistics['total_size'] ?? 0,
            'statistics'   => $baseline->statistics ?? [],
        ];
    }

    /**
     * Delete baseline (hanya untuk config_id saat ini)
     */
    public function deleteBaseline(): bool
    {
        return SecurityBaseline::query()->delete() > 0;
    }

    /**
     * Get pattern statistics from detector
     */
    public function getPatternStats(): array
    {
        return $this->detector->getPatternStats();
    }

    /**
     * Get security reports from database
     *
     * @param string|null $type Filter by type (integrity, scan, or null for all)
     */
    public function getReports(?string $type = null): Collection
    {
        $query = SecurityReport::latest();

        if ($type) {
            $query->ofType($type);
        }

        return $query->get();
    }

    /**
     * Get specific report by filename
     */
    public function getReport(string $filename): ?SecurityReport
    {
        return SecurityReport::where('filename', $filename)->first();
    }

    /**
     * Delete report by filename
     */
    public function deleteReport(string $filename): bool
    {
        return SecurityReport::where('filename', $filename)->delete() > 0;
    }

    /**
     * Get desa path
     */
    private function getDesaPath(): string
    {
        return base_path('desa/');
    }

    /**
     * Get excluded paths (full path)
     */
    private function getExcludedPaths(): Collection
    {
        return collect($this->excludedDirs)
            ->map(fn ($dir) => rtrim($this->getDesaPath(), '/') . '/' . ltrim($dir, '/'));
    }
}
