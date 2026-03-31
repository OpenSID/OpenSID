<?php

namespace Orchestra\Testbench\Concerns;

use Closure;
use Orchestra\Testbench\Features\TestingFeature;

/**
 * @deprecated
 */
trait HandlesTestingFeature
{
    /**
     * Resolve available testing features for Testbench.
     *
     * @param  (\Closure():(void))|null  $default
     * @param  (\Closure():(void))|null  $annotation
     * @param  (\Closure():(mixed))|null  $attribute
     * @param  (\Closure(\Closure|null):(mixed))|null  $pest
     * @return \Illuminate\Support\Fluent
     *
     * @deprecated
     *
     * @codeCoverageIgnore
     */
    protected function resolveTestbenchTestingFeature(
        ?Closure $default = null,
        ?Closure $annotation = null,
        ?Closure $attribute = null,
        ?Closure $pest = null
    ) {
        return TestingFeature::run(
            testCase: $this,
            default: $default,
            annotation: $annotation,
            attribute: $attribute,
            pest: $pest
        );
    }
}
