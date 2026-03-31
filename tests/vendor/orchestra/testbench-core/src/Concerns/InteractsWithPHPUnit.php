<?php

namespace Orchestra\Testbench\Concerns;

use Closure;
use Illuminate\Support\Collection;
use Orchestra\Testbench\PHPUnit\AttributeParser;
use PHPUnit\Framework\TestCase as PHPUnitTestCase;
use PHPUnit\Metadata\Annotation\Parser\Registry as PHPUnit10Registry;
use PHPUnit\Util\Annotation\Registry as PHPUnit9Registry;
use ReflectionClass;

use function Orchestra\Sidekick\phpunit_version_compare;

/**
 * @internal
 *
 * @phpstan-import-type TTestingFeature from \Orchestra\Testbench\PHPUnit\AttributeParser
 */
trait InteractsWithPHPUnit
{
    use InteractsWithTestCase;

    /**
     * The cached test case setUp resolver.
     *
     * @var (\Closure(\Closure):(void))|null
     */
    protected $testCaseSetUpCallback;

    /**
     * The cached test case tearDown resolver.
     *
     * @var (\Closure(\Closure):(void))|null
     */
    protected $testCaseTearDownCallback;

    /**
     * The cached class attributes for test case.
     *
     * @var array<string, array<int, array{key: class-string, instance: object}>>
     *
     * @phpstan-var array<string, array<int, array{key: class-string<TTestingFeature>, instance: TTestingFeature}>>
     */
    protected static array $cachedTestCaseClassAttributes = [];

    /**
     * The cached method attributes for test case.
     *
     * @var array<string, array<int, array{key: class-string, instance: object}>>
     *
     * @phpstan-var array<string, array<int, array{key: class-string<TTestingFeature>, instance: TTestingFeature}>>
     */
    protected static array $cachedTestCaseMethodAttributes = [];

    /**
     * Determine if the trait is used within testing.
     *
     * @return bool
     */
    public function isRunningTestCase(): bool
    {
        return $this instanceof PHPUnitTestCase || static::usesTestingConcern();
    }

    /**
     * Resolve PHPUnit test class name.
     *
     * @internal
     *
     * @return class-string|null
     *
     * @codeCoverageIgnore
     */
    public function resolvePhpUnitTestClassName(): ?string
    {
        $instance = new ReflectionClass($this);

        if (! $this instanceof PHPUnitTestCase || $instance->isAnonymous()) {
            return null;
        }

        return $instance->getName();
    }

    /**
     * Resolve PHPUnit test method name.
     *
     * @internal
     *
     * @return string|null
     *
     * @codeCoverageIgnore
     */
    public function resolvePhpUnitTestMethodName(): ?string
    {
        if (! $this instanceof PHPUnitTestCase) {
            return null;
        }

        return phpunit_version_compare('10', '>=')
            ? $this->name() // @phpstan-ignore-line
            : $this->getName(false); // @phpstan-ignore-line
    }

    /**
     * Resolve PHPUnit method annotations.
     *
     * @phpunit-overrides
     *
     * @return \Illuminate\Support\Collection<string, mixed>
     */
    protected function resolvePhpUnitAnnotations(): Collection
    {
        $className = $this->resolvePhpUnitTestClassName();
        $methodName = $this->resolvePhpUnitTestMethodName();

        if (\is_null($className) || \is_null($methodName)) {
            return new Collection;
        }

        $registry = phpunit_version_compare('10', '>=')
            ? PHPUnit10Registry::getInstance() // @phpstan-ignore-line
            : PHPUnit9Registry::getInstance(); // @phpstan-ignore-line

        /** @var array<string, mixed> $annotations */
        $annotations = rescue(
            fn () => $registry->forMethod($className, $methodName)->symbolAnnotations(),
            [],
            false
        );

        return new Collection($annotations);
    }

    /**
     * Resolve PHPUnit method attributes.
     *
     * @phpunit-overrides
     *
     * @return \Illuminate\Support\Collection<class-string, array<int, object>>
     *
     * @phpstan-return \Illuminate\Support\Collection<class-string<TTestingFeature>, array<int, TTestingFeature>>
     */
    protected function resolvePhpUnitAttributes(): Collection
    {
        $className = $this->resolvePhpUnitTestClassName();
        $methodName = $this->resolvePhpUnitTestMethodName();

        if (\is_null($className)) {
            return new Collection; /** @phpstan-ignore return.type */
        }

        return static::resolvePhpUnitAttributesForMethod($className, $methodName);
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
    protected static function resolvePhpUnitAttributesForMethod(string $className, ?string $methodName = null): Collection
    {
        if (! isset(static::$cachedTestCaseClassAttributes[$className])) {
            static::$cachedTestCaseClassAttributes[$className] = rescue(
                static fn () => AttributeParser::forClass($className), [], false
            );
        }

        if (! \is_null($methodName) && ! isset(static::$cachedTestCaseMethodAttributes["{$className}:{$methodName}"])) {
            static::$cachedTestCaseMethodAttributes["{$className}:{$methodName}"] = rescue(
                static fn () => AttributeParser::forMethod($className, $methodName), [], false
            );
        }

        /** @var \Illuminate\Support\Collection<class-string<TTestingFeature>, array<int, TTestingFeature>> $attributes */
        $attributes = (new Collection(array_merge(
            static::$testCaseTestingFeatures,
            static::$cachedTestCaseClassAttributes[$className],
            static::$testCaseMethodTestingFeatures,
            ! \is_null($methodName) ? static::$cachedTestCaseMethodAttributes["{$className}:{$methodName}"] : [],
        )))->groupBy('key')
            ->map(static function ($attributes) {
                /** @var \Illuminate\Support\Collection<int, array{key: class-string<TTestingFeature>, instance: TTestingFeature}> $attributes */
                return $attributes->map(static function ($attribute) {
                    /** @var array{key: class-string<TTestingFeature>, instance: TTestingFeature} $attribute */
                    return $attribute['instance'];
                });
            });

        return $attributes;
    }

    /**
     * Define the setUp environment using callback.
     *
     * @param  \Closure(\Closure):void  $setUp
     * @return void
     *
     * @codeCoverageIgnore
     */
    public function setUpTheEnvironmentUsing(Closure $setUp): void
    {
        $this->testCaseSetUpCallback = $setUp;
    }

    /**
     * Define the tearDown environment using callback.
     *
     * @param  \Closure(\Closure):void  $tearDown
     * @return void
     *
     * @codeCoverageIgnore
     */
    public function tearDownTheEnvironmentUsing(Closure $tearDown): void
    {
        $this->testCaseTearDownCallback = $tearDown;
    }

    /**
     * Prepare the testing environment before the running the test case.
     *
     * @return void
     *
     * @codeCoverageIgnore
     */
    public static function setUpBeforeClassUsingPHPUnit(): void
    {
        static::cachedUsesForTestCase();
    }

    /**
     * Clean up the testing environment before the next test case.
     *
     * @return void
     *
     * @codeCoverageIgnore
     */
    public static function tearDownAfterClassUsingPHPUnit(): void
    {
        static::$cachedTestCaseUses = null;
        static::$cachedTestCaseClassAttributes = [];
        static::$cachedTestCaseMethodAttributes = [];

        $registry = phpunit_version_compare('10', '>=')
            ? PHPUnit10Registry::getInstance() // @phpstan-ignore-line
            : PHPUnit9Registry::getInstance(); // @phpstan-ignore-line

        (function () {
            $this->classDocBlocks = [];
            $this->methodDocBlocks = [];
        })->call($registry);
    }
}
