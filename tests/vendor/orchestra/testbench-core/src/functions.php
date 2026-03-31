<?php

namespace Orchestra\Testbench;

use Closure;
use Illuminate\Contracts\Console\Kernel as ConsoleKernel;
use Illuminate\Contracts\Foundation\Application as ApplicationContract;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Foundation\Application;
use Illuminate\Routing\Router;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\ProcessUtils;
use Illuminate\Support\Str;
use Illuminate\Testing\PendingCommand;
use InvalidArgumentException;
use Orchestra\Sidekick;

/**
 * Create Laravel application instance.
 *
 * @api
 *
 * @param  string|null  $basePath
 * @param  (callable(\Illuminate\Foundation\Application):(void))|null  $resolvingCallback
 * @param  array{extra?: array{providers?: array, dont-discover?: array, env?: array}, load_environment_variables?: bool, enabled_package_discoveries?: bool}  $options
 * @param  \Orchestra\Testbench\Foundation\Config|null  $config
 * @return \Orchestra\Testbench\Foundation\Application
 */
function container(
    ?string $basePath = null,
    ?callable $resolvingCallback = null,
    array $options = [],
    ?Foundation\Config $config = null
): Foundation\Application {
    if ($config instanceof Foundation\Config) {
        return Foundation\Application::makeFromConfig($config, $resolvingCallback, $options);
    }

    return Foundation\Application::make($basePath, $resolvingCallback, $options);
}

/**
 * Run artisan command.
 *
 * @api
 *
 * @param  \Orchestra\Testbench\Contracts\TestCase|\Illuminate\Contracts\Foundation\Application  $context
 * @param  string  $command
 * @param  array<string, mixed>  $parameters
 * @return int
 */
function artisan(Contracts\TestCase|ApplicationContract $context, string $command, array $parameters = []): int
{
    if ($context instanceof ApplicationContract) {
        return $context->make(ConsoleKernel::class)->call($command, $parameters);
    }

    $command = $context->artisan($command, $parameters);

    return $command instanceof PendingCommand ? $command->run() : $command;
}

/**
 * Run remote action using Testbench CLI.
 *
 * @api
 *
 * @param  array<int, string>|string  $command
 * @param  array<string, mixed>|string  $env
 * @param  bool|null  $tty
 * @return \Orchestra\Testbench\Foundation\Process\ProcessDecorator
 */
function remote(array|string $command, array|string $env = [], ?bool $tty = null): Foundation\Process\ProcessDecorator
{
    $remote = new Foundation\Process\RemoteCommand(
        package_path(), $env, $tty
    );

    $binary = Sidekick\is_testbench_cli(dusk: true) ? 'testbench-dusk' : 'testbench';

    $commander = is_file($vendorBinary = package_path('vendor', 'bin', $binary))
        ? $vendorBinary
        : $binary;

    return $remote->handle($commander, $command);
}

/**
 * Run callback only once.
 *
 * @api
 *
 * @param  mixed  $callback
 * @return \Closure():mixed
 *
 * @deprecated 7.55.0 Use `Orchestra\Sidekick\once()` instead.
 *
 * @codeCoverageIgnore
 */
function once($callback): Closure
{
    return Sidekick\once($callback);
}

/**
 * Register after resolving callback.
 *
 * @api
 *
 * @template TLaravel of \Illuminate\Contracts\Foundation\Application
 *
 * @param  TLaravel  $app
 * @param  class-string|string  $name
 * @param  (\Closure(object, TLaravel):(mixed))|null  $callback
 * @return void
 */
function after_resolving(ApplicationContract $app, string $name, ?Closure $callback = null): void
{
    $app->afterResolving($name, $callback);

    if ($app->resolved($name)) {
        value($callback, $app->make($name), $app);
    }
}

/**
 * Load migration paths.
 *
 * @api
 *
 * @param  \Illuminate\Contracts\Foundation\Application  $app
 * @param  array<int, string>|string  $paths
 * @return void
 */
function load_migration_paths(ApplicationContract $app, array|string $paths): void
{
    after_resolving($app, 'migrator', static function ($migrator) use ($paths) {
        foreach (Arr::wrap($paths) as $path) {
            /** @var \Illuminate\Database\Migrations\Migrator $migrator */
            $migrator->path($path);
        }
    });
}

/**
 * Get default environment variables.
 *
 * @return array<int, string>
 *
 * @deprecated
 *
 * @codeCoverageIgnore
 */
function default_environment_variables(): array
{
    return [];
}

/**
 * Get defined environment variables.
 *
 * @api
 *
 * @return array<string, mixed>
 */
