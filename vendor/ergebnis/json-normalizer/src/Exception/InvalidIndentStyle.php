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

final class InvalidIndentStyle extends \InvalidArgumentException implements Exception
{
    private string $style = '';

    /**
     * @var list<string>
     */
    private array $allowedStyles = [];

    public static function fromStyleAndAllowedStyles(
        string $style,
        string ...$allowedStyles
    ): self {
        $exception = new self(\sprintf(
            'Style needs to be one of "%s", but "%s" is not.',
            \implode(
                '", "',
                $allowedStyles,
            ),
            $style,
        ));

        $exception->style = $style;
        $exception->allowedStyles = \array_values($allowedStyles);

        return $exception;
    }

    public function style(): string
    {
        return $this->style;
    }

    /**
     * @return list<string>
     */
    public function allowedStyles(): array
    {
        return $this->allowedStyles;
    }
}
