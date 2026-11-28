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

use App\Libraries\Database;
use App\Models\Config;
use App\Models\SettingAplikasi;
use App\Services\DatabaseConfiguratorService;
use Database\Seeders\DataStatisSeeder;
use Database\Seeders\StrukturAwalSeeder;
use Illuminate\Console\Command;
use Throwable;

class DesaBaruCommand extends Command
{
    /**
     * {@inheritDoc}
     */
    protected $signature = 'opensid:desa-baru
        {--force : Paksa jalankan meskipun data sudah ada}
        {--skip-struktur : Lewati pembuatan struktur tabel}
        {--skip-statis : Lewati data statis}
        {--skip-cache : Lewati pembersihan cache}
        {--kode-desa= : Kode desa untuk inisialisasi}
        {--token-layanan= : Token layanan OpenDesa (opsional)}
        {--db-host= : Database hostname}
        {--db-port=3306 : Database port}
        {--db-database= : Database name}
        {--db-username= : Database username}
        {--db-password= : Database password}
    ';

    /**
     * {@inheritDoc}
     */
    protected $description = 'Inisialisasi data desa baru untuk OpenSID';

    /**
     * Config ID yang baru dibuat.
     */
    protected ?int $configId = null;

    /**
     * Database configurator service.
     */
    protected DatabaseConfiguratorService $dbConfigurator;

    /**
     * Constructor dengan dependency injection.
     */
    public function __construct(DatabaseConfiguratorService $dbConfigurator)
    {
        parent::__construct();
        $this->dbConfigurator = $dbConfigurator;
    }

    /**
     * {@inheritDoc}
     */
    public function handle(): void
    {
        // Setup database jika parameter diberikan
        $isNewDatabase = $this->hasDatabaseParams();
        if ($isNewDatabase) {
            $this->setupDatabaseConnection();
        }

        // Guard checks
        if (! $this->option('force')) {
            try {
                // Cek apakah tabel config sudah ada
                if (\Schema::hasTable('config')) {
                    // Cek apakah kode_desa sudah ada di database (multitenant)
                    // Gunakan DB query builder langsung untuk hindari ConfigIdScope
                    $kodeDesa = $this->option('kode-desa');
                    if ($kodeDesa && \DB::table('config')->where('kode_desa', $kodeDesa)->exists()) {
                        $this->warn("Kode desa {$kodeDesa} sudah ada di database. Tidak dilakukan perubahan.");
                        
                        return;
                    }

                    // Jika tidak ada kode_desa, cek apakah ada config dengan app_key yang sama
                    if (! $kodeDesa && \DB::table('config')->where('app_key', get_app_key())->exists()) {
                        $this->warn('Data desa sudah ada. Tidak dilakukan perubahan.');
                        
                        return;
                    }
                }
            } catch (\Exception $e) {
                // Database baru atau error koneksi, lanjutkan
                $this->info('Database baru, akan dilakukan inisialisasi...');
            }
        }

        try {
            $this->info('Memulai inisialisasi data desa baru...');

            $tasks = $this->buildTasks();

            $this->withProgressBar($tasks, function ($task): void {
                $this->handleTask($task);
            });

            $this->newLine();
            $this->info("Inisialisasi data desa baru selesai. Config ID: {$this->configId}");
        } catch (Throwable $th) {
            $this->newLine();
            $this->error("Gagal inisialisasi desa baru:\n {$th->getMessage()}");
            logger()->error($th);
        }
    }

    /**
     * Bangun daftar task berdasarkan parameter.
     */
    protected function buildTasks(): array
    {
        // Skip buat folder jika tabel sudah ada
        $tablesExist = \Schema::hasTable('config');
        
        if (! $tablesExist) {
            $tasks = ['Membuat folder desa'];
        } else {
            $tasks = [];
        }

        if (! $this->option('skip-cache')) {
            $tasks[] = 'Menghapus cache';
        }

        if (! $this->option('skip-struktur')) {
            $tasks[] = 'Menjalankan migrasi struktur';
        }

        if (! $this->option('skip-statis')) {
            $tasks[] = 'Menjalankan data statis';
        }

        $tasks[] = 'Menjalankan data dinamis';
        $tasks[] = 'Memeriksa migrasi database';

        if ($this->option('token-layanan')) {
            $tasks[] = 'Setup token layanan';
        }

        $tasks[] = 'Menghapus session';

        return $tasks;
    }

