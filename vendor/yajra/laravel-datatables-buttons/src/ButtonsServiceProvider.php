<?php

namespace Yajra\DataTables;

use Illuminate\Support\ServiceProvider;
use Maatwebsite\Excel\ExcelServiceProvider;
use Yajra\DataTables\Generators\DataTablesHtmlCommand;
use Yajra\DataTables\Generators\DataTablesMakeCommand;
use Yajra\DataTables\Generators\DataTablesScopeCommand;

class ButtonsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application events.
     */
    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__.'/resources/views', 'datatables');

        $this->publishAssets();

        $this->registerCommands();
    }

    /**
     * Publish datatables assets.
     */
    protected function publishAssets(): void
    {
        $this->publishes([
            __DIR__.'/config/config.php' => config_path('datatables-buttons.php'),
        ], 'datatables-buttons');

        $this->publishes([
            __DIR__.'/resources/assets/buttons.server-side.js' => public_path('vendor/datatables/buttons.server-side.js'),
        ], 'datatables-buttons');

        $this->publishes([
            __DIR__.'/resources/views' => base_path('/resources/views/vendor/datatables'),
        ], 'datatables-buttons');
    }

    /**
     * Register datatables commands.
     */
    protected function registerCommands(): void
    {
        $this->commands(DataTablesMakeCommand::class);
        $this->commands(DataTablesScopeCommand::class);
        $this->commands(DataTablesHtmlCommand::class);
    }

    /**
     * Register the service provider.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/config/config.php', 'datatables-buttons');

        $this->app->register(HtmlServiceProvider::class);

        if (class_exists(ExcelServiceProvider::class)) {
            $this->app->register(ExcelServiceProvider::class);
        }
    }
}
