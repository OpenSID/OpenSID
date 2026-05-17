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

final class JsonEncodeNormalizer implements Normalizer
{
    private Format\JsonEncodeOptions $jsonEncodeOptions;

    public function __construct(Format\JsonEncodeOptions $jsonEncodeOptions)
    {
        $this->jsonEncodeOptions = $jsonEncodeOptions;
    }

    public function normalize(Json $json): Json
    {
        /** @var string $encodedWithJsonEncodeOptions */
        $encodedWithJsonEncodeOptions = \json_encode(
            $json->decoded(),
            $this->jsonEncodeOptions->toInt(),
        );

        return Json::fromString($encodedWithJsonEncodeOptions);
    }
}
