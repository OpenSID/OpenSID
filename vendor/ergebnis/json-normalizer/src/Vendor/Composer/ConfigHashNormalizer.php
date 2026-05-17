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

namespace Ergebnis\Json\Normalizer\Vendor\Composer;

use Ergebnis\Json\Json;
use Ergebnis\Json\Normalizer\Format;
use Ergebnis\Json\Normalizer\Normalizer;

final class ConfigHashNormalizer implements Normalizer
{
    private const PROPERTIES_WITH_WILDCARDS = [
        /**
         * @see https://getcomposer.org/doc/06-config.md#allow-plugins
         */
        'allow-plugins',
        /**
         * @see https://getcomposer.org/doc/06-config.md#preferred-install
         */
        'preferred-install',
    ];
    private WildcardSorter $wildcardSorter;

    public function __construct()
    {
        $this->wildcardSorter = new WildcardSorter();
    }

    public function normalize(Json $json): Json
    {
        $decoded = $json->decoded();

        if (!\is_object($decoded)) {
            return $json;
        }

        if (!\property_exists($decoded, 'config')) {
            return $json;
        }

        if (!\is_object($decoded->config)) {
            return $json;
        }

        /** @var array<string, mixed> $config */
        $config = (array) $decoded->config;

        if ([] === $config) {
            return $json;
        }

        \ksort($config);

        foreach (self::PROPERTIES_WITH_WILDCARDS as $property) {
            $this->wildcardSorter->sortPropertyWithWildcard(
                $config,
                $property,
            );
        }

        $decoded->config = $config;

        /** @var string $encoded */
        $encoded = \json_encode(
            $decoded,
            Format\JsonEncodeOptions::default()->toInt(),
        );

        return Json::fromString($encoded);
    }
}
