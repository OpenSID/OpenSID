<?php

namespace Orchestra\Testbench\PHPUnit;

use Orchestra\Testbench\Contracts\Attributes\Resolvable as ResolvableContract;
use Orchestra\Testbench\Contracts\Attributes\TestingFeature;
use ReflectionAttribute;
use ReflectionClass;
use ReflectionMethod;

/**
 * @internal
 *
 * @phpstan-type TTestingFeature \Orchestra\Testbench\Contracts\Attributes\TestingFeature
 * @phpstan-type TAttributes TTestingFeature|\Orchestra\Testbench\Contracts\Attributes\Resolvable
 */
class AttributeParser
{
    /**
     * Parse attribute for class.
     *
     * @param  class-string  $className
     * @return array<int, array{key: class-string, instance: object}>
     *
     * @phpstan-return array<int, array{key: class-string<TTestingFeature>, instance: TTestingFeature}>
     *
     * @codeCoverageIgnore
     */
    public static function forClass(string $className): array
    {
        $attributes = [];
        $reflection = new ReflectionClass($className);

        foreach ($reflection->getAttributes() as $attribute) {
            if (! static::validAttribute($attribute->getName())) {
                continue;
            }

            [$name, $instance] = static::resolveAttribute($attribute);

            if (! \is_null($name) && ! \is_null($instance)) {
                $attributes[] = ['key' => $name, 'instance' => $instance];
            }
        }

        $parent = $reflection->getParentClass();

        if ($parent && $parent->isSubclassOf(TestCase::class)) {
            $attributes = [...static::forClass($parent->getName()), ...$attributes];
        }

        return $attributes;
    }

    /**
     * Parse attribute for method.
     *
     * @param  class-string  $className
     * @param  string  $methodName
     * @return array<int, array{key: class-string, instance: object}>
     *
     * @phpstan-return array<int, array{key: class-string<TTestingFeature>, instance: TTestingFeature}>
     *
     * @codeCoverageIgnore
     */
    public static function forMethod(string $className, string $methodName): array
    {
        $attributes = [];

        foreach ((new ReflectionMethod($className, $methodName))->getAttributes() as $attribute) {
            if (! static::validAttribute($attribute->getName())) {
                continue;
            }

            [$name, $instance] = static::resolveAttribute($attribute);

            if (! \is_null($name) && ! \is_null($instance)) {
                $attributes[] = ['key' => $name, 'instance' => $instance];
            }
        }

        return $attributes;
    }

    /**
     * Validate given attribute.
     *
     * @param  class-string|object  $class
     * @return bool
     */
    public static function validAttribute($class): bool
    {
        if (\is_string($class) && ! class_exists($class)) {
            return false;
        }

        $implements = class_implements($class);

        return isset($implements[TestingFeature::class])
            || isset($implements[ResolvableContract::class]);
    }

    /**
     * Resolve given attribute.
     *
     * @param  \ReflectionAttribute  $attribute
     * @return array{0: class-string, 1: object|null}
     *
     * @phpstan-return array{0: class-string<TTestingFeature>|null, 1: TTestingFeature|null}
     */
    protected static function resolveAttribute(ReflectionAttribute $attribute): array
    {
        return rescue(static function () use ($attribute) {
            /** @var TTestingFeature|null $instance */
            $instance = isset(class_implements($attribute->getName())[ResolvableContract::class])
                ? transform($attribute->newInstance(), static fn (ResolvableContract $instance) => $instance->resolve()) /** @phpstan-ignore argument.type */
                : $attribute->newInstance();

            if (\is_null($instance)) {
                return [null, null];
            }

            return [$instance::class, $instance];
        }, [null, null], false);
    }
}
