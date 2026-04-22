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
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class UpdateThemesCommand extends Command
{
    /**
     * Nama dan signature dari console command.
     *
     * @var string
     */
    protected $signature = 'themes:update';

    /**
     * Deskripsi dari console command.
     *
     * @var string
     */
    protected $description = 'Perbarui tema - hapus semua kecuali esensi, clone dari repo, dan checkout ke branch rilis';

    /**
     * Tema-tema yang akan di-clone beserta URL repository mereka
     *
     * @var array
     */
    protected $themesToClone = [
        'wira'        => 'https://github.com/OpenSID/tema-wira.git',
        'seruit-lite' => 'https://github.com/OpenSID/tema-seruit-lite.git',
        'lestari'     => 'https://github.com/OpenSID/tema-lestari.git',
    ];

    /**
     * Jalankan console command.
     *
     * @return int
     */
    public function handle()
    {
        $themesPath = storage_path('app/themes');

        $this->info('Memulai proses pembaruan tema...');
        $this->newLine();

        // Langkah 1: Hapus semua folder tema kecuali esensi
        $this->info('Langkah 1: Menghapus folder tema lama (menjaga esensi)...');
        if (! $this->deleteOldThemes($themesPath)) {
            return Command::FAILURE;
        }

        $this->newLine();

        // Langkah 2: Clone repository dan checkout ke branch rilis
        $this->info('Langkah 2: Clone repository dan checkout ke branch rilis...');
        if (! $this->cloneThemes($themesPath)) {
            return Command::FAILURE;
        }

        $this->newLine();
        $this->info('✓ Pembaruan tema berhasil diselesaikan!');
        $this->table(['Nama Tema', 'Repository', 'Status'], $this->getThemeSummary($themesPath));

        return Command::SUCCESS;
    }

    /**
     * Hapus semua folder tema kecuali esensi
     *
     * @param string $themesPath
     *
     * @return bool
     */
    protected function deleteOldThemes($themesPath)
    {
        if (! is_dir($themesPath)) {
            $this->error("Direktori tema tidak ditemukan: {$themesPath}");

            return false;
        }

        $directories = array_diff(scandir($themesPath), ['.', '..', '.gitkeep', 'esensi']);

        foreach ($directories as $dir) {
            $dirPath = $themesPath . DIRECTORY_SEPARATOR . $dir;

            if (is_dir($dirPath)) {
                $this->info("  Menghapus: {$dir}");
                $this->deleteDirectory($dirPath);
            }
        }

        return true;
    }

    /**
     * Clone tema dan checkout ke branch rilis
     *
     * @param string $themesPath
     *
     * @return bool
     */
    protected function cloneThemes($themesPath)
    {
        foreach ($this->themesToClone as $themeName => $repoUrl) {
            $themeDir = $themesPath . DIRECTORY_SEPARATOR . $themeName;

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
            } catch (ProcessFailedException $e) {
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
                        $this->error('    ✗ Gagal checkout ke branch rilis: ' . $checkoutProcess->getErrorOutput());

                        return false;
                    }
                } catch (ProcessFailedException $fe) {
                    $this->error('    ✗ Gagal mengambil: ' . $fe->getProcess()->getErrorOutput());

                    return false;
                }
            }
        }

        return true;
    }

    /**
     * Hapus direktori secara rekursif
     *
     * @param string $path
     *
     * @return void
     */
    protected function deleteDirectory($path)
    {
        if (is_dir($path)) {
            $files = array_diff(scandir($path), ['.', '..']);

            foreach ($files as $file) {
                $filePath = $path . DIRECTORY_SEPARATOR . $file;

                if (is_dir($filePath)) {
                    $this->deleteDirectory($filePath);
                } else {
                    unlink($filePath);
                }
            }

            rmdir($path);
        }
    }

    /**
     * Dapatkan informasi summary tema
     *
     * @param string $themesPath
     *
     * @return array
     */
    protected function getThemeSummary($themesPath)
    {
        $summary = [];

        foreach ($this->themesToClone as $themeName => $repoUrl) {
            $themeDir = $themesPath . DIRECTORY_SEPARATOR . $themeName;
            $status   = is_dir($themeDir) ? 'Terpasang' : 'Gagal';

            $summary[] = [
                $themeName,
                $repoUrl,
                $status,
            ];
        }

        return $summary;
    }
}
