<?php

declare(strict_types=1);

/**
 * Copyright (c) 2022-2025 Andreas Möller
 *
 * For the full copyright and license information, please view
 * the LICENSE.md file that was distributed with this source code.
 *
 * @see https://github.com/ergebnis/json-pointer
 */

namespace Ergebnis\Json\Pointer;

/**
 * @internal
 */
final class Pattern
{
    /**
     * @see https://datatracker.ietf.org/doc/html/rfc6901#section-3
     */
    public const JSON_STRING_JSON_POINTER = '/^(?P<jsonStringJsonPointer>(\/(?P<referenceToken>((?P<unescaped>[\x00-\x2E]|[\x30-\x7D]|[\x7F-\x{10FFFF}])|(?P<escaped>~[01]))*))*)$/u';

    /**
     * @see https://datatracker.ietf.org/doc/html/rfc6901#section-3
     */
    public const JSON_STRING_REFERENCE_TOKEN = '/^(?P<referenceToken>((?P<unescaped>[\x00-\x2E]|[\x30-\x7D]|[\x7F-\x{10FFFF}])|(?P<escaped>~[01]))*)$/u';
    public const URI_FRAGMENT_IDENTIFIER_JSON_POINTER = '/^(?P<uriFragmentIdentifierJsonPointer>#(\/(?P<referenceToken>((?P<pchar>((?P<unreserved>((?P<alpha>[a-zA-Z])|(?P<digit>\d)|-|\.|_|~))|(?P<pctEncoded>%(?P<hexDig>[0-9a-fA-F]){2})|(?P<subDelims>(!|\$|&|\'|\(|\)|\*|\+|,|;|=))|:|@))*)))*)$/u';

    /**
     * @see https://datatracker.ietf.org/doc/html/rfc6901#section-6
     * @see https://datatracker.ietf.org/doc/html/rfc3986#section-3.5
     * @see https://datatracker.ietf.org/doc/html/rfc3986#appendix-A
     * @see https://datatracker.ietf.org/doc/html/rfc3986#section-2.3
     * @see https://datatracker.ietf.org/doc/html/rfc3986#section-2.1
     */
    public const URI_FRAGMENT_IDENTIFIER_REFERENCE_TOKEN = '/^(?P<referenceToken>((?P<pchar>((?P<unreserved>((?P<alpha>[a-zA-Z])|(?P<digit>\d)|-|\.|_|~))|(?P<pctEncoded>%(?P<hexDig>[0-9a-fA-F]){2})|(?P<subDelims>(!|\$|&|\'|\(|\)|\*|\+|,|;|=))|:|@))*))$/u';
}
