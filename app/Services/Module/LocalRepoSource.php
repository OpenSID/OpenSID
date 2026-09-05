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

namespace App\Services\Module;

use FilesystemIterator;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use RuntimeException;
use SplFileInfo;
use Symfony\Component\Process\Process;
use ZipArchive;

/**
 * Sumber modul untuk PENGEMBANGAN: simulasi Layanan dari repo lokal.
 *
 * Alih-alih mengunduh ZIP dari server Layanan, adapter ini membuat ZIP langsung
 * dari checkout repo modul di disk — sehingga alur pasang/uninstall add-on bisa
 * diuji end-to-end tanpa Layanan yang berjalan. Diaktifkan hanya di lingkungan
 * `development` (lihat pengikatan di {@see \App\Providers\AppServiceProvider}).
 *
 * Dua strategi pembungkusan:
 *   - `working-tree` (default): membungkus berkas di working-tree apa adanya
 *     (termasuk perubahan belum-commit), mengabaikan `.git`/`vendor`/… — cocok
 *     untuk iterasi cepat saat menyunting modul.
 *   - `git-archive`: `git archive <ref>` — membungkus pohon **tercommit** pada
 *     ref (mis. `HEAD`, tag, cabang) langsung dari repo modul, menghormati
 *     `export-ignore` di `.gitattributes` repo itu. Reprodusibel & byte-setara
 *     dengan cara Layanan/`build-release` mengemas paket — dipakai untuk
 *     memverifikasi PEMBARUAN modul cepat tanpa Layanan.
 *
 * Berkas ini di-`export-ignore` (.gitattributes) sehingga TIDAK ikut ke ZIP
 * rilis; pengikatannya juga dijaga `class_exists` agar core tanpa berkas ini
 * jatuh ke {@see LayananHttpSource}.
 *
 * Nama folder puncak ZIP dibuat berbeda dari nama modul (`<Nama>-src`) agar
 * {@see ModuleManager} memindahkannya ke tujuan — sama seperti ZIP arsip Layanan.
 */
class LocalRepoSource implements ModuleSource
{
    /** Berkas/direktori yang tak perlu ikut ke paket (strategi working-tree). */
    private const ABAIKAN = ['.git', '.github', 'node_modules', 'vendor', '.DS_Store'];

    /**
     * @param string $repoBase    Direktori induk berisi checkout repo modul.
     * @param string $strategy    `working-tree` (default) atau `git-archive`.
     * @param string $ref         Ref git default untuk `git-archive` (mis. `HEAD`).
     * @param bool   $fetchRemote Bila `true` (hanya `git-archive`), jalankan
     *                            `git fetch` dari remote sebelum mengarsip —
     *                            sehingga ref seperti `origin/main` menunjuk versi
     *                            TERBARU remote (paksa-perbarui untuk verifikasi).
     */
    public function __construct(
        private readonly string $repoBase,
        private readonly string $strategy = 'working-tree',
        private readonly string $ref = 'HEAD',
        private readonly bool $fetchRemote = false,
    ) {}

    public function fetch(string $name, string $locator = ''): string
    {
        $repoDir = $this->resolveRepoDir($name);

        if ($repoDir === null) {
            throw new RuntimeException(
                "LocalRepoSource: repo modul {$name} tak ditemukan di bawah {$this->repoBase}."
            );
        }

        // Folder puncak sengaja berbeda dari nama modul agar ModuleManager
        // memindahkan hasil ekstrak ke direktori tujuan (pola ZIP arsip).
        $topLevel = $name . '-src';
        $zipPath  = rtrim(sys_get_temp_dir(), '/\\') . DIRECTORY_SEPARATOR . $name . '-local-' . uniqid('', true) . '.zip';

        if ($this->strategy === 'git-archive') {
            return $this->bungkusGitArchive($name, $repoDir, $topLevel, $zipPath, $this->refFor($locator));
        }

        return $this->bungkusWorkingTree($name, $repoDir, $topLevel, $zipPath);
    }

