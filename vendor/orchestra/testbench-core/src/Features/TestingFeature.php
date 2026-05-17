<?php

namespace Orchestra\Testbench\Features;

use Closure;
use Illuminate\Support\Fluent;
use Orchestra\Testbench\Concerns\HandlesAnnotations;
use Orchestra\Testbench\Concerns\HandlesAttributes;
use Orchestra\Testbench\Pest\WithPest;
use PHPUnit\Framework\TestCase as PHPUnitTestCase;

use function Orchestra\Sidekick\once;

/**
 * @internal
 */
final class TestingFeature
{
    /**
     * Resolve available testing features for Testbench.
     *
     * @param  object  $testCase
     * @param  (\Closure():(void))|null  $default
     * @param  (\Closure():(void))|null  $annotation
     * @param  (\Closure():(mixed))|null  $attribute
     * @param  (\Closure(\Closure|null):(mixed))|null  $pest
     * @return \Illuminate\Support\Fluent<array-key, mixed>
     */
    public static function run(
        object $testCase,
        ?Closure $default = null,
        ?Closure $annotation = null,
        ?Closure $attribute = null,
        ?Closure $pest = null
    ): Fluent {
        /** @var \Illuminate\Support\Fluent<string, \Orchestra\Testbench\Features\FeaturesCollection> $result */
        $result = new Fluent(['attribute' => new FeaturesCollection]);

        $defaultResolver = once($default);

        if ($testCase instanceof PHPUnitTestCase) {
            /** @phpstan-ignore staticMethod.notFound */
            if ($testCase::usesTestingConcern(HandlesAnnotations::class)) {
                value($annotation, $defaultResolver);
            }

            /** @phpstan-ignore staticMethod.notFound */
            if ($testCase::usesTestingConcern(HandlesAttributes::class)) {
                $result['attribute'] = value($attribute, $defaultResolver);
            }

        }

        if (
            $testCase instanceof PHPUnitTestCase
            && $pest instanceof Closure
            && $testCase::usesTestingConcern(WithPest::class) /** @phpstan-ignore staticMethod.notFound, class.notFound */
        ) {
            value($pest, $defaultResolver);
        }

        $defaultResolver();

        return $result;
    }
}
