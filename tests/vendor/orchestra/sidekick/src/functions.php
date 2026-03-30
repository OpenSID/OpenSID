<?php

namespace Orchestra\Sidekick;

use BackedEnum;
use Closure;
use Composer\InstalledVersions;
use Composer\Semver\VersionParser;
use Illuminate\Foundation\Application;
use Illuminate\Support\Arr;
use OutOfBoundsException;
use PHPUnit\Runner\Version;
use RuntimeException;
use UnitEnum;

if (! \function_exists('Orchestra\Sidekick\enum_name')) {
    /**
     * Get the proper name from enum.
     *
     * @api
     *
     * @throws \RuntimeException
     */
    function enum_name(BackedEnum|UnitEnum $enum): string
    {
        return mb_convert_case(str_replace('_', ' ', $enum->name), MB_CASE_TITLE, 'UTF-8');
    }
}

if (! \function_exists('Orchestra\Sidekick\enum_value')) {
    /**
     * Get the proper name from enum.
     *
     * @api
     *
     * @template TValue
     * @template TDefault
     *
     * @param  TValue  $value
     * @param  TDefault|callable(TValue): TDefault  $default
     * @return ($value is empty ? TDefault : mixed)
     *
     * @throws \RuntimeException
     */
    function enum_value(mixed $value, mixed $default = null): mixed
    {
        return match (true) {
            $value instanceof BackedEnum => $value->value,
            $value instanceof UnitEnum => $value->name,

            default => $value ?? value($default),
        };
    }
}

if (! \function_exists('Orchestra\Sidekick\join_paths')) {
    /**
     * Join the given paths together.
     *
     * @api
     */
    function join_paths(?string $basePath, string ...$paths): string
    {
        foreach ($paths as $index => $path) {
            if (empty($path) && $path !== '0') {
                unset($paths[$index]);
            } else {
                $paths[$index] = DIRECTORY_SEPARATOR.ltrim($path, DIRECTORY_SEPARATOR);
            }
        }

        return $basePath.implode('', $paths);
    }
}

if (! \function_exists('Orchestra\Sidekick\once')) {
    /**
     * Run callback only once.
     *
     * @api
     *
     * @param  mixed  $callback
     * @return \Closure():mixed
     */
    function once($callback): Closure
    {
        $response = new UndefinedValue;

        return function () use ($callback, &$response) {
            if ($response instanceof UndefinedValue) {
                $response = value($callback) ?? null;
            }

            return $response;
        };
    }
}

if (! \function_exists('Orchestra\Sidekick\is_safe_callable')) {
    /**
     * Determine if the value is a callable and not a string matching an available function name.
     *
     * @api
     */
    function is_safe_callable(mixed $value): bool
    {
        if ($value instanceof Closure) {
            return true;
        }

        if (! \is_callable($value)) {
            return false;
        }

        if (\is_array($value)) {
            return \count($value) === 2 && array_is_list($value) && method_exists(...$value);
        }

        return ! \is_string($value);
    }
}

if (! \function_exists('Orchestra\Sidekick\is_symlink')) {
    /**
     * Determine if the path is a symlink for both Unix and Windows environments.
     *
     * @api
     */
    function is_symlink(string $path): bool
    {
        if (windows_os() && is_dir($path) && readlink($path) !== $path) {
            return true;
        } elseif (is_link($path)) {
            return true;
        }

        return false;
    }
}

if (! \function_exists('Orchestra\Sidekick\is_testbench_cli')) {
    /**
     * Determine if command executed via Testbench CLI.
     *
     * @api
     */
    function is_testbench_cli(?bool $dusk = null): bool
    {
        $usingTestbench = \defined('TESTBENCH_CORE');
        $usingTestbenchDusk = \defined('TESTBENCH_DUSK');

        return match ($dusk) {
            false => $usingTestbench === true && $usingTestbenchDusk === false,
            true => $usingTestbench === true && $usingTestbenchDusk === true,
            default => $usingTestbench === true,
        };
    }
}

