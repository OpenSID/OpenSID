<?php

declare(strict_types=1);

/**
 * Copyright (c) 2018-2025 Andreas Möller
 *
 * For the full copyright and license information, please view
 * the LICENSE.md file that was distributed with this source code.
 *
 * @see https://github.com/ergebnis/json-normalizer
 */

namespace Ergebnis\Json\Normalizer\Exception;

final class DependencyMissing extends \RuntimeException implements Exception
{
    public static function for(
        string $className,
        string $packageName
    ): self {
        return new self(\sprintf(
            <<<'TXT'
To use "%s", the package "%s" is required.

Run

composer require "%s"

to install it.
TXT,
            $className,
            $packageName,
            $packageName,
        ));
    }
}
