<?php

namespace Orchestra\Testbench\Workbench;

use Illuminate\Console\Application as Artisan;
use Illuminate\Console\Command;
use Illuminate\Contracts\Foundation\Application as ApplicationContract;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Routing\Router;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Orchestra\Sidekick\Env;
use Orchestra\Testbench\Contracts\Config as ConfigContract;
use Orchestra\Testbench\Foundation\Config;
use ReflectionClass;
use Symfony\Component\Finder\Finder;

use function Orchestra\Sidekick\join_paths;
use function Orchestra\Testbench\after_resolving;
use function Orchestra\Testbench\package_path;
use function Orchestra\Testbench\workbench_path;

/**
 * @api
 *
 * @phpstan-import-type TWorkbenchDiscoversConfig from \Orchestra\Testbench\Foundation\Config
 */
class Workbench
{
    /**
     * The cached test case configuration.
     *
     * @var \Orchestra\Testbench\Contracts\Config|null
     */
    protected static ?ConfigContract $cachedConfiguration = null;

    /**
     * Cached namespace by path.
     *
     * @var array<string, string|null>
     */
    protected static array $cachedNamespaces = [];

    /**
     * The cached test case configuration.
     *
     * @var class-string<\Illuminate\Foundation\Auth\User>|false|null
     */
    protected static string|false|null $cachedUserModel = null;

    /**
     * The cached core workbench bindings.
     *
     * @var array{kernel: array{console?: string|null, http?: string|null}, handler: array{exception?: string|null}}
     */
    public static array $cachedCoreBindings = [
        'kernel' => [],
        'handler' => [],
    ];

    /**
     * Start Workbench.
     *
     * @internal
     *
     * @param  \Illuminate\Contracts\Foundation\Application  $app
     * @param  \Orchestra\Testbench\Contracts\Config  $config
     * @param  array<int, string|class-string<\Illuminate\Support\ServiceProvider>>  $providers
     * @return void
     *
     * @codeCoverageIgnore
     */
    public static function start(ApplicationContract $app, ConfigContract $config, array $providers = []): void
    {
        $app->singleton(ConfigContract::class, static fn () => $config);

        (new Collection($providers))
            ->filter(static fn ($provider) => ! empty($provider) && class_exists($provider))
            ->each(static function ($provider) use ($app) {
                $app->register($provider);
            });
    }

    /**
     * Start Workbench with providers.
     *
     * @internal
     *
     * @param  \Illuminate\Contracts\Foundation\Application  $app
     * @param  \Orchestra\Testbench\Contracts\Config  $config
     * @return void
     *
     * @codeCoverageIgnore
     */
    public static function startWithProviders(ApplicationContract $app, ConfigContract $config): void
    {
        $hasAuthentication = $config->getWorkbenchAttributes()['auth'] ?? false;

        static::start($app, $config, array_filter([
            $hasAuthentication === true ? 'Orchestra\Workbench\AuthServiceProvider' : null,
            'Orchestra\Workbench\WorkbenchServiceProvider',
        ]));
    }

