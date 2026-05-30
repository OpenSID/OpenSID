<?php

declare(strict_types=1);

/**
 * Copyright (c) 2022-2025 Andreas Möller
 *
 * For the full copyright and license information, please view
 * the LICENSE.md file that was distributed with this source code.
 *
 * @see https://github.com/ergebnis/json-pointer
 */

namespace Ergebnis\Json\Pointer;

final class Specification
{
    private \Closure $closure;

    /**
     * @param \Closure(JsonPointer):bool $closure
     */
    private function __construct(\Closure $closure)
    {
        $this->closure = $closure;
    }

    public function isSatisfiedBy(JsonPointer $jsonPointer): bool
    {
        $closure = $this->closure;

        return $closure($jsonPointer);
    }

    public static function always(): self
    {
        return new self(static function (): bool {
            return true;
        });
    }

    public static function anyOf(self ...$specifications): self
    {
        return new self(static function (JsonPointer $jsonPointer) use ($specifications): bool {
            foreach ($specifications as $specification) {
                if ($specification->isSatisfiedBy($jsonPointer)) {
                    return true;
                }
            }

            return false;
        });
    }

    /**
     * @param \Closure(JsonPointer):bool $closure
     */
    public static function closure(\Closure $closure): self
    {
        return new self(static function (JsonPointer $jsonPointer) use ($closure): bool {
            return true === $closure($jsonPointer);
        });
    }

    public static function equals(JsonPointer $other): self
    {
        return new self(static function (JsonPointer $jsonPointer) use ($other): bool {
            return $jsonPointer->equals($other);
        });
    }

    public static function never(): self
    {
        return new self(static function (): bool {
            return false;
        });
    }

    public static function not(self $specification): self
    {
        return new self(static function (JsonPointer $jsonPointer) use ($specification): bool {
            return !$specification->isSatisfiedBy($jsonPointer);
        });
    }
}