    /**
     * Dispatch task berdasarkan nama.
     */
    protected function handleTask(string $task): void
    {
        match ($task) {
            'Membuat folder desa'          => $this->handleBuatFolderDesa(),
            'Menghapus cache'              => $this->handleHapusCache(),
            'Menjalankan migrasi struktur' => $this->handleMigrasiStruktur(),
            'Menjalankan data statis'      => $this->handleDataStatis(),
            'Menjalankan data dinamis'     => $this->handleMigrasiDataAwal(),
            'Memeriksa migrasi database'   => $this->handlePeriksaMigrasi(),
            'Setup token layanan'          => $this->handleSetupToken(),
            'Menghapus session'            => $this->handleHapusSession(),
            default                        => null,
        };
    }

    /**
     * Cek apakah parameter database diberikan.
     */
    protected function hasDatabaseParams(): bool
    {
        return $this->option('db-host') !== null
            || $this->option('db-database') !== null
            || $this->option('db-username') !== null;
    }

    /**
     * Setup koneksi database dari parameter.
     */
    protected function setupDatabaseConnection(): void
    {
        $host     = $this->option('db-host');
        $port     = $this->option('db-port');
        $database = $this->option('db-database');
        $username = $this->option('db-username');
        $password = $this->option('db-password');

        if (empty($host) || empty($database) || empty($username)) {
            $this->error('Parameter --db-host, --db-database, dan --db-username wajib diisi!');

            return;
        }

        try {
            // 1. Update CI3 config (sama dengan Installer)
            $this->dbConfigurator->updateConfig(
                $host,
                (int) $port,
                $database,
                $username,
                $password ?? ''
            );

            // 2. folder_desa() akan menulis file database.php dari config_item('database')
            //    Tidak perlu writeConfigFile() karena folder_desa() sudah handle

            // 3. Test koneksi
            $this->dbConfigurator->testConnection();

            $this->info('Koneksi database berhasil.');
        } catch (\Exception $e) {
            $this->error('Gagal koneksi ke database: ' . $e->getMessage());
        }
    }

    /**
     * Buat folder desa dan simpan config_id.
     */
    protected function handleBuatFolderDesa(): void
     {
        // Pastikan ci() function tersedia
        if (! function_exists('ci')) {
            require_once base_path('vendor/opensid/router/src/Functions.php');
        }

        // Buat folder desa (generate app_key)
        folder_desa();

        // Set kode desa jika diberikan
        if ($kodeDesa = $this->option('kode-desa')) {
            $this->setKodeDesa($kodeDesa);
        }

        // Config record akan dibuat oleh StrukturAwalSeeder::defaultConfig()
        // configId akan di-set setelah seeder berjalan di handleMigrasiStruktur()
    }

    /**
     * Set kode desa di CI3 runtime config.
     */
    protected function setKodeDesa(string $kodeDesa): void
    {
        // Update CI3 config agar config_item('kode_desa') bisa dibaca seeder
        // Gunakan get_instance() karena ci() tidak tersedia di namespace ini
        $CI = &get_instance();
        $CI->config->set_item('kode_desa', $kodeDesa);
    }

    /**
     * Pastikan direktori desa dan file app_key ada.
     * Dibutuhkan oleh identitas() dan get_app_key().
     */
    protected function ensureAppKeyFile(): void
    {
        $desaPath = base_path('desa');
        $configPath = $desaPath . '/config';
        $appKeyPath = $desaPath . '/app_key';

        // Buat direktori desa/config jika belum ada
        if (! is_dir($configPath)) {
            mkdir($configPath, 0755, true);
        }

        // Buat file app_key jika belum ada
        if (! file_exists($appKeyPath)) {
            file_put_contents($appKeyPath, set_app_key(), LOCK_EX);
        }
    }

