<?php

declare(strict_types=1);

/**
 * Copyright (c) 2018-2025 Andreas Möller
 *
 * For the full copyright and license information, please view
 * the LICENSE.md file that was distributed with this source code.
 *
 * @see https://github.com/ergebnis/composer-normalize
 */

namespace Ergebnis\Composer\Normalize;

/**
 * @internal
 */
final class Version
{
    /**
     * @see https://github.com/box-project/box/blob/master/doc/configuration.md#pretty-git-tag-placeholder-git
     */
    private static string $version = '@git@';

    public static function long(): string
    {
        $name = 'ergebnis/composer-normalize';
        $attribution = 'by <info>Andreas Möller</info> and contributors';

        $version = self::$version;

        if ('@' . 'git@' === $version) {
            return \sprintf(
                '<info>%s</info> %s',
                $name,
                $attribution,
            );
        }

        return \sprintf(
            '<info>%s</info> %s %s',
            $name,
            $version,
            $attribution,
        );
    }
}
