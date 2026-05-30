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

final class InvalidJsonEncodeOptions extends \InvalidArgumentException implements Exception
{
    private int $jsonEncodeOptions = 0;

    public static function fromJsonEncodeOptions(int $jsonEncodeOptions): self
    {
        $exception = new self(\sprintf(
            '"%s" is not valid options for json_encode().',
            $jsonEncodeOptions,
        ));

        $exception->jsonEncodeOptions = $jsonEncodeOptions;

        return $exception;
    }

    public function jsonEncodeOptions(): int
    {
        return $this->jsonEncodeOptions;
    }
}