function defined_environment_variables(): array
{
    return (new Collection(array_merge($_SERVER, $_ENV)))
        ->keys()
        ->mapWithKeys(static fn (string $key) => [$key => Sidekick\Env::forward($key)])
        ->unless(
            Sidekick\Env::has('TESTBENCH_WORKING_PATH'), static fn ($env) => $env->put('TESTBENCH_WORKING_PATH', package_path())
        )->all();
}

/**
 * Get default environment variables.
 *
 * @api
 *
 * @param  iterable<string, mixed>  $variables
 * @return array<int, string>
 */
function parse_environment_variables($variables): array
{
    return (new Collection($variables))
        ->transform(static function ($value, $key) {
            if (\is_bool($value) || \in_array($value, ['true', 'false'])) {
                $value = \in_array($value, [true, 'true']) ? '(true)' : '(false)';
            } elseif (\is_null($value) || \in_array($value, ['null'])) {
                $value = '(null)';
            } else {
                $value = $key === 'APP_DEBUG' ? \sprintf('(%s)', Str::of($value)->ltrim('(')->rtrim(')')) : "'{$value}'";
            }

            return "{$key}={$value}";
        })->values()->all();
}

/**
 * Refresh router lookups.
 *
 * @api
 *
 * @param  \Illuminate\Routing\Router  $router
 * @return void
 */
function refresh_router_lookups(Router $router): void
{
    $router->getRoutes()->refreshNameLookups();
}

/**
 * Transform realpath to alias path.
 *
 * @api
 *
 * @param  string  $path
 * @param  string|null  $workingPath
 * @return string
 */
function transform_realpath_to_relative(string $path, ?string $workingPath = null, string $prefix = ''): string
{
    $separator = DIRECTORY_SEPARATOR;

    if (! \is_null($workingPath)) {
        return str_replace(rtrim($workingPath, $separator).$separator, $prefix.$separator, $path);
    }

    $laravelPath = base_path();
    $workbenchPath = workbench_path();
    $packagePath = package_path();

    return match (true) {
        str_starts_with($path, $laravelPath) => str_replace($laravelPath.$separator, '@laravel'.$separator, $path),
        str_starts_with($path, $workbenchPath) => str_replace($workbenchPath.$separator, '@workbench'.$separator, $path),
        str_starts_with($path, $packagePath) => str_replace($packagePath.$separator, '.'.$separator, $path),
        ! empty($prefix) => implode($separator, [$prefix, ltrim($path, $separator)]),
        default => $path,
    };
}

/**
 * Transform relative path.
 *
 * @api
 *
 * @param  string  $path
 * @param  string  $workingPath
 * @return string
 *
 * @deprecated 7.55.0 Use `Orchestra\Sidekick\transform_relative_path()` instead.
 *
 * @codeCoverageIgnore
 */
function transform_relative_path(string $path, string $workingPath): string
{
    return Sidekick\transform_relative_path($path, $workingPath);
}

/**
 * Get the default skeleton path.
 *
 * @api
 *
 * @no-named-arguments
 *
 * @param  array<int, string|null>|string  ...$path
 * @return string
 */
function default_skeleton_path(array|string $path = ''): string
{
    return (string) realpath(
        Sidekick\join_paths(__DIR__, '..', 'laravel', ...Arr::wrap(\func_num_args() > 1 ? \func_get_args() : $path))
    );
}

/**
 * Get the migration path by type.
 *
 * @api
 *
 * @param  string|null  $type
 * @return string
 *
 * @throws \InvalidArgumentException
 */
function default_migration_path(?string $type = null): string
{
    $path = realpath(
        \is_null($type) ? base_path('migrations') : base_path(Sidekick\join_paths('migrations', $type))
    );

    if ($path === false) {
        throw new InvalidArgumentException(\sprintf('Unable to resolve migration path for type [%s]', $type ?? 'laravel'));
    }

    return $path;
}

/**
 * Get the path to the package folder.
 *
 * @api
 *
 * @no-named-arguments
 *
 * @param  array<int, string|null>|string  ...$path
 * @return string
 */
function package_path(array|string $path = ''): string
{
    $argumentCount = \func_num_args();

    $workingPath = \defined('TESTBENCH_WORKING_PATH')
        ? TESTBENCH_WORKING_PATH
        : Sidekick\Env::get('TESTBENCH_WORKING_PATH', getcwd());

    if ($argumentCount === 1 && \is_string($path) && str_starts_with($path, './')) {
        return transform_relative_path($path, $workingPath);
    }

    $path = Sidekick\join_paths(...Arr::wrap($argumentCount > 1 ? \func_get_args() : $path));

    return str_starts_with($path, './')
        ? transform_relative_path($path, $workingPath)
        : Sidekick\join_paths(rtrim($workingPath, DIRECTORY_SEPARATOR), $path);
}

