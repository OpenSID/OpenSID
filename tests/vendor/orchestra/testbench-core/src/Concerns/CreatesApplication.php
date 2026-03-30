<?php

namespace Orchestra\Testbench\Concerns;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Contracts\Console\Kernel as ConsoleKernelContract;
use Illuminate\Contracts\Http\Kernel as HttpKernelContract;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Facade;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Orchestra\Testbench\Attributes\DefineEnvironment;
use Orchestra\Testbench\Attributes\RequiresEnv;
use Orchestra\Testbench\Attributes\RequiresLaravel;
use Orchestra\Testbench\Attributes\ResolvesLaravel;
use Orchestra\Testbench\Attributes\UsesFrameworkConfiguration;
use Orchestra\Testbench\Attributes\WithConfig;
use Orchestra\Testbench\Attributes\WithEnv;
use Orchestra\Testbench\Attributes\WithImmutableDates;
use Orchestra\Testbench\Bootstrap\LoadEnvironmentVariables;
use Orchestra\Testbench\Bootstrap\RegisterProviders;
use Orchestra\Testbench\Features\TestingFeature;
use Orchestra\Testbench\Foundation\PackageManifest;
use PHPUnit\Framework\TestCase as PHPUnitTestCase;

use function Orchestra\Testbench\after_resolving;
use function Orchestra\Testbench\default_skeleton_path;
use function Orchestra\Testbench\refresh_router_lookups;

/**
 * @api
 *
 * @property bool|null $enablesPackageDiscoveries
 * @property bool|null $loadEnvironmentVariables
 */
trait CreatesApplication
{
    use InteractsWithWorkbench;
    use WithLaravelBootstrapFile;

    /**
     * Get the application's base path.
     *
     * @return string
     */
    public static function applicationBasePath()
    {
        return static::applicationBasePathUsingWorkbench() ?? default_skeleton_path();
    }

    /**
     * Ignore package discovery from.
     *
     * @return array<int, string>
     */
    public function ignorePackageDiscoveriesFrom()
    {
        return $this->ignorePackageDiscoveriesFromUsingWorkbench() ?? ['*'];
    }

    /**
     * Get the application timezone.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return string|null
     */
    protected function getApplicationTimezone($app)
    {
        return $app['config']['app.timezone'];
    }

    /**
     * Override application bindings.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return array<string|class-string, string|class-string>
     */
    protected function overrideApplicationBindings($app)
    {
        return [];
    }

    /**
     * Resolve application bindings.
     *
     * @internal
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return void
     */
    final protected function resolveApplicationBindings($app): void
    {
        foreach ($this->overrideApplicationBindings($app) as $original => $replacement) {
            $app->bind($original, $replacement);
        }
    }

    /**
     * Get application aliases.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return array<string, class-string>
     */
    protected function getApplicationAliases($app)
    {
        return $app['config']['app.aliases'];
    }

    /**
     * Override application aliases.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return array<string, class-string|false>
     */
    protected function overrideApplicationAliases($app)
    {
        return [];
    }

    /**
     * Resolve application aliases.
     *
     * @internal
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return array<string, class-string>
     */
    final protected function resolveApplicationAliases($app): array
    {
        $aliases = (new Collection(
            $this->getApplicationAliases($app)
        ))->merge($this->getPackageAliases($app));

        if (! empty($overrides = $this->overrideApplicationAliases($app))) {
            /** @phpstan-ignore argument.type */
            $aliases->transform(static function ($alias, $name) use ($overrides) {
                return with($overrides[$name] ?? $alias, static function ($alias) {
                    return $alias !== false ? $alias : null;
                });
            });
        }

        return $aliases->filter()->all();
    }

    /**
     * Get package aliases.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return array<string, class-string>
     */
    protected function getPackageAliases($app)
    {
        return [];
    }

    /**
     * Get package bootstrapper.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return array<int, class-string>
     */
    protected function getPackageBootstrappers($app)
    {
        return $this->getPackageBootstrappersUsingWorkbench($app) ?? [];
    }

    /**
     * Get application providers.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return array<int, class-string>
     */
    protected function getApplicationProviders($app)
    {
        return $app['config']['app.providers'] ?? ServiceProvider::defaultProviders()->toArray();
    }

    /**
     * Override application aliases.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return array<class-string, class-string|false>
     */
    protected function overrideApplicationProviders($app)
    {
        return [];
    }

    /**
     * Resolve application aliases.
     *
     * @internal
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return array<int, class-string>
     */
    final protected function resolveApplicationProviders($app): array
    {
        $providers = (new Collection(
            RegisterProviders::mergeAdditionalProvidersForTestbench($this->getApplicationProviders($app))
        ))->merge($this->getPackageProviders($app));

        if (! empty($overrides = $this->overrideApplicationProviders($app))) {
            /** @phpstan-ignore argument.type */
            $providers->transform(static function (string $provider) use ($overrides) {
                return with($overrides[$provider] ?? $provider, static function ($provider) {
                    return $provider !== false ? $provider : null;
                });
            });
        }

        return $providers->filter()->values()->all();
    }

