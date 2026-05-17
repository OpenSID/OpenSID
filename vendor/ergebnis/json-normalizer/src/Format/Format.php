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

/**
 * @psalm-immutable
 */
final class Format
{
    private bool $hasFinalNewLine;
    private NewLine $newLine;
    private Indent $indent;
    private JsonEncodeOptions $jsonEncodeOptions;

    private function __construct(
        JsonEncodeOptions $jsonEncodeOptions,
        Indent $indent,
        NewLine $newLine,
        bool $hasFinalNewLine
    ) {
        $this->jsonEncodeOptions = $jsonEncodeOptions;
        $this->indent = $indent;
        $this->newLine = $newLine;
        $this->hasFinalNewLine = $hasFinalNewLine;
    }

    public static function create(
        JsonEncodeOptions $jsonEncodeOptions,
        Indent $indent,
        NewLine $newLine,
        bool $hasFinalNewLine
    ): self {
        return new self(
            $jsonEncodeOptions,
            $indent,
            $newLine,
            $hasFinalNewLine,
        );
    }

    public static function fromJson(Json $json): self
    {
        $encoded = $json->encoded();

        return new self(
            JsonEncodeOptions::fromJson($json),
            Indent::fromJson($json),
            NewLine::fromJson($json),
            self::detectHasFinalNewLine($encoded),
        );
    }

    public function jsonEncodeOptions(): JsonEncodeOptions
    {
        return $this->jsonEncodeOptions;
    }

    public function indent(): Indent
    {
        return $this->indent;
    }

    public function newLine(): NewLine
    {
        return $this->newLine;
    }

    public function hasFinalNewLine(): bool
    {
        return $this->hasFinalNewLine;
    }

    public function withJsonEncodeOptions(JsonEncodeOptions $jsonEncodeOptions): self
    {
        $mutated = clone $this;

        $mutated->jsonEncodeOptions = $jsonEncodeOptions;

        return $mutated;
    }

    public function withIndent(Indent $indent): self
    {
        $mutated = clone $this;

        $mutated->indent = $indent;

        return $mutated;
    }

    public function withNewLine(NewLine $newLine): self
    {
        $mutated = clone $this;

        $mutated->newLine = $newLine;

        return $mutated;
    }

    public function withHasFinalNewLine(bool $hasFinalNewLine): self
    {
        $mutated = clone $this;

        $mutated->hasFinalNewLine = $hasFinalNewLine;

        return $mutated;
    }

    private static function detectHasFinalNewLine(string $encoded): bool
    {
        if (\rtrim($encoded, " \t") === \rtrim($encoded)) {
            return false;
        }

        return true;
    }
}
