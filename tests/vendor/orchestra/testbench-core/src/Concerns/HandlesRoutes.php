<?php

namespace Orchestra\Testbench\Concerns;

use Attribute;
use Closure;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Foundation\Application as LaravelApplication;
use Laravel\SerializableClosure\SerializableClosure;
use Orchestra\Testbench\Attributes\DefineRoute;
use Orchestra\Testbench\Attributes\UsesVendor;
use Orchestra\Testbench\Features\TestingFeature;
use Orchestra\Testbench\Foundation\Application;
use Orchestra\Testbench\Foundation\Bootstrap\SyncTestbenchCachedRoutes;

use function Orchestra\Sidekick\join_paths;
use function Orchestra\Testbench\refresh_router_lookups;
use function Orchestra\Testbench\remote;

/**
 * @internal
 */
trait HandlesRoutes
{
    use InteractsWithPHPUnit;
    use InteractsWithTestCase;

    /**
     * Indicates if we have made it through the requireApplicationCachedRoutes function.
     *
     * @var bool
     */
    protected bool $requireApplicationCachedRoutesHasRun = false;

    /**
     * Setup routes requirements.
     *
     * @param  \Illuminate\Foundation\Application  $app
     */
    protected function setUpApplicationRoutes($app): void
    {
        if ($app->routesAreCached()) {
            return;
        }

        /** @var \Illuminate\Routing\Router $router */
        $router = $app['router'];

        TestingFeature::run(
            testCase: $this,
            default: function () use ($router) {
                $this->defineRoutes($router);

                $router->middleware('web')
                    ->group(fn ($router) => $this->defineWebRoutes($router));
            },
            annotation: fn () => $this->parseTestMethodAnnotations($app, 'define-route', function ($method) use ($router) {
                $this->{$method}($router);
            }),
            attribute: fn () => $this->parseTestMethodAttributes($app, DefineRoute::class),
            pest: function () use ($router) {
                $this->defineRoutesUsingPest($router); // @phpstan-ignore method.notFound

                $router->middleware('web')
                    ->group(fn ($router) => $this->defineWebRoutesUsingPest($router)); /** @phpstan-ignore method.notFound */
            }
        );

        refresh_router_lookups($router);
    }

    /**
     * Define routes setup.
     *
     * @param  \Illuminate\Routing\Router  $router
     * @return void
     */
    protected function defineRoutes($router)
    {
        // Define routes.
    }

    /**
     * Define web routes setup.
     *
     * @param  \Illuminate\Routing\Router  $router
     * @return void
     */
    protected function defineWebRoutes($router)
    {
        // Define routes.
    }

    /**
     * Define stash routes setup.
     *
     * @param  \Closure|string  $route
     * @return void
     */
    protected function defineStashRoutes(Closure|string $route): void
    {
        $this->defineCacheRoutes($route, false);
    }

    /**
     * Define cache routes setup.
     *
     * @param  \Closure|string  $route
     * @param  bool  $cached
     * @return void
     */
    protected function defineCacheRoutes(Closure|string $route, bool $cached = true): void
    {
        static::usesTestingFeature($attribute = new UsesVendor, Attribute::TARGET_METHOD);

        if (
            $this->app instanceof LaravelApplication
            && property_exists($this, 'setUpHasRun')
            && $this->setUpHasRun === true
        ) {
            $attribute->beforeEach($this->app);
        }

        $files = new Filesystem;

        $time = time();

        $basePath = static::applicationBasePath();
        $bootstrapPath = $files->isDirectory(join_paths($basePath, '.laravel'))
            ? join_paths($basePath, '.laravel')
            : join_paths($basePath, 'bootstrap');

        if ($route instanceof Closure) {
            $cached = false;
            /** @var string $serializeRoute */
            $serializeRoute = serialize(SerializableClosure::unsigned($route));
            $stub = $files->get(join_paths(__DIR__, 'stubs', 'routes.stub'));
            $route = str_replace('{{routes}}', (string) json_encode($serializeRoute), $stub);
        }

        $files->put(
            join_paths($basePath, 'routes', "testbench-{$time}.php"), $route
        );

        if ($cached === true) {
            remote('route:cache')->mustRun();

            \assert($files->exists(join_paths($bootstrapPath, 'cache', 'routes-v7.php')) === true);
        }

        if ($this->app instanceof LaravelApplication) {
            $this->reloadApplication();
        }

        $this->requireApplicationCachedRoutes($files, $cached);
    }

    /**
     * Require application cached routes.
     */
    protected function requireApplicationCachedRoutes(Filesystem $files, bool $cached): void
    {
        if ($this->requireApplicationCachedRoutesHasRun === true) {
            return;
        }

        $this->afterApplicationCreated(function () use ($cached) {
            $app = $this->app;

            if ($app instanceof LaravelApplication) {
                if ($cached === true) {
                    require $app->getCachedRoutesPath();
                } else {
                    (new SyncTestbenchCachedRoutes)->bootstrap($app);
                }
            }
        });

        $this->beforeApplicationDestroyed(function () use ($files) {
            if ($this->app instanceof LaravelApplication) {
                $files->delete(
                    $this->app->bootstrapPath(join_paths('cache', 'routes-v7.php')),
                    ...$files->glob($this->app->basePath(join_paths('routes', 'testbench-*.php')))
                );
            }

            sleep(1);
        });

        $this->requireApplicationCachedRoutesHasRun = true;
    }
}
