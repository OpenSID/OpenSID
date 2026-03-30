<?php

namespace Orchestra\Testbench\Foundation\Bootstrap;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Collection;

use function Orchestra\Sidekick\join_paths;

class SyncTestbenchCachedRoutes
{
    /**
     * Bootstrap the given application.
     *
     * @param  \Illuminate\Contracts\Foundation\Application  $app
     * @return void
     */
    public function bootstrap(Application $app): void
    {
        /** @var \Illuminate\Routing\Router $router */
        $router = $app->make('router');

        /** @phpstan-ignore argument.type */
        (new Collection(glob($app->basePath(join_paths('routes', 'testbench-*.php')))))
            ->each(static function ($routeFile) use ($app, $router) { // @phpstan-ignore closure.unusedUse, closure.unusedUse
                require $routeFile;
            });
    }
}
