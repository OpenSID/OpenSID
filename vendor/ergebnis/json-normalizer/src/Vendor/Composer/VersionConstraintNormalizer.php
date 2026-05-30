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

use Composer\Semver;
use Ergebnis\Json\Json;
use Ergebnis\Json\Normalizer\Format;
use Ergebnis\Json\Normalizer\Normalizer;

final class VersionConstraintNormalizer implements Normalizer
{
    private const PROPERTIES_THAT_SHOULD_BE_NORMALIZED = [
        'conflict',
        'provide',
        'replace',
        'require',
        'require-dev',
    ];
    private Semver\VersionParser $versionParser;

    public function __construct(Semver\VersionParser $versionParser)
    {
        $this->versionParser = $versionParser;
    }

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
            $packages = (array) $value;

            if ([] === $packages) {
                continue;
            }

            $decoded->{$name} = \array_map(function (string $versionConstraint): string {
                $versionConstraint = self::trim($versionConstraint);
                $versionConstraint = self::removeExtraSpaces($versionConstraint);

                try {
                    $this->versionParser->parseConstraints($versionConstraint);
                } catch (\UnexpectedValueException $exception) {
                    return $versionConstraint;
                }

                return $this->normalizeVersionConstraint($versionConstraint);
            }, $packages);
        }

        /** @var string $encoded */
        $encoded = \json_encode(
            $decoded,
            Format\JsonEncodeOptions::default()->toInt(),
        );

        return Json::fromString($encoded);
    }

    private function normalizeVersionConstraint(string $versionConstraint): string
    {
        $versionConstraint = self::normalizeVersionConstraintSeparators($versionConstraint);
        $versionConstraint = self::removeLeadingVersionPrefix($versionConstraint);
        $versionConstraint = self::assertDevPrefixSuffixPosition($versionConstraint);
        $versionConstraint = self::replaceWildcardXWithAsterisk($versionConstraint);
        $versionConstraint = self::replaceWildcardWithTilde($versionConstraint);
        $versionConstraint = self::replaceTildeWithCaret($versionConstraint);
        $versionConstraint = self::removeDuplicateVersionConstraints($versionConstraint);
        $versionConstraint = self::removeUselessInlineAliases($versionConstraint);
        $versionConstraint = self::sortVersionConstraints($versionConstraint);

        return self::removeOverlappingVersionConstraints($versionConstraint);
    }

    private static function trim(string $versionConstraint): string
    {
        return \trim($versionConstraint);
    }

    private static function removeExtraSpaces(string $versionConstraint): string
    {
        return \preg_replace(
            '/ +/',
            ' ',
            $versionConstraint,
        );
    }

    private static function normalizeVersionConstraintSeparators(string $versionConstraint): string
    {
        $orConstraints = self::splitIntoOrConstraints($versionConstraint);

        return self::joinOrConstraints(...\array_map(static function (string $orConstraint): string {
            $andConstraints = self::splitIntoAndConstraints($orConstraint);

            return self::joinAndConstraints(...$andConstraints);
        }, $orConstraints));
    }

    private static function replaceWildcardXWithAsterisk(string $versionConstraint): string
    {
        // '1.x.x' -> '1.*'
        $versionConstraint = self::applyRegularExpressionReplacementToVersionsInTurn(
            $versionConstraint,
            '{^(\d+)\.[xX]\.[xX]$}',
            '$1.*',
        );

        // '1.x' -> '1.*'
        $versionConstraint = self::applyRegularExpressionReplacementToVersionsInTurn(
            $versionConstraint,
            '{^(\d+)\.[xX]$}',
            '$1.*',
        );

        // 'x' -> '*'
        return self::applyRegularExpressionReplacementToVersionsInTurn(
            $versionConstraint,
            '{^[xX]$}',
            '*',
        );
    }

    private static function replaceWildcardWithTilde(string $versionConstraint): string
    {
        return self::applyRegularExpressionReplacementToVersionsInTurn(
            $versionConstraint,
            '{^(\d+(?:\.\d+)*)\.[*xX]$}',
            '~$1.0',
        );
    }

    private static function replaceTildeWithCaret(string $versionConstraint): string
    {
        return self::applyRegularExpressionReplacementToVersionsInTurn(
            $versionConstraint,
            '{^~(\d+(?:\.\d+)?)$}',
            '^$1',
        );
    }

    private static function removeDuplicateVersionConstraints(string $versionConstraint): string
    {
        $orConstraints = self::splitIntoOrConstraints($versionConstraint);

        return self::joinOrConstraints(...\array_unique(\array_map(static function (string $orConstraint): string {
            $andConstraints = self::splitIntoAndConstraints($orConstraint);

            return self::joinAndConstraints(...\array_unique($andConstraints));
        }, $orConstraints)));
    }

    private static function removeLeadingVersionPrefix(string $versionConstraint): string
    {
        return self::applyRegularExpressionReplacementToVersionsInTurn(
            $versionConstraint,
            '{^(|[!<>]=|[~<>^])v(\d+.*(?<!-dev))$}',
            '$1$2',
        );
    }

    private static function assertDevPrefixSuffixPosition(string $versionConstraint): string
    {
        $split = \explode(
            ' ',
            $versionConstraint,
        );

        foreach ($split as &$part) {
            if (\strlen($part) <= 4) {
                continue;
            }

            if (\strpos($part, 'dev-') === 0) {
                $branch = \substr($part, 4);
            } elseif (\substr($part, -4) === '-dev') {
                $branch = \substr($part, 0, -4);
            } else {
                continue;
            }

            /**
             * @see https://github.com/composer/semver/blob/3.4.0/src/VersionParser.php#L216
             */
            if (1 === \preg_match('{^v?\d+(\.(?:\d+|[xX*]))?(\.(?:\d+|[xX*]))?(\.(?:\d+|[xX*]))?$}i', $branch)) {
                $part = $branch . '-dev';
            } else {
                $part = 'dev-' . $branch;
            }
        }

        return \implode(
            ' ',
            $split,
        );
    }

    private static function removeOverlappingVersionConstraints(string $versionConstraint): string
    {
        $orConstraints = self::splitIntoOrConstraints($versionConstraint);

        $regex = '{^[~^]?\d+(?:\.\d+)*$}';

        $count = \count($orConstraints);

        for ($i = 0; $i < $count; ++$i) {
            $a = $orConstraints[$i];

            if (!\is_string($a)) {
                continue;
            }

            if ('*' === $a) {
                return $a;
            }

            if (1 !== \preg_match($regex, $a)) {
                continue;
            }

            for ($j = $i + 1; $j < $count; ++$j) {
                $b = $orConstraints[$j];

                if (!\is_string($b)) {
                    continue;
                }

                if (1 !== \preg_match($regex, $b)) {
                    continue;
                }

                if (Semver\Semver::satisfies(\ltrim($a, '^~'), $b) || Semver\Semver::satisfies(\ltrim($b, '^~'), $a)) {
                    if ('^' === $a[0]) {
                        $orConstraints[$j] = null;
                    } elseif ('^' === $b[0]) {
                        $orConstraints[$i] = null;
                    } elseif ('~' === $a[0]) {
                        $orConstraints[$j] = null;
                    } elseif ('~' === $b[0]) {
                        $orConstraints[$i] = null;
                    }
                }
            }
        }

        return self::joinOrConstraints(...\array_filter($orConstraints, static function (?string $orConstraint): bool {
            return \is_string($orConstraint);
        }));
    }

    private static function removeUselessInlineAliases(string $normalized): string
    {
        return \preg_replace_callback(
            '{(\S+)\s+as\s+(\S+)}',
            static function (array $matches): string {
                if ($matches[1] === $matches[2]) {
                    return $matches[1];
                }

                return $matches[0];
            },
            $normalized,
        );
    }

    private static function sortVersionConstraints(string $versionConstraint): string
    {
        $normalize = static function (string $versionConstraint): string {
            return \trim($versionConstraint, '<>=!~^');
        };

        $sort = static function (string $a, string $b) use ($normalize): int {
            return \strnatcmp(
                $normalize($a),
                $normalize($b),
            );
        };

        $orConstraints = self::splitIntoOrConstraints($versionConstraint);

        $orConstraints = \array_map(static function (string $orConstraint) use ($sort): string {
            $andConstraints = self::splitIntoAndConstraints($orConstraint);

            \usort($andConstraints, $sort);

            return self::joinAndConstraints(...$andConstraints);
        }, $orConstraints);

        \usort($orConstraints, $sort);

        return self::joinOrConstraints(...$orConstraints);
    }

    /**
     * @see https://github.com/composer/semver/blob/3.3.2/src/VersionParser.php#L257
     *
     * @return list<string>
     */
    private static function splitIntoOrConstraints(string $versionConstraint): array
    {
        return \preg_split(
            '{\s*\|\|?\s*}',
            $versionConstraint,
        );
    }

    private static function joinOrConstraints(string ...$orConstraints): string
    {
        return \implode(
            ' || ',
            $orConstraints,
        );
    }

    /**
     * @see https://github.com/composer/semver/blob/3.3.2/src/VersionParser.php#L264
     *
     * @return list<string>
     */
    private static function splitIntoAndConstraints(string $orConstraint): array
    {
        return \preg_split(
            '{(?<!^|as|[=>< ,]) *(?<!-)[, ](?!-) *(?!,|as|$)}',
            $orConstraint,
        );
    }

    private static function joinAndConstraints(string ...$andConstraints): string
    {
        return \implode(
            ' ',
            $andConstraints,
        );
    }

    /**
     * @param non-empty-string $find
     */
    private static function applyRegularExpressionReplacementToVersionsInTurn(string $versionConstraint, string $find, string $replace): string
    {
        $split = \explode(
            ' ',
            $versionConstraint,
        );

        foreach ($split as &$part) {
            $part = \preg_replace(
                $find,
                $replace,
                $part,
            );
        }

        return \implode(
            ' ',
            $split,
        );
    }
}
