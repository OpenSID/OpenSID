<?php

declare(strict_types=1);

/**
 * Copyright (c) 2021-2025 Andreas Möller
 *
 * For the full copyright and license information, please view
 * the LICENSE.md file that was distributed with this source code.
 *
 * @see https://github.com/ergebnis/json-schema-validator
 */

namespace Ergebnis\Json\SchemaValidator;

use Ergebnis\Json\Json;
use Ergebnis\Json\Pointer;
use Ergebnis\Json\SchemaValidator\Exception\CanNotResolve;
use JsonSchema\Constraints;
use JsonSchema\Exception;
use JsonSchema\SchemaStorage;
use JsonSchema\Uri;
use JsonSchema\Validator;

final class SchemaValidator
{
    /**
     * @throws CanNotResolve
     */
    public function validate(
        Json $json,
        Json $schema,
        Pointer\JsonPointer $jsonPointer
    ): ValidationResult {
        $schemaDecoded = \json_decode(
            $schema->toString(),
            false,
        );

        $uriRetriever = new Uri\UriRetriever();

        if (!$jsonPointer->equals(Pointer\JsonPointer::document())) {
            try {
                $subSchemaDecoded = $uriRetriever->resolvePointer(
                    $schemaDecoded,
                    $jsonPointer->toUriFragmentIdentifierString(),
                );
            } catch (Exception\ResourceNotFoundException $exception) {
                throw CanNotResolve::jsonPointer($jsonPointer);
            }

            $schemaDecoded = $subSchemaDecoded;
        }

        $schemaStorage = new SchemaStorage(
            $uriRetriever,
            new Uri\UriResolver(),
        );

        $validator = new Validator(new Constraints\Factory(
            $schemaStorage,
            $uriRetriever,
        ));

        $jsonDecoded = \json_decode(
            $json->toString(),
            false,
        );

        $validator->validate(
            $jsonDecoded,
            $schemaDecoded,
        );

        /** @var array<int, array> $originalErrors */
        $originalErrors = $validator->getErrors();

        $validationErrors = \array_map(static function (array $error): ValidationError {
            return ValidationError::create(
                Pointer\JsonPointer::fromJsonString($error['pointer']),
                Message::fromString($error['message']),
            );
        }, $originalErrors);

        return ValidationResult::create(...$validationErrors);
    }
}
