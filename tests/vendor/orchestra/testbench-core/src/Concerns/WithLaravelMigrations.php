<?php

namespace Orchestra\Testbench\Concerns;

use Illuminate\Foundation\Testing\RefreshDatabaseState;

use function Orchestra\Testbench\after_resolving;
use function Orchestra\Testbench\default_migration_path;

/**
 * @api
 */
trait WithLaravelMigrations
{
    use InteractsWithWorkbench;

    /**
     * Bootstrap with laravel migrations.
     *
     * @return void
     */
    protected function setUpWithLaravelMigrations(): void
    {
        /** @var bool $loadLaravelMigrations */
        $loadLaravelMigrations = static::cachedConfigurationForWorkbench()->getWorkbenchAttributes()['install'] ?? false;

        if (! ($loadLaravelMigrations && is_dir(default_migration_path()))) {
            return;
        }

        if (
            static::usesRefreshDatabaseTestingConcern()
            && RefreshDatabaseState::$migrated === false
            && RefreshDatabaseState::$lazilyRefreshed === false
        ) {
            after_resolving($this->app, 'migrator', static function ($migrator, $app) {
                /** @var \Illuminate\Database\Migrations\Migrator $migrator */
                $migrator->path(default_migration_path());
            });
        } else {
            $this->loadLaravelMigrations();
        }
    }
}
