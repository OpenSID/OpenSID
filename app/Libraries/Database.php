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
 * Hak Cipta 2016 - 2025 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright Hak Cipta 2016 - 2025 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

namespace App\Libraries;

use App\Models\Migrasi;
use App\Models\SettingAplikasi;
use App\Traits\Migration;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;

class Database
{
    use Migration;

    private $engine           = 'InnoDB';
    private int $showProgress = 0;
    public string $minimumVersion;
    private array $databaseOption;
    private string $databaseName;


    public function __construct()
    {
        $this->minimumVersion = MINIMUM_VERSI;
        $this->databaseOption = DB::getConnections()['default']->getConfig();
        $this->databaseName   = $this->databaseOption['database'];
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

    public function migrateDatabase($install = false): void
    {
        if (session('sedang_restore') == 1) {
            return;
        }

        $doesntHaveMigrasiConfigId = ! Schema::hasColumn('migrasi', 'config_id');
        $migratedDatabase          = Migrasi::when($doesntHaveMigrasiConfigId, static fn ($q) => $q->withoutConfigId())->pluck('versi_database', 'versi_database')->toArray();

        $version        = (int) str_replace('.', '', $this->checkCurrentVersion());
        $minimumVersion = (int) str_replace('.', '', $this->minimumVersion);
        $currentVersion = currentVersion();
        if (! PREMIUM) {
            $versiSetara = SettingAplikasi::where(['key' => 'compatible_version_general'])->first()?->value;
            if ($versiSetara) {
                if ($currentVersion < $versiSetara) {
                    show_error('<h2>OpenSID bisa diupgrade dengan minimal versi ' . $versiSetara . '</h2>');
                }
            }
        }

        if (! $install && $version < $minimumVersion) {
            show_error('<h2>Silakan upgrade dulu ke OpenSID dengan minimal versi ' . $this->minimumVersion . '</h2>');
        }

        $migrations = File::files('donjo-app/models/migrations');

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
        $defaultMigrasi = ['migrasi_surat_bawaan', 'migrasi_beta', 'migrasi_rev', 'migrasi_umum', 'migrasi_module'];

        foreach ($defaultMigrasi as $migrateName) {
            if ($this->getShowProgress()) {
                echo json_encode(['message' => 'Jalankan ' . $migrateName, 'status' => 0]);
            }
            $resultMigration = $this->runMigration($migrateName);
            if ($this->getShowProgress()) {
                echo json_encode(['message' => $resultMigration['message'], 'status' => $resultMigration['status'] ? 0 : 500]);
            }
        }

        // Lengkapi folder desa
        folder_desa();
        kosongkanFolder(config_item('cache_blade'));

        // Clear cache and update settings
        cache()->forget('views_blade');
        cache()->forget('siappakai');
        cache()->forget('modul_aktif');

        SettingAplikasi::where('key', '=', 'current_version')->update(['value' => $currentVersion]);
        SettingAplikasi::where(['key' => 'compatible_version_general'])->update(['value' => PREMIUM ? versiUmumSetara($currentVersion) : null]);

        log_message('notice', 'Versi database sudah terbaru');
        if ($this->getShowProgress()) {
            echo json_encode(['message' => 'Versi database sudah terbaru', 'status' => 0]);
        }
        $password = $this->databaseOption['password'];
        if (strlen($password) < 80) {
            updateConfigFile('password', encrypt($password));
        }

        set_session('success', 'Migrasi berhasil dilakukan');
    }

    public function checkMigration($install = false): void
    {
        $doesntHaveMigrasiConfigId = ! Schema::hasColumn('migrasi', 'config_id');
        if (($install) && Migrasi::when($doesntHaveMigrasiConfigId, static fn ($q) => $q->withoutConfigId())->where('versi_database', VERSI_DATABASE)->doesntExist()) {
            $this->migrateDatabase($install);
        }
    }

    public function getViews()
    {
        $db    = $this->databaseOption['database'];
        $views = DB::select("SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_TYPE = 'VIEW' AND TABLE_SCHEMA = ?", [$db]);

        return array_column($views, 'TABLE_NAME');
    }

    public function getShowProgress()
    {
        return $this->showProgress;
    }

    public function setShowProgress($showProgress)
    {
        $this->showProgress = $showProgress;

        return $this;
    }

    private function updateVersi($migrateName)
    {
        $doesntHaveMigrasiConfigId = ! Schema::hasColumn('migrasi', 'config_id');
        if ($doesntHaveMigrasiConfigId) {
            $migrasiDb = DB::table('migrasi')->where(['versi_database' => $migrateName])->first();
            if ($migrasiDb) {
                DB::table('migrasi')->update(['premium' => ['Migrasi_' . $migrateName]]);
            } else {
                DB::table('migrasi')->insert(['versi_database' => $migrateName, 'premium' => ['Migrasi_' . $migrateName]]);
            }
        } else {
            $migrasiDb = Migrasi::firstOrCreate(['versi_database' => $migrateName]);
            $migrasiDb->update(['premium' => ['Migrasi_' . $migrateName]]);
        }
    }

    /**
     * Get the value of databaseName
     */
    public function getDatabaseName()
    {
        return $this->databaseName;
    }

    /**
     * Get the value of databaseOption
     */
    public function getDatabaseOption()
    {
        return $this->databaseOption;
    }
}
