<?php

namespace Orchestra\Testbench\Console;

use Illuminate\Console\Concerns\InteractsWithSignals;
use Illuminate\Contracts\Console\Kernel as ConsoleKernel;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Foundation\Application as LaravelApplication;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Orchestra\Testbench\Foundation\Application as Testbench;
use Orchestra\Testbench\Foundation\Bootstrap\LoadMigrationsFromArray;
use Orchestra\Testbench\Foundation\Config;
use Orchestra\Testbench\Foundation\Console\Concerns\CopyTestbenchFiles;
use Orchestra\Testbench\Foundation\Console\Signals;
use Orchestra\Testbench\Foundation\Console\TerminatingConsole;
use Orchestra\Testbench\Foundation\TestbenchServiceProvider;
use Orchestra\Testbench\Workbench\Workbench;
use Symfony\Component\Console\Application as ConsoleApplication;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\SignalRegistry\SignalRegistry;
use Throwable;

use function Orchestra\Sidekick\is_symlink;
use function Orchestra\Sidekick\join_paths;
use function Orchestra\Sidekick\transform_relative_path;

/**
 * @phpstan-import-type TConfig from \Orchestra\Testbench\Foundation\Config
 *
 * @codeCoverageIgnore
 */
class Commander
{
    use CopyTestbenchFiles;
    use InteractsWithSignals;

    /**
     * Application instance.
     *
     * @var \Illuminate\Foundation\Application|null
     */
    protected $app;

    /**
     * List of configurations.
     *
     * @var \Orchestra\Testbench\Foundation\Config
     */
    protected $config;

    /**
     * Working path.
     *
     * @var string
     */
    protected $workingPath;

    /**
     * The environment file name.
     *
     * @var string
     */
    protected $environmentFile = '.env';

    /**
     * The testbench implementation class.
     *
     * @var class-string<\Orchestra\Testbench\Foundation\Application>
     */
    protected static string $testbench = Testbench::class;

    /**
     * List of providers.
     *
     * @var array<int, class-string<\Illuminate\Support\ServiceProvider>>
     */
    protected array $providers = [
        TestbenchServiceProvider::class,
    ];

    /**
     * Construct a new Commander.
     *
     * @param  \Orchestra\Testbench\Foundation\Config|array  $config
     * @param  string  $workingPath
     *
     * @phpstan-param \Orchestra\Testbench\Foundation\Config|TConfig  $config
     */
    public function __construct($config, string $workingPath)
    {
        $this->config = $config instanceof Config ? $config : new Config($config);
        $this->workingPath = $workingPath;

        $_ENV['TESTBENCH_ENVIRONMENT_FILENAME'] = $this->environmentFile;
    }

    /**
     * Handle the command.
     *
     * @return void
     */
    public function handle()
    {
        $input = new ArgvInput;
        $output = new ConsoleOutput;

        try {
            $laravel = $this->laravel();
            $kernel = $laravel->make(ConsoleKernel::class);

            $this->prepareCommandSignals();

            $status = $kernel->handle($input, $output);

            $kernel->terminate($input, $status);
        } catch (Throwable $error) {
            $status = $this->handleException($output, $error);
        } finally {
            TerminatingConsole::handle();
            Workbench::flush();
            static::$testbench::flushState();

            $this->untrap();
        }

        exit($status);
    }

