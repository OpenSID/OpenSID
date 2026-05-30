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

namespace Ergebnis\Json\Normalizer;

use Ergebnis\Json\Json;

final class ChainNormalizer implements Normalizer
{
    /**
     * @var array<int, Normalizer>
     */
    private array $normalizers;

    public function __construct(Normalizer ...$normalizers)
    {
        $this->normalizers = \array_values($normalizers);
    }

    public function normalize(Json $json): Json
    {
        return \array_reduce(
            $this->normalizers,
            static function (Json $json, Normalizer $normalizer): Json {
                return $normalizer->normalize($json);
            },
            $json,
        );
    }
}