    /**
     * Discover Workbench routes.
     *
     * @param  \Illuminate\Contracts\Foundation\Application  $app
     * @param  \Orchestra\Testbench\Contracts\Config  $config
     * @return void
     */
    public static function discoverRoutes(ApplicationContract $app, ConfigContract $config): void
    {
        /** @var TWorkbenchDiscoversConfig $discoversConfig */
        $discoversConfig = $config->getWorkbenchDiscoversAttributes();

        $app->booted(static function ($app) use ($discoversConfig) {
            tap($app->make('router'), static function (Router $router) use ($discoversConfig) {
                foreach (['web', 'api'] as $group) {
                    if (($discoversConfig[$group] ?? false) === true) {
                        if (is_file($route = workbench_path('routes', "{$group}.php"))) {
                            $router->middleware($group)->group($route);
                        }
                    }
                }
            });

            if ($app->runningInConsole() && ($discoversConfig['commands'] ?? false) === true) {
                static::discoverCommandsRoutes($app);
            }
        });

        after_resolving($app, 'translator', static function ($translator) {
            /** @var \Illuminate\Contracts\Translation\Loader $translator */
            $path = (new Collection([
                workbench_path('lang'),
                workbench_path('resources', 'lang'),
            ]))->filter(static fn ($path) => is_dir($path))
                ->first();

            if (\is_null($path)) {
                return;
            }

            $translator->addNamespace('workbench', $path);
        });

        if (is_dir($workbenchViewPath = workbench_path('resources', 'views'))) {
            if (($discoversConfig['views'] ?? false) === true) {
                $app->booted(static function () use ($app, $workbenchViewPath) {
                    tap($app->make('config'), function ($config) use ($workbenchViewPath) {
                        /** @var \Illuminate\Contracts\Config\Repository $config */
                        $config->set('view.paths', array_merge(
                            $config->get('view.paths', []),
                            [$workbenchViewPath]
                        ));
                    });
                });
            }

            after_resolving($app, 'view', static function ($view, $app) use ($discoversConfig, $workbenchViewPath) {
                /** @var \Illuminate\Contracts\View\Factory|\Illuminate\View\Factory $view */
                if (($discoversConfig['views'] ?? false) === true && method_exists($view, 'addLocation')) {
                    $view->addLocation($workbenchViewPath);
                }

                $view->addNamespace('workbench', $workbenchViewPath);
            });
        }

        after_resolving($app, 'blade.compiler', static function ($blade) use ($discoversConfig) {
            /** @var \Illuminate\View\Compilers\BladeCompiler $blade */
            if (($discoversConfig['components'] ?? false) === false && is_dir(workbench_path('app', 'View', 'Components'))) {
                $blade->componentNamespace('Workbench\\App\\View\\Components', 'workbench');
            }
        });

        if (($discoversConfig['factories'] ?? false) === true) {
            Factory::guessFactoryNamesUsing(static function ($modelName) {
                /** @var class-string<\Illuminate\Database\Eloquent\Model> $modelName */
                $workbenchNamespace = static::detectNamespace('app') ?? 'Workbench\\App\\';
                $factoryNamespace = static::detectNamespace('database/factories') ?? 'Workbench\\Database\\Factories\\';

                $modelBasename = str_starts_with($modelName, $workbenchNamespace.'Models\\')
                    ? Str::after($modelName, $workbenchNamespace.'Models\\')
                    : Str::after($modelName, $workbenchNamespace);

                /** @var class-string<\Illuminate\Database\Eloquent\Factories\Factory> $factoryName */
                $factoryName = $factoryNamespace.$modelBasename.'Factory';

                return $factoryName;
            });

            Factory::guessModelNamesUsing(static function ($factory) {
                /** @var \Illuminate\Database\Eloquent\Factories\Factory $factory */
                $workbenchNamespace = static::detectNamespace('app') ?? 'Workbench\\App\\';
                $factoryNamespace = static::detectNamespace('database/factories') ?? 'Workbench\\Database\\Factories\\';

                $namespacedFactoryBasename = Str::replaceLast(
                    'Factory', '', Str::replaceFirst($factoryNamespace, '', $factory::class)
                );

                $factoryBasename = Str::replaceLast('Factory', '', class_basename($factory));

                /** @var class-string<\Illuminate\Database\Eloquent\Model> $modelName */
                $modelName = class_exists($workbenchNamespace.'Models\\'.$namespacedFactoryBasename)
                    ? $workbenchNamespace.'Models\\'.$namespacedFactoryBasename
                    : $workbenchNamespace.$factoryBasename;

                return $modelName;
            });
        }
    }

    /**
     * Discover Workbench command routes.
     *
     * @param  \Illuminate\Contracts\Foundation\Application  $app
     * @return void
     */
    public static function discoverCommandsRoutes(ApplicationContract $app): void
    {
        if (is_file($console = workbench_path('routes', 'console.php'))) {
            require $console;
        }

        if (! is_dir(workbench_path('app', 'Console', 'Commands'))) {
            return;
        }

        $namespace = rtrim((static::detectNamespace('app') ?? 'Workbench\App\\'), '\\');

        foreach ((new Finder)->in([workbench_path('app', 'Console', 'Commands')])->files() as $command) {
            $command = $namespace.str_replace(
                ['/', '.php'],
                ['\\', ''],
                Str::after($command->getRealPath(), (string) realpath(workbench_path('app').DIRECTORY_SEPARATOR))
            );

            if (
                is_subclass_of($command, Command::class) &&
                ! (new ReflectionClass($command))->isAbstract()
            ) {
                Artisan::starting(static function ($artisan) use ($command) {
                    $artisan->resolve($command);
                });
            }
        }
    }

