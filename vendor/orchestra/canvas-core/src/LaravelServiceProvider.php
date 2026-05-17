<?php

namespace Orchestra\Canvas\Core;

use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Composer;
use Illuminate\Support\ServiceProvider;

class LaravelServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind('canvas.composer', static fn () => new Composer(new Filesystem));

        $this->app->singleton(PresetManager::class, static fn ($app) => new PresetManager($app));
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array<int, class-string>
     */
    public function provides(): array
    {
        return [
            PresetManager::class,
        ];
    }
}
