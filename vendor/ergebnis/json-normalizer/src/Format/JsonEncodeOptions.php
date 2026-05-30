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

namespace Ergebnis\Json\Normalizer\Format;

use Ergebnis\Json\Json;
use Ergebnis\Json\Normalizer\Exception;

/**
 * @psalm-immutable
 */
final class JsonEncodeOptions
{
    private int $value;

    private function __construct(int $value)
    {
        $this->value = $value;
    }

    public static function default(): self
    {
        return new self(\JSON_PRETTY_PRINT | \JSON_UNESCAPED_SLASHES | \JSON_UNESCAPED_UNICODE);
    }

    /**
     * @throws Exception\InvalidJsonEncodeOptions
     */
    public static function fromInt(int $value): self
    {
        if (0 > $value) {
            throw Exception\InvalidJsonEncodeOptions::fromJsonEncodeOptions($value);
        }

        return new self($value);
    }

    public static function fromJson(Json $json): self
    {
        $jsonEncodeOptions = 0;

        if (false === \strpos($json->encoded(), '\/')) {
            $jsonEncodeOptions = \JSON_UNESCAPED_SLASHES;
        }

        if (1 !== \preg_match('/(\\\\+)u([0-9a-f]{4})/i', $json->encoded())) {
            $jsonEncodeOptions |= \JSON_UNESCAPED_UNICODE;
        }

        return self::fromInt($jsonEncodeOptions);
    }

    public function toInt(): int
    {
        return $this->value;
    }
}
