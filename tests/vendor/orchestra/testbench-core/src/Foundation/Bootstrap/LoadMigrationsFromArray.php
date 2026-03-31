<?php

namespace Orchestra\Testbench\Foundation\Bootstrap;

use Illuminate\Contracts\Console\Kernel as ConsoleKernel;
use Illuminate\Contracts\Events\Dispatcher as EventDispatcher;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Database\Events\DatabaseRefreshed;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Orchestra\Sidekick\Env;

use function Orchestra\Sidekick\transform_relative_path;
use function Orchestra\Testbench\default_migration_path;
use function Orchestra\Testbench\load_migration_paths;
use function Orchestra\Testbench\workbench;

/**
 * @internal
 */
final class LoadMigrationsFromArray
{
    /**
     * The migrations.
     *
     * @var array<int, string>|bool|string
     */
    public $migrations;

    /**
     * The seeders.
     *
     * @var array<int, class-string>|bool|class-string
     */
    public $seeders;

    /**
     * Construct a new Create Vendor Symlink bootstrapper.
     *
     * @param  array<int, string>|bool|string  $migrations
     * @param  array<int, class-string>|bool|class-string  $seeders
     */
    public function __construct($migrations = [], $seeders = false)
    {
        $this->migrations = $migrations;
        $this->seeders = $seeders;
    }

    /**
     * Bootstrap the given application.
     *
     * @param  \Illuminate\Contracts\Foundation\Application  $app
     * @return void
     */
    public function bootstrap(Application $app): void
    {
        if ($this->seeders !== false) {
            $this->bootstrapSeeders($app);
        }

        if ($this->migrations !== false) {
            $this->bootstrapMigrations($app);
        }
    }

    /**
     * Bootstrap seeders.
     *
     * @param  \Illuminate\Contracts\Foundation\Application  $app
     * @return void
     */
    protected function bootstrapSeeders(Application $app): void
    {
        $app->make(EventDispatcher::class)
            ->listen(DatabaseRefreshed::class, function (DatabaseRefreshed $event) use ($app) {
                if (\is_bool($this->seeders) && $this->seeders === false) {
                    return;
                }

                (new Collection(Arr::wrap($this->seeders)))
                    ->flatten()
                    ->filter(static fn ($seederClass) => ! \is_null($seederClass) && class_exists($seederClass))
                    ->each(static function ($seederClass) use ($app) {
                        $app->make(ConsoleKernel::class)->call('db:seed', [
                            '--class' => $seederClass,
                        ]);
                    });
            });
    }

    /**
     * Bootstrap migrations.
     *
     * @param  \Illuminate\Contracts\Foundation\Application  $app
     * @return void
     */
    protected function bootstrapMigrations(Application $app): void
    {
        $paths = (new Collection(
            ! \is_bool($this->migrations) ? Arr::wrap($this->migrations) : []
        ))->when(
            $this->includesDefaultMigrations($app),
            static fn ($migrations) => $migrations->push(default_migration_path()),
        )->filter(static fn ($migration) => \is_string($migration))
            ->transform(static fn ($migration) => transform_relative_path($migration, $app->basePath()))
            ->all();

        load_migration_paths($app, $paths);
    }

    /**
     * Determine whether default migrations should be included.
     *
     * @param  \Illuminate\Contracts\Foundation\Application  $app
     * @return bool
     */
    protected function includesDefaultMigrations($app): bool
    {
        return
            workbench()['install'] === true
            && Env::get('TESTBENCH_WITHOUT_DEFAULT_MIGRATIONS') !== true
            && rescue(static fn () => is_dir(default_migration_path()), false, false);
    }
}
