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

namespace App\Libraries;

use App\Models\Migrasi;
use App\Models\SettingAplikasi;
use App\Traits\Migration;
use Exception;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Modules\Pelanggan\Services\CekService;

class Database
{
    use Migration;

    public string $minimumVersion = MINIMUM_VERSI;

    /**
     * @var CekService
     */
    public $premium;

    private string $engine    = 'InnoDB';
    private int $showProgress = 0;
    private array $databaseOption;
    private string $databaseName;

    public function __construct()
    {
        $this->databaseOption = DB::getConnections()['default']->getConfig();
        $this->databaseName   = $this->databaseOption['database'];
    }

    public function migrateDatabase($install = false): void
    {
        if (session('sedang_restore') == 1) {
            return;
        }

        $migratedDatabase = Migrasi::pluck('versi_database', 'versi_database')->toArray();

        $version        = (int) str_replace('.', '', $this->checkCurrentVersion());
        $minimumVersion = (int) str_replace('.', '', $this->minimumVersion);

        $currentVersion = (int) str_replace('.', '', currentVersion());
        if (! PREMIUM) {
            $versiSetara = SettingAplikasi::where(['key' => 'compatible_version_general'])->first()?->value;
            $versiSetara = (int) str_replace('.', '', $versiSetara);
            if ($versiSetara && $currentVersion < $versiSetara) {
                show_error('<h2>OpenSID bisa diupgrade dengan minimal versi ' . $versiSetara . '. Versi terakhir yang digunakan adalah ' . $version . '</h2>');
            }
        }

        if (! $install && $version < $minimumVersion) {
            show_error('<h2>Silakan upgrade dulu ke OpenSID dengan minimal versi ' . $this->minimumVersion . '. Versi terakhir yang digunakan adalah ' . $version . '</h2>');
        }

        $migrations = File::files('app/database/migrations');

        // sort by name
        usort($migrations, static fn ($a, $b): int => strcmp($a->getFilename(), $b->getFilename()));

        try {
            foreach ($migrations as $migrate) {
                preg_match('/\d+/', $migrate->getFilename(), $matches);
                if ($matches) {
                    $migrateName = $matches[0];
                    if (! isset($migratedDatabase[$migrateName])) {
                        if ($this->getShowProgress()) {
                            echo json_encode(['message' => 'Jalankan Migrasi_' . $migrateName, 'status' => 0]);
                        }
                        $resultMigration = $this->runMigration('Migrasi_' . $migrateName);
                        if ($this->getShowProgress()) {
                            echo json_encode(['message' => $resultMigration['message'], 'status' => $resultMigration['status'] ? 0 : 500]);
                        }
                        log_message($resultMigration['status'] ? 'notice' : 'error', $resultMigration['message']);

                        if (isset($resultMigration['exception'])) {
                            logger()->error($resultMigration['exception']);
                        }

                        $this->updateVersi($migrateName);
                    }
                }
            }

            $this->updateVersi(VERSI_DATABASE);
        } catch (Exception $e) {
            log_message('error', $e->getMessage());
            if ($this->getShowProgress()) {
                echo json_encode(['message' => $e->getMessage(), 'status' => 0]);
            }
        }

        // Run additional migrations
        $defaultMigrasi = ['Migrasi_required', 'Migrasi_rev', 'Migrasi_beta', 'Migrasi_module'];

        foreach ($defaultMigrasi as $migrateName) {
            if ($this->getShowProgress()) {
                echo json_encode(['message' => 'Jalankan ' . $migrateName, 'status' => 0]);
            }
            $resultMigration = $this->runMigration($migrateName);
            if ($this->getShowProgress()) {
                log_message($resultMigration['status'] ? 'notice' : 'error', $resultMigration['message']);
                echo json_encode(['message' => $resultMigration['message'], 'status' => $resultMigration['status'] ? 0 : 500]);
            }
        }

        // Untuk pembaruan font
        (new Filesystem())->copyDirectory('vendor/tecnickcom/tcpdf/fonts', LOKASI_FONT_DESA);

        // Lengkapi folder desa
        folder_desa();
        kosongkanFolder(config_item('cache_blade'));

        // Clear cache and update settings
        cache()->forget('siappakai');
        cache()->forget('modul_aktif');

        $currentVersion = currentVersion();
        SettingAplikasi::where('key', '=', 'current_version')->update(['value' => $currentVersion]);
        SettingAplikasi::where(['key' => 'compatible_version_general'])->update(['value' => PREMIUM ? versiUmumSetara($currentVersion) : null]);

        log_message('notice', 'Versi database sudah terbaru');
        if ($this->getShowProgress()) {
            echo json_encode(['message' => 'Versi database sudah terbaru', 'status' => 0]);
        }
        $password = $this->databaseOption['password'];
        if (strlen((string) $password) < 80) {
            updateConfigFile('password', encrypt($password));
        }

        set_session('success', 'Migrasi berhasil dilakukan');
    }

    public function checkMigration($install = false): void
    {
        $premium = new CekService();

        if (($premium->validasiVersi($install) || $install) && Migrasi::where('versi_database', VERSI_DATABASE)->doesntExist()) {
            $this->migrateDatabase($install);
        }
    }

    public function getViews(): array
    {
        $db    = $this->databaseOption['database'];
        $views = DB::select("SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_TYPE = 'VIEW' AND TABLE_SCHEMA = ?", [$db]);

        return array_column($views, 'TABLE_NAME');
    }

    public function getShowProgress(): int
    {
        return $this->showProgress;
    }

    public function setShowProgress(int $showProgress): static
    {
        $this->showProgress = $showProgress;

        return $this;
    }

    /**
     * Get the value of databaseName
     */
    public function getDatabaseName(): string
    {
        return $this->databaseName;
    }

    /**
     * Get the value of databaseOption
     */
    public function getDatabaseOption(): array
    {
        return $this->databaseOption;
    }

    private function checkCurrentVersion()
    {
        $version = setting('current_version');
        if ($version == null) {
            // versi tidak terdeteksi dari modul periksa.
            return SettingAplikasi::where('key', 'current_version')->first()->value;
        }

        return $version;
    }

    private function updateVersi(string $migrateName): void
    {
        $migrasiDb = Migrasi::firstOrCreate(['versi_database' => $migrateName]);
        $migrasiDb->update(['premium' => ['Migrasi_' . $migrateName]]);
    }
}
