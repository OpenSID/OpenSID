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
final class NewLine
{
    private string $value;

    private function __construct(string $value)
    {
        $this->value = $value;
    }

    /**
     * @throws Exception\InvalidNewLineString
     */
    public static function fromString(string $value): self
    {
        if ("\n" !== $value && "\r" !== $value && "\r\n" !== $value) {
            throw Exception\InvalidNewLineString::fromString($value);
        }

        return new self($value);
    }

    public static function fromJson(Json $json): self
    {
        if (1 === \preg_match('/(?P<newLine>\r\n|\n|\r)/', $json->encoded(), $match)) {
            return self::fromString($match['newLine']);
        }

        return self::fromString(\PHP_EOL);
    }

    public function toString(): string
    {
        return $this->value;
    }
}
