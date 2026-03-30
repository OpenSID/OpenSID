<?php

namespace Orchestra\Testbench\Concerns;

use Illuminate\Support\Arr;
use Orchestra\Testbench\Workbench\Workbench;

/**
 * @internal
 */
trait InteractsWithWorkbench
{
    use InteractsWithPest;
    use InteractsWithPHPUnit;
    use InteractsWithTestCase;

    /**
     * Get Application's base path.
     *
     * @return string|null
     */
    public static function applicationBasePathUsingWorkbench(): ?string
    {
        if (! static::usesTestingConcern()) {
            return $_ENV['APP_BASE_PATH'] ?? null;
        }

        return $_ENV['APP_BASE_PATH'] ?? $_ENV['TESTBENCH_APP_BASE_PATH'] ?? null;
    }

    /**
     * Ignore package discovery from.
     *
     * @return array<int, string>|null
     */
    public function ignorePackageDiscoveriesFromUsingWorkbench(): ?array
    {
        if (property_exists($this, 'enablesPackageDiscoveries') && \is_bool($this->enablesPackageDiscoveries)) {
            return $this->enablesPackageDiscoveries === false ? ['*'] : [];
        }

        return static::usesTestingConcern(WithWorkbench::class)
            ? static::cachedConfigurationForWorkbench()?->getExtraAttributes()['dont-discover'] ?? []
            : null;
    }

    /**
     * Get package bootstrapper.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return array<int, class-string>|null
     */
    protected function getPackageBootstrappersUsingWorkbench($app): ?array
    {
        if (empty($bootstrappers = static::cachedConfigurationForWorkbench()?->getExtraAttributes()['bootstrappers'] ?? null)) {
            return null;
        }

        return static::usesTestingConcern(WithWorkbench::class)
            ? Arr::wrap($bootstrappers)
            : [];
    }

    /**
     * Get package providers.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return array<int, class-string<\Illuminate\Support\ServiceProvider>>|null
     */
    protected function getPackageProvidersUsingWorkbench($app): ?array
    {
        $config = static::cachedConfigurationForWorkbench();

        $hasAuthentication = $config?->getWorkbenchAttributes()['auth'] ?? false;
        $providers = $config?->getExtraAttributes()['providers'] ?? [];

        if ($hasAuthentication === true && class_exists('Orchestra\Workbench\AuthServiceProvider')) {
            $providers[] = 'Orchestra\Workbench\AuthServiceProvider';
        }

        if (empty($providers)) {
            return null;
        }

        return static::usesTestingConcern(WithWorkbench::class) || ! static::usesTestingConcern()
            ? Arr::wrap($providers)
            : [];
    }

    /**
     * Resolve application Console Kernel implementation.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return string
     */
    protected function applicationConsoleKernelUsingWorkbench($app): string
    {
        if (static::usesTestingConcern(WithWorkbench::class)) {
            return Workbench::applicationConsoleKernel() ?? 'Orchestra\Testbench\Console\Kernel';
        }

        return 'Orchestra\Testbench\Console\Kernel';
    }

    /**
     * Get application HTTP Kernel implementation using Workbench.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return string
     */
    protected function applicationHttpKernelUsingWorkbench($app): string
    {
        if (static::usesTestingConcern(WithWorkbench::class)) {
            return Workbench::applicationHttpKernel() ?? 'Orchestra\Testbench\Http\Kernel';
        }

        return 'Orchestra\Testbench\Http\Kernel';
    }

    /**
     * Get application HTTP exception handler using Workbench.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return string
     */
    protected function applicationExceptionHandlerUsingWorkbench($app): string
    {
        if (static::usesTestingConcern(WithWorkbench::class)) {
            return Workbench::applicationExceptionHandler() ?? 'Orchestra\Testbench\Exceptions\Handler';
        }

        return 'Orchestra\Testbench\Exceptions\Handler';
    }

    /**
     * Define or get the cached uses for test case.
     *
     * @return \Orchestra\Testbench\Contracts\Config|null
     */
    public static function cachedConfigurationForWorkbench()
    {
        return Workbench::configuration();
    }

    /**
     * Prepare the testing environment before the running the test case.
     *
     * @return void
     *
     * @codeCoverageIgnore
     */
    public static function setUpBeforeClassUsingWorkbench(): void
    {
        /** @var array{laravel: string|null} $config */
        $config = static::cachedConfigurationForWorkbench();

        if (
            ! \is_null($config['laravel'])
            && isset(static::$cachedTestCaseUses[WithWorkbench::class])
        ) {
            $_ENV['TESTBENCH_APP_BASE_PATH'] = $config['laravel'];
        }
    }

    /**
     * Clean up the testing environment before the next test case.
     *
     * @return void
     *
     * @codeCoverageIgnore
     */
    public static function tearDownAfterClassUsingWorkbench(): void
    {
        unset($_ENV['TESTBENCH_APP_BASE_PATH']);
    }
}
