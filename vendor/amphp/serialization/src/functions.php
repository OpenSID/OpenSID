<?php declare(strict_types=1);

namespace Amp\Serialization;

/**
 * @param string $data Binary data.
 *
 * @return string Unprintable characters encoded as \x##.
 */
function encodeUnprintableChars(string $data): string
{
    $result = \preg_replace_callback("/[^\x20-\x7e]/", function (array $matches): string {
        return "\\x" . \dechex(\ord($matches[0]));
    }, $data);

    // For Psalm.
    \assert(\is_string($result));

    return $result;
}