    /**
     * Resolve the configuration.
     *
     * @return \Orchestra\Testbench\Contracts\Config
     *
     * @codeCoverageIgnore
     */
    public static function configuration(): ConfigContract
    {
        return static::$cachedConfiguration ??= Config::cacheFromYaml(package_path());
    }

    /**
     * Get application Console Kernel implementation.
     *
     * @return string|null
     */
    public static function applicationConsoleKernel(): ?string
    {
        if (! isset(static::$cachedCoreBindings['kernel']['console'])) {
            static::$cachedCoreBindings['kernel']['console'] = is_file(workbench_path('app', 'Console', 'Kernel.php'))
                ? \sprintf('%sConsole\Kernel', static::detectNamespace('app'))
                : null;
        }

        return static::$cachedCoreBindings['kernel']['console'];
    }

    /**
     * Get application HTTP Kernel implementation using Workbench.
     *
     * @return string|null
     */
    public static function applicationHttpKernel(): ?string
    {
        if (! isset(static::$cachedCoreBindings['kernel']['http'])) {
            static::$cachedCoreBindings['kernel']['http'] = is_file(workbench_path('app', 'Http', 'Kernel.php'))
                ? \sprintf('%sHttp\Kernel', static::detectNamespace('app'))
                : null;
        }

        return static::$cachedCoreBindings['kernel']['http'];
    }

    /**
     * Get application HTTP exception handler using Workbench.
     *
     * @return string|null
     */
    public static function applicationExceptionHandler(): ?string
    {
        if (! isset(static::$cachedCoreBindings['handler']['exception'])) {
            static::$cachedCoreBindings['handler']['exception'] = is_file(workbench_path('app', 'Exceptions', 'Handler.php'))
                ? \sprintf('%sExceptions\Handler', static::detectNamespace('app'))
                : null;
        }

        return static::$cachedCoreBindings['handler']['exception'];
    }

    /**
     * Get application User Model
     *
     * @return class-string<\Illuminate\Foundation\Auth\User>|null
     */
    public static function applicationUserModel(): ?string
    {
        if (\is_null(static::$cachedUserModel)) {
            /** @var class-string<\Illuminate\Foundation\Auth\User>|false $userModel */
            $userModel = match (true) {
                Env::has('AUTH_MODEL') => Env::get('AUTH_MODEL'),
                is_file(workbench_path('app', 'Models', 'User.php')) => \sprintf('%sModels\User', static::detectNamespace('app')),
                is_file(base_path(join_paths('Models', 'User.php'))) => 'App\Models\User',
                default => false,
            };

            static::$cachedUserModel = $userModel;
        }

        return static::$cachedUserModel != false ? static::$cachedUserModel : null;
    }

    /**
     * Detect namespace by type.
     *
     * @param  string  $type
     * @param  bool  $force
     * @return string|null
     */
    public static function detectNamespace(string $type, bool $force = false): ?string
    {
        $type = trim($type, '/');

        if (! isset(static::$cachedNamespaces[$type]) || $force === true) {
            static::$cachedNamespaces[$type] = null;

            /** @var array{'autoload-dev': array{'psr-4': array<string, array<int, string>|string>}} $composer */
            $composer = json_decode((string) file_get_contents(package_path('composer.json')), true);

            $collection = $composer['autoload-dev']['psr-4'] ?? [];

            $path = implode('/', ['workbench', $type]);

            foreach ((array) $collection as $namespace => $paths) {
                foreach ((array) $paths as $pathChoice) {
                    if (trim($pathChoice, '/') === $path) {
                        static::$cachedNamespaces[$type] = $namespace;
                    }
                }
            }
        }

        $defaults = [
            'app' => 'Workbench\App\\',
            'database/factories' => 'Workbench\Database\Factories\\',
            'database/seeders' => 'Workbench\Database\Seeders\\',
        ];

        return static::$cachedNamespaces[$type] ?? $defaults[$type] ?? null;
    }

    /**
     * Flush the cached configuration.
     *
     * @return void
     *
     * @codeCoverageIgnore
     */
    public static function flush(): void
    {
        static::$cachedConfiguration = null;

        static::flushCachedClassAndNamespaces();
    }

    /**
     * Flush the cached namespace configuration.
     *
     * @return void
     *
     * @codeCoverageIgnore
     */
    public static function flushCachedClassAndNamespaces(): void
    {
        static::$cachedUserModel = null;
        static::$cachedNamespaces = [];

        static::$cachedCoreBindings = [
            'kernel' => [],
            'handler' => [],
        ];
    }
}
