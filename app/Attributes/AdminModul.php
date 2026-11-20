<?php

namespace App\Attributes;

use Attribute;
use Exception;
use Illuminate\Support\Str;
use ReflectionAttribute;
use ReflectionClass;
use ReflectionMethod;

#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_METHOD)]
class AdminModul
{
    private static array $cache = [];

    private static array $resolved = [];

    public function __construct(
        public readonly string $modul,
        public readonly ?string $subModul = null,
        public readonly ?string $kategoriPengaturan = null,
        public readonly ?string $controller = null,
    ) {}

    public static function resolve(?string $class, ?string $method = null): ?self
    {
        if ($class === null) {
            return null;
        }

        $key = $class . ($method !== null ? '::' . $method : '');

        if (array_key_exists($key, self::$resolved)) {
            return self::$resolved[$key];
        }

        $attribute = $method !== null
            ? self::forMethod($class, $method)[0] ?? self::resolveClassAttribute($class)
            : self::resolveClassAttribute($class);

        $name = self::name($class);

        return self::$resolved[$key] = new self(
            modul: $attribute->modul ?? $name,
            subModul: $attribute?->subModul,
            kategoriPengaturan: $attribute?->kategoriPengaturan,
            controller: $attribute->controller ?? $name,
        );
    }

    /** @return list<self> */
    public static function forClass(string $class): array
    {
        return self::$cache[$class] ??= self::instances(self::reflectClass($class));
    }

    /** @return list<self> */
    public static function forMethod(string $class, string $method): array
    {
        $key = "{$class}::{$method}";

        return self::$cache[$key] ??= self::instances(self::reflectMethod($class, $method));
    }

    public static function name(?string $class): ?string
    {
        if ($class === null) {
            return null;
        }

        return strtolower(Str::beforeLast(class_basename($class), 'Controller'));
    }

    public static function flush(): void
    {
        self::$cache    = [];
        self::$resolved = [];
    }

    private static function resolveClassAttribute(string $class): ?self
    {
        try {
            $reflection = new ReflectionClass($class);

            do {
                $attributes = self::forClass($reflection->getName());

                if ($attributes !== []) {
                    return $attributes[0];
                }

                foreach ($reflection->getTraits() as $trait) {
                    $attributes = self::forClass($trait->getName());

                    if ($attributes !== []) {
                        return $attributes[0];
                    }
                }
            } while ($reflection = $reflection->getParentClass());
        } catch (Exception) {
            // Kelas tidak bisa direfleksikan — abaikan
        }

        return null;
    }

    /** @return list<ReflectionAttribute<self>> */
    private static function reflectClass(string $class): array
    {
        try {
            return (new ReflectionClass($class))->getAttributes(self::class);
        } catch (Exception) {
            return [];
        }
    }

    /** @return list<ReflectionAttribute<self>> */
    private static function reflectMethod(string $class, string $method): array
    {
        try {
            return (new ReflectionMethod($class, $method))->getAttributes(self::class);
        } catch (Exception) {
            return [];
        }
    }

    /** @param list<ReflectionAttribute<self>> $attributes @return list<self> */
    private static function instances(array $attributes): array
    {
        return array_map(
            static fn (ReflectionAttribute $attribute) => $attribute->newInstance(),
            $attributes,
        );
    }
}
