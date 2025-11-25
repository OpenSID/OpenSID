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

namespace App\Traits;

use ReflectionClass;

defined('BASEPATH') || exit('No direct script access allowed');

trait ModulTrait
{
    public $moduleDirectory;
    public $moduleName;

    /**
     * Get the module directory dynamically based on the file's location.
     *
     * This method uses Reflection to determine the current class file's directory
     * and then calculates the module's root directory by stripping off the
     * "Http/Controllers" part of the path.
     *
     * @return string The module directory path.
     */
    protected function getModuleDirectory()
    {
        if (! $this->moduleDirectory) {
            $reflection = new ReflectionClass(static::class);
            $directory  = dirname($reflection->getFileName());

            // Use DIRECTORY_SEPARATOR for dynamic path handling
            $this->moduleDirectory = substr($directory, 0, strpos($directory, 'Http' . DIRECTORY_SEPARATOR . 'Controllers') - 1);
        }

        return $this->moduleDirectory;
    }

    /**
     * Load helper files from the module's "Helpers" directory.
     *
     * This method calls the `loadFilesFromDirectory` method to load all PHP files
     * from the module's "Helpers" directory.
     */
    private function loadHelper(): void
    {
        $this->loadFilesFromDirectory('Helpers');
    }

    /**
     * Load configuration files from the module's "Config" directory.
     *
     * This method calls the `loadFilesFromDirectory` method to load all PHP files
     * from the "Config" directory, and merges them into the application configuration.
     */
    private function loadConfig(): void
    {
        $this->loadFilesFromDirectory('Config', function ($file) {
            $this->mergeConfigFrom($file, pathinfo($file, PATHINFO_FILENAME));
        });
    }

    /**
     * Load all files from a specified subdirectory within the module's directory.
     *
     * This method is responsible for requiring or executing the PHP files from
     * the given subdirectory. If a callback is provided, it will be called for each file.
     *
     * @param string        $subDirectory The subdirectory from which to load files.
     * @param callable|null $callback     Optional callback to execute on each file.
     */
    private function loadFilesFromDirectory($subDirectory, ?callable $callback = null): void
    {
        foreach (glob($this->getModuleDirectory() . DIRECTORY_SEPARATOR . $subDirectory . DIRECTORY_SEPARATOR . '*.php') as $file) {
            if ($callback) {
                $callback($file);
            } else {
                require_once $file;
            }
        }
    }

    /**
     * Merge a configuration file into the application's configuration.
     *
     * This method loads a configuration file and merges its contents into the
     * application's configuration under the given key.
     *
     * @param string $path The file path to the configuration file.
     * @param string $key  The configuration key to merge under.
     */
    protected function mergeConfigFrom($path, $key)
    {
        $config = app()->make('config');

        $config->set($key, array_merge(
            require $path,
            $config->get($key, [])
        ));
    }

    /**
     * Load the module's "module.json" file.
     *
     * This method checks if the "module.json" file exists in the module directory
     * and returns its decoded JSON content as an associative array.
     *
     * @return array The contents of the "module.json" file, or an empty array if the file doesn't exist.
     */
    protected function loadModuleJson()
    {
        $path = $this->getModuleDirectory() . DIRECTORY_SEPARATOR . 'module.json';

        return file_exists($path) ? json_decode(file_get_contents($path), true) : [];
    }

    /**
     * Activate the module if it is not excluded or not in demo mode.
     *
     * This method checks whether the module should be activated based on conditions like
     * demo mode, cache status, or if the module is in the list of active modules.
     * If not activated, it redirects the user with an error message.
     */
    protected function activate()
    {
        // Check if the module is excluded from activation
        if (in_array($this->moduleName, MODUL_BAWAAN)) {
            return true;
        }

        // Check demo mode and other conditions
        if (ENVIRONMENT === 'development' || (config_item('demo_mode') && in_array(get_domain(APP_URL), WEBSITE_DEMO))) {
            return true;
        }

        // If module is not in the list of active modules, show error and redirect
        if (! in_array($this->moduleName, $this->getLayananModul())) {
            set_session('error', 'Paket ' . $this->moduleName . ' belum bisa digunakan karena belum diaktivasi.');

            redirect('plugin');
        }
    }

    /**
     * Daftar modul yang aktif berdasarkan status langganan.
     */
    protected function getLayananModul(): array
    {
        return cache()->rememberForever('modul_aktif', static function () {
            $cache = app('ci')->cache->file->get('status_langganan');

            return collect($cache->body->pemesanan)
                ->filter(static fn ($data): bool => $data->status_pemesanan === 'aktif')
                ->map(
                    static fn ($data) => collect($data->layanan)
                        ->filter(static fn ($layanan) => $layanan->nama_kategori === 'Modul')
                        ->map(static fn ($layanan) => trim(str_replace('Modul', '', $layanan->nama)))
                        ->toArray()
                )
                ->flatten()
                ->toArray();
        });
    }
}
