<?php

namespace Yajra\DataTables;

use Illuminate\Support\ServiceProvider;

class HtmlServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application events.
     */
    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__.'/resources/views', 'datatables');

        if ($this->app->runningInConsole()) {
            $this->publishAssets();
        }
    }

    /**
     * Publish datatables assets.
     */
    protected function publishAssets(): void
    {
        $this->publishes([
            __DIR__.'/resources/views' => base_path('/resources/views/vendor/datatables'),
            __DIR__.'/resources/config/config.php' => config_path('datatables-html.php'),
        ], 'datatables-html');
    }

    /**
     * Register the service provider.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/resources/config/config.php', 'datatables-html');

        $this->app->bind('datatables.html', fn () => $this->app->make(Html\Builder::class));

        DataTables::macro('getHtmlBuilder', fn (): Html\Builder => app('datatables.html'));
    }
}