    /**
     * Jalankan migrasi struktur (buat tabel).
     */
    protected function handleMigrasiStruktur(): void
    {
        // Cek apakah tabel sudah ada sebelum jalankan migrasi
        if (\Schema::hasTable('config')) {
            $this->newLine();
            $this->info('Tabel sudah ada, skip migrasi struktur.');
            
            // Pastikan desa/app_key ada (dibutuhkan oleh identitas() dan get_app_key())
            $this->ensureAppKeyFile();
            
            // Cek apakah kode_desa sudah ada
            // Gunakan DB query builder langsung untuk hindari ConfigIdScope
            $kodeDesa = $this->option('kode-desa');
            $existingConfig = \DB::table('config')->where('kode_desa', $kodeDesa)->first();
            
            if ($existingConfig) {
                // Kode desa sudah ada, gunakan config yang ada
                $this->configId = $existingConfig->id;

                // Update desa/app_key agar sesuai dengan config ini
                $appKeyPath = base_path('desa/app_key');
                file_put_contents($appKeyPath, $existingConfig->app_key, LOCK_EX);
            } else {
                // Kode desa belum ada, buat config baru (multitenant)
                // Generate app_key baru untuk desa ini
                $newAppKey = set_app_key();

                // Simpan app_key langsung ke database dengan query builder
                // (hindari Config::create karena model event auto-set app_key dari file)
                $newConfigId = \DB::table('config')->insertGetId([
                    'app_key'           => $newAppKey,
                    'kode_desa'         => $kodeDesa ?? '',
                    'nama_desa'         => '',
                    'nama_kecamatan'    => '',
                    'kode_kecamatan'    => '',
                    'nama_kabupaten'    => '',
                    'kode_kabupaten'    => '',
                    'nama_propinsi'     => '',
                    'kode_propinsi'     => '',
                    'nama_kepala_camat' => '',
                    'nip_kepala_camat'  => '',
                    'created_at'        => now(),
                    'updated_at'        => now(),
                ]);
                $this->configId = $newConfigId;

                // Update desa/app_key agar identitas() bisa menemukan config baru
                $appKeyPath = base_path('desa/app_key');
                file_put_contents($appKeyPath, $newAppKey, LOCK_EX);

                // Clear identitas cache agar membaca app_key baru
                cache()->forget('identitas_desa');
            }
            
            return;
        }

        $seeder = new StrukturAwalSeeder();
        $seeder->run();

        // Update config_id setelah seeder membuat Config record
        // Gunakan DB query builder langsung untuk hindari ConfigIdScope
        $config = \DB::table('config')->where('app_key', get_app_key())->first();
        $this->configId = $config?->id;

        // Simpan kode_desa ke database jika diberikan via parameter
        // (StrukturAwalSeeder::defaultConfig() selalu buat kode_desa kosong)
        if ($kodeDesa = $this->option('kode-desa')) {
            \DB::table('config')->where('id', $this->configId)->update(['kode_desa' => $kodeDesa]);
        }
    }

    /**
     * Jalankan data statis.
     */
    protected function handleDataStatis(): void
    {
        // DataStatisSeeder sudah dijalankan di StrukturAwalSeeder
        // Method ini untuk skip-struktur tapi tetap jalankan data statis
        if ($this->option('skip-struktur')) {
            (new DataStatisSeeder())->run();
        }
    }

    /**
     * Jalankan data dinamis (per desa).
     */
    protected function handleMigrasiDataAwal(): void
    {
        // Pastikan config_id tersedia
        if (! $this->configId) {
            // Gunakan DB query builder langsung untuk hindari ConfigIdScope
            $config = \DB::table('config')->where('app_key', get_app_key())->first();
            $this->configId = $config?->id;
        }

        // Set kode_desa di CI3 config agar DataDinamisSeeder bisa baca
        if ($kodeDesa = $this->option('kode-desa')) {
            $this->setKodeDesa($kodeDesa);
        }

        // Pastikan ci() function tersedia
        if (! function_exists('ci')) {
            require_once base_path('vendor/opensid/router/src/Functions.php');
        }

        // Clear cache sebelum jalankan seeder
        cache()->flush();

        // Set identitas_desa cache untuk performa (identitas() sudah bypass ConfigIdScope)
        $config = \DB::table('config')->where('app_key', get_app_key())->first();
        if ($config) {
            cache()->put('identitas_desa', $config, 604800);
        }

        // Pass config_id secara eksplisit ke seeder
        (new \Database\Seeders\DataDinamisSeeder($this->configId))->run();
    }

    /**
     * Periksa migrasi database.
     */
    protected function handlePeriksaMigrasi(): void
    {
        // Pastikan ci() function tersedia
        if (! function_exists('ci')) {
            require_once base_path('vendor/opensid/router/src/Functions.php');
        }

        (new Database())->checkMigration(true);
    }

    /**
     * Setup token layanan (hanya simpan di database).
     */
    protected function handleSetupToken(): void
    {
        $tokenLayanan = $this->option('token-layanan');

        if (empty($tokenLayanan)) {
            return;
        }

        // Validasi format JWT
        if (! $this->isValidJwtFormat($tokenLayanan)) {
            $this->warn('Format token tidak valid (bukan JWT).');

            return;
        }

        // Simpan HANYA ke database
        SettingAplikasi::updateOrCreate(
            ['key' => 'layanan_opendesa_token'],
            ['value' => $tokenLayanan]
        );

        $this->info('Token layanan berhasil disimpan di database.');
    }

    /**
     * Validasi format JWT.
     */
    protected function isValidJwtFormat(string $token): bool
    {
        $parts = explode('.', $token);

        if (count($parts) !== 3) {
            return false;
        }

        $payload = base64_decode($parts[1], true);

        return $payload !== false && json_decode($payload) !== null;
    }

    /**
     * Hapus cache desa.
     */
    protected function handleHapusCache(): void
    {
        resetCacheDesa();
        $this->callSilent('cache:clear');
    }

    /**
     * Hapus session.
     */
    protected function handleHapusSession(): void
    {
        session_destroy();
    }
}
