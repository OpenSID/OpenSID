<?php

namespace App\Bootstrap;

use Illuminate\Foundation\Application;

class CodeIgniterBootstrap
{
    /**
     * Indicates if the pre_system hook has already been called for the current request.
     *
     * Laravel rebinds the "request" instance twice per request, so this flag
     * ensures the pre_system hook is only executed once per request.
     */
    public static bool $preSystemCalled = false;

    /**
     * Bootstrap CodeIgniter framework.
     */
    public function bootstrap(Application $app): void
    {
        require_once base_path('bootstrap/bootCodeIgniter.php');

        // Replicate the CodeIgniter booting process whenever the "request" instance is
        // rebound. The rebinding is registered on every application instance so it also
        // works during testing, where a new application instance is created per test.
        $app->rebinding('request', function ($app, $request) {
            if (! isset($GLOBALS['EXT'])) {
                return;
            }

            // Sync the request instance to CodeIgniter core classes
            $GLOBALS['EXT']->setRequest($request);

            if (CodeIgniterBootstrap::$preSystemCalled) {
                return;
            }

            CodeIgniterBootstrap::$preSystemCalled = true;

            $GLOBALS['EXT']->call_hook('pre_system');
        });

        static::configureConfig($app);
    }

    /**
     * Configure CodeIgniter configuration
     */
    public static function configureConfig(Application $app): void
    {
        if (! isset($GLOBALS['CFG']) || ! $GLOBALS['CFG'] instanceof \CI_Config) {
            return;
        }

        // Hapus semua path yang mengandung 'database' dari is_loaded
        $GLOBALS['CFG']->is_loaded = array_values(array_filter(
            $GLOBALS['CFG']->is_loaded,
            fn ($path) => ! str_contains($path, 'database.php')
        ));

        // Hapus config database lama agar tidak di-merge dengan data lama
        unset($GLOBALS['CFG']->config['database']);

        $GLOBALS['CFG']->load('database', true);

        $config   = $app['config'];
        $configCI = $GLOBALS['CFG']->config;

        // Jangan timpa app.url jika sudah di-set lewat environment (.env)
        // Hanya jalankan saat bukan console dan APP_URL belum diset
        if (! $app->runningInConsole() && empty(env('APP_URL'))) {
            $config->set('app.url', APP_URL);
            $config->set('app.asset_url', APP_URL . 'assets');
        }

        // Saat testing, biarkan konfigurasi database dari phpunit.xml yang digunakan
        // dan jangan load sid_ini (config CI3 lama, termasuk desa/config/config.php)
        // karena bisa membawa setting spesifik desa yang mengganggu environment testing
        if ($app->environment('testing')) {
            return;
        }

        // Set database configuration
        $config->set('database.default', $configCI['database']['default'] ?? 'default');
        $config->set('database.connections', array_merge(
            $config->get('database.connections', []),
            $configCI['database']['connections'] ?? []
        ));

        $GLOBALS['CFG']->load('sid_ini');
        $configCI = $GLOBALS['CFG']->config;

        $config->set([
            'opensid.demo_mode'  => $config->get('opensid.demo_mode') || ($configCI['demo_mode'] ?? false),
            'opensid.user_admin' => $config->get('opensid.user_admin') ?? ($configCI['user_admin'] ?? null),
        ]);
    }
}
