<?php

namespace Orchestra\Testbench\Concerns;

use Attribute;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Orchestra\Testbench\Contracts\Attributes\AfterAll as AfterAllContract;
use Orchestra\Testbench\Contracts\Attributes\AfterEach as AfterEachContract;
use Orchestra\Testbench\Contracts\Attributes\BeforeAll as BeforeAllContract;
use Orchestra\Testbench\Contracts\Attributes\BeforeEach as BeforeEachContract;
use Orchestra\Testbench\Contracts\Attributes\Resolvable as ResolvableContract;
use Orchestra\Testbench\PHPUnit\AttributeParser;

use function Orchestra\Testbench\laravel_or_fail;

/**
 * @internal
 *
 * @phpstan-import-type TTestingFeature from \Orchestra\Testbench\PHPUnit\AttributeParser
 * @phpstan-import-type TAttributes from \Orchestra\Testbench\PHPUnit\AttributeParser
 */
trait InteractsWithTestCase
{
    /**
     * The cached uses for test case.
     *
     * @var array<class-string, class-string>|null
     */
    protected static ?array $cachedTestCaseUses = null;

    /**
     * The method attributes for test case.
     *
     * @var array<int, array{key: class-string, instance: object}>
     *
     * @phpstan-var array<int, array{key: class-string<TTestingFeature>, instance: TTestingFeature}>
     */
    protected static array $testCaseTestingFeatures = [];

    /**
     * The method attributes for test case's method.
     *
     * @var array<int, array{key: class-string, instance: object}>
     *
     * @phpstan-var array<int, array{key: class-string<TTestingFeature>, instance: TTestingFeature}>
     */
    protected static array $testCaseMethodTestingFeatures = [];

    /**
     * Determine if the trait is using given trait (or default to \Orchestra\Testbench\Concerns\Testing trait).
     *
     * @param  class-string|null  $trait
     * @return bool
     */
    public static function usesTestingConcern(?string $trait = null): bool
    {
        return isset(static::cachedUsesForTestCase()[$trait ?? Testing::class]);
    }

    /**
     * Determine if the trait is using \Illuminate\Foundation\Testing\LazilyRefreshDatabase or \Illuminate\Foundation\Testing\RefreshDatabase trait.
     *
     * @return bool
     */
    public static function usesRefreshDatabaseTestingConcern(): bool
    {
        return static::usesTestingConcern(LazilyRefreshDatabase::class) || static::usesTestingConcern(RefreshDatabase::class);
    }

    /**
     * Define or get the cached uses for test case.
     *
     * @return array<class-string, class-string>
     */
    public static function cachedUsesForTestCase(): array
    {
        if (\is_null(static::$cachedTestCaseUses)) {
            /** @var array<class-string, class-string> $uses */
            $uses = array_flip(class_uses_recursive(static::class));

            static::$cachedTestCaseUses = $uses;
        }

        return static::$cachedTestCaseUses;
    }

    /**
     * Uses testing feature (attribute) on the current test.
     *
     * @param  object  $attribute
     * @param  int  $flag
     *
     * @phpstan-param TAttributes $attribute
     *
     * @return void
     */
    public static function usesTestingFeature($attribute, int $flag = Attribute::TARGET_CLASS): void
    {
        if (! AttributeParser::validAttribute($attribute)) {
            return;
        }

        $attribute = $attribute instanceof ResolvableContract ? $attribute->resolve() : $attribute;

        if (\is_null($attribute)) {
            return;
        }

        if ($flag & Attribute::TARGET_CLASS) {
            static::$testCaseTestingFeatures[] = [
                'key' => $attribute::class,
                'instance' => $attribute,
            ];
        } elseif ($flag & Attribute::TARGET_METHOD) {
            static::$testCaseMethodTestingFeatures[] = [
                'key' => $attribute::class,
                'instance' => $attribute,
            ];
        }
    }

    /**
     * Resolve PHPUnit method attributes for specific method.
     *
     * @phpunit-overrides
     *
     * @param  class-string  $className
     * @param  string|null  $methodName
     * @return \Illuminate\Support\Collection<class-string, array<int, object>>
     *
     * @phpstan-return \Illuminate\Support\Collection<class-string<TTestingFeature>, array<int, TTestingFeature>>
     */
    abstract protected static function resolvePhpUnitAttributesForMethod(string $className, ?string $methodName = null): Collection;

    /**
     * Prepare the testing environment before the running the test case.
     *
     * @return void
     */
    protected function setUpTheTestEnvironmentUsingTestCase(): void
    {
        $app = laravel_or_fail($this->app);

        $this->resolvePhpUnitAttributes()
            ->flatten()
            ->filter(static fn ($instance) => $instance instanceof BeforeEachContract)
            ->map(static function ($instance) use ($app) {
                $instance->beforeEach($app);
            });
    }

    /**
     * Prepare the testing environment before the running the test case.
     *
     * @return void
     */
    protected function tearDownTheTestEnvironmentUsingTestCase(): void
    {
        $app = laravel_or_fail($this->app);

        $this->resolvePhpUnitAttributes()
            ->flatten()
            ->filter(static fn ($instance) => $instance instanceof AfterEachContract)
            ->map(static function ($instance) use ($app) {
                $instance->afterEach($app);
            });

        static::$testCaseMethodTestingFeatures = [];
    }

    /**
     * Prepare the testing environment before the running the test case.
     *
     * @return void
     *
     * @codeCoverageIgnore
     */
    public static function setUpBeforeClassUsingTestCase(): void
    {
        static::resolvePhpUnitAttributesForMethod(static::class)
            ->flatten()
            ->filter(static fn ($instance) => $instance instanceof BeforeAllContract)
            ->map(static function ($instance) {
                $instance->beforeAll();
            });
    }

    /**
     * Clean up the testing environment before the next test case.
     *
     * @return void
     *
     * @codeCoverageIgnore
     */
    public static function tearDownAfterClassUsingTestCase(): void
    {
        static::resolvePhpUnitAttributesForMethod(static::class)
            ->flatten()
            ->filter(static fn ($instance) => $instance instanceof AfterAllContract)
            ->map(static function ($instance) {
                $instance->afterAll();
            });

        /** @phpstan-ignore-next-line */
        static::$latestResponse = null;
        static::$testCaseTestingFeatures = [];
    }
}
