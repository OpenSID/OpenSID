<?php

declare(strict_types=1);

/**
 * Copyright (c) 2018-2025 Andreas Möller
 *
 * For the full copyright and license information, please view
 * the LICENSE.md file that was distributed with this source code.
 *
 * @see https://github.com/ergebnis/composer-normalize
 */

namespace Ergebnis\Composer\Normalize\Exception;

final class ShouldNotHappen extends \LogicException implements Exception
{
    public static function create(): self
    {
        return new self('This should not happen.');
    }
}
