<?php

namespace App\Providers;

use Spatie\Activitylog\LogBatch;
use Spatie\Activitylog\ActivityLogger;
use Spatie\Activitylog\CauserResolver;
use Illuminate\Support\ServiceProvider;
use Spatie\Activitylog\ActivityLogStatus;
use Spatie\Activitylog\CleanActivitylogCommand;

class ActivitylogServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->configure('activitylog');

        $this->app->bind(ActivityLogger::class);
        $this->app->scoped(LogBatch::class);
        $this->app->scoped(CauserResolver::class);
        $this->app->scoped(ActivityLogStatus::class);
    }

    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                CleanActivitylogCommand::class,
            ]);
        }
    }
}