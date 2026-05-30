<?php

declare(strict_types=1);

/**
 * Copyright (c) 2021-2025 Andreas Möller
 *
 * For the full copyright and license information, please view
 * the LICENSE.md file that was distributed with this source code.
 *
 * @see https://github.com/ergebnis/json-schema-validator
 */

namespace Ergebnis\Json\SchemaValidator;

use Ergebnis\Json\Pointer;

/**
 * @psalm-immutable
 */
final class ValidationError
{
    private Pointer\JsonPointer $jsonPointer;
    private Message $message;

    private function __construct(
        Pointer\JsonPointer $jsonPointer,
        Message $message
    ) {
        $this->message = $message;
        $this->jsonPointer = $jsonPointer;
    }

    public static function create(
        Pointer\JsonPointer $jsonPointer,
        Message $message
    ): self {
        return new self(
            $jsonPointer,
            $message,
        );
    }

    public function jsonPointer(): Pointer\JsonPointer
    {
        return $this->jsonPointer;
    }

    public function message(): Message
    {
        return $this->message;
    }
}
