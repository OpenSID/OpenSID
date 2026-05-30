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
use Ergebnis\Json\Pointer;
use Ergebnis\Json\SchemaValidator;
use JsonSchema\Exception\InvalidSchemaMediaTypeException;
use JsonSchema\Exception\JsonDecodingException;
use JsonSchema\Exception\ResourceNotFoundException;
use JsonSchema\Exception\UriResolverException;
use JsonSchema\SchemaStorage;

final class SchemaNormalizer implements Normalizer
{
    private Pointer\Specification $specificationForPointerToDataThatShouldNotBeSorted;
    private SchemaValidator\SchemaValidator $schemaValidator;
    private SchemaStorage $schemaStorage;
    private string $schemaUri;
    private bool $pruneEmpty;

    public function __construct(
        string $schemaUri,
        SchemaStorage $schemaStorage,
        SchemaValidator\SchemaValidator $schemaValidator,
        Pointer\Specification $specificationForPointerToDataThatShouldNotBeSorted,
        bool $pruneEmpty = false
    ) {
        $this->schemaUri = $schemaUri;
        $this->schemaStorage = $schemaStorage;
        $this->schemaValidator = $schemaValidator;
        $this->specificationForPointerToDataThatShouldNotBeSorted = $specificationForPointerToDataThatShouldNotBeSorted;
        $this->pruneEmpty = $pruneEmpty;
    }

    public function normalize(Json $json): Json
    {
        try {
            $schema = $this->schemaStorage->getSchema($this->schemaUri);
        } catch (UriResolverException $exception) {
            throw Exception\SchemaUriCouldNotBeResolved::fromSchemaUri($this->schemaUri);
        } catch (ResourceNotFoundException $exception) {
            throw Exception\SchemaUriCouldNotBeRead::fromSchemaUri($this->schemaUri);
        } catch (InvalidSchemaMediaTypeException $exception) {
            throw Exception\SchemaUriReferencesDocumentWithInvalidMediaType::fromSchemaUri($this->schemaUri);
        } catch (JsonDecodingException $exception) {
            throw Exception\SchemaUriReferencesInvalidJsonDocument::fromSchemaUri($this->schemaUri);
        }

        $resultBeforeNormalization = $this->schemaValidator->validate(
            $json,
            Json::fromString(\json_encode($schema)),
            Pointer\JsonPointer::document(),
        );

        if (!$resultBeforeNormalization->isValid()) {
            throw Exception\OriginalInvalidAccordingToSchema::fromSchemaUriAndErrors(
                $this->schemaUri,
                ...\array_map(static function (SchemaValidator\ValidationError $error): string {
                    return $error->message()->toString();
                }, $resultBeforeNormalization->errors()),
            );
        }

        $normalized = Json::fromString(\json_encode(
            $this->normalizeData(
                $json->decoded(),
                $schema,
                Pointer\JsonPointer::document(),
            ),
            Format\JsonEncodeOptions::default()->toInt(),
        ));

        $resultAfterNormalization = $this->schemaValidator->validate(
            $normalized,
            Json::fromString(\json_encode($schema)),
            Pointer\JsonPointer::document(),
        );

        if (!$resultAfterNormalization->isValid()) {
            throw Exception\NormalizedInvalidAccordingToSchema::fromSchemaUriAndErrors(
                $this->schemaUri,
                ...\array_map(static function (SchemaValidator\ValidationError $error): string {
                    return $error->message()->toString();
                }, $resultAfterNormalization->errors()),
            );
        }

        return $normalized;
    }

    /**
     * @param null|array<int, mixed>|bool|float|int|object|string $data
     *
     * @throws \InvalidArgumentException
     *
     * @return null|array<int, mixed>|bool|float|int|object|string
     */
    private function normalizeData(
        $data,
        object $schema,
        Pointer\JsonPointer $pointerToData
    ) {
        if (\is_array($data)) {
            return $this->normalizeArray(
                $data,
                $schema,
                $pointerToData,
            );
        }

        if (\is_object($data)) {
            return $this->normalizeObject(
                $data,
                $schema,
                $pointerToData,
            );
        }

        return $data;
    }

    /**
     * @param array<int, mixed> $data
     *
     * @return array<int, mixed>
     */
    private function normalizeArray(
        array $data,
        object $schema,
        Pointer\JsonPointer $pointerToData
    ): array {
        $schema = $this->resolveSchema(
            $data,
            $schema,
        );

        $itemSchema = new \stdClass();

        /**
         * @see https://json-schema.org/understanding-json-schema/reference/array.html#items
         */
        if (\property_exists($schema, 'items')) {
            $itemSchema = $schema->items;

            /**
             * @see https://json-schema.org/understanding-json-schema/reference/array.html#tuple-validation
             */
            if (\is_array($itemSchema)) {
                return \array_map(function (int $key, $item, object $itemSchema) use ($pointerToData) {
                    return $this->normalizeData(
                        $item,
                        $itemSchema,
                        $pointerToData->append(Pointer\ReferenceToken::fromInt($key)),
                    );
                }, \array_keys($data), $data, $itemSchema);
            }
        }

        /**
         * @see https://json-schema.org/understanding-json-schema/reference/array.html#list-validation
         */
        return \array_map(function (int $key, $item) use ($itemSchema, $pointerToData) {
            return $this->normalizeData(
                $item,
                $itemSchema,
                $pointerToData->append(Pointer\ReferenceToken::fromInt($key)),
            );
        }, \array_keys($data), $data);
    }

