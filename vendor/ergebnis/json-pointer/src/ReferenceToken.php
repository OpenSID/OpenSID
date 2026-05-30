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

namespace Ergebnis\Json\Pointer;

/**
 * @psalm-immutable
 *
 * @see https://datatracker.ietf.org/doc/html/rfc6901
 */
final class ReferenceToken
{
    private string $value;

    private function __construct(string $value)
    {
        $this->value = $value;
    }

    /**
     * @throws Exception\InvalidReferenceToken
     */
    public static function fromInt(int $value): self
    {
        if (0 > $value) {
            throw Exception\InvalidReferenceToken::fromInt($value);
        }

        return new self((string) $value);
    }

    /**
     * @see https://datatracker.ietf.org/doc/html/rfc6901#section-5
     *
     * @throws Exception\InvalidReferenceToken
     */
    public static function fromJsonString(string $value): self
    {
        if (1 !== \preg_match(Pattern::JSON_STRING_REFERENCE_TOKEN, $value)) {
            throw Exception\InvalidReferenceToken::fromJsonString($value);
        }

        return new self(\str_replace(
            [
                '~1',
                '~0',
            ],
            [
                '/',
                '~',
            ],
            $value,
        ));
    }

    public static function fromString(string $value): self
    {
        return new self($value);
    }

    /**
     * @throws Exception\InvalidReferenceToken
     */
    public static function fromUriFragmentIdentifierString(string $value): self
    {
        if (1 !== \preg_match(Pattern::URI_FRAGMENT_IDENTIFIER_REFERENCE_TOKEN, $value)) {
            throw Exception\InvalidReferenceToken::fromJsonString($value);
        }

        return new self(\str_replace(
            [
                '~1',
                '~0',
            ],
            [
                '/',
                '~',
            ],
            \rawurldecode($value),
        ));
    }

    public function toJsonString(): string
    {
        return \str_replace(
            [
                '~',
                '/',
            ],
            [
                '~0',
                '~1',
            ],
            $this->value,
        );
    }

    public function toUriFragmentIdentifierString(): string
    {
        return \rawurlencode(\str_replace(
            [
                '~',
                '/',
            ],
            [
                '~0',
                '~1',
            ],
            $this->value,
        ));
    }

    public function toString(): string
    {
        return $this->value;
    }

    public function equals(self $other): bool
    {
        return $this->value === $other->value;
    }
}
