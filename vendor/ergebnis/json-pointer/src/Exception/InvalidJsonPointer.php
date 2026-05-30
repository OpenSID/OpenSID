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

final class InvalidJsonPointer extends \InvalidArgumentException implements Exception
{
    public static function fromJsonString(string $value): self
    {
        return new self(\sprintf(
            'Value "%s" does not appear to be a valid JSON string representation of a JSON Pointer.',
            $value,
        ));
    }

    public static function fromUriFragmentIdentifierString(string $value): self
    {
        return new self(\sprintf(
            'Value "%s" does not appear to be a valid URI fragment identifier representation of a JSON Pointer.',
            $value,
        ));
    }
}