    /**
     * Create a Laravel application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function laravel()
    {
        if (! $this->app instanceof LaravelApplication) {
            $APP_BASE_PATH = $this->getApplicationBasePath();
            $VENDOR_PATH = join_paths($this->workingPath, 'vendor');

            TerminatingConsole::beforeWhen(
                ! is_symlink(join_paths($APP_BASE_PATH, 'vendor')),
                static function () use ($APP_BASE_PATH) {
                    static::$testbench::deleteVendorSymlink($APP_BASE_PATH);
                }
            );

            $filesystem = new Filesystem;

            $hasEnvironmentFile = static fn () => is_file(join_paths($APP_BASE_PATH, '.env'));

            tap(
                static::$testbench::createVendorSymlink($APP_BASE_PATH, $VENDOR_PATH),
                function ($app) use ($filesystem, $hasEnvironmentFile) {
                    $this->copyTestbenchConfigurationFile($app, $filesystem, $this->workingPath);

                    if (! $hasEnvironmentFile()) {
                        $this->copyTestbenchDotEnvFile($app, $filesystem, $this->workingPath);
                    }
                }
            );

            $this->app = static::$testbench::create(
                basePath: $APP_BASE_PATH,
                resolvingCallback: $this->resolveApplicationCallback(),
                options: array_filter([
                    'load_environment_variables' => $hasEnvironmentFile(),
                    'extra' => $this->config->getExtraAttributes(),
                ]),
            );

            $this->app->instance('TESTBENCH_COMMANDER', $this);
        }

        return $this->app;
    }

    /**
     * Resolve application implementation.
     *
     * @return \Closure(\Illuminate\Foundation\Application): void
     */
    protected function resolveApplicationCallback()
    {
        return function ($app) {
            Workbench::startWithProviders($app, $this->config);
            Workbench::discoverRoutes($app, $this->config);

            (new LoadMigrationsFromArray(
                $this->config['migrations'] ?? [],
                $this->config['seeders'] ?? false,
            ))->bootstrap($app);

            foreach ($this->providers as $provider) {
                $app->register($provider);
            }
        };
    }

    /**
     * Resolve the application's base path.
     *
     * @return string
     */
    protected function getApplicationBasePath()
    {
        $path = $this->config['laravel'] ?? null;

        if (! \is_null($path)) {
            return tap(transform_relative_path($path, $this->workingPath), static function ($path) {
                $_ENV['APP_BASE_PATH'] = $path;
            });
        }

        return static::applicationBasePath();
    }

    /**
     * Get the application's base path.
     *
     * @api
     *
     * @return string
     */
    public static function applicationBasePath()
    {
        return static::$testbench::applicationBasePath();
    }

    /**
     * Render an exception to the console.
     *
     * @param  \Symfony\Component\Console\Output\OutputInterface  $output
     * @param  \Throwable  $error
     * @return int
     */
    protected function handleException(OutputInterface $output, Throwable $error)
    {
        if ($this->app instanceof LaravelApplication) {
            tap($this->app->make(ExceptionHandler::class), static function ($handler) use ($error, $output) {
                $handler->report($error);
                $handler->renderForConsole($output, $error);
            });
        } else {
            (new ConsoleApplication)->renderThrowable($error, $output);
        }

        return 1;
    }

    /**
     * Prepare command signals.
     *
     * @return void
     */
    protected function prepareCommandSignals(): void
    {
        Signals::resolveAvailabilityUsing(static fn () => \extension_loaded('pcntl'));

        Signals::whenAvailable(function () {
            $this->signals ??= new Signals(new SignalRegistry);

            (new Collection(Arr::wrap([SIGTERM, SIGINT, SIGHUP, SIGUSR1, SIGUSR2, SIGQUIT])))
                ->each(
                    fn ($signal) => $this->signals->register($signal, function () use ($signal) {
                        TerminatingConsole::handle();
                        Workbench::flush();

                        $status = match ($signal) {
                            SIGINT => 130,
                            SIGTERM => 143,
                            default => 128 + $signal,
                        };

                        $this->untrap();

                        if (\in_array($status, [130])) {
                            exit;
                        }

                        exit($status);
                    })
                );
        }, function () {
            if (windows_os() && PHP_SAPI === 'cli' && \function_exists('sapi_windows_set_ctrl_handler')) {
                sapi_windows_set_ctrl_handler(static function ($event) {
                    TerminatingConsole::handle();
                    Workbench::flush();

                    $status = match ($event) {
                        PHP_WINDOWS_EVENT_CTRL_C => 572,
                        PHP_WINDOWS_EVENT_CTRL_BREAK => 572,
                        default => 0,
                    };

                    if (\in_array($status, [0])) {
                        exit;
                    }

                    exit($status);
                });
            }
        });
    }
}
