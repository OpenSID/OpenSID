<?php

namespace Orchestra\Testbench\Concerns;

use Illuminate\Foundation\Testing\Traits\CanConfigureMigrationCommands;
use Illuminate\Support\Collection;
use Orchestra\Testbench\Contracts\Config as ConfigContract;
use Orchestra\Testbench\Foundation\Bootstrap\LoadMigrationsFromArray;
use Orchestra\Testbench\Workbench\Workbench;

/**
 * @api
 */
trait WithWorkbench
{
    use InteractsWithPHPUnit;
    use InteractsWithWorkbench;

    /**
     * Bootstrap with Workbench.
     *
     * @return void
     */
    protected function setUpWithWorkbench(): void
    {
        /** @var \Illuminate\Contracts\Foundation\Application $app */
        $app = $this->app;

        /** @var \Orchestra\Testbench\Contracts\Config $config */
        $config = static::cachedConfigurationForWorkbench();

        Workbench::start($app, $config);

        $seeders = $config['seeders'] ?? false;

        $seeders = static::usesTestingConcern(CanConfigureMigrationCommands::class)
            ? $this->mergeSeedersForWorkbench($config)
            : ($config['seeders'] ?? false);

        (new LoadMigrationsFromArray(
            $config['migrations'] ?? [], $seeders,
        ))->bootstrap($app);
    }

    /**
     * Bootstrap discover routes.
     *
     * @param  \Illuminate\Contracts\Foundation\Application  $app
     * @return void
     */
    protected function bootDiscoverRoutesForWorkbench($app): void
    {
        /** @var \Orchestra\Testbench\Contracts\Config $config */
        $config = static::cachedConfigurationForWorkbench();

        Workbench::discoverRoutes($app, $config);
    }

    /**
     * Merge seeders for Workbench.
     *
     * @param  \Orchestra\Testbench\Contracts\Config  $config
     * @return array<int, class-string>|false
     */
    protected function mergeSeedersForWorkbench(ConfigContract $config): array|false
    {
        $seeders = $config['seeders'] ?? false;

        if ($this->shouldSeed() === false || $seeders === false) {
            return false;
        }

        $testCaseSeeder = $this->seeder();

        /** @var class-string $testCaseSeeder */
        $testCaseSeeder = $testCaseSeeder !== false
            ? $testCaseSeeder
            : \Database\Seeders\DatabaseSeeder::class;

        $seeders = (new Collection($seeders))
            ->reject(static fn ($seeder) => $seeder === $testCaseSeeder)
            ->values();

        return $seeders->isEmpty() ? false : $seeders->all();
    }
}
