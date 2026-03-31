<?php

namespace Orchestra\Testbench\Bootstrap;

use Illuminate\Contracts\Foundation\Application;

class RegisterProviders extends \Illuminate\Foundation\Bootstrap\RegisterProviders
{
    /**
     * The service providers that should be merged before registration.
     *
     * @laravel-overrides
     *
     * @var array
     */
    protected static $merge = [];

    /**
     * The path to the bootstrap provider configuration file.
     *
     * @laravel-overrides
     *
     * @var string|null
     */
    protected static $bootstrapProviderPath;

    /** {@inheritDoc} */
    #[\Override]
    public function bootstrap(Application $app)
    {
        $this->mergeAdditionalProviders($app);

        parent::bootstrap($app);
    }

    /**
     * Merge additional providers for Testbench.
     *
     * @internal
     *
     * @template TProviders of array<int, class-string>
     *
     * @param  TProviders  $providers
     * @return TProviders
     */
    public static function mergeAdditionalProvidersForTestbench(array $providers): array
    {
        if (
            static::$bootstrapProviderPath &&
            file_exists(static::$bootstrapProviderPath)
        ) {
            $packageProviders = require static::$bootstrapProviderPath;

            foreach ($packageProviders as $index => $provider) {
                if (! class_exists($provider)) {
                    unset($packageProviders[$index]);
                }
            }
        }

        return tap(
            array_merge($providers, static::$merge, array_values($packageProviders ?? [])),
            static function ($providers) {
                static::$merge = [];
                static::$bootstrapProviderPath = null;
            }
        );
    }

    /**
     * Merge the additional configured providers into the configuration.
     *
     * @laravel-overrides
     *
     * @param  \Illuminate\Contracts\Foundation\Application  $app
     */
    protected function mergeAdditionalProviders(Application $app): void
    {
        if (static::$bootstrapProviderPath &&
            file_exists(static::$bootstrapProviderPath)) {
            $packageProviders = require static::$bootstrapProviderPath;

            foreach ($packageProviders as $index => $provider) {
                if (! class_exists($provider)) {
                    unset($packageProviders[$index]);
                }
            }
        }

        $app->make('config')->set(
            'app.providers',
            array_merge(
                $app->make('config')->get('app.providers'),
                static::$merge,
                array_values($packageProviders ?? []),
            ),
        );
    }

    /**
     * Merge the given providers into the provider configuration before registration.
     *
     * @laravel-overrides
     *
     * @param  array  $providers
     * @param  string|null  $bootstrapProviderPath
     * @return void
     */
    public static function merge(array $providers, ?string $bootstrapProviderPath = null): void
    {
        static::$bootstrapProviderPath = $bootstrapProviderPath;

        static::$merge = array_values(array_filter(array_unique(
            array_merge(static::$merge, $providers)
        )));
    }

    /**
     * Flush the bootstrapper's global state.
     *
     * @laravel-overrides
     *
     * @return void
     */
    public static function flushState(): void
    {
        static::$bootstrapProviderPath = null;

        static::$merge = [];
    }
}
