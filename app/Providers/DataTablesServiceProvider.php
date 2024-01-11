<?php

namespace App\Providers;

use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;
use Yajra\DataTables\Utilities\Config;
use Illuminate\Support\ServiceProvider;
use Yajra\DataTables\Utilities\Request;

class DataTablesServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     */
    public function register(): void
    {
        $this->app->alias('datatables', DataTables::class);
        $this->app->singleton('datatables', fn(): \Yajra\DataTables\DataTables => new DataTables);

        $this->app->singleton('datatables.request', fn(): \Yajra\DataTables\Utilities\Request => new Request);

        $this->app->singleton('datatables.config', Config::class);
    }

    /**
     * Boot the instance, add macros for datatable engines.
     */
    public function boot(): void
    {
        $engines = $this->app['config']['datatables.engines'];

        foreach ($engines as $engine => $class) {
            $engine = Str::camel($engine);
            if (method_exists(DataTables::class, $engine)) {
                continue;
            }
            if (DataTables::hasMacro($engine)) {
                continue;
            }
            DataTables::macro($engine, function () use ($class) {
                if (! call_user_func_array([$class, 'canCreate'], func_get_args())) {
                    throw new \InvalidArgumentException();
                }

                return call_user_func_array([$class, 'create'], func_get_args());
            });
        }
    }
}
