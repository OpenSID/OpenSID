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
use Ergebnis\Json\Printer;

final class FormatNormalizer implements Normalizer
{
    private Format\Format $format;
    private Printer\PrinterInterface $printer;

    public function __construct(
        Printer\PrinterInterface $printer,
        Format\Format $format
    ) {
        $this->printer = $printer;
        $this->format = $format;
    }

    public function normalize(Json $json): Json
    {
        $normalized = $this->printer->print(
            \json_encode(
                $json->decoded(),
                $this->format->jsonEncodeOptions()->toInt(),
            ),
            $this->format->indent()->toString(),
            $this->format->newLine()->toString(),
        );

        if (!$this->format->hasFinalNewLine()) {
            return Json::fromString($normalized);
        }

        return Json::fromString($normalized . $this->format->newLine()->toString());
    }
}