    private function normalizeObject(
        object $data,
        object $schema,
        Pointer\JsonPointer $pointerToData
    ): object {
        $schema = $this->resolveSchema(
            $data,
            $schema,
        );

        $normalized = new \stdClass();

        $dataShouldBeSorted = true;

        if ($this->specificationForPointerToDataThatShouldNotBeSorted->isSatisfiedBy($pointerToData)) {
            $dataShouldBeSorted = false;
        }

        /**
         * @see https://json-schema.org/understanding-json-schema/reference/object.html#properties
         */
        if (
            $dataShouldBeSorted
            && \property_exists($schema, 'properties')
            && \is_object($schema->properties)
        ) {
            /** @var array<string, object> $objectPropertiesThatAreDefinedBySchema */
            $objectPropertiesThatAreDefinedBySchema = \array_intersect_key(
                \get_object_vars($schema->properties),
                \get_object_vars($data),
            );

            foreach ($objectPropertiesThatAreDefinedBySchema as $name => $valueSchema) {
                $value = $data->{$name};

                $valueSchema = $this->resolveSchema(
                    $value,
                    $valueSchema,
                );

                $normalized->{$name} = $this->normalizeData(
                    $value,
                    $valueSchema,
                    $pointerToData->append(Pointer\ReferenceToken::fromString($name)),
                );

                if (
                    $this->pruneEmpty
                    && [] === (array) $normalized->{$name}
                    && self::isKeyOptionalInSchema($schema, $name)
                ) {
                    unset($normalized->{$name});
                }

                unset($data->{$name});
            }
        }

        $additionalProperties = \get_object_vars($data);

        if ([] === $additionalProperties) {
            return $normalized;
        }

        if ($dataShouldBeSorted) {
            \ksort($additionalProperties);
        }

        $valueSchema = new \stdClass();

        /**
         * @see https://json-schema.org/understanding-json-schema/reference/object.html#additional-properties
         */
        if (
            \property_exists($schema, 'additionalProperties')
            && \is_object($schema->additionalProperties)
        ) {
            $valueSchema = $schema->additionalProperties;
        }

        foreach ($additionalProperties as $name => $value) {
            $normalized->{$name} = $this->normalizeData(
                $value,
                $valueSchema,
                $pointerToData->append(Pointer\ReferenceToken::fromString((string) $name)),
            );
        }

        return $normalized;
    }

    private function resolveSchema(
        $data,
        object $schema
    ): object {
        /**
         * @see https://json-schema.org/understanding-json-schema/reference/combining.html#anyof
         */
        if (
            \property_exists($schema, 'anyOf')
            && \is_array($schema->anyOf)
        ) {
            foreach ($schema->anyOf as $anyOfSchema) {
                $result = $this->schemaValidator->validate(
                    Json::fromString(\json_encode($data)),
                    Json::fromString(\json_encode($anyOfSchema)),
                    Pointer\JsonPointer::document(),
                );

                if ($result->isValid()) {
                    return $this->resolveSchema(
                        $data,
                        $anyOfSchema,
                    );
                }
            }
        }

        /**
         * @see https://json-schema.org/understanding-json-schema/reference/combining.html#oneof
         */
        if (
            \property_exists($schema, 'oneOf')
            && \is_array($schema->oneOf)
        ) {
            foreach ($schema->oneOf as $oneOfSchema) {
                $result = $this->schemaValidator->validate(
                    Json::fromString(\json_encode($data)),
                    Json::fromString(\json_encode($oneOfSchema)),
                    Pointer\JsonPointer::document(),
                );

                if ($result->isValid()) {
                    return $this->resolveSchema(
                        $data,
                        $oneOfSchema,
                    );
                }
            }
        }

        /**
         * @see https://json-schema.org/understanding-json-schema/structuring.html#reuse
         */
        if (
            \property_exists($schema, '$ref')
            && \is_string($schema->{'$ref'})
        ) {
            $referenceSchema = $this->schemaStorage->resolveRefSchema($schema);

            return $this->resolveSchema(
                $data,
                $referenceSchema,
            );
        }

        return $schema;
    }

    private static function isKeyOptionalInSchema(
        object $schema,
        string $name
    ): bool {
        if (
            \property_exists($schema, 'required')
            && \is_array($schema->required)
            && \in_array($name, $schema->required, true)
        ) {
            return false;
        }

        return true;
    }
}
