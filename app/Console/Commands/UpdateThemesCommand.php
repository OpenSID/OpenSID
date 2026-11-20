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

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class UpdateThemesCommand extends Command
{
    /**
     * Nama dan signature dari console command.
     *
     * @var string
     */
    protected $signature = 'themes:update {theme? : Nama tema yang ingin diperbarui (wira, seruit-lite, lestari)}';

    /**
     * Deskripsi dari console command.
     *
     * @var string
     */
    protected $description = 'Perbarui tema - hapus tema lama kecuali esensi, clone dari repo, dan checkout ke branch rilis';

    /**
     * Tema-tema yang akan di-clone beserta URL repository mereka
     *
     * @var array
     */
    protected $themesToClone = [
        'palanta'     => 'https://github.com/OpenSID/tema-palanta.git',
        'wira'        => 'https://github.com/OpenSID/tema-wira.git',
        'seruit-lite' => 'https://github.com/OpenSID/tema-seruit-lite.git',
        'lestari'     => 'https://github.com/OpenSID/tema-lestari.git',
    ];

    /**
     * Jalankan console command.
     */
    public function handle(): int
    {
        $themesPath = str_replace(['/', '\\'], DIRECTORY_SEPARATOR, storage_path('app/themes'));
        $themeInput = $this->argument('theme');
        $selectedThemes = [];

        if ($themeInput) {
            $themeInput = strtolower($themeInput);
            if (! array_key_exists($themeInput, $this->themesToClone)) {
                $this->error("Tema '{$themeInput}' tidak valid. Pilihan yang tersedia: " . implode(', ', array_keys($this->themesToClone)));

                return Command::FAILURE;
            }
            $selectedThemes[$themeInput] = $this->themesToClone[$themeInput];
        } else {
            $choices = array_merge(['Semua Tema'], array_keys($this->themesToClone));
            $choice = $this->choice('Pilih tema yang ingin diperbarui', $choices, 0);

            if ($choice === 'Semua Tema') {
                $selectedThemes = $this->themesToClone;
            } else {
                $selectedThemes[$choice] = $this->themesToClone[$choice];
            }
        }

        $this->info('Memulai proses pembaruan tema...');
        $this->newLine();

        // Langkah 1: Hapus folder tema yang terpilih kecuali esensi
        $this->info('Langkah 1: Menghapus folder tema lama (menjaga esensi)...');
        if (! $this->deleteOldThemes($themesPath, array_keys($selectedThemes))) {
            return Command::FAILURE;
        }

        $this->newLine();

        // Langkah 2: Clone repository dan checkout ke branch rilis
        $this->info('Langkah 2: Clone repository dan checkout ke branch rilis...');
        if (! $this->cloneThemes($themesPath, $selectedThemes)) {
            return Command::FAILURE;
        }

        $this->newLine();
        $this->info('✓ Pembaruan tema berhasil diselesaikan!');
        $this->table(['Nama Tema', 'Repository', 'Branch', 'Status'], $this->getThemeSummary($themesPath, $selectedThemes));

        return Command::SUCCESS;
    }

    /**
     * Hapus folder tema yang terpilih kecuali esensi
     */
    protected function deleteOldThemes(string $themesPath, array $selectedThemeNames): bool
    {
        clearstatcache();
        if (! is_dir($themesPath)) {
            $this->error("Direktori tema tidak ditemukan: {$themesPath}");

            return false;
        }

        foreach ($selectedThemeNames as $themeName) {
            $dirPath = $themesPath . DIRECTORY_SEPARATOR . $themeName;

            clearstatcache();
            if (is_dir($dirPath)) {
                $this->info("  Menghapus: {$themeName}");
                $this->deleteDirectory($dirPath);
            }
        }

        return true;
    }

    /**
     * Clone tema dan checkout ke branch rilis
     */
    protected function cloneThemes(string $themesPath, array $selectedThemes): bool
    {
        foreach ($selectedThemes as $themeName => $repoUrl) {
            $themeDir = $themesPath . DIRECTORY_SEPARATOR . $themeName;

            // Jika folder masih ada sebelum clone (karena proses hapus tertunda atau terkunci), hapus sekali lagi
            clearstatcache();
            if (is_dir($themeDir)) {
                $this->deleteDirectory($themeDir);
            }

            // Clone repository
            $this->info("  Mengclone {$themeName} dari {$repoUrl}...");
            $cloneProcess = new Process(['git', 'clone', $repoUrl, $themeDir]);
            $cloneProcess->setTimeout(null);

            try {
                $cloneProcess->mustRun();
                $this->line('    ✓ Berhasil di-clone');
            } catch (ProcessFailedException $e) {
                $this->error('    ✗ Gagal mengclone: ' . $e->getProcess()->getErrorOutput());

                return false;
            }

            // Checkout ke branch rilis
            $this->info("  Checkout ke branch rilis untuk {$themeName}...");
            $checkoutProcess = new Process(['git', 'checkout', 'rilis'], $themeDir);
            $checkoutProcess->setTimeout(null);

            try {
                $checkoutProcess->mustRun();
                $this->line('    ✓ Berhasil checkout ke branch rilis');
            } catch (ProcessFailedException) {
                // Coba fetch dan checkout jika percobaan pertama gagal
                $this->warn('    ! Mencoba mengambil branch remote terlebih dahulu...');
                $fetchProcess = new Process(['git', 'fetch', 'origin'], $themeDir);
                $fetchProcess->setTimeout(null);

                try {
                    $fetchProcess->mustRun();
                    $checkoutProcess->run();
                    if ($checkoutProcess->isSuccessful()) {
                        $this->line('    ✓ Berhasil checkout ke branch rilis');
                    } else {
                        $this->warn('    ! Gagal checkout ke branch rilis: ' . trim($checkoutProcess->getErrorOutput()));
                        $this->warn('    ! Menggunakan branch default dari repository.');
                    }
                } catch (ProcessFailedException $fe) {
                    $this->warn('    ! Gagal mengambil branch remote: ' . trim($fe->getProcess()->getErrorOutput()));
                    $this->warn('    ! Menggunakan branch default dari repository.');
                }
            }
        }

        return true;
    }

    /**
     * Hapus direktori secara paksa menggunakan perintah sistem operasi
     */
    protected function deleteDirectory(string $path)
    {
        if (! is_dir($path)) {
            return;
        }

        if (strncasecmp(PHP_OS, 'WIN', 3) === 0) {
            // Windows: rmdir /S /Q
            $process = new Process(['cmd', '/c', 'rmdir', '/S', '/Q', $path]);
            $process->run();
        } else {
            // Linux/macOS: rm -rf
            $process = new Process(['rm', '-rf', $path]);
            $process->run();
        }

        clearstatcache();

        // Fallback jika proses OS gagal
        if (is_dir($path)) {
            File::deleteDirectory($path);
            clearstatcache();
        }
    }

    /**
     * Dapatkan informasi summary tema
     */
    protected function getThemeSummary(string $themesPath, array $selectedThemes): array
    {
        $summary = [];

        foreach ($selectedThemes as $themeName => $repoUrl) {
            $themeDir = $themesPath . DIRECTORY_SEPARATOR . $themeName;
            $status   = is_dir($themeDir) ? 'Terpasang' : 'Gagal';
            $branch   = is_dir($themeDir) ? $this->getActiveBranch($themeDir) : '-';

            $summary[] = [
                $themeName,
                $repoUrl,
                $branch,
                $status,
            ];
        }

        return $summary;
    }

    /**
     * Dapatkan branch git aktif dari direktori tema
     */
    protected function getActiveBranch(string $themeDir): string
    {
        if (! is_dir($themeDir . DIRECTORY_SEPARATOR . '.git')) {
            return '-';
        }

        $process = new Process(['git', 'branch', '--show-current'], $themeDir);
        try {
            $process->mustRun();
            return trim($process->getOutput()) ?: '-';
        } catch (ProcessFailedException) {
            $alternativeProcess = new Process(['git', 'rev-parse', '--abbrev-ref', 'HEAD'], $themeDir);
            try {
                $alternativeProcess->mustRun();
                return trim($alternativeProcess->getOutput()) ?: '-';
            } catch (ProcessFailedException) {
                return '-';
            }
        }
    }
}
