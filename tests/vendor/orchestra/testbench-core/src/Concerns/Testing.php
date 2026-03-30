<?php

namespace Orchestra\Testbench\Concerns;

use Closure;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\DatabaseTruncation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\WithoutEvents;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Support\LazyCollection;
use Orchestra\Testbench\Pest\WithPest;
use PHPUnit\Framework\TestCase as PHPUnitTestCase;

use function Orchestra\Sidekick\once;

/**
 * @api
 */
trait Testing
{
    use ApplicationTestingHooks;
    use CreatesApplication;
    use HandlesAnnotations;
    use HandlesAssertions;
    use HandlesAttributes;
    use HandlesDatabases;
    use HandlesRoutes;
    use InteractsWithMigrations;
    use WithFactories;

    /**
     * Setup the test environment.
     *
     * @internal
     *
     * @return void
     */
    final protected function setUpTheTestEnvironment(): void
    {
        $setUp = once(function () {
            $this->setUpTheApplicationTestingHooks(function () {
                $this->setUpTraits();
            });
        });

        /** @phpstan-ignore class.notFound */
        if ($this instanceof PHPUnitTestCase && static::usesTestingConcern(WithPest::class)) {
            $this->setUpTheEnvironmentUsingPest(); /** @phpstan-ignore method.notFound */
        }

        if ($this->testCaseSetUpCallback instanceof Closure) {
            value($this->testCaseSetUpCallback, $setUp);
        }

        value($setUp);
    }

    /**
     * Clean up the testing environment before the next test.
     *
     * @internal
     *
     * @return void
     */
    final protected function tearDownTheTestEnvironment(): void
    {
        $tearDown = once(function () {
            $this->tearDownTheApplicationTestingHooks(function () {
                if (property_exists($this, 'serverVariables')) {
                    $this->serverVariables = [];
                }

                if (property_exists($this, 'defaultHeaders')) {
                    $this->defaultHeaders = [];
                }

                if (property_exists($this, 'originalExceptionHandler')) {
                    $this->originalExceptionHandler = null;
                }

                if (property_exists($this, 'originalDeprecationHandler')) {
                    $this->originalDeprecationHandler = null;
                }
            });
        });

        /** @phpstan-ignore class.notFound */
        if ($this instanceof PHPUnitTestCase && static::usesTestingConcern(WithPest::class)) {
            $this->tearDownTheEnvironmentUsingPest(); /** @phpstan-ignore method.notFound */
        }

        if ($this->testCaseTearDownCallback instanceof Closure) {
            value($this->testCaseTearDownCallback, $tearDown);
        }

        value($tearDown);

        $this->testCaseSetUpCallback = null;
        $this->testCaseTearDownCallback = null;
    }

    /**
     * Boot the testing helper traits.
     *
     * @internal
     *
     * @param  array<class-string, class-string>  $uses
     * @return array<class-string, class-string>
     */
    final protected function setUpTheTestEnvironmentTraits(array $uses): array
    {
        if (isset($uses[WithWorkbench::class])) {
            $this->setUpWithWorkbench(); /** @phpstan-ignore method.notFound */
        }

        $this->setUpDatabaseRequirements(function () use ($uses) {
            if (isset($uses[RefreshDatabase::class])) {
                $this->refreshDatabase(); /** @phpstan-ignore method.notFound */
            }

            if (isset($uses[DatabaseMigrations::class])) {
                $this->runDatabaseMigrations(); /** @phpstan-ignore method.notFound */
            }

            if (isset($uses[DatabaseTruncation::class])) {
                $this->truncateDatabaseTables(); /** @phpstan-ignore method.notFound */
            }
        });

        if (isset($uses[DatabaseTransactions::class])) {
            $this->beginDatabaseTransaction(); /** @phpstan-ignore method.notFound */
        }

        if (isset($uses[WithoutMiddleware::class])) {
            $this->disableMiddlewareForAllTests(); /** @phpstan-ignore method.notFound */
        }

        if (isset($uses[WithoutEvents::class])) {
            $this->disableEventsForAllTests(); /** @phpstan-ignore method.notFound */
        }

        if (isset($uses[WithFaker::class])) {
            $this->setUpFaker(); /** @phpstan-ignore method.notFound */
        }

        (new LazyCollection(static function () use ($uses) {
            foreach ($uses as $use) {
                yield $use;
            }
        }))
            ->reject(function ($use) {
                /** @var class-string $use */
                return $this->setUpTheTestEnvironmentTraitToBeIgnored($use);
            })->map(static function ($use) {
                /** @var class-string $use */
                return class_basename($use);
            })->each(function ($traitBaseName) {
                /** @var string $traitBaseName */
                if (method_exists($this, $method = 'setUp'.$traitBaseName)) {
                    $this->{$method}();
                }

                if (method_exists($this, $method = 'tearDown'.$traitBaseName)) {
                    $this->beforeApplicationDestroyed(function () use ($method) {
                        $this->{$method}();
                    });
                }
            });

        return $uses;
    }

    /**
     * Determine trait should be ignored from being autoloaded.
     *
     * @param  class-string  $use
     * @return bool
     */
    protected function setUpTheTestEnvironmentTraitToBeIgnored(string $use): bool
    {
        return false;
    }

    /**
     * Reload the application instance with cached routes.
     */
    protected function reloadApplication(): void
    {
        $this->tearDownTheTestEnvironment();
        $this->setUpTheTestEnvironment();
    }

    /**
     * Boot the testing helper traits.
     *
     * @return array<class-string, class-string>
     */
    abstract protected function setUpTraits();
}
