<?php

namespace Orchestra\Testbench\Bootstrap;

use Illuminate\Contracts\Foundation\Application;
use Spatie\Ray\Settings\Settings;

use function Orchestra\Testbench\after_resolving;

/**
 * @internal
 */
final class ConfigureRay
{
    /**
     * Bootstrap the given application.
     *
     * @param  \Illuminate\Contracts\Foundation\Application  $app
     * @return void
     *
     * @codeCoverageIgnore
     */
    public function bootstrap(Application $app): void
    {
        after_resolving($app, Settings::class, static function ($settings, $app) {
            /** @var \Illuminate\Contracts\Foundation\Application $app */
            /** @var \Spatie\Ray\Settings\Settings $settings */
            /** @var \Illuminate\Contracts\Config\Repository $config */
            $config = $app->make('config');

            if ($config->get('database.default') === 'sqlite' && ! is_file($config->get('database.connections.sqlite.database'))) {
                $settings->send_queries_to_ray = false; /** @phpstan-ignore property.notFound */
                $settings->send_duplicate_queries_to_ray = false; /** @phpstan-ignore property.notFound */
                $settings->send_slow_queries_to_ray = false; /** @phpstan-ignore property.notFound */
            }
        });
    }
}
