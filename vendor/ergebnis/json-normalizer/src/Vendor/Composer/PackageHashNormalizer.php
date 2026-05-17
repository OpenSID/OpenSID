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

final class PackageHashNormalizer implements Normalizer
{
    /**
     * @see https://github.com/composer/composer/blob/2.0.11/src/Composer/Repository/PlatformRepository.php#L33
     */
    private const PLATFORM_PACKAGE_REGEX = '{^(?:php(?:-64bit|-ipv6|-zts|-debug)?|hhvm|(?:ext|lib)-[a-z0-9](?:[_.-]?[a-z0-9]+)*|composer-(?:plugin|runtime)-api)$}iD';
    private const PROPERTIES_THAT_SHOULD_BE_NORMALIZED = [
        'conflict',
        'provide',
        'replace',
        'require',
        'require-dev',
        'suggest',
    ];

    public function normalize(Json $json): Json
    {
        $decoded = $json->decoded();

        if (!\is_object($decoded)) {
            return $json;
        }

        $objectPropertiesThatShouldBeNormalized = \array_intersect_key(
            \get_object_vars($decoded),
            \array_flip(self::PROPERTIES_THAT_SHOULD_BE_NORMALIZED),
        );

        if ([] === $objectPropertiesThatShouldBeNormalized) {
            return $json;
        }

        foreach ($objectPropertiesThatShouldBeNormalized as $name => $value) {
            /** @var array<string, string> $packages */
            $packages = (array) $value;

            if ([] === $packages) {
                continue;
            }

            $packages = self::mergeDuplicateExtensions($packages);
            $decoded->{$name} = self::sortPackages($packages);
        }

        /** @var string $encoded */
        $encoded = \json_encode(
            $decoded,
            Format\JsonEncodeOptions::default()->toInt(),
        );

        return Json::fromString($encoded);
    }

    /**
     * This code is adopted from composer/composer (originally licensed under MIT by Nils Adermann <naderman@naderman.de>
     * and Jordi Boggiano <j.boggiano@seld.be>).
     *
     * @see https://github.com/composer/composer/blob/1.6.2/src/Composer/Json/JsonManipulator.php#L110-L146
     *
     * @param array<string, string> $packages
     *
     * @return array<string, string>
     */
    private static function sortPackages(array $packages): array
    {
        $prefix = static function (string $requirement): string {
            if (1 === \preg_match(self::PLATFORM_PACKAGE_REGEX, $requirement)) {
                return \preg_replace(
                    [
                        '/^php/',
                        '/^hhvm/',
                        '/^ext/',
                        '/^lib/',
                        '/^\D/',
                    ],
                    [
                        '0-$0',
                        '1-$0',
                        '2-$0',
                        '3-$0',
                        '4-$0',
                    ],
                    $requirement,
                );
            }

            return '5-' . $requirement;
        };

        \uksort($packages, static function (string $a, string $b) use ($prefix): int {
            return \strnatcmp(
                $prefix($a),
                $prefix($b),
            );
        });

        return $packages;
    }

    /**
     * This code is adopted from composer/composer (originally licensed under MIT by Nils Adermann <naderman@naderman.de>
     * and Jordi Boggiano <j.boggiano@seld.be>).
     *
     * @see https://github.com/composer/composer/blob/2.8.1/src/Composer/Repository/PlatformRepository.php#L682
     *
     * @param array<string, string> $packages
     *
     * @return array<string, string>
     */
    private static function mergeDuplicateExtensions($packages): array
    {
        foreach ($packages as $name => $value) {
            if (!isset($name[4]) || \strtolower(\substr($name, 0, 4)) !== 'ext-') {
                continue;
            }

            $newName = \str_replace(' ', '-', \strtolower($name));

            if ($name === $newName) {
                continue;
            }

            if (isset($packages[$newName])) {
                $value .= '||' . $packages[$newName];
            }

            $packages[$newName] = $value;
            unset($packages[$name]);
        }

        return $packages;
    }
}