if (! \function_exists('Orchestra\Sidekick\transform_relative_path')) {
    /**
     * Transform relative path.
     *
     * @api
     */
    function transform_relative_path(string $path, string $workingPath): string
    {
        return str_starts_with($path, './')
            ? rtrim($workingPath, DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR.mb_substr($path, 2)
            : $path;
    }
}

if (! \function_exists('Orchestra\Sidekick\working_path')) {
    /**
     * Get the working path.
     *
     * @api
     *
     * @no-named-arguments
     *
     * @param  array<int, string|null>|string  ...$path
     */
    function working_path(array|string $path = ''): string
    {
        return is_testbench_cli() && \function_exists('Orchestra\Testbench\package_path')
            ? \Orchestra\Testbench\package_path($path)
            : base_path(join_paths(...Arr::wrap(\func_num_args() > 1 ? \func_get_args() : $path)));
    }
}

if (! \function_exists('Orchestra\Sidekick\laravel_normalize_version')) {
    /**
     * Laravel normalize version.
     *
     * @api
     *
     * @throws \OutOfBoundsException
     */
    function laravel_normalize_version(): string
    {
        if (! class_exists(Application::class)) {
            throw new OutOfBoundsException('Unable to verify "laravel/framework" version');
        }

        /** @var string $version */
        $version = transform(
            Application::VERSION,
            fn (string $version) => match ($version) {
                '13.x-dev' => '13.0.0',
                default => $version,
            }
        );

        return (new VersionParser)->normalize($version);
    }
}

if (! \function_exists('Orchestra\Sidekick\phpunit_normalize_version')) {
    /**
     * PHPUnit normalize version.
     *
     * @api
     *
     * @throws \OutOfBoundsException
     */
    function phpunit_normalize_version(): string
    {
        if (! class_exists(Version::class)) {
            throw new OutOfBoundsException('Unable to verify "phpunit/phpunit" version');
        }

        /** @var string $version */
        $version = transform(
            Version::id(),
            fn (string $version) => match (true) {
                str_starts_with($version, '12.4-') => '12.4.0',
                default => $version,
            }
        );

        return (new VersionParser)->normalize($version);
    }
}

if (! \function_exists('Orchestra\Sidekick\laravel_version_compare')) {
    /**
     * Laravel version compare.
     *
     * @api
     *
     * @template TOperator of string|null
     *
     * @phpstan-param  TOperator  $operator
     *
     * @phpstan-return (TOperator is null ? int : bool)
     *
     * @throws \RuntimeException
     *
     * @codeCoverageIgnore
     */
    function laravel_version_compare(string $version, ?string $operator = null): int|bool
    {
        if (! class_exists(Application::class)) {
            return package_version_compare('laravel/framework', $version, $operator);
        }

        $laravel = laravel_normalize_version();
        $version = (new VersionParser)->normalize($version);

        if (\is_null($operator)) {
            return version_compare($laravel, $version);
        }

        return version_compare($laravel, $version, $operator);
    }
}

if (! \function_exists('Orchestra\Sidekick\package_version_compare')) {
    /**
     * Package version compare.
     *
     * @api
     *
     * @template TOperator of string|null
     *
     * @phpstan-param  TOperator  $operator
     *
     * @phpstan-return (TOperator is null ? int : bool)
     *
     * @throws \OutOfBoundsException
     * @throws \RuntimeException
     *
     * @codeCoverageIgnore
     */
    function package_version_compare(string $package, string $version, ?string $operator = null): int|bool
    {
        $prettyVersion = InstalledVersions::getPrettyVersion($package);

        if (\is_null($prettyVersion)) {
            throw new RuntimeException(\sprintf('Unable to compare "%s" version', $package));
        }

        $versionParser = new VersionParser;

        $package = $versionParser->normalize($prettyVersion);
        $version = $versionParser->normalize($version);

        if (\is_null($operator)) {
            return version_compare($package, $version);
        }

        return version_compare($package, $version, $operator);
    }
}

if (! \function_exists('Orchestra\Sidekick\phpunit_version_compare')) {
    /**
     * PHPUnit version compare.
     *
     * @api
     *
     * @template TOperator of string|null
     *
     * @phpstan-param  TOperator  $operator
     *
     * @phpstan-return (TOperator is null ? int : bool)
     *
     * @throws \OutOfBoundsException
     * @throws \RuntimeException
     *
     * @codeCoverageIgnore
     */
    function phpunit_version_compare(string $version, ?string $operator = null): int|bool
    {
        if (! class_exists(Version::class)) {
            return package_version_compare('phpunit/phpunit', $version, $operator);
        }

        $phpunit = phpunit_normalize_version();
        $version = (new VersionParser)->normalize($version);

        if (\is_null($operator)) {
            return version_compare($phpunit, $version);
        }

        return version_compare($phpunit, $version, $operator);
    }
}

if (! \function_exists('Orchestra\Sidekick\php_binary')) {
    /**
     * Determine the PHP Binary.
     *
     * @api
     *
     * @codeCoverageIgnore
     */
    function php_binary(): string
    {
        return (new PhpExecutableFinder)->find(false) ?: 'php';
    }
}

if (! \function_exists('Orchestra\Sidekick\windows_os')) {
    /**
     * Determine whether the current environment is Windows-based.
     *
     * @api
     *
     * @codeCoverageIgnore
     */
    function windows_os(): bool
    {
        return PHP_OS_FAMILY === 'Windows';
    }
}
