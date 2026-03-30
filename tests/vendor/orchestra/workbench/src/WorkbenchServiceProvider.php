<?php

namespace Orchestra\Workbench;

use Composer\InstalledVersions;
use Illuminate\Contracts\Events\Dispatcher as EventDispatcher;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Http\Kernel as HttpKernel;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Foundation\Console\AboutCommand;
use Illuminate\Support\Composer;
use Illuminate\Support\ServiceProvider;
use Orchestra\Canvas\Core\PresetManager;
use Orchestra\Testbench\Foundation\Events\ServeCommandEnded;
use Orchestra\Testbench\Foundation\Events\ServeCommandStarted;

use function Orchestra\Sidekick\join_paths;

class WorkbenchServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind('workbench.composer', static fn () => new Composer(new Filesystem));
        $this->app->singleton(Contracts\RecipeManager::class, static fn (Application $app) => new RecipeManager($app));

        $this->callAfterResolving(PresetManager::class, static function ($manager) {
            $manager->extend('workbench', static fn (Application $app) => new GeneratorPreset($app));

            $manager->setDefaultDriver('workbench');
        });

        AboutCommand::add('Workbench', static fn () => array_filter([
            'Version' => InstalledVersions::getPrettyVersion('orchestra/workbench'),
        ]));
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->booted(function () {
            $this->loadRoutesFrom((string) realpath(join_paths(__DIR__, '..', 'routes', 'workbench.php')));
        });

        $this->app->make(HttpKernel::class)->pushMiddleware(Http\Middleware\CatchDefaultRoute::class);

        if ($this->app->runningInConsole()) {
            $this->commands([
                Console\BuildCommand::class,
                Console\CreateSqliteDbCommand::class,
                Console\DropSqliteDbCommand::class,
                Console\InstallCommand::class,
                Console\DevToolCommand::class,
                Console\PurgeSkeletonCommand::class,
                Console\SyncSkeletonCommand::class,
            ]);

            tap($this->app->make('events'), static function (EventDispatcher $event) {
                $event->listen(ServeCommandStarted::class, [Listeners\AddAssetSymlinkFolders::class, 'handle']);
                $event->listen(ServeCommandEnded::class, [Listeners\RemoveAssetSymlinkFolders::class, 'handle']);
            });
        }
    }
}
