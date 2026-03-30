<?php

namespace Orchestra\Testbench\Foundation;

use Composer\InstalledVersions;
use Illuminate\Foundation\Console\AboutCommand;
use Illuminate\Support\ServiceProvider;
use NunoMaduro\Collision\Adapters\Laravel\Commands\TestCommand as CollisionTestCommand;

use function Orchestra\Testbench\package_path;

/**
 * @internal
 */
class TestbenchServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        AboutCommand::add('Testbench', fn () => array_filter([
            'Core Version' => InstalledVersions::getPrettyVersion('orchestra/testbench-core'),
            'Dusk Version' => InstalledVersions::isInstalled('orchestra/testbench-dusk') ? InstalledVersions::getPrettyVersion('orchestra/testbench-dusk') : null,
            'Skeleton Path' => AboutCommand::format($this->app->basePath(), console: fn ($value) => str_replace(package_path(), '', $value)),
            'Version' => InstalledVersions::isInstalled('orchestra/testbench') ? InstalledVersions::getPrettyVersion('orchestra/testbench') : null,
        ]));
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                $this->isCollisionDependenciesInstalled()
                    ? Console\TestCommand::class
                    : Console\TestFallbackCommand::class,
                Console\CreateSqliteDbCommand::class,
                Console\DevToolCommand::class,
                Console\DropSqliteDbCommand::class,
                Console\PurgeSkeletonCommand::class,
                Console\SyncSkeletonCommand::class,
                Console\ServeCommand::class,
                Console\VendorPublishCommand::class,
            ]);
        }
    }

    /**
     * Check if the parallel dependencies are installed.
     *
     * @return bool
     */
    protected function isCollisionDependenciesInstalled(): bool
    {
        return class_exists(CollisionTestCommand::class);
    }
}