    /**
     * Bungkus pohon tercommit `$ref` langsung dari repo lewat `git archive`
     * (menghormati `export-ignore` `.gitattributes` repo modul).
     *
     * @throws RuntimeException
     */
    private function bungkusGitArchive(string $name, string $repoDir, string $topLevel, string $zipPath, string $ref): string
    {
        if ($this->fetchRemote) {
            $this->tarikRemote($name, $repoDir);
        }

        $process = new Process([
            'git', '-C', rtrim($repoDir, '/\\'), 'archive',
            '--format=zip', '--prefix=' . $topLevel . '/',
            '-o', $zipPath, $ref,
        ]);
        $process->run();

        if (! $process->isSuccessful()) {
            @unlink($zipPath);

            throw new RuntimeException(
                "LocalRepoSource: `git archive` gagal untuk {$name} (ref {$ref}): " . trim($process->getErrorOutput())
            );
        }

        return $zipPath;
    }

    /**
     * Tarik pembaruan dari semua remote (`git fetch --all --prune --tags`) agar
     * ref remote (mis. `origin/main`) mencerminkan versi terbaru saat mengarsip.
     *
     * @throws RuntimeException bila fetch gagal (mis. tak ada remote / luring).
     */
    private function tarikRemote(string $name, string $repoDir): void
    {
        $process = new Process(['git', '-C', rtrim($repoDir, '/\\'), 'fetch', '--all', '--prune', '--tags']);
        $process->run();

        if (! $process->isSuccessful()) {
            throw new RuntimeException(
                "LocalRepoSource: `git fetch` gagal untuk {$name}: " . trim($process->getErrorOutput())
            );
        }
    }

    /**
     * Bungkus working-tree apa adanya (abaikan `.git`/`vendor`/… dan berkas kotor).
     *
     * @throws RuntimeException
     */
    private function bungkusWorkingTree(string $name, string $repoDir, string $topLevel, string $zipPath): string
    {
        $zip = new ZipArchive();
        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            throw new RuntimeException("LocalRepoSource: gagal membuat ZIP untuk {$name}.");
        }

        $zip->addEmptyDir($topLevel);

        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($repoDir, FilesystemIterator::SKIP_DOTS),
            RecursiveIteratorIterator::SELF_FIRST
        );

        foreach ($files as $file) {
            /** @var SplFileInfo $file */
            $relative = ltrim(str_replace($repoDir, '', $file->getPathname()), '/\\');
            $relative = str_replace('\\', '/', $relative);

            if ($this->diabaikan($relative)) {
                continue;
            }

            $entry = $topLevel . '/' . $relative;

            if ($file->isDir()) {
                $zip->addEmptyDir($entry);
            } else {
                $zip->addFile($file->getPathname(), $entry);
            }
        }

        $zip->close();

        return $zipPath;
    }

    /**
     * Ref git efektif: `$locator` bila diberikan sebagai ref (bukan URL Layanan),
     * selain itu ref default konstruktor. Memudahkan memverifikasi tag/cabang
     * tertentu, mis. CLI `install_modul pasang Anjungan___v1.2.0___2609`.
     */
    private function refFor(string $locator): string
    {
        if ($locator !== '' && ! str_contains($locator, '://')) {
            return $locator;
        }

        return $this->ref;
    }

    /**
     * Temukan direktori repo untuk `$name` di bawah basis. Coba beberapa
     * konvensi penamaan; kembalikan yang pertama memuat `module.json`.
     */
    private function resolveRepoDir(string $name): ?string
    {
        $base       = rtrim($this->repoBase, '/\\') . DIRECTORY_SEPARATOR;
        $kandidat   = [$name, 'modul-' . strtolower($name), strtolower($name)];

        foreach ($kandidat as $folder) {
            $dir = $base . $folder;
            if (is_file($dir . DIRECTORY_SEPARATOR . 'module.json')) {
                return rtrim($dir, '/\\') . DIRECTORY_SEPARATOR;
            }
        }

        return null;
    }

    /**
     * Apakah entri relatif harus diabaikan (segmen mana pun cocok daftar abaikan)?
     */
    private function diabaikan(string $relative): bool
    {
        foreach (explode('/', $relative) as $segmen) {
            if (in_array($segmen, self::ABAIKAN, true)) {
                return true;
            }
        }

        return false;
    }
}
