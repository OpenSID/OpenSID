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

final class InvalidIndentSize extends \InvalidArgumentException implements Exception
{
    private int $size = 0;
    private int $minimumSize = 0;

    public static function fromSizeAndMinimumSize(
        int $size,
        int $minimumSize
    ): self {
        $exception = new self(\sprintf(
            'Size needs to be greater than %d, but %d is not.',
            $minimumSize - 1,
            $size,
        ));

        $exception->size = $size;
        $exception->minimumSize = $minimumSize;

        return $exception;
    }

    public function size(): int
    {
        return $this->size;
    }

    public function minimumSize(): int
    {
        return $this->minimumSize;
    }
}