    /**
     * Get package providers.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return array<int, class-string>
     */
    protected function getPackageProviders($app)
    {
        return $this->getPackageProvidersUsingWorkbench($app) ?? [];
    }

    /**
     * Resolve the application's base path.
     *
     * @internal
     *
     * @return string
     */
    protected function getApplicationBasePath()
    {
        return static::applicationBasePath();
    }

    /**
     * Resolve the application's base path (deprecated).
     *
     * @api
     *
     * @return string|null
     *
     * @deprecated 6.22.0 Use `applicationBasePath()` static method instead.
     */
    protected function getBasePath()
    {
        trigger_deprecation('orchestra/testbench-core', '6.22.0', 'Use `%s` static method instead.', 'applicationBasePath()');

        return static::applicationBasePath();
    }

    /**
     * Creates the application.
     *
     * Needs to be implemented by subclasses.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
        $app = $this->resolveApplication();

        $this->resolveApplicationFacades($app);

        $this->resolveApplicationResolvingCallback($app);

        $this->resolveApplicationBindings($app);
        $this->resolveApplicationExceptionHandler($app);
        $this->resolveApplicationCore($app);
        $this->resolveApplicationEnvironmentVariables($app);
        $this->resolveApplicationConfiguration($app);
        $this->resolveApplicationHttpKernel($app);
        $this->resolveApplicationConsoleKernel($app);
        $this->resolveApplicationBootstrappers($app);

        return $app;
    }

    /**
     * Create the default application implementation.
     *
     * @return \Illuminate\Foundation\Application
     */
    final protected function resolveDefaultApplication()
    {
        return new Application($this->getApplicationBasePath());
    }

    /**
     * Resolve application implementation.
     *
     * @return \Illuminate\Foundation\Application
     */
    protected function resolveApplication()
    {
        return $this->resolveDefaultApplication();
    }

    /**
     * Resolve application resolving callback.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return void
     */
    protected function resolveApplicationResolvingCallback($app): void
    {
        $app->bind(
            'Illuminate\Foundation\Bootstrap\LoadConfiguration',
            static::usesTestingConcern() && ! static::usesTestingConcern(WithWorkbench::class)
                ? 'Orchestra\Testbench\Bootstrap\LoadConfiguration'
                : 'Orchestra\Testbench\Bootstrap\LoadConfigurationWithWorkbench'
        );

        PackageManifest::swap($app, $this);
    }

    /**
     * Resolve application facades implementation.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return void
     */
    protected function resolveApplicationFacades($app)
    {
        Facade::clearResolvedInstances();
        Facade::setFacadeApplication($app);
    }

    /**
     * Resolve application core environment variables implementation.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return void
     */
    protected function resolveApplicationEnvironmentVariables($app)
    {
        if (property_exists($this, 'loadEnvironmentVariables') && $this->loadEnvironmentVariables === true) {
            $app->make(LoadEnvironmentVariables::class)->bootstrap($app);
        }

        $attributeCallbacks = TestingFeature::run(
            testCase: $this,
            attribute: fn () => $this->parseTestMethodAttributes($app, WithEnv::class),
        )->get('attribute');

        TestingFeature::run(
            testCase: $this,
            attribute: function () use ($app) {
                $this->parseTestMethodAttributes($app, RequiresEnv::class);
                $this->parseTestMethodAttributes($app, RequiresLaravel::class);
            },
        );

        if ($this instanceof PHPUnitTestCase && method_exists($this, 'beforeApplicationDestroyed')) {
            $this->beforeApplicationDestroyed(static function () use ($attributeCallbacks) {
                $attributeCallbacks->handle();
            });
        }
    }

    /**
     * Resolve application core configuration implementation.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return void
     */
    protected function resolveApplicationConfiguration($app)
    {
        TestingFeature::run(
            testCase: $this,
            attribute: function () use ($app) {
                $this->parseTestMethodAttributes($app, ResolvesLaravel::class); /** @phpstan-ignore method.notFound */
                $this->parseTestMethodAttributes($app, UsesFrameworkConfiguration::class); /** @phpstan-ignore method.notFound */
            }
        );

        $app->make('Illuminate\Foundation\Bootstrap\LoadConfiguration')->bootstrap($app);
        $app->make('Orchestra\Testbench\Bootstrap\ConfigureRay')->bootstrap($app);
        $app->make('Orchestra\Testbench\Foundation\Bootstrap\SyncDatabaseEnvironmentVariables')->bootstrap($app);

        tap($this->getApplicationTimezone($app), static function ($timezone) {
            ! \is_null($timezone) && date_default_timezone_set($timezone);
        });

        tap($app['config'], function ($config) use ($app) {
            if (! $app->bound('env')) {
                $app->detectEnvironment(static fn () => $config->get('app.env', 'workbench'));
            }

            if (\is_string($bootstrapProviderPath = $this->getApplicationBootstrapFile('providers.php'))) {
                RegisterProviders::merge([], $bootstrapProviderPath);
            }

            $config->set([
                'app.aliases' => $this->resolveApplicationAliases($app),
                'app.providers' => $this->resolveApplicationProviders($app),
            ]);

            TestingFeature::run(
                testCase: $this,
                attribute: fn () => $this->parseTestMethodAttributes($app, WithConfig::class), /** @phpstan-ignore method.notFound */
            );
        });
    }

