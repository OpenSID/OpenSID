<?php declare(strict_types=1);

namespace Amp\Serialization;

final class CompressingSerializer implements Serializer
{
    private const FLAG_COMPRESSED = 1;
    private const COMPRESSION_THRESHOLD = 256;

    private Serializer $serializer;

    /** @var \Closure():true */
    private \Closure $errorHandler;

    public function __construct(Serializer $serializer)
    {
        $this->serializer = $serializer;
        $this->errorHandler = static fn () => true;
    }

    #[\Override]
    public function serialize($data): string
    {
        $serializedData = $this->serializer->serialize($data);

        $flags = 0;

        if (\strlen($serializedData) > self::COMPRESSION_THRESHOLD) {
            \set_error_handler($this->errorHandler);
            try {
                $serializedData = \gzdeflate($serializedData, 1);
            } finally {
                \restore_error_handler();
            }

            if ($serializedData === false) {
                $error = \error_get_last();
                throw new SerializationException('Could not compress data: ' . ($error['message'] ?? 'unknown error'));
            }

            $flags |= self::FLAG_COMPRESSED;
        }

        return \chr($flags & 0xff) . $serializedData;
    }

    #[\Override]
    public function unserialize(string $data)
    {
        if ($data === '') {
            throw new SerializationException('Empty string provided');
        }

        $firstByte = \ord($data[0]);
        $data = \substr($data, 1);
        \assert(\is_string($data));

        if ($firstByte & self::FLAG_COMPRESSED) {
            \set_error_handler($this->errorHandler);
            try {
                $data = \gzinflate($data);
            } finally {
                \restore_error_handler();
            }
        }

        if ($data === false) {
            $error = \error_get_last();
            throw new SerializationException('Could not decompress data: ' . ($error['message'] ?? 'unknown error'));
        }

        return $this->serializer->unserialize($data);
    }
}
