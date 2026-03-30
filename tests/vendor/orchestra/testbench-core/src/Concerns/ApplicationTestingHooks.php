<?php

namespace Orchestra\Testbench\Concerns;

use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Closure;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\ParallelTesting;
use Orchestra\Testbench\Foundation\Application as Testbench;
use PHPUnit\Framework\TestCase as PHPUnitTestCase;
use Throwable;

trait ApplicationTestingHooks
{
    use InteractsWithMockery;
    use InteractsWithPest;
    use InteractsWithPHPUnit;
    use InteractsWithTestCase;

    /**
     * The Illuminate application instance.
     *
     * @var \Illuminate\Foundation\Application|null
     */
    protected $app;

    /**
     * The callbacks that should be run after the application is created.
     *
     * @var array<int, callable():void>
     */
    protected array $afterApplicationCreatedCallbacks = [];

    /**
     * The callbacks that should be run after the application is refreshed.
     *
     * @var array<int, callable():void>
     */
    protected array $afterApplicationRefreshedCallbacks = [];

    /**
     * The callbacks that should be run before the application is destroyed.
     *
     * @var array<int, callable():void>
     */
    protected array $beforeApplicationDestroyedCallbacks = [];

    /**
     * The exception thrown while running an application destruction callback.
     *
     * @var \Throwable|null
     */
    protected $callbackException;

    /**
     * Indicates if we have made it through the base setUp function.
     *
     * @var bool
     */
    protected $setUpHasRun = false;

    /**
     * Setup the testing hooks.
     *
     * @param  (\Closure():(void))|null  $callback
     * @return void
     */
    final protected function setUpTheApplicationTestingHooks(?Closure $callback = null): void
    {
        if (! $this->app) {
            $this->refreshApplication();

            $this->setUpTheTestEnvironmentUsingTestCase();

            $this->setUpParallelTestingCallbacks();
        }

        $this->setUpHasRun = true;

        /** @var \Illuminate\Foundation\Application $app */
        $app = $this->app;

        $this->callAfterApplicationRefreshedCallbacks();

        if (! \is_null($callback)) {
            \call_user_func($callback);
        }

        $this->callAfterApplicationCreatedCallbacks();

        Model::setEventDispatcher($app['events']);
    }

    /**
     * Teardown the testing hooks.
     *
     * @param  (\Closure():(void))|null  $callback
     * @return void
     *
     * @throws \Throwable
     */
    final protected function tearDownTheApplicationTestingHooks(?Closure $callback = null): void
    {
        if ($this->app) {
            $this->callBeforeApplicationDestroyedCallbacks();

            $this->tearDownTheTestEnvironmentUsingTestCase();

            $this->tearDownParallelTestingCallbacks();

            $this->app?->flush();

            $this->app = null;
        }

        $this->setUpHasRun = false;

        if (! \is_null($callback)) {
            \call_user_func($callback);
        }

        $this->tearDownTheTestEnvironmentUsingMockery();

        Carbon::setTestNow();

        if (class_exists(CarbonImmutable::class)) {
            CarbonImmutable::setTestNow();
        }

        $this->afterApplicationCreatedCallbacks = [];
        $this->beforeApplicationDestroyedCallbacks = [];

        Testbench::flushState();

        if ($this->callbackException) {
            throw $this->callbackException;
        }
    }

    /**
     * Setup parallel testing callback.
     */
    protected function setUpParallelTestingCallbacks(): void
    {
        if ($this instanceof PHPUnitTestCase) {
            /** @phpstan-ignore staticMethod.notFound, argument.type */
            ParallelTesting::callSetUpTestCaseCallbacks($this);
        }
    }

    /**
     * Teardown parallel testing callback.
     */
    protected function tearDownParallelTestingCallbacks(): void
    {
        if ($this instanceof PHPUnitTestCase) {
            /** @phpstan-ignore staticMethod.notFound, argument.type */
            ParallelTesting::callTearDownTestCaseCallbacks($this);
        }
    }

    /**
     * Register a callback to be run after the application is refreshed.
     *
     * @param  callable():void  $callback
     * @return void
     */
    public function afterApplicationRefreshed(callable $callback): void
    {
        $this->afterApplicationRefreshedCallbacks[] = $callback;

        if ($this->setUpHasRun) {
            \call_user_func($callback);
        }
    }

    /**
     * Execute the application's post-refreshed callbacks.
     *
     * @return void
     */
    protected function callAfterApplicationRefreshedCallbacks(): void
    {
        foreach ($this->afterApplicationRefreshedCallbacks as $callback) {
            \call_user_func($callback);
        }
    }

    /**
     * Register a callback to be run after the application is created.
     *
     * @param  callable():void  $callback
     * @return void
     */
    public function afterApplicationCreated(callable $callback): void
    {
        $this->afterApplicationCreatedCallbacks[] = $callback;

        if ($this->setUpHasRun) {
            \call_user_func($callback);
        }
    }

    /**
     * Execute the application's post-creation callbacks.
     *
     * @return void
     */
    protected function callAfterApplicationCreatedCallbacks(): void
    {
        foreach ($this->afterApplicationCreatedCallbacks as $callback) {
            \call_user_func($callback);
        }
    }

    /**
     * Register a callback to be run before the application is destroyed.
     *
     * @param  callable():void  $callback
     * @return void
     */
    public function beforeApplicationDestroyed(callable $callback): void
    {
        array_unshift($this->beforeApplicationDestroyedCallbacks, $callback);
    }

    /**
     * Execute the application's pre-destruction callbacks.
     *
     * @return void
     */
    protected function callBeforeApplicationDestroyedCallbacks(): void
    {
        foreach ($this->beforeApplicationDestroyedCallbacks as $callback) {
            try {
                \call_user_func($callback);
            } catch (Throwable $e) {
                if (! $this->callbackException) {
                    $this->callbackException = $e;
                }
            }
        }
    }

    /**
     * Refresh the application instance.
     *
     * @return void
     */
    abstract protected function refreshApplication();
}
