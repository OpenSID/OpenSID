<?php

namespace Orchestra\Workbench;

use Illuminate\Support\ServiceProvider;
use Illuminate\View\Compilers\BladeCompiler;

use function Orchestra\Sidekick\join_paths;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->booted(function () {
            $this->loadRoutesFrom((string) realpath(join_paths(__DIR__, '..', 'routes', 'workbench-auth.php')));
        });

        $this->loadViewsFrom((string) realpath(join_paths(__DIR__, '..', 'resources', 'views')), '');

        $this->loadViewComponentsAs('', [
            View\Components\AppLayout::class,
            View\Components\GuestLayout::class,
        ]);

        $this->loadAnonymousComponentsFrom((string) realpath(join_paths(__DIR__, '..', 'resources', 'views', 'components')));

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../public/' => public_path(''),
            ], ['laravel-assets']);
        }
    }

    /** {@inheritDoc} */
    #[\Override]
    protected function loadViewsFrom($path, $namespace)
    {
        if (empty($namespace)) {
            $this->callAfterResolving('view', static function ($view) use ($path) {
                $view->getFinder()->addLocation($path);
            });
        }

        parent::loadViewsFrom($path, $namespace);
    }

    /**
     * Register the given view components with a custom prefix.
     */
    protected function loadAnonymousComponentsFrom(string $path, ?string $prefix = null): void
    {
        $this->callAfterResolving(BladeCompiler::class, static function ($blade) use ($path, $prefix) {
            $blade->anonymousComponentPath($path, $prefix);
        });
    }
}
