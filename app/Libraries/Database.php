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
use Illuminate\Support\Facades\Log;
use App\Services\Penjaga\PenjagaPermintaan;

class Database
{
    use Migration;

    public string $minimumVersionBuild = MINIMUM_VERSION_BUILD;

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
        $listVersionBuild  = VERSION_BUILD;
        $minVersionPremium = $listVersionBuild[$this->minimumVersionBuild];
        $minVersionUmum    = $this->nextVersion($minVersionPremium, RANGE_PREMIUM_MASUK_UMUM);

        $lastVersionPremium = $listVersionBuild[$this->checkVersionBuild()];
        $lastVersionUmum    = $this->nextVersion($lastVersionPremium, RANGE_PREMIUM_MASUK_UMUM);

        if (session('sedang_restore') == 1) {
            return;
        }

        $migratedDatabase    = Migrasi::pluck('versi_database', 'versi_database')->toArray();
        $currentVersionBuild = (int) str_replace('.', '', $this->checkVersionBuild());
        $minimumVersionBuild = (int) str_replace('.', '', $this->minimumVersionBuild);

        if (! $install && (! $currentVersionBuild || $currentVersionBuild < $minimumVersionBuild)) {
            $minVersion = 'v' . $minVersionPremium . '-premium / ' . $minVersionUmum . '-umum';
            if ($lastVersionPremium === '-') {
                $lastVersion = 'Tidak Diketahui';
            } else {
                $lastVersion = 'v' . $lastVersionPremium . '-premium / ' . $lastVersionUmum . '-umum';
            }

            throw new \RuntimeException('OpenSID bisa diupgrade dengan minimal ' . $minVersion . '. Versi terakhir yang digunakan adalah ' . $lastVersion . '.');
        }

        $migrations = File::files('app/database/migrations');

        // sort by name
        usort($migrations, static fn ($a, $b): int => strcmp($a->getFilename(), $b->getFilename()));

        // gabungkan migrasi tambahan (default) ke daftar migrasi
        $defaultMigrasi = ['required', 'rev', 'beta', 'module'];
        $migrations     = array_merge($migrations, $defaultMigrasi);

        try {
            foreach ($migrations as $migrate) {
                if (is_string($migrate)) {
                    $migrateName = $migrate;
                } else {
                    preg_match('/\d+/', $migrate->getFilename(), $matches);
                    $migrateName = $matches[0] ?? null;
                }

                // Jalankan migrasi jika belum pernah dijalankan dan selalu jalankan migrasi default
                if ($migrateName && (! isset($migratedDatabase[$migrateName]) || in_array($migrateName, $defaultMigrasi, true))) {
                    if ($this->getShowProgress()) {
                        echo json_encode(['message' => 'Jalankan Migrasi_' . $migrateName, 'status' => 0]);
                    }
                    $resultMigration = $this->runMigration('Migrasi_' . $migrateName);
                    if ($this->getShowProgress()) {
                        echo json_encode(['message' => $resultMigration['message'], 'status' => $resultMigration['status'] ? 0 : 500]);
                    }
                    $resultMigration['status'] ? Log::notice($resultMigration['message']) : Log::error($resultMigration['message']);

                    if (isset($resultMigration['exception'])) {
                        logger()->error($resultMigration['exception']);
                    }

                    // skip update versi untuk migrasi default
                    if (in_array($migrateName, $defaultMigrasi, true)) {
                        continue;
                    }

                    $this->updateVersi($migrateName);
                }
            }

            $this->updateVersi(VERSI_DATABASE);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            if ($this->getShowProgress()) {
                echo json_encode(['message' => $e->getMessage(), 'status' => 0]);
            }
        }

        // Untuk pembaruan font
        (new Filesystem())->copyDirectory('vendor/tecnickcom/tcpdf/fonts', base_path('desa/upload/fonts'));

        // Lengkapi folder desa
        folder_desa();

        // Clear cache and update settings
        cache()->forget('siappakai');
        cache()->forget('modul_aktif');

        $currentVersion = currentVersion();
        SettingAplikasi::where('key', '=', 'current_version')->update(['value' => $currentVersion]);
        SettingAplikasi::where(['key' => 'version_build_script'])->update(['value' => array_key_first(VERSION_BUILD)]);

        Log::notice('Versi database sudah terbaru');
        if ($this->getShowProgress()) {
            echo json_encode(['message' => 'Versi database sudah terbaru', 'status' => 0]);
        }
        // Simpan password DB dalam bentuk terenkripsi jika masih plaintext (< 80 char).
        // Password kosong dibiarkan kosong — meng-encrypt('') menghasilkan blob non-kosong
        // yang menimpa setelan "tanpa password" di desa/config/database.php (mis. saat
        // restore dengan opsi pertahankan kredensial lokal).
        $password = (string) $this->databaseOption['password'];
        if ($password !== '' && strlen($password) < 80 && ! session()->has('instalasi')) {
            $file    = base_path('desa/config/database.php');
            $pattern = '/(\$db\[\'default\'\]\[\'password\'\]\s*=\s*\'.*\';)/';
            $newKey  = "\$db['default']['password'] = '" . encrypt($password) . "';";
            $configContent = file_get_contents($file);
            if (preg_match($pattern, $configContent)) {
                $configContent = preg_replace($pattern, $newKey, $configContent);
            } else {
                $configContent .= PHP_EOL . $newKey;
            }
            file_put_contents($file, $configContent);
        }

        session()->flash('success', 'Migrasi berhasil dilakukan');
    }

    public function checkMigration($install = false): void
    {
        

        $settingVersionBuild = SettingAplikasi::where('key', 'version_build_script')->first();
        if (null === $settingVersionBuild) {
            $install = true;
        }

        if ((app(PenjagaPermintaan::class)->izinkanMigrasi($install) || $install) && Migrasi::where('versi_database', VERSI_DATABASE)->doesntExist()) {
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

    private function checkVersionBuild()
    {
        $version = setting('version_build_script');
        if ($version == null) {
            // versi tidak terdeteksi dari modul periksa.
            return SettingAplikasi::where('key', 'version_build_script')->first()->value;
        }

        return $version;
    }

    private function updateVersi(string $migrateName): void
    {
        $migrasiDb = Migrasi::firstOrCreate(['versi_database' => $migrateName]);
        $migrasiDb->update(['premium' => ['Migrasi_' . $migrateName]]);
    }

    private function nextVersion(string $version, int $months): string
    {
        $year  = (int) substr($version, 0, 2);
        $month = (int) substr($version, 2, 2);
        $same  = substr($version, 5, 8);

        $addYear  = (int) ($months / 12);
        $addMonth = $months % 12;
        $month += $addMonth;
        $year += $addYear;

        if ($month > 12) {
            $year++;
            $month -= 12;
        }

        if ($month < 10) {
            $month = sprintf('0%d', $month);
        }

        return sprintf('%s%s.%s', $year, $month, $same);
    }
}
