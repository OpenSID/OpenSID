<?php declare(strict_types=1);

namespace Amp\Serialization;

final class JsonSerializer implements Serializer
{
    private bool $associative;
    private int $encodeOptions;
    private int $decodeOptions;
    private int $depth;

    /**
     * Creates a JSON serializer that decodes objects to associative arrays.
     *
     * @param int $encodeOptions {@see \json_encode()} options parameter.
     * @param int $decodeOptions {@see \json_decode()} options parameter.
     * @param int $depth Maximum recursion depth.
     */
    public static function withAssociativeArrays(int $encodeOptions = 0, int $decodeOptions = 0, int $depth = 512): self
    {
        return new self(true, $encodeOptions, $decodeOptions, $depth);
    }

    /**
     * Creates a JSON serializer that decodes objects to instances of stdClass.
     *
     * @param int $encodeOptions {@see \json_encode()} options parameter.
     * @param int $decodeOptions {@see \json_decode()} options parameter.
     * @param int $depth Maximum recursion depth.
     */
    public static function withObjects(int $encodeOptions = 0, int $decodeOptions = 0, int $depth = 512): self
    {
        return new self(false, $encodeOptions, $decodeOptions, $depth);
    }

    private function __construct(bool $associative, int $encodeOptions = 0, int $decodeOptions = 0, int $depth = 512)
    {
        $this->associative = $associative;
        $this->depth = $depth;

        // We always want to throw on errors.
        $this->encodeOptions = $encodeOptions | \JSON_THROW_ON_ERROR;
        $this->decodeOptions = $decodeOptions | \JSON_THROW_ON_ERROR;
    }

    /**
     * @psalm-suppress InvalidFalsableReturnType $this->encodeOptions always contains JSON_THROW_ON_ERROR.
     */
    #[\Override]
    public function serialize($data): string
    {
        try {
            /** @psalm-suppress ArgumentTypeCoercion, FalsableReturnStatement */
            return \json_encode($data, $this->encodeOptions, $this->depth);
        } catch (\JsonException $e) {
            throw new SerializationException($e->getMessage(), $e->getCode(), $e);
        }
    }

    #[\Override]
    public function unserialize(string $data)
    {
        try {
            /** @psalm-suppress ArgumentTypeCoercion, FalsableReturnStatement */
            return \json_decode($data, $this->associative, $this->depth, $this->decodeOptions);
        } catch (\JsonException $e) {
            throw new SerializationException($e->getMessage(), $e->getCode(), $e);
        }
    }
}
