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

/**
 * @internal
 */
final class WildcardSorter
{
    /**
     * When sorting with wildcards, special care needs to be taken.
     *
     * @see https://github.com/ergebnis/json-normalizer/pull/775#issuecomment-1346095415
     * @see https://github.com/composer/composer/blob/2.6.5/src/Composer/Plugin/PluginManager.php#L85-L86
     * @see https://github.com/composer/composer/blob/2.6.5/src/Composer/Plugin/PluginManager.php#L626-L646
     * @see https://github.com/composer/composer/blob/2.6.5/src/Composer/Package/BasePackage.php#L252-L257
     * @see https://github.com/composer/composer/blob/2.6.5/src/Composer/Plugin/PluginManager.php#L687-L691
     */
    public function sortPropertyWithWildcard(
        array &$config,
        string $property,
        bool $sortByKey = true
    ): void {
        if (!\array_key_exists($property, $config)) {
            return;
        }

        if (!\is_array($config[$property]) && !\is_object($config[$property])) {
            return;
        }

        $value = (array) $config[$property];

        if ([] === $value) {
            return;
        }

        $packages = $sortByKey ? \array_keys($value) : \array_values($value);

        foreach ($packages as $package) {
            /** @var string $package */
            if (false !== \strpos(\rtrim($package, '*'), '*')) {
                // We cannot reliably sort allow-plugins when there's a wildcard other than at the end of the string.
                return;
            }
        }

        $normalize = static function (string $package): string {
            // Any key with an asterisk needs to be the last entry in its group
            return \str_replace(
                '*',
                '~',
                $package,
            );
        };

        $callback = static function (string $a, string $b) use ($normalize): int {
            return \strcmp(
                $normalize($a),
                $normalize($b),
            );
        };

        if ($sortByKey) {
            /** @var array<string, mixed> $value */
            \uksort($value, $callback);
        } else {
            /** @var array<mixed, string> $value */
            \usort($value, $callback);
        }

        $config[$property] = $value;
    }
}
