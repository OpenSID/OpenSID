<?php

namespace App\Bootstrap;

use Illuminate\Foundation\Application;

class CodeIgniterBootstrap
{
    /**
     * Bootstrap CodeIgniter framework.
     */
    public function bootstrap(Application $app): void
    {
        require_once base_path('bootstrap/bootCodeIgniter.php');

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

        if (! $app->runningInConsole()) {
            $config->set('app.url', APP_URL);
            $config->set('app.asset_url', APP_URL . 'assets');
        }

        // Saat testing, biarkan konfigurasi database dari phpunit.xml yang digunakan
        if ($app->environment('testing')) {
            return;
        }

        // Set database configuration
        $config->set('database.default', $configCI['database']['default'] ?? 'default');
        $config->set('database.connections', array_merge(
            $config->get('database.connections', []),
            $configCI['database']['connections'] ?? []
        ));
    }
}
