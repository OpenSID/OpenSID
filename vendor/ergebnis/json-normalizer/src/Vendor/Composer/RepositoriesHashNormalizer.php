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

final class RepositoriesHashNormalizer implements Normalizer
{
    private const PROPERTIES_WITH_WILDCARDS = [
        /**
         * @see https://getcomposer.org/doc/articles/repository-priorities.md#filtering-packages
         */
        'exclude',
        'only',
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

        if (!\property_exists($decoded, 'repositories')) {
            return $json;
        }

        if (!\is_array($decoded->repositories) && !\is_object($decoded->repositories)) {
            return $json;
        }

        /** @var array<string, mixed> $repositories */
        $repositories = (array) $decoded->repositories;

        if ([] === $repositories) {
            return $json;
        }

        foreach ($repositories as &$repository) {
            /**
             * @see https://getcomposer.org/doc/05-repositories.md#disabling-packagist-org
             */
            if (!\is_array($repository) && !\is_object($repository)) {
                continue;
            }

            $repository = (array) $repository;

            foreach (self::PROPERTIES_WITH_WILDCARDS as $property) {
                $this->wildcardSorter->sortPropertyWithWildcard(
                    $repository,
                    $property,
                    false,
                );
            }
        }

        $decoded->repositories = $repositories;

        /** @var string $encoded */
        $encoded = \json_encode(
            $decoded,
            Format\JsonEncodeOptions::default()->toInt(),
        );

        return Json::fromString($encoded);
    }
}