    /**
     * Resolve application core implementation.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return void
     */
    protected function resolveApplicationCore($app)
    {
        if ($this->isRunningTestCase()) {
            $app->detectEnvironment(static fn () => 'testing');
        }
    }

    /**
     * Resolve application Console Kernel implementation.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return void
     */
    protected function resolveApplicationConsoleKernel($app)
    {
        $app->singleton(ConsoleKernelContract::class, $this->applicationConsoleKernelUsingWorkbench($app));
    }

    /**
     * Resolve application HTTP Kernel implementation.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return void
     */
    protected function resolveApplicationHttpKernel($app)
    {
        $app->singleton(HttpKernelContract::class, $this->applicationHttpKernelUsingWorkbench($app));
    }

    /**
     * Resolve application HTTP exception handler.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return void
     */
    protected function resolveApplicationExceptionHandler($app)
    {
        $app->singleton('Illuminate\Contracts\Debug\ExceptionHandler', $this->applicationExceptionHandlerUsingWorkbench($app));
    }

    /**
     * Resolve application bootstrapper.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return void
     */
    protected function resolveApplicationBootstrappers($app)
    {
        $app->make(
            $this->isRunningTestCase()
                ? 'Orchestra\Testbench\Bootstrap\HandleExceptions'
                : 'Illuminate\Foundation\Bootstrap\HandleExceptions'
        )->bootstrap($app);

        $app->make('Illuminate\Foundation\Bootstrap\RegisterFacades')->bootstrap($app);
        $app->make('Illuminate\Foundation\Bootstrap\SetRequestForConsole')->bootstrap($app);
        $app->make(RegisterProviders::class)->bootstrap($app);

        if (class_exists('Illuminate\Database\Eloquent\LegacyFactoryServiceProvider')) {
            $app->register('Illuminate\Database\Eloquent\LegacyFactoryServiceProvider');
        }

        TestingFeature::run(
            testCase: $this,
            default: function () use ($app) {
                $this->defineEnvironment($app);
                $this->getEnvironmentSetUp($app);
            },
            annotation: function () use ($app) {
                $this->parseTestMethodAnnotations($app, 'environment-setup'); /** @phpstan-ignore method.notFound */
                $this->parseTestMethodAnnotations($app, 'define-env'); /** @phpstan-ignore method.notFound */
            },
            attribute: function () use ($app) {
                $this->parseTestMethodAttributes($app, WithImmutableDates::class); /** @phpstan-ignore method.notFound */
                $this->parseTestMethodAttributes($app, DefineEnvironment::class); /** @phpstan-ignore method.notFound */
            },
            pest: fn () => $this->defineEnvironmentUsingPest($app), /** @phpstan-ignore method.notFound */
        );

        $this->resolveApplicationRateLimiting($app);

        if (static::usesTestingConcern(WithWorkbench::class)) {
            $this->bootDiscoverRoutesForWorkbench($app); /** @phpstan-ignore method.notFound */
        }

        if ($this->isRunningTestCase() && static::usesTestingConcern(HandlesRoutes::class)) {
            $app->booted(function () use ($app) {
                $this->setUpApplicationRoutes($app); /** @phpstan-ignore method.notFound */
            });
        }

        $app->make('Illuminate\Foundation\Bootstrap\BootProviders')->bootstrap($app);

        foreach ($this->getPackageBootstrappers($app) as $bootstrap) {
            $app->make($bootstrap)->bootstrap($app);
        }

        $app->make(ConsoleKernelContract::class)->bootstrap();

        $this->refreshApplicationRouteNameLookups($app);
    }

    /**
     * Refresh route name lookup for the application.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return void
     */
    final protected function refreshApplicationRouteNameLookups($app): void
    {
        /** @var \Illuminate\Routing\Router $router */
        $router = $app->make('router');

        refresh_router_lookups($router);

        after_resolving($app, 'url', static function ($url, $app) use ($router) {
            refresh_router_lookups($router);
        });
    }

    /**
     * Resolve application rate limiting configuration.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return void
     */
    protected function resolveApplicationRateLimiting($app)
    {
        RateLimiter::for(
            'api', static fn (Request $request) => Limit::perMinute(60)->by($request->user()?->id ?: $request->ip())
        );
    }

    /**
     * Reset artisan commands for the application.
     *
     * @internal
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return void
     */
    final protected function resetApplicationArtisanCommands($app): void
    {
        $app[ConsoleKernelContract::class]->setArtisan(null);
    }

    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return void
     */
    protected function defineEnvironment($app)
    {
        // Define environment.
    }

    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        // Define your environment setup.
    }
}
