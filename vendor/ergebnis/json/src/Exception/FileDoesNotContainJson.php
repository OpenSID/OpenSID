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

final class FileDoesNotContainJson extends \InvalidArgumentException
{
    public static function file(string $name): self
    {
        return new self(\sprintf(
            'File "%s" does not contain a valid JSON string.',
            $name,
        ));
    }
}
