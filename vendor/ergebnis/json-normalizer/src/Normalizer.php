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

interface Normalizer
{
    /**
     * @throws Exception\SchemaUriCouldNotBeResolved
     * @throws Exception\SchemaUriCouldNotBeRead
     * @throws Exception\SchemaUriReferencesDocumentWithInvalidMediaType
     * @throws Exception\SchemaUriReferencesInvalidJsonDocument
     * @throws Exception\OriginalInvalidAccordingToSchema
     * @throws Exception\NormalizedInvalidAccordingToSchema
     */
    public function normalize(Json $json): Json;
}
