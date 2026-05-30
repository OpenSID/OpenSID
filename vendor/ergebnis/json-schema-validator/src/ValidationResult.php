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

/**
 * @psalm-immutable
 */
final class ValidationResult
{
    /**
     * @psalm-var list<ValidationError>
     *
     * @var array<int, ValidationError>
     */
    private array $errors;

    private function __construct(ValidationError ...$errors)
    {
        $this->errors = \array_values($errors);
    }

    public static function create(ValidationError ...$errors): self
    {
        return new self(...$errors);
    }

    public function isValid(): bool
    {
        return [] === $this->errors;
    }

    /**
     * @psalm-return list<ValidationError>
     *
     * @return array<int, ValidationError>
     */
    public function errors(): array
    {
        return $this->errors;
    }
}
