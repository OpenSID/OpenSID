<?php

namespace Orchestra\Testbench\Attributes;

use Attribute;
use Orchestra\Testbench\Contracts\Attributes\Resolvable as ResolvableContract;
use Orchestra\Testbench\Contracts\Attributes\TestingFeature;

#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
final class Define implements ResolvableContract
{
    /**
     * Construct a new attribute.
     *
     * @param  string  $group
     * @param  string  $method
     */
    public function __construct(
        public string $group,
        public string $method
    ) {}

    /**
     * Resolve the actual attribute class.
     *
     * @return \Orchestra\Testbench\Contracts\Attributes\TestingFeature|null
     */
    public function resolve(): ?TestingFeature
    {
        return match (strtolower($this->group)) {
            'env' => new DefineEnvironment($this->method),
            'db' => new DefineDatabase($this->method),
            'route' => new DefineRoute($this->method),
            default => null,
        };
    }
}
