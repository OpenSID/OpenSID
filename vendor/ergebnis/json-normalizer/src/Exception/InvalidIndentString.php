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

final class InvalidIndentString extends \InvalidArgumentException implements Exception
{
    private string $string = '';

    public static function fromString(string $string): self
    {
        $exception = new self(\sprintf(
            '"%s" is not a valid indent string',
            $string,
        ));

        $exception->string = $string;

        return $exception;
    }

    public function string(): string
    {
        return $this->string;
    }
}
