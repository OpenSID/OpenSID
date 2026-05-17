<?php

namespace Orchestra\Testbench\Concerns;

use Illuminate\Support\Collection;
use Orchestra\Testbench\Contracts\Attributes\Actionable as ActionableContract;
use Orchestra\Testbench\Contracts\Attributes\Invokable as InvokableContract;
use Orchestra\Testbench\Features\FeaturesCollection;

/**
 * @internal
 *
 * @phpstan-import-type TTestingFeature from \Orchestra\Testbench\PHPUnit\AttributeParser
 */
trait HandlesAttributes
{
    /**
     * Parse test method attributes.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @param  class-string  $attribute
     * @return \Orchestra\Testbench\Features\FeaturesCollection<int, mixed>
     */
    protected function parseTestMethodAttributes($app, string $attribute): Collection
    {
        /** @var \Illuminate\Support\Collection<int, mixed> $attributes */
        $attributes = $this->resolvePhpUnitAttributes()
            ->filter(static fn ($attributes, string $key) => $key === $attribute && ! empty($attributes))
            ->flatten()
            ->map(function ($instance) use ($app) {
                if ($instance instanceof InvokableContract) {
                    return $instance($app);
                } elseif ($instance instanceof ActionableContract) {
                    return $instance->handle($app, fn ($method, $parameters) => $this->{$method}(...$parameters));
                }
            })->filter()
            ->values();

        return new FeaturesCollection($attributes);
    }

    /**
     * Resolve PHPUnit method attributes.
     *
     * @phpunit-overrides
     *
     * @return \Illuminate\Support\Collection<class-string<TTestingFeature>, array<int, TTestingFeature>>
     */
    abstract protected function resolvePhpUnitAttributes(): Collection;
}
