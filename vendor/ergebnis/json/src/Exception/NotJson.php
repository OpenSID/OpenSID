<?php

declare(strict_types=1);

/**
 * Copyright (c) 2022-2025 Andreas Möller
 *
 * For the full copyright and license information, please view
 * the LICENSE.md file that was distributed with this source code.
 *
 * @see https://github.com/ergebnis/json
 */

namespace Ergebnis\Json\Exception;

final class NotJson extends \InvalidArgumentException implements Exception
{
    public static function value(string $value): self
    {
        return new self(\sprintf(
            'Value "%s" is not a valid JSON string.',
            $value,
        ));
    }
}
