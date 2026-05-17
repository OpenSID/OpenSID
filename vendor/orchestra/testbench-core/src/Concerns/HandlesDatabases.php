<?php

namespace Orchestra\Testbench\Concerns;

use Closure;
use Illuminate\Database\Events\DatabaseRefreshed;
use Orchestra\Testbench\Attributes\DefineDatabase;
use Orchestra\Testbench\Attributes\WithMigration;
use Orchestra\Testbench\Features\TestingFeature;

use function Orchestra\Testbench\laravel_or_fail;

/**
 * @internal
 */
trait HandlesDatabases
{
    /**
     * Setup database requirements.
     *
     * @param  \Closure():void  $callback
     */
    protected function setUpDatabaseRequirements(Closure $callback): void
    {
        $app = laravel_or_fail($this->app);

        $app['events']->listen(DatabaseRefreshed::class, function () {
            $this->defineDatabaseMigrationsAfterDatabaseRefreshed();
        });

        if (static::usesTestingConcern(WithLaravelMigrations::class)) {
            $this->setUpWithLaravelMigrations(); /** @phpstan-ignore method.notFound */
        }

        TestingFeature::run(
            testCase: $this,
            attribute: fn () => $this->parseTestMethodAttributes($app, WithMigration::class),
        );

        $attributeCallbacks = TestingFeature::run(
            testCase: $this,
            default: function () {
                $this->defineDatabaseMigrations();
                $this->beforeApplicationDestroyed(fn () => $this->destroyDatabaseMigrations());
            },
            annotation: fn () => $this->parseTestMethodAnnotations($app, 'define-db'),
            attribute: fn () => $this->parseTestMethodAttributes($app, DefineDatabase::class),
            pest: function () {
                $this->defineDatabaseMigrationsUsingPest(); /** @phpstan-ignore method.notFound */
                $this->beforeApplicationDestroyed(fn () => $this->destroyDatabaseMigrationsUsingPest()); /** @phpstan-ignore method.notFound */
            },
        )->get('attribute');

        $callback();

        $attributeCallbacks->handle();

        TestingFeature::run(
            testCase: $this,
            default: fn () => $this->defineDatabaseSeeders(),
            pest: fn () => $this->defineDatabaseSeedersUsingPest(), /** @phpstan-ignore method.notFound */
        );
    }

    /**
     * Determine if using in-memory SQLite database connection
     *
     * @param  string|null  $connection
     * @return bool
     */
    protected function usesSqliteInMemoryDatabaseConnection(?string $connection = null): bool
    {
        $app = laravel_or_fail($this->app);

        /** @var \Illuminate\Contracts\Config\Repository $config */
        $config = $app->make('config');

        /** @var string $connection */
        $connection ??= $config->get('database.default');

        /** @var array{driver: string, database: string}|null $database */
        $database = $config->get("database.connections.{$connection}");

        return ! \is_null($database) && $database['driver'] === 'sqlite' && $database['database'] == ':memory:';
    }

    /**
     * Define database migrations.
     *
     * @return void
     */
    protected function defineDatabaseMigrations()
    {
        // Define database migrations.
    }

    /**
     * Define database migrations after database refreshed.
     *
     * @return void
     */
    protected function defineDatabaseMigrationsAfterDatabaseRefreshed()
    {
        // Define database migrations after database refreshed.
    }

    /**
     * Destroy database migrations.
     *
     * @return void
     */
    protected function destroyDatabaseMigrations()
    {
        // Destroy database migrations.
    }

    /**
     * Define database seeders.
     *
     * @return void
     */
    protected function defineDatabaseSeeders()
    {
        // Define database seeders.
    }
}
