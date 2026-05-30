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

final class WithoutFinalNewLineNormalizer implements Normalizer
{
    public function normalize(Json $json): Json
    {
        $withoutFinalNewLine = \rtrim($json->encoded());

        return Json::fromString($withoutFinalNewLine);
    }
}
