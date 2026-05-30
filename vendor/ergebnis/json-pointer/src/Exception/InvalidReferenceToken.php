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

namespace Ergebnis\Json\Pointer\Exception;

final class InvalidReferenceToken extends \InvalidArgumentException implements Exception
{
    public static function fromJsonString(string $value): self
    {
        return new self(\sprintf(
            'Value "%s" does not appear to be a valid JSON Pointer reference token.',
            $value,
        ));
    }

    public static function fromInt(int $value): self
    {
        return new self(\sprintf(
            'Value "%d" does not appear to be a valid JSON Pointer array index.',
            $value,
        ));
    }
}