/**
 * Get the workbench configuration.
 *
 * @api
 *
 * @return array<string, mixed>
 */
function workbench(): array
{
    /** @var \Orchestra\Testbench\Contracts\Config $config */
    $config = app()->bound(Contracts\Config::class)
        ? app()->make(Contracts\Config::class)
        : new Foundation\Config;

    return $config->getWorkbenchAttributes();
}

/**
 * Get the path to the workbench folder.
 *
 * @api
 *
 * @no-named-arguments
 *
 * @param  array<int, string|null>|string  ...$path
 * @return string
 */
function workbench_path(array|string $path = ''): string
{
    return package_path('workbench', ...Arr::wrap(\func_num_args() > 1 ? \func_get_args() : $path));
}

/**
 * Get the migration path by type.
 *
 * @api
 *
 * @param  string|null  $type
 * @return string
 *
 * @throws \InvalidArgumentException
 *
 * @deprecated
 */
function laravel_migration_path(?string $type = null): string
{
    return default_migration_path($type);
}

/**
 * Determine if vendor symlink exists on the laravel application.
 *
 * @api
 *
 * @param  \Illuminate\Contracts\Foundation\Application  $app
 * @param  string|null  $workingPath
 * @return bool
 */
function laravel_vendor_exists(ApplicationContract $app, ?string $workingPath = null): bool
{
    $filesystem = new Filesystem;

    $appVendorPath = $app->basePath('vendor');
    $workingPath ??= package_path('vendor');

    return $filesystem->isFile(join_paths($appVendorPath, 'autoload.php')) &&
        $filesystem->hash(join_paths($appVendorPath, 'autoload.php')) === $filesystem->hash(join_paths($workingPath, 'autoload.php'));
}

/**
 * Laravel version compare.
 *
 * @api
 *
 * @template TOperator of string|null
 *
 * @param  string  $version
 * @param  string|null  $operator
 *
 * @phpstan-param  TOperator  $operator
 *
 * @return int|bool
 *
 * @phpstan-return (TOperator is null ? int : bool)
 */
function laravel_version_compare(string $version, ?string $operator = null)
{
    return Sidekick\laravel_version_compare($version, $operator);
}

/**
 * PHPUnit version compare.
 *
 * @api
 *
 * @template TOperator of string|null
 *
 * @param  string  $version
 * @param  string|null  $operator
 *
 * @phpstan-param  TOperator  $operator
 *
 * @return int|bool
 *
 * @phpstan-return (TOperator is null ? int : bool)
 *
 * @throws \RuntimeException
 */
function phpunit_version_compare(string $version, ?string $operator = null)
{
    return Sidekick\phpunit_version_compare($version, $operator);
}

/**
 * Determine the PHP Binary.
 *
 * @api
 *
 * @param  bool  $escape
 * @return string
 */
function php_binary(bool $escape = false): string
{
    $phpBinary = Sidekick\php_binary();

    return $escape === true ? ProcessUtils::escapeArgument((string) $phpBinary) : $phpBinary;
}

/**
 * Join the given paths together.
 *
 * @param  string|null  $basePath
 * @param  string  ...$paths
 * @return string
 *
 * @codeCoverageIgnore
 */
function join_paths(?string $basePath, string ...$paths): string
{
    return Sidekick\join_paths($basePath, ...$paths);
}

/**
 * Ensure the provided `$app` return an instance of Laravel application or throw an exception.
 *
 * @internal
 *
 * @param  \Illuminate\Foundation\Application|null  $app
 * @param  string|null  $caller
 * @return \Illuminate\Foundation\Application
 *
 * @throws \Orchestra\Testbench\Exceptions\ApplicationNotAvailableException
 */
function laravel_or_fail($app, ?string $caller = null): Application
{
    if ($app instanceof Application) {
        return $app;
    }

    if (\is_null($caller)) {
        $caller = transform(debug_backtrace()[1] ?? null, function ($debug) {
            /** @phpstan-ignore isset.offset */
            if (isset($debug['class']) && isset($debug['function'])) {
                return \sprintf('%s::%s', $debug['class'], $debug['function']);
            }

            /** @phpstan-ignore offsetAccess.notFound */
            return $debug['function'];
        });
    }

    throw Exceptions\ApplicationNotAvailableException::make($caller);
}
